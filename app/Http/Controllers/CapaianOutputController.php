<?php
namespace App\Http\Controllers;

use App\Models\Tema;
use App\Models\KlpdInstansi;
use App\Models\GeneralPerencanaanTarget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\TematikSasaranRoadmap;
use App\Models\TematikRekapCapaianOutput;

class CapaianOutputController extends Controller
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

    public function rbTematikCapaianOutput(Request $request)
    {
        $years = Tema::select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');
        $selectedYear = $request->get('tahun', $years->first());
        $temaIds = Tema::when($selectedYear, function ($query) use ($selectedYear) {
                $query->where('tahun', $selectedYear);
            })
            ->orderBy('id')
            ->pluck('id');
        $temaIndexById = $temaIds->values()->mapWithKeys(function ($temaId, $index) {
            return [$temaId => $index + 1];
        });
        $klpdinstansis = KlpdInstansi::select('id', 'name', 'group')->get();
        $instansiIds = $klpdinstansis->pluck('id');

        $rekaps = TematikRekapCapaianOutput::select(
                'instansi_id',
                'tema_id',
                'capaian_output_tw1',
                'capaian_output_tw2',
                'capaian_output_tw3',
                'capaian_output_tw4',
                'capaian_output_total',
                'pengisian_tw1',
                'pengisian_tw2',
                'pengisian_tw3',
                'pengisian_tw4'
            )
            ->whereIn('instansi_id', $instansiIds)
            ->whereIn('tema_id', $temaIds)
            ->get()
            ->groupBy('instansi_id');

        $instansis = [];
        foreach ($klpdinstansis as $instansi) {
            $instansiData = [
                'nama' => $instansi->name,
                'group' => $instansi->group,
            ];

            foreach ($temaIndexById as $temaKey) {
                $instansiData[$temaKey] = [
                    'capaian_output_tw1' => 0,
                    'capaian_output_tw2' => 0,
                    'capaian_output_tw3' => 0,
                    'capaian_output_tw4' => 0,
                    'capaian_output_total' => 0,
                    'pengisian_tw1' => 0,
                    'pengisian_tw2' => 0,
                    'pengisian_tw3' => 0,
                    'pengisian_tw4' => 0,
                ];
            }

            foreach ($rekaps->get($instansi->id, []) as $rekap) {
                $temaKey = $temaIndexById->get($rekap->tema_id);
                if (!$temaKey) {
                    continue;
                }
                $instansiData[$temaKey] = [
                    'capaian_output_tw1' => $rekap->capaian_output_tw1,
                    'capaian_output_tw2' => $rekap->capaian_output_tw2,
                    'capaian_output_tw3' => $rekap->capaian_output_tw3,
                    'capaian_output_tw4' => $rekap->capaian_output_tw4,
                    'capaian_output_total' => $rekap->capaian_output_total,
                    'pengisian_tw1' => $rekap->pengisian_tw1,
                    'pengisian_tw2' => $rekap->pengisian_tw2,
                    'pengisian_tw3' => $rekap->pengisian_tw3,
                    'pengisian_tw4' => $rekap->pengisian_tw4,
                ];
            }

            $instansis[$instansi->id] = $instansiData;
        }

        $data_pertama = TematikRekapCapaianOutput::when($temaIds->isNotEmpty(), function ($query) use ($temaIds) {
                $query->whereIn('tema_id', $temaIds);
            })
            ->orderBy('updated_at', 'desc')
            ->first();

        return view('webdashboard.rb-tematik-capaianoutput', compact('instansis', 'data_pertama', 'years', 'selectedYear'));
    }

    public function rbTematikCapaianOutputGenerate($pilihan, Request $request)
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $selectedYear = $request->get('tahun');
        if (!$selectedYear) {
            $selectedYear = Tema::select('tahun')->distinct()->orderBy('tahun', 'desc')->value('tahun');
        }
        $temas = Tema::when($selectedYear, function ($query) use ($selectedYear) {
                $query->where('tahun', $selectedYear);
            })
            ->get(['id']);
        if ($temas->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada tema untuk tahun ini.');
        }

        $instansiQuery = KlpdInstansi::query()->select('id');
        if ($pilihan == 99 && $request->id) {
            $instansiQuery->where('id', $request->id);
        } elseif ($pilihan === 'all' || $pilihan === null || $pilihan === '0') {
            $instansiQuery->whereIn('group', ['kl', 'provinsi', 'kabupaten']);
        } else {
            $ranges = [
                1 => [1, 50],
                2 => [51, 100],
                3 => [101, 150],
                4 => [151, 200],
                5 => [201, 250],
                6 => [251, 300],
                7 => [301, 350],
                8 => [351, 400],
                9 => [401, 450],
                10 => [451, 500],
                11 => [501, 550],
                12 => [551, 600],
                13 => [601, 655],
            ];
            if (isset($ranges[$pilihan])) {
                $instansiQuery->whereBetween('id', $ranges[$pilihan]);
            } else {
                $instansiQuery->whereIn('group', ['kl', 'provinsi', 'kabupaten']);
            }
        }

        $instansiIds = $instansiQuery->pluck('id');
        if ($instansiIds->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada instansi untuk diproses.');
        }

        $temaIds = $temas->pluck('id');
        $capaianTw1 = "CAST(NULLIF(REPLACE(trao.capaian_output_tw1, '%', ''), '') AS DECIMAL(12,2))";
        $capaianTw2 = "CAST(NULLIF(REPLACE(trao.capaian_output_tw2, '%', ''), '') AS DECIMAL(12,2))";
        $capaianTw3 = "CAST(NULLIF(REPLACE(trao.capaian_output_tw3, '%', ''), '') AS DECIMAL(12,2))";
        $capaianTw4 = "CAST(NULLIF(REPLACE(trao.capaian_output_tw4, '%', ''), '') AS DECIMAL(12,2))";
        $capaianTotal = "CAST(NULLIF(REPLACE(trao.capaian_output_total, '%', ''), '') AS DECIMAL(12,2))";

        $aggregates = DB::table('tematik_sasaran_roadmap as tsr')
            ->join('tematik_indikator_roadmap as tir', 'tir.tematik_sasaran_roadmap_id', '=', 'tsr.id')
            ->join('tematik_permasalahan as tp', 'tp.tematik_indikator_roadmap_id', '=', 'tir.id')
            ->join('tematik_indikator_permasalahan as tip', 'tip.tematik_permasalahan_id', '=', 'tp.id')
            ->join('tematik_rencana_aksi as tra', 'tra.tematik_indikator_permasalahan_id', '=', 'tip.id')
            ->join('tematik_rencana_aksi_output as trao', 'trao.tematik_rencana_aksi_id', '=', 'tra.id')
            ->whereIn('tsr.tema_id', $temaIds)
            ->whereIn('tsr.instansi_id', $instansiIds)
            ->select(
                'tsr.instansi_id',
                'tsr.tema_id',
                DB::raw("SUM(CASE WHEN trao.target_tw1 > 0 AND {$capaianTw1} > 0 AND {$capaianTw1} < 300 THEN {$capaianTw1} ELSE 0 END) as sum_tw1"),
                DB::raw("SUM(CASE WHEN trao.target_tw1 > 0 AND {$capaianTw1} > 0 AND {$capaianTw1} < 300 THEN 1 ELSE 0 END) as count_tw1"),
                DB::raw("SUM(CASE WHEN trao.target_tw1 > 0 THEN 1 ELSE 0 END) as pembagi_tw1"),
                DB::raw("SUM(CASE WHEN trao.target_tw1 > 0 AND {$capaianTw1} > 0 THEN 1 ELSE 0 END) as pembilang_tw1"),
                DB::raw("SUM(CASE WHEN trao.target_tw2 > 0 AND {$capaianTw2} > 0 AND {$capaianTw2} < 300 THEN {$capaianTw2} ELSE 0 END) as sum_tw2"),
                DB::raw("SUM(CASE WHEN trao.target_tw2 > 0 AND {$capaianTw2} > 0 AND {$capaianTw2} < 300 THEN 1 ELSE 0 END) as count_tw2"),
                DB::raw("SUM(CASE WHEN trao.target_tw2 > 0 THEN 1 ELSE 0 END) as pembagi_tw2"),
                DB::raw("SUM(CASE WHEN trao.target_tw2 > 0 AND {$capaianTw2} > 0 THEN 1 ELSE 0 END) as pembilang_tw2"),
                DB::raw("SUM(CASE WHEN trao.target_tw3 > 0 AND {$capaianTw3} > 0 AND {$capaianTw3} < 300 THEN {$capaianTw3} ELSE 0 END) as sum_tw3"),
                DB::raw("SUM(CASE WHEN trao.target_tw3 > 0 AND {$capaianTw3} > 0 AND {$capaianTw3} < 300 THEN 1 ELSE 0 END) as count_tw3"),
                DB::raw("SUM(CASE WHEN trao.target_tw3 > 0 THEN 1 ELSE 0 END) as pembagi_tw3"),
                DB::raw("SUM(CASE WHEN trao.target_tw3 > 0 AND {$capaianTw3} > 0 THEN 1 ELSE 0 END) as pembilang_tw3"),
                DB::raw("SUM(CASE WHEN trao.target_tw4 > 0 AND {$capaianTw4} > 0 AND {$capaianTw4} < 300 THEN {$capaianTw4} ELSE 0 END) as sum_tw4"),
                DB::raw("SUM(CASE WHEN trao.target_tw4 > 0 AND {$capaianTw4} > 0 AND {$capaianTw4} < 300 THEN 1 ELSE 0 END) as count_tw4"),
                DB::raw("SUM(CASE WHEN trao.target_tw4 > 0 THEN 1 ELSE 0 END) as pembagi_tw4"),
                DB::raw("SUM(CASE WHEN trao.target_tw4 > 0 AND {$capaianTw4} > 0 THEN 1 ELSE 0 END) as pembilang_tw4"),
                DB::raw("SUM(CASE WHEN trao.target_total > 0 AND {$capaianTotal} < 300 THEN {$capaianTotal} ELSE 0 END) as sum_total"),
                DB::raw("SUM(CASE WHEN trao.target_total > 0 AND {$capaianTotal} < 300 THEN 1 ELSE 0 END) as count_total")
            )
            ->groupBy('tsr.instansi_id', 'tsr.tema_id')
            ->get();

        $aggregateMap = [];
        foreach ($aggregates as $aggregate) {
            $key = $aggregate->instansi_id . ':' . $aggregate->tema_id;
            $aggregateMap[$key] = $aggregate;
        }

        $rows = [];
        $now = now();
        $updatedBy = Auth::id();
        foreach ($instansiIds as $instansiId) {
            foreach ($temaIds as $temaId) {
                $key = $instansiId . ':' . $temaId;
                $aggregate = $aggregateMap[$key] ?? null;
                $countTw1 = $aggregate ? (int) $aggregate->count_tw1 : 0;
                $countTw2 = $aggregate ? (int) $aggregate->count_tw2 : 0;
                $countTw3 = $aggregate ? (int) $aggregate->count_tw3 : 0;
                $countTw4 = $aggregate ? (int) $aggregate->count_tw4 : 0;
                $countTotal = $aggregate ? (int) $aggregate->count_total : 0;
                $pembagiTw1 = $aggregate ? (int) $aggregate->pembagi_tw1 : 0;
                $pembagiTw2 = $aggregate ? (int) $aggregate->pembagi_tw2 : 0;
                $pembagiTw3 = $aggregate ? (int) $aggregate->pembagi_tw3 : 0;
                $pembagiTw4 = $aggregate ? (int) $aggregate->pembagi_tw4 : 0;
                $pembilangTw1 = $aggregate ? (int) $aggregate->pembilang_tw1 : 0;
                $pembilangTw2 = $aggregate ? (int) $aggregate->pembilang_tw2 : 0;
                $pembilangTw3 = $aggregate ? (int) $aggregate->pembilang_tw3 : 0;
                $pembilangTw4 = $aggregate ? (int) $aggregate->pembilang_tw4 : 0;
                $sumTw1 = $aggregate ? (float) $aggregate->sum_tw1 : 0;
                $sumTw2 = $aggregate ? (float) $aggregate->sum_tw2 : 0;
                $sumTw3 = $aggregate ? (float) $aggregate->sum_tw3 : 0;
                $sumTw4 = $aggregate ? (float) $aggregate->sum_tw4 : 0;
                $sumTotal = $aggregate ? (float) $aggregate->sum_total : 0;

                $rows[] = [
                    'instansi_id' => $instansiId,
                    'tema_id' => $temaId,
                    'capaian_output_tw1' => $countTw1 > 0 ? ($sumTw1 / $countTw1) : 0,
                    'capaian_output_tw2' => $countTw2 > 0 ? ($sumTw2 / $countTw2) : 0,
                    'capaian_output_tw3' => $countTw3 > 0 ? ($sumTw3 / $countTw3) : 0,
                    'capaian_output_tw4' => $countTw4 > 0 ? ($sumTw4 / $countTw4) : 0,
                    'capaian_output_total' => $countTotal > 0 ? ($sumTotal / $countTotal) : 0,
                    'pengisian_tw1' => $pembagiTw1 > 0 ? (100 * $pembilangTw1 / $pembagiTw1) : 0,
                    'pengisian_tw2' => $pembagiTw2 > 0 ? (100 * $pembilangTw2 / $pembagiTw2) : 0,
                    'pengisian_tw3' => $pembagiTw3 > 0 ? (100 * $pembilangTw3 / $pembagiTw3) : 0,
                    'pengisian_tw4' => $pembagiTw4 > 0 ? (100 * $pembilangTw4 / $pembagiTw4) : 0,
                    'updated_by' => $updatedBy,
                    'updated_at' => $now,
                ];
            }
        }

        TematikRekapCapaianOutput::upsert(
            $rows,
            ['instansi_id', 'tema_id'],
            [
                'capaian_output_tw1',
                'capaian_output_tw2',
                'capaian_output_tw3',
                'capaian_output_tw4',
                'capaian_output_total',
                'pengisian_tw1',
                'pengisian_tw2',
                'pengisian_tw3',
                'pengisian_tw4',
                'updated_by',
                'updated_at',
            ]
        );

        return redirect()->back()->with('success', 'Generate capaian output selesai.');
    }

    public function rbGeneralCapaianOutput(Request $request)
    {
        $years = GeneralPerencanaanTarget::select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');
        $selectedYear = $request->get('tahun', $years->first());

        $capaians = DB::table('klpd_instansi as ki')
            ->leftJoin('general_perencanaan as gp', 'gp.instansi_id', '=', 'ki.id')
            ->leftJoin('general_perencanaan_target as gpt', 'gpt.general_perencanaan_id', '=', 'gp.id')
            ->leftJoin('general_rencana_aksi as gra', 'gra.general_perencanaan_target_id', '=', 'gpt.id')
            ->leftJoin('general_rencana_aksi_output as grao', 'grao.general_rencana_aksi_id', '=', 'gra.id')
            ->when($selectedYear, function ($query) use ($selectedYear) {
                $query->where('gpt.tahun', $selectedYear);
            })
            ->select('ki.name', 'ki.group',
                DB::raw('SUM(CASE WHEN grao.target_tw1 > 0 THEN 1 ELSE 0 END) as jumlah_target_tw1'),
                DB::raw('SUM(CASE WHEN grao.target_tw2 > 0 THEN 1 ELSE 0 END) as jumlah_target_tw2'),
                DB::raw('SUM(CASE WHEN grao.target_tw3 > 0 THEN 1 ELSE 0 END) as jumlah_target_tw3'),
                DB::raw('SUM(CASE WHEN grao.target_tw4 > 0 THEN 1 ELSE 0 END) as jumlah_target_tw4'),
                DB::raw('SUM(CASE WHEN grao.realisasi_output_tw1 > 0 THEN 1 ELSE 0 END) as jumlah_realisasi_output_tw1'),
                DB::raw('SUM(CASE WHEN grao.realisasi_output_tw2 > 0 THEN 1 ELSE 0 END) as jumlah_realisasi_output_tw2'),
                DB::raw('SUM(CASE WHEN grao.realisasi_output_tw3 > 0 THEN 1 ELSE 0 END) as jumlah_realisasi_output_tw3'),
                DB::raw('SUM(CASE WHEN grao.realisasi_output_tw4 > 0 THEN 1 ELSE 0 END) as jumlah_realisasi_output_tw4'),
                DB::raw('SUM(grao.capaian_output_tw1) as jumlah_capaian_output_tw1'),
                DB::raw('SUM(grao.capaian_output_tw2) as jumlah_capaian_output_tw2'),
                DB::raw('SUM(grao.capaian_output_tw3) as jumlah_capaian_output_tw3'),
                DB::raw('SUM(grao.capaian_output_tw4) as jumlah_capaian_output_tw4'),
                DB::raw('SUM(grao.capaian_output_total) as jumlah_capaian_output_total'),
                DB::raw('SUM(case when grao.target_tw1 > 0 or grao.target_tw2 > 0 or grao.target_tw3 > 0 or grao.target_tw4 > 0 then 1 else 0 end) as jumlah_target_total')
            )
            ->groupBy('ki.name', 'ki.group')
            ->orderBy(DB::raw('SUM(CASE WHEN grao.target_tw1 > 0 THEN 1 ELSE 0 END)'), 'desc')
            ->get();
        
        foreach ($capaians as $capaian) {
            $capaian->realisasi_tw1 = $capaian->jumlah_target_tw1 > 0 ? round(($capaian->jumlah_realisasi_output_tw1 / $capaian->jumlah_target_tw1) * 100) : '';
            $capaian->realisasi_tw2 = $capaian->jumlah_target_tw2 > 0 ? round(($capaian->jumlah_realisasi_output_tw2 / $capaian->jumlah_target_tw2) * 100) : '';
            $capaian->realisasi_tw3 = $capaian->jumlah_target_tw3 > 0 ? round(($capaian->jumlah_realisasi_output_tw3 / $capaian->jumlah_target_tw3) * 100) : '';
            $capaian->realisasi_tw4 = $capaian->jumlah_target_tw4 > 0 ? round(($capaian->jumlah_realisasi_output_tw4 / $capaian->jumlah_target_tw4) * 100) : '';
            $capaian->output_tw1 = $capaian->jumlah_capaian_output_tw1 > 0 ? round($capaian->jumlah_capaian_output_tw1 / $capaian->jumlah_target_total) : '';
            $capaian->output_tw2 = $capaian->jumlah_capaian_output_tw2 > 0 ? round($capaian->jumlah_capaian_output_tw2 / $capaian->jumlah_target_total) : '';
            $capaian->output_tw3 = $capaian->jumlah_capaian_output_tw3 > 0 ? round($capaian->jumlah_capaian_output_tw3 / $capaian->jumlah_target_total) : '';
            $capaian->output_tw4 = $capaian->jumlah_capaian_output_tw4 > 0 ? round($capaian->jumlah_capaian_output_tw4 / $capaian->jumlah_target_total) : '';
            $capaian->output_total = $capaian->jumlah_target_total > 0 ? round($capaian->jumlah_capaian_output_total / $capaian->jumlah_target_total) : '';
            
            $capaian->realisasi_tw1 = $capaian->realisasi_tw1 != '' ? ($capaian->realisasi_tw1 > 100 ? 100 . '%' : $capaian->realisasi_tw1 . '%') : '';
            $capaian->realisasi_tw2 = $capaian->realisasi_tw2 != '' ? ($capaian->realisasi_tw2 > 100 ? 100 . '%' : $capaian->realisasi_tw2 . '%') : '';
            $capaian->realisasi_tw3 = $capaian->realisasi_tw3 != '' ? ($capaian->realisasi_tw3 > 100 ? 100 . '%' : $capaian->realisasi_tw3 . '%') : '';
            $capaian->realisasi_tw4 = $capaian->realisasi_tw4 != '' ? ($capaian->realisasi_tw4 > 100 ? 100 . '%' : $capaian->realisasi_tw4 . '%') : '';
            $capaian->output_tw1 = $capaian->output_tw1 != '' ? ($capaian->output_tw1 > 100 ? 100 . '%' : $capaian->output_tw1 . '%') : '';
            $capaian->output_tw2 = $capaian->output_tw2 != '' ? ($capaian->output_tw2 > 100 ? 100 . '%' : $capaian->output_tw2 . '%') : '';
            $capaian->output_tw3 = $capaian->output_tw3 != '' ? ($capaian->output_tw3 > 100 ? 100 . '%' : $capaian->output_tw3 . '%') : '';
            $capaian->output_tw4 = $capaian->output_tw4 != '' ? ($capaian->output_tw4 > 100 ? 100 . '%' : $capaian->output_tw4 . '%') : '';
            $capaian->output_total = $capaian->output_total != '' ? ($capaian->output_total > 100 ? 100 . '%' : $capaian->output_total . '%') : '';
        }
        
        return view('webdashboard.rb-general-capaianoutput', compact('capaians', 'years', 'selectedYear'));
    }

}
