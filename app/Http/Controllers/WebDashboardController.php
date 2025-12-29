<?php

namespace App\Http\Controllers;

use App\Models\KlpdInstansi;
use App\Models\LKE\LkeKegiatan;
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

        $permasalahanSub = DB::table('tematik_sasaran_roadmap as tsr')
            ->join('tema as te', function ($join) use ($tahun) {
                $join->on('te.id', '=', 'tsr.tema_id')
                    ->where('te.tahun', $tahun);
            })
            ->join('tematik_indikator_roadmap as tir', 'tir.tematik_sasaran_roadmap_id', '=', 'tsr.id')
            ->join('tematik_permasalahan as tp', 'tp.tematik_indikator_roadmap_id', '=', 'tir.id')
            ->select('tsr.instansi_id', DB::raw("'yes' as permasalahan"))
            ->distinct();

        $rencanaSub = DB::table('tematik_sasaran_roadmap as tsr')
            ->join('tema as te', function ($join) use ($tahun) {
                $join->on('te.id', '=', 'tsr.tema_id')
                    ->where('te.tahun', $tahun);
            })
            ->join('tematik_indikator_roadmap as tir', 'tir.tematik_sasaran_roadmap_id', '=', 'tsr.id')
            ->join('tematik_permasalahan as tp', 'tp.tematik_indikator_roadmap_id', '=', 'tir.id')
            ->join('tematik_indikator_permasalahan as tip', 'tip.tematik_permasalahan_id', '=', 'tp.id')
            ->join('tematik_rencana_aksi as tra', 'tra.tematik_indikator_permasalahan_id', '=', 'tip.id')
            ->select('tsr.instansi_id', DB::raw("'yes' as rencana_aksi"))
            ->distinct();

        $selectColumns = "
            tsr.instansi_id,
            'yes' AS tematik,
            COUNT(DISTINCT tsr.tema_id) AS tema_id_count" .
            ($temaSelects ? ", {$temaSelects}" : '') .
            ",
            COALESCE(pc.permasalahan, '---') AS permasalahan,
            COALESCE(rac.rencana_aksi, '---') AS rencana_aksi
        ";

        $tematiks = DB::table('tematik_sasaran_roadmap as tsr')
            ->join('tema as te', function ($join) use ($tahun) {
                $join->on('te.id', '=', 'tsr.tema_id')
                    ->where('te.tahun', $tahun);
            })
            ->leftJoinSub($permasalahanSub, 'pc', function ($join) {
                $join->on('pc.instansi_id', '=', 'tsr.instansi_id');
            })
            ->leftJoinSub($rencanaSub, 'rac', function ($join) {
                $join->on('rac.instansi_id', '=', 'tsr.instansi_id');
            })
            ->selectRaw($selectColumns)
            ->groupBy('tsr.instansi_id')
            ->get()
            ->keyBy('instansi_id');
        $instansis = KlpdInstansi::all();

        return view('webdashboard.rb-tematik', compact('instansis', 'tematiks', 'tahun', 'temas'));
    }

    public function hasilEvaluasi(Request $request)
    {
        $kegiatans = LkeKegiatan::orderByDesc('tahun')->orderByDesc('id')->get();
        $selectedKegiatanId = $request->get('kegiatan_id');
        if ($selectedKegiatanId && !$kegiatans->contains('id', (int) $selectedKegiatanId)) {
            $selectedKegiatanId = null;
        }
        if (!$selectedKegiatanId) {
            $selectedKegiatanId = $kegiatans->first()->id ?? null;
        }

        $predikatKeys = ['AA', 'A', 'A-', 'BB', 'B', 'CC', 'C', 'D'];
        $predikatCounts = [
            'kl' => array_fill_keys($predikatKeys, 0),
            'provinsi' => array_fill_keys($predikatKeys, 0),
            'kabupaten' => array_fill_keys($predikatKeys, 0),
        ];
        $rows = collect();

        if ($selectedKegiatanId) {
            $targetTotals = DB::table('lke_bobot as lb')
                ->join('lke_parameter as lp', 'lp.id', '=', 'lb.lke_parameter_id')
                ->where('lp.lke_kegiatan_id', $selectedKegiatanId)
                ->whereNotNull('lb.target_baik')
                ->groupBy('lb.group')
                ->select('lb.group', DB::raw('COUNT(*) as target_baik_total'))
                ->pluck('target_baik_total', 'group');

            $targetMetSub = DB::table('lke_test_tp_line as lttl')
                ->join('lke_bobot as lb', 'lb.id', '=', 'lttl.lke_bobot_id')
                ->join('lke_parameter as lp', 'lp.id', '=', 'lb.lke_parameter_id')
                ->where('lp.lke_kegiatan_id', $selectedKegiatanId)
                ->whereNotNull('lb.target_baik')
                ->whereRaw('lttl.score >= lb.target_baik')
                ->select('lttl.instansi_id', DB::raw('COUNT(*) as target_baik_met'))
                ->groupBy('lttl.instansi_id');

            $instansiRows = DB::table('klpd_instansi_new as ki')
                ->leftJoin('lke_test_tp as ltt', function ($join) use ($selectedKegiatanId) {
                    $join->on('ltt.instansi_id', '=', DB::raw('COALESCE(ki.id_before, ki.id)'))
                        ->where('ltt.lke_kegiatan_id', '=', $selectedKegiatanId);
                })
                ->leftJoinSub($targetMetSub, 'tbm', function ($join) {
                    $join->on('tbm.instansi_id', '=', DB::raw('COALESCE(ki.id_before, ki.id)'));
                })
                ->whereIn('ki.group', ['kl', 'provinsi', 'kabupaten'])
                ->orderByRaw("FIELD(ki.group , 'kl', 'provinsi', 'kabupaten') ASC")
                ->orderBy('ki.name')
                ->select(
                    'ki.id',
                    'ki.name',
                    'ki.name_before',
                    'ki.group',
                    'ltt.rb_general',
                    'ltt.rb_tematik',
                    'ltt.index_rb',
                    DB::raw('COALESCE(tbm.target_baik_met, 0) as target_baik_met')
                )
                ->get();

            $rows = $instansiRows->map(function ($row) use ($selectedKegiatanId, $targetTotals, &$predikatCounts) {
                $score = $row->index_rb !== null ? (float) $row->index_rb : null;
                $targetTotal = (int) ($targetTotals[$row->group] ?? 0);
                $targetMet = (int) $row->target_baik_met;
                $allTargetBaik = $targetTotal === 0 ? true : $targetMet >= $targetTotal;

                $predikat = $this->resolvePredikat($score, $allTargetBaik);

                if ($score !== null && isset($predikatCounts[$row->group][$predikat])) {
                    $predikatCounts[$row->group][$predikat]++;
                }

                return [
                    'id' => $row->id,
                    'name' => $row->name,
                    'group' => $row->group,
                    'group_label' => group_instansi($row->group),
                    'rb_general' => $row->rb_general !== null ? round($row->rb_general, 2) : '---',
                    'rb_tematik' => $row->rb_tematik !== null ? round($row->rb_tematik, 2) : '---',
                    'index_rb' => $row->index_rb !== null ? round($row->index_rb, 2) : '---',
                    'predikat' => $predikat,
                    'detail_url' => url('evaluasi/hasil-evaluasi/' . $row->id . '/' . $selectedKegiatanId),
                ];
            });
        }

        $predikatCountsData = [
            'kl' => array_values($predikatCounts['kl']),
            'provinsi' => array_values($predikatCounts['provinsi']),
            'kabupaten' => array_values($predikatCounts['kabupaten']),
            'labels' => $predikatKeys,
        ];

        return view('webdashboard.hasil-evaluasi', compact(
            'kegiatans',
            'selectedKegiatanId',
            'rows',
            'predikatCountsData'
        ));
    }

    private function resolvePredikat(?float $score, bool $allTargetBaik): string
    {
        if ($score === null) {
            return 'D';
        }

        if ($score >= 100) {
            return 'AA';
        }

        if ($score > 80 && $score < 100) {
            return $allTargetBaik ? 'A' : 'A-';
        }

        if ($score > 70 && $score <= 80) {
            return 'BB';
        }

        if ($score > 60 && $score <= 70) {
            return 'B';
        }

        if ($score > 50 && $score <= 60) {
            return 'CC';
        }

        if ($score > 30 && $score <= 50) {
            return 'C';
        }

        return 'D';
    }
}
