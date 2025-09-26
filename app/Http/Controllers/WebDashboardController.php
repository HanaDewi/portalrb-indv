<?php

namespace App\Http\Controllers;

use App\Models\KlpdInstansi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WebDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            foreach (allowed_url() as $allowed) {
                if ($request->is($allowed)) {
                    return $next($request);
                }
            }
            abort('403');
        });
    }

    public function rbGeneral(Request $request)
    {
        $tahun = $request->get('tahun', 2025); // default ke 2024, bisa diganti jadi now()->year kalau dinamis

        $instansis = KlpdInstansi::all();
        $instansiIds = $instansis->pluck('id');

        $all_plans = DB::table('general_perencanaan')
            ->join('general_perencanaan_target as gpt', 'gpt.general_perencanaan_id', '=', 'general_perencanaan.id')
            ->select('general_perencanaan.instansi_id', 'gpt.baseline_tahun')
            ->where('gpt.baseline_tahun', $tahun - 1)
            ->whereIn('general_perencanaan.instansi_id', $instansiIds)
            ->get()
            ->groupBy('instansi_id');

        $all_targets = DB::table('general_perencanaan_target as gpt')
            ->join('general_perencanaan as gp', 'gp.id', '=', 'gpt.general_perencanaan_id')
            ->where('gpt.tahun', $tahun)
            ->whereIn('gp.instansi_id', $instansiIds)
            ->select('gp.instansi_id', DB::raw('COUNT(gpt.id) as target_count'))
            ->groupBy('gp.instansi_id')
            ->get()
            ->keyBy('instansi_id');

        $all_rencana_aksi = DB::table('general_rencana_aksi')
            ->join('general_perencanaan_target as gpt', 'gpt.id', '=', 'general_rencana_aksi.general_perencanaan_target_id')
            ->join('general_perencanaan as gp', 'gp.id', '=', 'gpt.general_perencanaan_id')
            ->where('gpt.tahun', $tahun)
            ->whereIn('gp.instansi_id', $instansiIds)
            ->select('gp.instansi_id', DB::raw('COUNT(general_rencana_aksi.id) as rencana_aksi_count'))
            ->groupBy('gp.instansi_id')
            ->get()
            ->keyBy('instansi_id');

        $rekap = [];
        $yes_kl = $no_kl = $yes_prov = $no_prov = $yes_kab = $no_kab = 0;

        foreach ($instansis as $instansi) {
            $plans = $all_plans->get($instansi->id, collect());
            $target = $all_targets->get($instansi->id);
            $rencana = $all_rencana_aksi->get($instansi->id);

            $baseline = $plans->count() >= 5;
            $target_ok = ($target->target_count ?? 0) >= 5;
            $rencana_ok = ($rencana->rencana_aksi_count ?? 0) >= 5;
            $semua = $baseline && $target_ok && $rencana_ok;

            switch ($instansi->group) {
                case 'kl':
                    $semua ? $yes_kl++ : $no_kl++;
                    break;
                case 'provinsi':
                    $semua ? $yes_prov++ : $no_prov++;
                    break;
                case 'kabupaten':
                    $semua ? $yes_kab++ : $no_kab++;
                    break;
            }

            $rekap[] = [
                'instansi' => $instansi,
                'group' => group_instansi($instansi->group),
                'baseline' => $baseline,
                'tahun_target' => $tahun,
                'target' => $target_ok,
                'rencana_aksi' => $rencana_ok,
                'semua' => $semua
            ];
        }

        return view('webdashboard.rb-general', compact(
            'rekap',
            'yes_kl',
            'no_kl',
            'yes_prov',
            'no_prov',
            'yes_kab',
            'no_kab',
            'tahun'
        ));
    }

    public function rbTematik(Request $request)
    {
        $tahun = $request->get('tahun', 2025); 

        $temas = DB::table('tema')
            ->where('tahun', $tahun)
            ->orderBy('id')
            ->get();

        $temaSelects = $temas->map(function ($theme, $index) {
            $alias = 'tema' . ($index + 1);
            return "MAX(CASE WHEN tsr.tema_id = {$theme->id} THEN 1 ELSE 0 END) AS {$alias}";
        })->implode(",\n");

        $sql = "SELECT
                tsr.instansi_id,
                'yes' AS tematik,
                COUNT(DISTINCT tsr.tema_id) AS tema_id_count,
                {$temaSelects},
                COALESCE(MAX(pc.permasalahan), '---') AS permasalahan,
                COALESCE(MAX(rac.rencana_aksi), '---') AS rencana_aksi
            FROM tematik_sasaran_roadmap tsr
            JOIN tema te ON te.id = tsr.tema_id
                AND te.tahun = ?
            LEFT JOIN (
                SELECT DISTINCT tsr.instansi_id, 'yes' AS permasalahan
                FROM tematik_sasaran_roadmap tsr
                JOIN tematik_indikator_roadmap tir ON tir.tematik_sasaran_roadmap_id = tsr.id
                JOIN tematik_permasalahan tp ON tp.tematik_indikator_roadmap_id = tir.id
                JOIN tema te ON te.id = tsr.tema_id
                WHERE te.tahun = ?
            ) pc ON pc.instansi_id = tsr.instansi_id
            LEFT JOIN (
                SELECT DISTINCT tsr.instansi_id, 'yes' AS rencana_aksi
                FROM tematik_sasaran_roadmap tsr
                JOIN tematik_indikator_roadmap tir ON tir.tematik_sasaran_roadmap_id = tsr.id
                JOIN tematik_permasalahan tp ON tp.tematik_indikator_roadmap_id = tir.id
                JOIN tematik_indikator_permasalahan tip ON tip.tematik_permasalahan_id = tp.id
                JOIN tematik_rencana_aksi tra ON tra.tematik_indikator_permasalahan_id = tip.id
                JOIN tema te ON te.id = tsr.tema_id
                WHERE te.tahun = ?
            ) rac ON rac.instansi_id = tsr.instansi_id
            GROUP BY tsr.instansi_id";
        $tematiks = DB::select($sql, [$tahun, $tahun, $tahun]);
        $tematiks = collect($tematiks)->keyBy('instansi_id');
        $instansis = KlpdInstansi::all();

        return view('webdashboard.rb-tematik', compact('instansis', 'tematiks', 'tahun', 'temas'));
    }

    public function hasilEvaluasi()
    {
        $instansis = KlpdInstansi::all();

        return view('webdashboard.hasil-evaluasi', compact('instansis'));
    }
}
