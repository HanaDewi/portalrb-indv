<?php

namespace App\Http\Controllers\Akip;

use App\Http\Controllers\Controller;
use App\Models\Akip\EvaluasiSakip;
use App\Models\InstansiTimEvaluasi;
use App\Models\KlpdInstansi;
use App\Models\OpenAccessSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EvaluasiSakipController extends Controller
{
    protected $currentUser;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->currentUser = auth()->user();
            foreach (allowed_url('akip') as $allowed) {
                if ($request->is($allowed)) {
                    return $next($request);
                }
            }
            abort('403');
        });
    }

    public function dashboard(Request $request)
    {
        // Get level user
        $level = $this->currentUser->level;

        if ($level == 'tpn') {
            // Use distinct names to pass into view
            $selectedYear = $request->input('tahun', date('Y'));
            $selectedPeriode = $request->input('tw') ?? 1;
            $selectedYearK = $request->input('tahunK', date('Y'));

            // Get all tims and their evaluasi status in klpd group (provinsi, kabupaten)
            $tims = DB::table('instansi_tim')
                ->join('tim_evaluasi', 'instansi_tim.tim_id', '=', 'tim_evaluasi.id')
                ->leftJoin('evaluasi_sakip', function ($join) use ($selectedYear, $selectedPeriode) {
                    $join->on('evaluasi_sakip.instansi_id', '=', 'instansi_tim.instansi_id')
                        ->where('evaluasi_sakip.tahun', $selectedYear)
                        ->where('evaluasi_sakip.periode', 'TW ' . $selectedPeriode);
                })
                ->join('klpd_instansi_new', 'instansi_tim.instansi_id', '=', 'klpd_instansi_new.id')
                ->where(function ($query) {
                    $query->where('klpd_instansi_new.group', '=', 'provinsi')
                        ->orWhere('klpd_instansi_new.group', '=', 'kabupaten');
                })
                ->select(
                    'instansi_tim.tim_id',
                    'tim_evaluasi.nama',
                    'tim_evaluasi.keterangan',
                    DB::raw('COUNT(DISTINCT instansi_tim.instansi_id) as total_instansi'),
                    DB::raw('COUNT(DISTINCT CASE WHEN evaluasi_sakip.id IS NOT NULL THEN evaluasi_sakip.instansi_id END) as total_instansi_filled')
                )
                ->groupBy('instansi_tim.tim_id', 'tim_evaluasi.nama', 'tim_evaluasi.keterangan')
                ->get();

            // Get all tims and their evaluasi status in klpd group (kl, lain)
            $tims_kl = DB::table('instansi_tim')
                ->join('tim_evaluasi', 'instansi_tim.tim_id', '=', 'tim_evaluasi.id')
                ->leftJoin('evaluasi_sakip', function ($join) use ($selectedYear) {
                    $join->on('evaluasi_sakip.instansi_id', '=', 'instansi_tim.instansi_id')
                        ->where('evaluasi_sakip.tahun', $selectedYear)
                        ->where('evaluasi_sakip.periode', 'Final');
                })
                ->join('klpd_instansi_new', 'instansi_tim.instansi_id', '=', 'klpd_instansi_new.id')
                ->where(function ($query) {
                    $query->where('klpd_instansi_new.group', '=', 'kl')
                        ->orWhere('klpd_instansi_new.group', '=', 'lain');
                })
                ->select(
                    'tim_evaluasi.nama',
                    'tim_evaluasi.keterangan',
                    DB::raw('COUNT(DISTINCT instansi_tim.instansi_id) as total_instansi'),
                    DB::raw('COUNT(DISTINCT CASE WHEN evaluasi_sakip.id IS NOT NULL THEN evaluasi_sakip.instansi_id END) as total_instansi_filled')
                )
                ->groupBy('instansi_tim.tim_id', 'tim_evaluasi.nama', 'tim_evaluasi.keterangan')
                ->get();

            return view('akip.dashboard', compact('tims_kl', 'tims', 'selectedYear', 'selectedPeriode', 'selectedYearK', 'level'));
        } else {
            return view('akip.dashboard', compact('level'));
        }
    }

    public function filterDashboard(Request $request)
    {
        $request->validate([
            'tahun' => 'required|integer|min:2020|max:' . date('Y'),
            'periode' => 'required|integer|min:1|max:4'
        ]);

        $tahun = $request->tahun;
        $periode = $request->periode;

        // Your existing logic to get teams data
        $tims = DB::table('instansi_tim')
            ->join('tim_evaluasi', 'instansi_tim.tim_id', '=', 'tim_evaluasi.id')
            ->leftJoin('evaluasi_sakip', function ($join) use ($tahun, $periode) {
                $join->on('evaluasi_sakip.instansi_id', '=', 'instansi_tim.instansi_id')
                    ->where('evaluasi_sakip.tahun', $tahun)
                    ->where('evaluasi_sakip.periode', 'TW ' . $periode);
            })
            ->join('klpd_instansi_new', 'instansi_tim.instansi_id', '=', 'klpd_instansi_new.id')
            ->where(function ($query) {
                $query->where('klpd_instansi_new.group', '=', 'provinsi')
                    ->orWhere('klpd_instansi_new.group', '=', 'kabupaten');
            })
            ->select(
                'instansi_tim.tim_id',
                'tim_evaluasi.nama',
                'tim_evaluasi.keterangan',
                DB::raw('COUNT(DISTINCT instansi_tim.instansi_id) as total_instansi'),
                DB::raw('COUNT(DISTINCT CASE WHEN evaluasi_sakip.id IS NOT NULL THEN evaluasi_sakip.instansi_id END) as total_instansi_filled')
            )
            ->groupBy('instansi_tim.tim_id', 'tim_evaluasi.nama', 'tim_evaluasi.keterangan')
            ->get();

        return response()->json([
            'tims' => $tims,
            'tahun' => $tahun,
            'periode' => $periode
        ]);
    }

    public function filterDashboardKl(Request $request)
    {
        $request->validate([
            'tahunK' => 'required|integer|min:2020|max:' . date('Y')
        ]);

        $tahunK = $request->tahunK;

        $tims_kl = DB::table('instansi_tim')
            ->join('tim_evaluasi', 'instansi_tim.tim_id', '=', 'tim_evaluasi.id')
            ->leftJoin('evaluasi_sakip', function ($join) use ($tahunK) {
                $join->on('evaluasi_sakip.instansi_id', '=', 'instansi_tim.instansi_id')
                    ->where('evaluasi_sakip.tahun', $tahunK)
                    ->where('evaluasi_sakip.periode', 'Final');
            })
            ->join('klpd_instansi_new', 'instansi_tim.instansi_id', '=', 'klpd_instansi_new.id')
            ->where(function ($query) {
                $query->where('klpd_instansi_new.group', '=', 'kl')
                    ->orWhere('klpd_instansi_new.group', '=', 'lain');
            })
            ->select(
                'tim_evaluasi.nama',
                'tim_evaluasi.keterangan',
                DB::raw('COUNT(DISTINCT instansi_tim.instansi_id) as total_instansi'),
                DB::raw('COUNT(DISTINCT CASE WHEN evaluasi_sakip.id IS NOT NULL THEN evaluasi_sakip.instansi_id END) as total_instansi_filled')
            )
            ->groupBy('instansi_tim.tim_id', 'tim_evaluasi.nama', 'tim_evaluasi.keterangan')
            ->get();

        return response()->json([
            'tims_kl' => $tims_kl,
            'tahunK' => $tahunK,
        ]);
    }

    public function evaluasi_sakip(Request $request)
    {
        if (in_array($this->currentUser->level, ['tpn', 'admin'])) {
            $tim = $this->currentUser->anggota ? $this->currentUser->anggota->tim : false;
            $anggota_tims = $tim ? $tim->instansi_tim : [];
            if ($this->currentUser->level == 'admin') {
                $anggota_tims = InstansiTimEvaluasi::whereHas('instansi', function ($query) {
                    $query->whereIn('group', ['kl', 'provinsi', 'kabupaten'])->where('deleted_at', null);
                })->get();
            }

            // Filter berdasarkan tahun dengan validasi
            $tahun = $request->input('tahun', date('Y'));
            $tahun = is_numeric($tahun) && $tahun >= 2020 && $tahun <= date('Y') ? (int) $tahun : date('Y');

            // Get search parameters
            $search_kl = $request->input('search_kl', '');
            $search_pemda = $request->input('search_pemda', '');

            // Pisahkan data K/L dan Pemda
            $anggota_kl = collect();
            $anggota_pemda = collect();

            foreach ($anggota_tims as $anggota_tim) {
                $instansi = $anggota_tim->instansi;

                if ($instansi->group == 'kl') {
                    // Apply search filter for K/L
                    if (!empty($search_kl) && stripos($instansi->nama_instansi, $search_kl) === false) {
                        continue;
                    }

                    // Untuk K/L, cek status evaluasi Final
                    $evaluasi_final = EvaluasiSakip::where('instansi_id', $instansi->id)
                        ->where('tahun', $tahun)
                        ->where('periode', 'Final')
                        ->first();

                    $anggota_tim->status_evaluasi = $evaluasi_final ? 'sudah' : 'belum';
                    $anggota_tim->evaluasi_data = $evaluasi_final;
                    $anggota_tim->tahun_evaluasi = $tahun;

                    $anggota_kl->push($anggota_tim);
                } else {
                    // Apply search filter for Pemda
                    if (!empty($search_pemda) && stripos($instansi->nama_instansi, $search_pemda) === false) {
                        continue;
                    }

                    // Untuk Pemda, cek status evaluasi per TW
                    $evaluasi_tw1 = EvaluasiSakip::where('instansi_id', $instansi->id)
                        ->where('tahun', $tahun)
                        ->where('periode', 'TW 1')
                        ->first();

                    $evaluasi_tw2 = EvaluasiSakip::where('instansi_id', $instansi->id)
                        ->where('tahun', $tahun)
                        ->where('periode', 'TW 2')
                        ->first();

                    $evaluasi_tw3 = EvaluasiSakip::where('instansi_id', $instansi->id)
                        ->where('tahun', $tahun)
                        ->where('periode', 'TW 3')
                        ->first();

                    $evaluasi_tw4 = EvaluasiSakip::where('instansi_id', $instansi->id)
                        ->where('tahun', $tahun)
                        ->where('periode', 'TW 4')
                        ->first();

                    $anggota_tim->status_tw1 = $evaluasi_tw1 ? 'sudah' : 'belum';
                    $anggota_tim->status_tw2 = $evaluasi_tw2 ? 'sudah' : 'belum';
                    $anggota_tim->status_tw3 = $evaluasi_tw3 ? 'sudah' : 'belum';
                    $anggota_tim->status_tw4 = $evaluasi_tw4 ? 'sudah' : 'belum';

                    $anggota_tim->evaluasi_tw1 = $evaluasi_tw1;
                    $anggota_tim->evaluasi_tw2 = $evaluasi_tw2;
                    $anggota_tim->evaluasi_tw3 = $evaluasi_tw3;
                    $anggota_tim->evaluasi_tw4 = $evaluasi_tw4;
                    $anggota_tim->tahun_evaluasi = $tahun;

                    $anggota_pemda->push($anggota_tim);
                }
            }

            // Pagination untuk K/L
            $perPage = 10;
            $currentPageKl = $request->input('page_kl', 1);
            $klPaginated = $anggota_kl->forPage($currentPageKl, $perPage);
            $klTotalPages = ceil($anggota_kl->count() / $perPage);

            // Pagination untuk Pemda
            $currentPagePemda = $request->input('page_pemda', 1);
            $pemdaPaginated = $anggota_pemda->forPage($currentPagePemda, $perPage);
            $pemdaTotalPages = ceil($anggota_pemda->count() / $perPage);

            return view('akip.evaluasi.tim', compact(
                'tim',
                'anggota_kl',
                'anggota_pemda',
                'klPaginated',
                'pemdaPaginated',
                'klTotalPages',
                'pemdaTotalPages',
                'currentPageKl',
                'currentPagePemda',
                'tahun',
                'search_kl',
                'search_pemda'
            ));
        } else if (in_array($this->currentUser->level, ['kl', 'kabupaten', 'provinsi'])) {
            $access = OpenAccessSetting::where('user_level', $this->currentUser->level)->where('fitur', 'evaluasi_akip')->first();
            if ($access) {
                $today = date('Y-m-d');
                if ($access->waktu_awal > $today || $access->waktu_akhir < $today) {
                    return view('belumbuka');
                }
            }
            $instansi = KlpdInstansi::find($this->currentUser->instansi_id);
            if (!$instansi) {
                abort('404');
            }
            $evaluasi_sakip = EvaluasiSakip::where('instansi_id', $this->currentUser->instansi_id)->orderBy('tahun')->orderBy('periode')->get();
            return view('akip.evaluasi.instansi', compact('instansi', 'evaluasi_sakip'));
        }
    }

    public function evaluasi_sakip_instansi($instansi_id)
    {
        $cek = $this->currentUser->anggota ? $this->currentUser->anggota->tim->instansi_tim->where('instansi_id', $instansi_id)->first() : false;
        if (!$cek && $this->currentUser->level != 'admin') {
            abort('404');
        } else {
            $instansi = KlpdInstansi::find($instansi_id);
        }
        $evaluasi_sakip = EvaluasiSakip::where('instansi_id', $instansi_id)->orderBy('tahun')->orderBy('periode')->get();

        return view('akip.evaluasi.instansi', compact('instansi', 'evaluasi_sakip'));
    }

    public function evaluasi_sakip_instansi_cekPeriode($instansi_id, Request $request)
    {
        $cek = $this->currentUser->anggota ? $this->currentUser->anggota->tim->instansi_tim->where('instansi_id', $instansi_id)->first() : false;
        if (!$cek) {
            abort('404');
        }
        $evaluasi_sakip = EvaluasiSakip::where('instansi_id', $instansi_id)
            ->where('tahun', $request->tahun)
            ->where('periode', $request->periode)
            ->first();
        if ($evaluasi_sakip) {
            return response()->json(['exists' => true]);
        } else {
            $evaluasi_sakip = EvaluasiSakip::where('instansi_id', $instansi_id)
                ->where('tahun', $request->tahun)
                ->orderBy('periode', 'desc')
                ->first();
            return response()->json(['exists' => false, 'evaluasi_sakip' => $evaluasi_sakip]);
        }
    }

    public function evaluasi_sakip_instansi_getData($instansi_id, $id)
    {
        $cek = $this->currentUser->anggota ? $this->currentUser->anggota->tim->instansi_tim->where('instansi_id', $instansi_id)->first() : false;
        if (!$cek) {
            abort('404');
        }
        $evaluasi_sakip = EvaluasiSakip::find($id);
        if ($evaluasi_sakip) {
            return response()->json(['evaluasi_sakip' => $evaluasi_sakip]);
        } else {
            abort('404');
        }
    }

    public function evaluasi_sakip_instansi_simpan($instansi_id, Request $request)
    {
        $cek = $this->currentUser->anggota ? $this->currentUser->anggota->tim->instansi_tim->where('instansi_id', $instansi_id)->first() : false;
        if (!$cek) {
            abort('404');
        }
        $success = false;
        $instansi = KlpdInstansi::find($instansi_id);
        if (!$instansi) {
            abort('404');
        }
        DB::beginTransaction();
        try {
            if (!$request->id_evaluasi) {
                $evaluasi_sakip = new EvaluasiSakip();
                $evaluasi_sakip->instansi_id = $instansi_id;
                $evaluasi_sakip->tahun = $request->tahun;
                $evaluasi_sakip->periode = $instansi->group == 'kl' ? 'Final' : $request->periode;
                $evaluasi_sakip->input_user_id = $this->currentUser->id;
            } else {
                $evaluasi_sakip = EvaluasiSakip::find($request->id_evaluasi);
                if (!$evaluasi_sakip) {
                    abort('404');
                }
                if ($evaluasi_sakip->instansi_id != $instansi_id) {
                    abort('404');
                }
            }
            $evaluasi_sakip->pic_lke = $request->pic_lke;
            $evaluasi_sakip->link_lke = $request->link_lke;
            $evaluasi_sakip->penanggung_jawab = $request->penanggung_jawab;
            $evaluasi_sakip->nilai_komponen_perencanaan_kinerja_tahun_lalu = str_replace(',', '.', $request->nilai_komponen_perencanaan_kinerja_tahun_lalu);
            $evaluasi_sakip->nilai_komponen_pengukuran_kinerja_tahun_lalu = str_replace(',', '.', $request->nilai_komponen_pengukuran_kinerja_tahun_lalu);
            $evaluasi_sakip->nilai_komponen_pelaporan_kinerja_tahun_lalu = str_replace(',', '.', $request->nilai_komponen_pelaporan_kinerja_tahun_lalu);
            $evaluasi_sakip->nilai_komponen_evaluasi_internal_tahun_lalu = str_replace(',', '.', $request->nilai_komponen_evaluasi_internal_tahun_lalu);
            $evaluasi_sakip->nilai_komponen_perencanaan_kinerja = str_replace(',', '.', $request->nilai_komponen_perencanaan_kinerja);
            $evaluasi_sakip->nilai_komponen_pengukuran_kinerja = str_replace(',', '.', $request->nilai_komponen_pengukuran_kinerja);
            $evaluasi_sakip->nilai_komponen_pelaporan_kinerja = str_replace(',', '.', $request->nilai_komponen_pelaporan_kinerja);
            $evaluasi_sakip->nilai_komponen_evaluasi_internal = str_replace(',', '.', $request->nilai_komponen_evaluasi_internal);
            $evaluasi_sakip->catatan_komponen_perencanaan_kinerja = $request->catatan_komponen_perencanaan_kinerja;
            $evaluasi_sakip->catatan_komponen_pengukuran_kinerja = $request->catatan_komponen_pengukuran_kinerja;
            $evaluasi_sakip->catatan_komponen_pelaporan_kinerja = $request->catatan_komponen_pelaporan_kinerja;
            $evaluasi_sakip->catatan_komponen_evaluasi_internal = $request->catatan_komponen_evaluasi_internal;
            $evaluasi_sakip->rekomendasi_komponen_perencanaan_kinerja = $request->rekomendasi_komponen_perencanaan_kinerja;
            $evaluasi_sakip->rekomendasi_komponen_pengukuran_kinerja = $request->rekomendasi_komponen_pengukuran_kinerja;
            $evaluasi_sakip->rekomendasi_komponen_pelaporan_kinerja = $request->rekomendasi_komponen_pelaporan_kinerja;
            $evaluasi_sakip->rekomendasi_komponen_evaluasi_internal = $request->rekomendasi_komponen_evaluasi_internal;
            $evaluasi_sakip->nilai_total_evaluasi_akip_tahun_lalu = str_replace(',', '.', $request->nilai_total_evaluasi_akip_tahun_lalu);
            $evaluasi_sakip->nilai_total_evaluasi_akip = str_replace(',', '.', $request->nilai_total_evaluasi_akip);
            if (in_array($instansi->group, ['kabupaten', 'provinsi'])) {
                $evaluasi_sakip->angka_kemiskinan_tahun_lalu = str_replace(',', '.', $request->angka_kemiskinan_tahun_lalu);
                $evaluasi_sakip->laju_pertumbuhan_ekonomi_tahun_lalu = str_replace(',', '.', $request->laju_pertumbuhan_ekonomi_tahun_lalu);
                $evaluasi_sakip->tingkat_pengangguran_terbuka_tahun_lalu = str_replace(',', '.', $request->tingkat_pengangguran_terbuka_tahun_lalu);
                $evaluasi_sakip->penurunan_emisi_grk_tahun_lalu = str_replace(',', '.', $request->penurunan_emisi_grk_tahun_lalu);
                $evaluasi_sakip->indeks_pembangunan_manusia_tahun_lalu = str_replace(',', '.', $request->indeks_pembangunan_manusia_tahun_lalu);
                $evaluasi_sakip->indeks_gini_ratio_tahun_lalu = str_replace(',', '.', $request->indeks_gini_ratio_tahun_lalu);
                $evaluasi_sakip->pendapatan_perkapita_tahun_lalu = str_replace(',', '.', str_replace('.', '', $request->pendapatan_perkapita_tahun_lalu));
                $evaluasi_sakip->angka_kemiskinan = str_replace(',', '.', $request->angka_kemiskinan);
                $evaluasi_sakip->laju_pertumbuhan_ekonomi = str_replace(',', '.', $request->laju_pertumbuhan_ekonomi);
                $evaluasi_sakip->tingkat_pengangguran_terbuka = str_replace(',', '.', $request->tingkat_pengangguran_terbuka);
                $evaluasi_sakip->penurunan_emisi_grk = str_replace(',', '.', $request->penurunan_emisi_grk);
                $evaluasi_sakip->indeks_pembangunan_manusia = str_replace(',', '.', $request->indeks_pembangunan_manusia);
                $evaluasi_sakip->indeks_gini_ratio = str_replace(',', '.', $request->indeks_gini_ratio);
                $evaluasi_sakip->pendapatan_perkapita = str_replace(',', '.', str_replace('.', '', $request->pendapatan_perkapita));
            }
            $evaluasi_sakip->last_update_user_id = $this->currentUser->id;
            if ($evaluasi_sakip->save()) {
                $success = true;
                if ($request->hasFile('file_evaluasi')) {
                    $filename = 'file_evaluasi_' . $evaluasi_sakip->instansi_id . '_' . $evaluasi_sakip->tahun . '_' . $evaluasi_sakip->periode . '.pdf';
                    $request->file('file_evaluasi')->storeAs('akip', $filename, 'public');
                    $evaluasi_sakip->file_evaluasi = $filename;
                    if ($evaluasi_sakip->save()) {
                        $success = true;
                    } else {
                        $success = false;
                    }
                }
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        if ($success) {
            DB::commit();
            return redirect('akip/evaluasi/sakip/' . $instansi_id)->with('success', 'Data evaluasi SAKIP berhasil disimpan.');
        } else {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan data evaluasi SAKIP. Silakan coba lagi.');
        }
    }

    public function evaluasi_sakip_instansi_hapus($instansi_id, $id)
    {
        $cek = $this->currentUser->anggota ? $this->currentUser->anggota->tim->instansi_tim->where('instansi_id', $instansi_id)->first() : false;
        if (!$cek) {
            abort('404');
        }
        $evaluasi_sakip = EvaluasiSakip::find($id);
        if ($evaluasi_sakip) {
            if ($evaluasi_sakip->delete()) {
                return true;
            } else {
                return false;
            }
        } else {
            abort('404');
        }
    }

    /**
     * Handle AJAX search request with improved pagination and error handling
     */
    public function evaluasi_sakip_search(Request $request)
    {
        try {
            // Validate request
            $request->validate([
                'tahun' => 'required|integer|min:2020|max:' . date('Y'),
                'search_kl' => 'nullable|string|max:255',
                'search_pemda' => 'nullable|string|max:255',
                'page_kl' => 'nullable|integer|min:1',
                'page_pemda' => 'nullable|integer|min:1'
            ]);

            if (!in_array($this->currentUser->level, ['tpn', 'admin'])) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            $tim = $this->currentUser->anggota ? $this->currentUser->anggota->tim : false;
            $anggota_tims = $tim ? $tim->instansi_tim : [];

            if ($this->currentUser->level == 'admin') {
                $anggota_tims = InstansiTimEvaluasi::whereHas('instansi', function ($query) {
                    $query->whereIn('group', ['kl', 'provinsi', 'kabupaten'])->where('deleted_at', null);
                })->get();
            }

            // Get parameters with proper validation
            $tahun = (int) $request->input('tahun');
            $search_kl = trim($request->input('search_kl', ''));
            $search_pemda = trim($request->input('search_pemda', ''));
            $currentPageKl = max(1, (int) $request->input('page_kl', 1));
            $currentPagePemda = max(1, (int) $request->input('page_pemda', 1));

            // Pisahkan data K/L dan Pemda
            $anggota_kl = collect();
            $anggota_pemda = collect();

            foreach ($anggota_tims as $anggota_tim) {
                $instansi = $anggota_tim->instansi;

                if ($instansi->group == 'kl') {
                    // Apply search filter for K/L
                    if (!empty($search_kl) && stripos($instansi->nama_instansi, $search_kl) === false) {
                        continue;
                    }

                    // Untuk K/L, cek status evaluasi Final
                    $evaluasi_final = EvaluasiSakip::where('instansi_id', $instansi->id)
                        ->where('tahun', $tahun)
                        ->where('periode', 'Final')
                        ->first();

                    $anggota_tim->status_evaluasi = $evaluasi_final ? 'sudah' : 'belum';
                    $anggota_tim->evaluasi_data = $evaluasi_final;
                    $anggota_tim->tahun_evaluasi = $tahun;

                    $anggota_kl->push($anggota_tim);
                } else {
                    // Apply search filter for Pemda
                    if (!empty($search_pemda) && stripos($instansi->nama_instansi, $search_pemda) === false) {
                        continue;
                    }

                    // Untuk Pemda, cek status evaluasi per TW
                    $evaluasi_tw1 = EvaluasiSakip::where('instansi_id', $instansi->id)
                        ->where('tahun', $tahun)
                        ->where('periode', 'TW 1')
                        ->first();

                    $evaluasi_tw2 = EvaluasiSakip::where('instansi_id', $instansi->id)
                        ->where('tahun', $tahun)
                        ->where('periode', 'TW 2')
                        ->first();

                    $evaluasi_tw3 = EvaluasiSakip::where('instansi_id', $instansi->id)
                        ->where('tahun', $tahun)
                        ->where('periode', 'TW 3')
                        ->first();

                    $evaluasi_tw4 = EvaluasiSakip::where('instansi_id', $instansi->id)
                        ->where('tahun', $tahun)
                        ->where('periode', 'TW 4')
                        ->first();

                    $anggota_tim->status_tw1 = $evaluasi_tw1 ? 'sudah' : 'belum';
                    $anggota_tim->status_tw2 = $evaluasi_tw2 ? 'sudah' : 'belum';
                    $anggota_tim->status_tw3 = $evaluasi_tw3 ? 'sudah' : 'belum';
                    $anggota_tim->status_tw4 = $evaluasi_tw4 ? 'sudah' : 'belum';

                    $anggota_tim->evaluasi_tw1 = $evaluasi_tw1;
                    $anggota_tim->evaluasi_tw2 = $evaluasi_tw2;
                    $anggota_tim->evaluasi_tw3 = $evaluasi_tw3;
                    $anggota_tim->evaluasi_tw4 = $evaluasi_tw4;
                    $anggota_tim->tahun_evaluasi = $tahun;

                    $anggota_pemda->push($anggota_tim);
                }
            }

            // Pagination untuk K/L
            $perPage = 10;
            $klPaginated = $anggota_kl->forPage($currentPageKl, $perPage);
            $klTotalPages = ceil($anggota_kl->count() / $perPage);

            // Pagination untuk Pemda
            $pemdaPaginated = $anggota_pemda->forPage($currentPagePemda, $perPage);
            $pemdaTotalPages = ceil($anggota_pemda->count() / $perPage);

            // Generate table content for K/L
            $klTableContent = $this->generateKlTableContent($klPaginated, $currentPageKl);

            // Generate table content for Pemda
            $pemdaTableContent = $this->generatePemdaTableContent($pemdaPaginated, $currentPagePemda);

            return response()->json([
                'success' => true,
                'klTableContent' => $klTableContent,
                'pemdaTableContent' => $pemdaTableContent,
                'klPaginationInfo' => [
                    'showingStart' => $anggota_kl->count() > 0 ? ($currentPageKl - 1) * $perPage + 1 : 0,
                    'showingEnd' => $anggota_kl->count() > 0 ? min($currentPageKl * $perPage, $anggota_kl->count()) : 0,
                    'total' => $anggota_kl->count(),
                    'totalPages' => $klTotalPages,
                    'currentPage' => $currentPageKl,
                    'perPage' => $perPage
                ],
                'pemdaPaginationInfo' => [
                    'showingStart' => $anggota_pemda->count() > 0 ? ($currentPagePemda - 1) * $perPage + 1 : 0,
                    'showingEnd' => $anggota_pemda->count() > 0 ? min($currentPagePemda * $perPage, $anggota_pemda->count()) : 0,
                    'total' => $anggota_pemda->count(),
                    'totalPages' => $pemdaTotalPages,
                    'currentPage' => $currentPagePemda,
                    'perPage' => $perPage
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Search error: ' . $e->getMessage(), [
                'request' => $request->all(),
                'user_id' => $this->currentUser->id,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mencari data'
            ], 500);
        }
    }

    /**
     * Generate K/L table content
     */
    private function generateKlTableContent($klPaginated, $currentPageKl)
    {
        $content = '';

        foreach ($klPaginated as $index => $anggota) {
            $statusBadge = $this->generateStatusBadge($anggota->status_evaluasi ?? null, [
                'sudah' => 'Sudah Dinilai',
                'belum' => 'Belum Dinilai'
            ]);

            // Parse HTML syntax in nama_instansi
            $namaInstansi = $this->parseHtmlInNamaInstansi($anggota->instansi->nama_instansi);

            $content .= '<tr class="kl-row">
                <td>' . (($currentPageKl - 1) * 10 + $index + 1) . '</td>
                <td>' . $namaInstansi . '</td>
                <td><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">' . ucfirst($anggota->instansi->group) . '</span></td>
                <td>' . $statusBadge . '</td>
                <td>
                    <a href="' . url('akip/evaluasi/sakip/' . $anggota->instansi_id) . '" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i data-feather="eye" class="w-4 h-4 mr-1"></i>
                        Lihat Detail
                    </a>
                </td>
            </tr>';
        }

        return $content;
    }

    /**
     * Generate Pemda table content
     */
    private function generatePemdaTableContent($pemdaPaginated, $currentPagePemda)
    {
        $content = '';

        foreach ($pemdaPaginated as $index => $anggota) {
            $tw1Badge = $this->generateStatusBadge($anggota->status_tw1 ?? null, [
                'sudah' => 'Sudah',
                'belum' => 'Belum'
            ]);

            $tw2Badge = $this->generateStatusBadge($anggota->status_tw2 ?? null, [
                'sudah' => 'Sudah',
                'belum' => 'Belum'
            ]);

            $tw3Badge = $this->generateStatusBadge($anggota->status_tw3 ?? null, [
                'sudah' => 'Sudah',
                'belum' => 'Belum'
            ]);

            $tw4Badge = $this->generateStatusBadge($anggota->status_tw4 ?? null, [
                'sudah' => 'Sudah',
                'belum' => 'Belum'
            ]);

            // Parse HTML syntax in nama_instansi
            $namaInstansi = $this->parseHtmlInNamaInstansi($anggota->instansi->nama_instansi);

            $content .= '<tr class="pemda-row">
                <td>' . (($currentPagePemda - 1) * 10 + $index + 1) . '</td>
                <td>' . $namaInstansi . '</td>
                <td><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">' . ucfirst($anggota->instansi->group) . '</span></td>
                <td>' . $tw1Badge . '</td>
                <td>' . $tw2Badge . '</td>
                <td>' . $tw3Badge . '</td>
                <td>' . $tw4Badge . '</td>
                <td>
                    <a href="' . url('akip/evaluasi/sakip/' . $anggota->instansi_id) . '" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i data-feather="eye" class="w-4 h-4 mr-1"></i>
                        Lihat Detail
                    </a>
                </td>
            </tr>';
        }

        return $content;
    }

    /**
     * Parse HTML syntax in nama_instansi to render HTML properly
     */
    private function parseHtmlInNamaInstansi($namaInstansi)
    {
        // Decode HTML entities first
        $decoded = html_entity_decode($namaInstansi, ENT_QUOTES, 'UTF-8');

        // If it contains HTML tags, return as is (will be rendered as HTML)
        // Otherwise, escape it to prevent XSS
        if (strip_tags($decoded) !== $decoded) {
            return $decoded;
        }

        // If no HTML tags found, escape the content
        return htmlspecialchars($namaInstansi, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Generate status badge HTML
     */
    private function generateStatusBadge($status, $labels = [])
    {
        if ($status === 'sudah') {
            return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">' .
                ($labels['sudah'] ?? 'Sudah') . '</span>';
        } elseif ($status === 'belum') {
            return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">' .
                ($labels['belum'] ?? 'Belum') . '</span>';
        } else {
            return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">-</span>';
        }
    }
}
