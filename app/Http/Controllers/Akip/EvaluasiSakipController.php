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

    public function evaluasi_sakip()
    {
        if (in_array($this->currentUser->level, ['tpn', 'admin'])) {
            $tim = $this->currentUser->anggota ? $this->currentUser->anggota->tim : false;
            $anggota_tims = $tim ? $tim->instansi_tim : [];
            if ($this->currentUser->level == 'admin') {
                $anggota_tims = InstansiTimEvaluasi::whereHas('instansi', function ($query) {
                    $query->where('group', 'kl')->where('deleted_at', null);
                })->get();
            }
            return view('akip.evaluasi.tim', compact('tim', 'anggota_tims'));
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
                dd($request->hasFile('file_evaluasi'));
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
}
