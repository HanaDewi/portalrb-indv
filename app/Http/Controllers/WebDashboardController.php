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

    public function index(Request $request)
    {
        $kegiatans = LkeKegiatan::orderByDesc('tahun')->orderByDesc('id')->get();
        $selectedKegiatanId = $request->get('kegiatan_id');
        if ($selectedKegiatanId && !$kegiatans->contains('id', (int) $selectedKegiatanId)) {
            $selectedKegiatanId = null;
        }
        if (!$selectedKegiatanId) {
            $selectedKegiatanId = $kegiatans->first()->id ?? null;
        }

        // CARI ID KEGIATAN TAHUN LALU UNTUK BASELINE
        $kegiatanNow = $kegiatans->firstWhere('id', (int)$selectedKegiatanId);
        $tahunNow = $kegiatanNow ? $kegiatanNow->tahun : 2025;
        $kegiatanPrev = $kegiatans->firstWhere('tahun', $tahunNow - 1);
        $kegiatanPrevId = $kegiatanPrev ? $kegiatanPrev->id : null;

        $predikatKeys = ['AA', 'A', 'A-', 'BB', 'B', 'CC', 'C', 'D'];
        $predikatCounts = [
            'kementerian' => array_fill_keys($predikatKeys, 0),
            'lembaga'     => array_fill_keys($predikatKeys, 0),
            'provinsi'    => array_fill_keys($predikatKeys, 0),
            'kabupaten'   => array_fill_keys($predikatKeys, 0),
            'kota'        => array_fill_keys($predikatKeys, 0),
            'lain'        => array_fill_keys($predikatKeys, 0),
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
                ->leftJoin('lke_test_tp as ltt_prev', function ($join) use ($kegiatanPrevId) { // JOIN UNTUK BASELINE
                    $join->on('ltt_prev.instansi_id', '=', DB::raw('COALESCE(ki.id_before, ki.id)'))
                        ->where('ltt_prev.lke_kegiatan_id', '=', $kegiatanPrevId);
                })
                ->leftJoinSub($targetMetSub, 'tbm', function ($join) {
                    $join->on('tbm.instansi_id', '=', DB::raw('COALESCE(ki.id_before, ki.id)'));
                })
                ->whereIn('ki.group', ['kl', 'provinsi', 'kabupaten'])
                ->orderBy('ki.name')
                ->select(
                    'ki.id', 'ki.name', 'ki.group', 'ki.prov_id',
                    'ltt.rb_general_penyesuaian', 'ltt.rb_tematik', 'ltt.index_rb',
                    'ltt_prev.index_rb as index_rb_prev', // AMBIL NILAI TAHUN LALU
                    DB::raw('COALESCE(tbm.target_baik_met, 0) as target_baik_met')
                )->get();

            $rows = $instansiRows->map(function ($row) use ($selectedKegiatanId, $targetTotals, &$predikatCounts) {
                $spesifikGroup = $row->group;
                if ($row->group === 'kl') {
                    $spesifikGroup = str_contains($row->name, 'Kementerian') ? 'kementerian' : 'lembaga';
                } elseif ($row->group === 'kabupaten') {
                    $spesifikGroup = str_contains($row->name, 'Kabupaten') ? 'kabupaten' : 'kota';
                }

                $score = $row->index_rb !== null ? (float) $row->index_rb : null;
                $targetTotal = (int) ($targetTotals[$row->group] ?? 0);
                $targetMet = (int) $row->target_baik_met;
                $allTargetBaik = $targetTotal === 0 ? true : $targetMet >= $targetTotal;
                $predikat = $this->resolvePredikat($score, $allTargetBaik);

                if ($score !== null && isset($predikatCounts[$spesifikGroup][$predikat])) {
                    $predikatCounts[$spesifikGroup][$predikat]++;
                }

                return [
                    'id' => $row->id,
                    'prov_id' => $row->prov_id,
                    'name' => $row->name,
                    'group' => $spesifikGroup, 
                    'group_label' => ucfirst($spesifikGroup),
                    'rb_general_penyesuaian' => $row->rb_general_penyesuaian !== null ? round($row->rb_general_penyesuaian, 2) : '---',
                    'rb_tematik' => $row->rb_tematik !== null ? round($row->rb_tematik, 2) : '---',
                    'index_rb' => $row->index_rb !== null ? round($row->index_rb, 2) : '---',
                    'index_rb_prev' => $row->index_rb_prev !== null ? round($row->index_rb_prev, 2) : '---', // KIRIM KE BLADE
                    'predikat' => $predikat,
                    'detail_url' => route('webdashboard.detail', ['id' => $row->id, 'tahun' => $selectedKegiatanId]),
                ];
            });
        }

        $predikatCountsData = [
            'kementerian' => array_values($predikatCounts['kementerian']),
            'lembaga'     => array_values($predikatCounts['lembaga']),
            'provinsi'    => array_values($predikatCounts['provinsi']),
            'kabupaten'   => array_values($predikatCounts['kabupaten']),
            'kota'        => array_values($predikatCounts['kota']),
            'lain'        => array_values($predikatCounts['lain']),
            'labels'      => $predikatKeys,
        ];

        $tahun = $tahunNow;

        return view('webdashboard.index', compact(
            'kegiatans', 'selectedKegiatanId', 'rows', 'predikatCountsData', 'tahun'
        ));
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
            'kementerian' => array_fill_keys($predikatKeys, 0),
            'lembaga'     => array_fill_keys($predikatKeys, 0),
            'provinsi'    => array_fill_keys($predikatKeys, 0),
            'kabupaten'   => array_fill_keys($predikatKeys, 0),
            'kota'        => array_fill_keys($predikatKeys, 0),
            'lain'        => array_fill_keys($predikatKeys, 0),
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
                ->orderBy('ki.name')
                ->select(
                    'ki.id', 'ki.name', 'ki.name_before', 'ki.group',
                    'ltt.rb_general_penyesuaian', 'ltt.rb_tematik', 'ltt.index_rb',
                    DB::raw('COALESCE(tbm.target_baik_met, 0) as target_baik_met')
                )->get();

            $rows = $instansiRows->map(function ($row) use ($selectedKegiatanId, $targetTotals, &$predikatCounts) {
                
                // LOGIKA PEMISAH KATEGORI (Sudah Diperbaiki)
                $spesifikGroup = $row->group;
                if ($row->group === 'kl') {
                    $spesifikGroup = str_contains($row->name, 'Kementerian') ? 'kementerian' : 'lembaga';
                } elseif ($row->group === 'kabupaten') {
                    // Jika ada kata 'Kabupaten' = kabupaten. Sisanya = kota.
                    $spesifikGroup = str_contains($row->name, 'Kabupaten') ? 'kabupaten' : 'kota';
                }

                $score = $row->index_rb !== null ? (float) $row->index_rb : null;
                $targetTotal = (int) ($targetTotals[$row->group] ?? 0);
                $targetMet = (int) $row->target_baik_met;
                $allTargetBaik = $targetTotal === 0 ? true : $targetMet >= $targetTotal;

                $predikat = $this->resolvePredikat($score, $allTargetBaik);

                if ($score !== null && isset($predikatCounts[$spesifikGroup][$predikat])) {
                    $predikatCounts[$spesifikGroup][$predikat]++;
                }

                return [
                    'id' => $row->id,
                    'name' => $row->name,
                    'group' => $spesifikGroup,
                    'group_label' => ucfirst($spesifikGroup),
                    'rb_general_penyesuaian' => $row->rb_general_penyesuaian !== null ? round($row->rb_general_penyesuaian, 2) : '---',
                    'rb_tematik' => $row->rb_tematik !== null ? round($row->rb_tematik, 2) : '---',
                    'index_rb' => $row->index_rb !== null ? round($row->index_rb, 2) : '---',
                    'predikat' => $predikat,
                    'detail_url' => route('webdashboard.detail', ['id' => $row->id, 'tahun' => $selectedKegiatanId]),
                ];
            });
        }

        $predikatCountsData = [
            'kementerian' => array_values($predikatCounts['kementerian']),
            'lembaga'     => array_values($predikatCounts['lembaga']),
            'provinsi'    => array_values($predikatCounts['provinsi']),
            'kabupaten'   => array_values($predikatCounts['kabupaten']),
            'kota'        => array_values($predikatCounts['kota']),
            'lain'        => array_values($predikatCounts['lain']),
            'labels'      => $predikatKeys,
        ];

        return view('webdashboard.hasil-evaluasi', compact(
            'kegiatans', 'selectedKegiatanId', 'rows', 'predikatCountsData'
        ));
    }
    public function rbGeneral(Request $request)
    {
        $tahun = $request->get('tahun', 2025);

        // Ambil data instansi dari database (pastikan 'lain' tidak ikut)
        $instansis = KlpdInstansi::whereIn('group', ['kl', 'provinsi', 'kabupaten'])->get();
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
        
        // Siapkan wadah hitungan untuk 5 kategori
        $yes_kementerian = $no_kementerian = 0;
        $yes_lembaga = $no_lembaga = 0;
        $yes_prov = $no_prov = 0;
        $yes_kab = $no_kab = 0;
        $yes_kota = $no_kota = 0;

        foreach ($instansis as $instansi) {
            $plans   = $all_plans->get($instansi->id, collect());
            $target  = $all_targets->get($instansi->id);
            $rencana = $all_rencana_aksi->get($instansi->id);

            $baseline    = $plans->count() >= 5;
            $target_ok   = ($target->target_count ?? 0) >= 5;
            $rencana_ok  = ($rencana->rencana_aksi_count ?? 0) >= 5;
            $semua       = $baseline && $target_ok && $rencana_ok;

            // Logika pemisah kategori spesifik
            $spesifikGroup = $instansi->group;
            if ($instansi->group === 'kl') {
                $spesifikGroup = str_contains($instansi->name, 'Kementerian') ? 'kementerian' : 'lembaga';
            } elseif ($instansi->group === 'kabupaten') {
                $spesifikGroup = str_contains($instansi->name, 'Kabupaten') ? 'kabupaten' : 'kota';
            }

            // Masukkan ke hitungan masing-masing
            switch ($spesifikGroup) {
                case 'kementerian': $semua ? $yes_kementerian++ : $no_kementerian++; break;
                case 'lembaga':     $semua ? $yes_lembaga++ : $no_lembaga++; break;
                case 'provinsi':    $semua ? $yes_prov++ : $no_prov++; break;
                case 'kabupaten':   $semua ? $yes_kab++ : $no_kab++; break;
                case 'kota':        $semua ? $yes_kota++ : $no_kota++; break;
            }

            $rekap[] = [
                'instansi'     => $instansi,
                'group'        => group_instansi($spesifikGroup),
                'baseline'     => $baseline,
                'tahun_target' => $tahun,
                'target'       => $target_ok,
                'rencana_aksi' => $rencana_ok,
                'semua'        => $semua,
            ];
        }

        // Siapkan data JSON untuk modal popup di blade
        $rekapJson = collect($rekap)->map(function ($d) {
            return [
                'name'         => $d['instansi']->name,
                'group'        => $d['group'],
                'group_key'    => strtolower($d['group']), // ini akan mengembalikan kementerian/lembaga/provinsi/kabupaten/kota
                'baseline'     => (bool) $d['baseline'],
                'target'       => (bool) $d['target'],
                'rencana_aksi' => (bool) $d['rencana_aksi'],
                'semua'        => (bool) $d['semua'],
                'detail_url'   => url('/rencana_aksi/rb-general/rekap_data?instansi_id[]=' . $d['instansi']->id),
            ];
        })->values();

        return view('webdashboard.rb-general', compact(
            'rekap', 'rekapJson',
            'yes_kementerian', 'no_kementerian',
            'yes_lembaga', 'no_lembaga',
            'yes_prov', 'no_prov',
            'yes_kab', 'no_kab',
            'yes_kota', 'no_kota',
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

    public function detail($id, $tahun)
    {
        $instansi = \App\Models\KlpdInstansi::find($id);
        if (!$instansi) abort(404, 'Instansi tidak ditemukan');

        $instansiId = $instansi->id_before ?? $instansi->id;

        $kegiatanNow  = DB::table('lke_kegiatan')->where('id', $tahun)->first();
        $kegiatanPrev = DB::table('lke_kegiatan')->where('tahun', ($kegiatanNow?->tahun ?? 2025) - 1)->first();

        $kegiatanNowId  = $kegiatanNow?->id;
        $kegiatanPrevId = $kegiatanPrev?->id;
        $tahunLabel = $kegiatanNow?->tahun ?? $tahun;

        // ── Summary (lke_test_tp) ──────────────────────────────────
        $evalNow  = DB::table('lke_test_tp')
            ->where('instansi_id', $instansiId)
            ->where('lke_kegiatan_id', $kegiatanNowId)
            ->first();

        $evalPrev = DB::table('lke_test_tp')
            ->where('instansi_id', $instansiId)
            ->where('lke_kegiatan_id', $kegiatanPrevId)
            ->first();

        // Predikat
        $score = $evalNow ? (float)$evalNow->index_rb : null;
        $targetTotal = DB::table('lke_bobot as lb')
            ->join('lke_parameter as lp','lp.id','=','lb.lke_parameter_id')
            ->where('lp.lke_kegiatan_id', $kegiatanNowId)
            ->whereNotNull('lb.target_baik')
            ->where('lb.group', $instansi->group)
            ->count();
        $targetMet = DB::table('lke_test_tp_line as lttl')
            ->join('lke_bobot as lb','lb.id','=','lttl.lke_bobot_id')
            ->join('lke_parameter as lp','lp.id','=','lb.lke_parameter_id')
            ->where('lp.lke_kegiatan_id', $kegiatanNowId)
            ->whereNotNull('lb.target_baik')
            ->whereRaw('lttl.score >= lb.target_baik')
            ->where('lttl.instansi_id', $instansiId)
            ->count();
        $allTargetBaik = $targetTotal === 0 ? true : $targetMet >= $targetTotal;
        $predikat = $this->resolvePredikat($score, $allTargetBaik);

        // Predikat N-1
        $scorePrev = $evalPrev ? (float)$evalPrev->index_rb : null;
        $predikatPrev = $this->resolvePredikat($scorePrev, true);

        // ── Rincian per indikator ──────────────────────────────────
        // Helper closure untuk ambil rincian per kegiatan
        $getRincianData = function($kegId) use ($instansiId) {
            if (!$kegId) return collect();
            return DB::table('lke_test_tp_line as lttl')
                ->join('lke_bobot as lb', 'lb.id', '=', 'lttl.lke_bobot_id')
                ->join('lke_parameter as lp', 'lp.id', '=', 'lb.lke_parameter_id')
                ->where('lttl.instansi_id', $instansiId)
                ->where('lp.lke_kegiatan_id', $kegId)
                ->select(
                    'lp.nama as indikator',
                    'lb.bobot',
                    'lb.min_value',
                    'lb.max_value',
                    'lb.target_baik',
                    'lttl.score'
                )
                ->get()
                ->keyBy('indikator');
        };

        $rincianNow  = $getRincianData($kegiatanNowId);
        $rincianPrev = $getRincianData($kegiatanPrevId);

        // Helper hitung skor index dan capaian
        $hitungIndex = function($score, $bobot, $maxValue) {
            if ($score === null) return [null, null];
            $s = (float)$score;
            $b = (float)$bobot;
            if ($maxValue !== null) {
                $mv = (float)$maxValue;
                $skorIndex   = $mv > 0 ? round(($s / $mv) * $b, 2) : 0;
                $capaianPct  = $mv > 0 ? round(($s / $mv) * 100, 2) : 0;
            } else {
                // Skor sudah berbobot
                $skorIndex   = $s;
                $capaianPct  = $b > 0 ? round(($s / $b) * 100, 2) : 0;
            }
            return [$skorIndex, $capaianPct];
        };

        // Gabungkan rincian now + prev
        $rincian = $rincianNow->map(function($row) use ($rincianPrev, $hitungIndex) {
            [$skorIdx, $capaian] = $hitungIndex($row->score, $row->bobot, $row->max_value);

            $prevRow = $rincianPrev->get($row->indikator);
            [$skorIdxPrev, ] = $prevRow
                ? $hitungIndex($prevRow->score, $prevRow->bobot, $prevRow->max_value)
                : [null, null];

            $selisih = null;
            $ketSelisih = '-';
            if ($skorIdx !== null && $skorIdxPrev !== null) {
                $selisih = round($skorIdx - $skorIdxPrev, 2);
                $ketSelisih = $selisih > 0 ? 'Naik' : ($selisih < 0 ? 'Turun' : '-');
            }

            // Ket tercapai/tidak
            $ket = '-';
            $ketColor = 'text-slate-500';
            if ($row->target_baik !== null && $row->score !== null) {
                if ((float)$row->score >= (float)$row->target_baik) {
                    $ket = 'Tercapai';
                    $ketColor = 'text-green-600';
                } else {
                    $ket = 'di bawah Target';
                    $ketColor = 'text-orange-500';
                }
            }

            return (object)[
                'indikator'     => $row->indikator,
                'bobot'         => $row->bobot ?? '-',
                'target_baik'   => $row->target_baik ?? '-',
                'score'         => $row->score ?? '-',
                'ket'           => $ket,
                'ketColor'      => $ketColor,
                'skor_index'    => $skorIdx ?? '-',
                'capaian_pct'   => $capaian  ?? '-',
                'skor_prev'     => $prevRow?->score ?? '-',
                'skor_index_prev' => $skorIdxPrev ?? '-',
                'selisih'       => $selisih,
                'ket_selisih'   => $ketSelisih,
            ];
        });

        // ── Hitung sub-total per grup untuk Tabel Hasil Evaluasi RB ──
        $grupNow = [
            'strategi'   => 0.0,
            'kebijakan'  => 0.0,
            'sasaran'    => 0.0,
        ];
        $grupPrev = $grupNow;

        $strategiKeywords  = ['Rencana Aksi Pembangunan', 'Tingkat Implementasi Rencana Aksi'];
        $saasaranKeywords  = ['SPBE','Opini BPK','Tindak Lanjut Rekomendasi','BerAkhlak',
                              'Survei Penilaian Integritas','Survei Kepuasan Masyarakat',
                              'Prioritas Nasional','IKU Kementerian','IKU Makro','IKU Non Makro'];

        foreach ($rincianNow as $nama => $row) {
            [$si, ] = $hitungIndex($row->score, $row->bobot, $row->max_value);
            if ($si === null) continue;
            $isStrategi = false;
            $isSasaran  = false;
            foreach ($strategiKeywords as $kw) {
                if (stripos($nama, $kw) !== false) { $isStrategi = true; break; }
            }
            foreach ($saasaranKeywords as $kw) {
                if (stripos($nama, $kw) !== false) { $isSasaran = true; break; }
            }
            if ($isStrategi)      $grupNow['strategi']  += $si;
            elseif ($isSasaran)   $grupNow['sasaran']   += $si;
            else                  $grupNow['kebijakan'] += $si;
        }

        foreach ($rincianPrev as $nama => $row) {
            [$si, ] = $hitungIndex($row->score, $row->bobot, $row->max_value);
            if ($si === null) continue;
            $isStrategi = false; $isSasaran = false;
            foreach ($strategiKeywords as $kw) {
                if (stripos($nama, $kw) !== false) { $isStrategi = true; break; }
            }
            foreach ($saasaranKeywords as $kw) {
                if (stripos($nama, $kw) !== false) { $isSasaran = true; break; }
            }
            if ($isStrategi)      $grupPrev['strategi']  += $si;
            elseif ($isSasaran)   $grupPrev['sasaran']   += $si;
            else                  $grupPrev['kebijakan'] += $si;
        }

        $grupNow  = array_map(fn($v) => round($v, 2), $grupNow);
        $grupPrev = array_map(fn($v) => round($v, 2), $grupPrev);

        return view('webdashboard.detail', [
            'instansi'    => $instansi,
            'tahun'        => $tahunLabel,
            'evalNow'     => $evalNow,
            'evalPrev'    => $evalPrev,
            'predikat'    => $predikat,
            'predikatPrev'=> $predikatPrev,
            'rincian'     => $rincian,
            'grupNow'     => $grupNow,
            'grupPrev'    => $grupPrev,
        ]);
    }
}