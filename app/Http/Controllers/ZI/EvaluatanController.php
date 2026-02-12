<?php

namespace App\Http\Controllers\ZI;

use App\Models\ZI\UnitZI;
use App\Models\KlpdInstansi;
use App\Models\ZI\Wawancara;
use Illuminate\Http\Request;
use App\Models\ZI\InstansiZI;
use App\Models\ZI\SanggahUnit;
use App\Models\ZI\TahapSeleksiZI;
use App\Models\ZI\SanggahInstansi;
use App\Http\Controllers\Controller;
use App\Models\ZI\MultipleLink;
use App\Models\ZI\TahunEvaluasi;
use Illuminate\Support\Facades\Auth;

class EvaluatanController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::User()) {
                return $next($request);
            }
            abort('403');
        });
    }


    public function seleksi_administrasi(Request $request)
    {
        if (Auth::User()->level == "admin" || Auth::User()->level == "tpn") {
            return redirect()->route('dashboard_zi');
        }
        $tahun = TahunEvaluasi::orderBy('tahun', 'desc')->first()->tahun;
        $tahap_seleksi = TahapSeleksiZI::where('tahun', $tahun)->where('tahap_seleksi', 'Sanggah')->first();
        $date_now = new \DateTime();
        $date_buka  = new \DateTime($tahap_seleksi->tanggal_mulai);
        $date_tutup    = new \DateTime($tahap_seleksi->tanggal_selesai);
        if ($date_now >= $date_buka) {
            if ($date_now > $date_tutup) {
                $status_akses = "Tutup";
            } else {
                $status_akses = "Buka";
            }
            $title = "Seleksi Administrasi";
            if (Auth::User()->user_rel) {
                $instansi_obj = Auth::User()->user_rel->instansi;
            } else {
                $instansi_obj = KlpdInstansi::find(Auth::User()->instansi_id);
            }
            $instansi_id = $instansi_obj->id;
            $instansi = $instansi_obj->name;
            $group_kld = $instansi_obj->group;
            $instansiZI = InstansiZI::where("instansi_id", $instansi_obj->id)
                ->where('tahun', $tahun)
                ->first();
            if ($instansiZI) {
                $syarat_akhir_wbk = $instansiZI->syarat_akhir_wbk;
                $syarat_akhir_wbbm   = $instansiZI->syarat_akhir_wbbm;
                $status_akhir = $instansiZI->status_akhir;
                $unit_wbks = UnitZI::where("instansi_zi_id", $instansiZI->id)->where('wbk', 1)->get();
                $unit_wbbms = UnitZI::where("instansi_zi_id", $instansiZI->id)->where('wbbm', 1)->get();
                return view('zi.evaluatan.seleksi_administrasi_zi', compact(
                    'title',
                    'status_akses',
                    'instansi_id',
                    'instansi',
                    'group_kld',
                    'instansiZI',
                    'unit_wbks',
                    'unit_wbbms',
                    'syarat_akhir_wbk',
                    'syarat_akhir_wbbm',
                    'status_akhir'
                ));
            } else {
                echo "mohon maaf instansi anda belum terdapat penilaian RB di tahun lalu";
            }
        } else {
            if (Auth::User()->user_rel) {
                $instansi_obj = Auth::User()->user_rel->instansi;
            } else {
                $instansi_obj = KlpdInstansi::find(Auth::User()->instansi_id);
            }
            $instansi_id = $instansi_obj->id;
            return redirect('zi-tinjau?instansi_id=' . $instansi_id);
        }
    }

    public function sanggah_simpan(Request $request)
    {
        $tahun = TahunEvaluasi::orderBy('tahun', 'desc')->first()->tahun;
        $tahap_seleksi = TahapSeleksiZI::where('tahun', $tahun)->where('tahap_seleksi', 'Sanggah')->first();
        $date_now = new \DateTime();
        $date_buka  = new \DateTime($tahap_seleksi->tanggal_mulai);
        $date_tutup    = new \DateTime($tahap_seleksi->tanggal_selesai);
        if ($date_now >= $date_buka && $date_now <= $date_tutup) {
            $instansiZIid = $request->get('instansi_id');
            $instansi_ZI = InstansiZI::find($instansiZIid);
            $suratUsulan = $request->get('surat_usulan');
            $sptjm = $request->get('sptjm');
            $seleksiSanggahInstansi = SanggahInstansi::where('instansi_zi_id', $instansiZIid)->first();
            if (!$seleksiSanggahInstansi) {
                $seleksiSanggahInstansi = new SanggahInstansi();
            }
            $seleksiSanggahInstansi->instansi_zi_id = $instansiZIid;
            $seleksiSanggahInstansi->surat_usulan = $suratUsulan;
            $seleksiSanggahInstansi->sptjm = $sptjm;
            if ($seleksiSanggahInstansi->save()) {
                foreach ($instansi_ZI->unit_zi as $unit_zi) {
                    $seleksiSanggahUnit = SanggahUnit::where('unit_zi_id', $unit_zi->id)->first();
                    if (!$seleksiSanggahUnit) {
                        $seleksiSanggahUnit = new SanggahUnit();
                    }
                    $seleksiSanggahUnit->unit_zi_id = $unit_zi->id;
                    $seleksiSanggahUnit->lke = $request->get('lke_' . $unit_zi->id);
                    $seleksiSanggahUnit->th2wbk = $request->get('2wbk_' . $unit_zi->id);
                    $seleksiSanggahUnit->tlhp = $request->get('tlhp_' . $unit_zi->id);
                    $seleksiSanggahUnit->survei_mandiri = $request->get('survei_mandiri_' . $unit_zi->id);
                    $seleksiSanggahUnit->save();
                }
            };
            return redirect()->route('evaluatan_seleksi_administrasi', $instansiZIid);
        } else {
            $title = "Sanggah Seleksi Administrasi";
            $status_akses = "Tutup"; //tutup jika melebihi tanggal 4 September
            dd("Maaf, waktu untuk melakukan sanggah sudah ditutup. Silakan hubungi admin jika ada kendala.");
        }
    }

    public function hasil_sanggah(Request $request)
    {
        if (Auth::User()->level == "admin" || Auth::User()->level == "tpn") {
            return redirect()->route('dashboard_zi');
        }
        $tahun = TahunEvaluasi::orderBy('tahun', 'desc')->first()->tahun;
        $tahap_seleksi = TahapSeleksiZI::where('tahun', $tahun)->where('tahap_seleksi', 'Hasil Sanggah')->first();
        $date_now = new \DateTime();
        $date_buka  = new \DateTime($tahap_seleksi->tanggal_mulai);
        $date_tutup    = new \DateTime($tahap_seleksi->tanggal_selesai);
        if ($date_now >= $date_buka) {
            if ($date_now > $date_tutup) {
                $status_akses = "Tutup";
            } else {
                $status_akses = "Buka";
            }
            $title = "Hasil Sanggah";
            $status_akses = "Tutup"; //tutup jika melebihi tanggal 4 September
            if (Auth::User()->user_rel) {
                $instansi_obj = Auth::User()->user_rel->instansi;
            } else {
                $instansi_obj = KlpdInstansi::find(Auth::User()->instansi_id);
            }
            $instansi_id = $instansi_obj->id;
            $instansi = $instansi_obj->name;
            $group_kld = $instansi_obj->group;
            $instansiZI = InstansiZI::where("instansi_id", $instansi_obj->id)->where('tahun', $tahun)->first();
            if ($instansiZI) {
                $syarat_akhir_wbk = $instansiZI->syarat_akhir_wbk;
                $syarat_akhir_wbbm   = $instansiZI->syarat_akhir_wbbm;
                $status_akhir = $instansiZI->status_akhir;
                $unit_wbks = UnitZI::where("instansi_zi_id", $instansiZI->id)->where('wbk', 1)->get();
                $unit_wbbms = UnitZI::where("instansi_zi_id", $instansiZI->id)->where('wbbm', 1)->get();
                return view('zi.evaluatan.seleksi_sanggah', compact(
                    'title',
                    'status_akses',
                    'instansi_id',
                    'instansi',
                    'group_kld',
                    'instansiZI',
                    'unit_wbks',
                    'unit_wbbms',
                    'syarat_akhir_wbk',
                    'syarat_akhir_wbbm',
                    'status_akhir'
                ));
            } else {
                echo "mohon maaf instansi anda belum terdapat penilaian RB di tahun lalu";
            }
        } else {
            if (Auth::User()->user_rel) {
                $instansi_obj = Auth::User()->user_rel->instansi;
            } else {
                $instansi_obj = KlpdInstansi::find(Auth::User()->instansi_id);
            }
            $instansi_id = $instansi_obj->id;
            return redirect('zi-administrasi');
        }
    }

    public function seleksi_desk(Request $request)
    {
        if (Auth::User()->level == "admin" || Auth::User()->level == "tpn") {
            return redirect()->route('dashboard_zi');
        }
        $title = "Seleksi Desk";
        if (Auth::User()->user_rel) {
            $instansi_obj = Auth::User()->user_rel->instansi;
        } else {
            $instansi_obj = KlpdInstansi::find(Auth::User()->instansi_id);
        }
        $instansi_id = $instansi_obj->id;
        $instansi = $instansi_obj->name;
        $tahun = TahunEvaluasi::orderBy('tahun', 'desc')->first()->tahun;
        $instansiZI = InstansiZI::where("instansi_id", $instansi_obj->id)->where('tahun', $tahun)->first();
        $tahap_seleksi = TahapSeleksiZI::where('tahun', $tahun)->where('tahap_seleksi', 'Wawancara')->first();
        $date_now = new \DateTime();
        $date_buka  = new \DateTime($tahap_seleksi->tanggal_mulai);
        $date_tutup    = new \DateTime($tahap_seleksi->tanggal_selesai);
        $buka_formulir = false;
        if ($date_now >= $date_buka && $date_now <= $date_tutup) {
            $buka_formulir = true;
        }
        if ($instansiZI) {
            $units = UnitZI::where("instansi_zi_id", $instansiZI->id)->orderBy('wbbm')->get();
            return view('zi.evaluatan.seleksi_desk', compact(
                'title',
                'instansi_id',
                'instansi',
                'instansiZI',
                'units',
                'buka_formulir'
            ));
        } else {
            echo "mohon maaf instansi anda belum terdapat penilaian RB di tahun lalu";
        }
    }

    public function link_paparan_simpan(Request $request)
    {
        $tahun = TahunEvaluasi::orderBy('tahun', 'desc')->first()->tahun;
        $tahap_seleksi = TahapSeleksiZI::where('tahun', $tahun)->where('tahap_seleksi', 'Wawancara')->first();
        $date_now = new \DateTime();
        $date_buka  = new \DateTime($tahap_seleksi->tanggal_mulai);
        $date_tutup    = new \DateTime($tahap_seleksi->tanggal_selesai);
        if ($date_now >= $date_buka && $date_now <= $date_tutup) {
            if (Auth::User()->user_rel) {
                $instansi_obj = Auth::User()->user_rel->instansi;
            } else {
                $instansi_obj = KlpdInstansi::find(Auth::User()->instansi_id);
            }
            $instansi_ZI = InstansiZI::where("instansi_id", $instansi_obj->id)->where('tahun', $tahun)->first();
            foreach ($instansi_ZI->unit_zi as $unit_zi) {
                $wawancaraUnit = Wawancara::where('unit_zi_id', $unit_zi->id)->first();
                if ($wawancaraUnit) {
                    $wawancaraUnit->link_paparan = $request->get('link_paparan_' . $unit_zi->id);
                    $wawancaraUnit->save();
                }
            }
        }
        return redirect()->route('evaluatan_desk');
    }

    public function seleksi_verifikasi_lapangan(Request $request)
    {
        if (Auth::User()->level == "admin" || Auth::User()->level == "tpn") {
            return redirect()->route('dashboard_zi');
        }
        if (Auth::User()->user_rel) {
            $instansi_obj = Auth::User()->user_rel->instansi;
        } else {
            $instansi_obj = KlpdInstansi::find(Auth::User()->instansi_id);
        }
        $instansi_id = $instansi_obj->id;
        $instansi = $instansi_obj->name;
        $group_kld = $instansi_obj->group;
        $instansiZI = InstansiZI::where("instansi_id", $instansi_obj->id)->first();
        if ($instansiZI) {
            $syarat_akhir_wbk = $instansiZI->syarat_akhir_wbk;
            $syarat_akhir_wbbm   = $instansiZI->syarat_akhir_wbbm;
            $status_akhir = $instansiZI->status_akhir;
            $unit_wbks = UnitZI::where("instansi_zi_id", $instansiZI->id)->where('wbk', 1)->get();
            $unit_wbbms = UnitZI::where("instansi_zi_id", $instansiZI->id)->where('wbbm', 1)->get();
            return view('zi.evaluatan.seleksi_verifikasi_lapangan_zi', compact(
                'instansi_id',
                'instansi',
                'group_kld',
                'instansiZI',
                'unit_wbks',
                'unit_wbbms',
                'syarat_akhir_wbk',
                'syarat_akhir_wbbm',
                'status_akhir'
            ));
        } else {
            echo "mohon maaf instansi anda belum terdapat penilaian RB di tahun lalu";
        }
    }


    public function hasil_akhir(Request $request)
    {
        $title = "Hasil Akhir";
        if (Auth::User()->level == "admin" || Auth::User()->level == "tpn") {
            $instansiZI = InstansiZI::where("id", $request->get("instansi_zi_id"))->first();
        } else {
            if (Auth::User()->user_rel) {
                $instansi_obj = Auth::User()->user_rel->instansi;
            } else {
                $instansi_obj = KlpdInstansi::find(Auth::User()->instansi_id);
            }
        }


        $tahun = TahunEvaluasi::orderBy('tahun', 'desc')->first()->tahun;
        $instansiZI = InstansiZI::where("instansi_id", $instansi_obj->id)->where('tahun', $tahun)->first();
        $buka_formulir = false;
        $penghargaans = MultipleLink::where('instansi_zi_id', $instansiZI->id)->get();

        if ($instansiZI) {
            $units = UnitZI::where("instansi_zi_id", $instansiZI->id)->get();
            $unit_wbk_lulus = UnitZI::where("instansi_zi_id", $instansiZI->id)->where('wbk', 1)
                ->whereHas('panel', function ($query) {
                    $query->where('status', 1);
                })->count();

            $unit_wbbm_lulus = UnitZI::where("instansi_zi_id", $instansiZI->id)->where('wbbm', 1)
                ->whereHas('panel', function ($query) {
                    $query->where('status', 1);
                })->count();


            return view('zi.evaluatan.hasil_akhir_zi', compact(
                'title',
                'instansiZI',
                'penghargaans',
                'units',
                'unit_wbk_lulus',
                'unit_wbbm_lulus'
            ));
        } else {
            echo "mohon maaf Anda tidak terdaftar dalam orang yang berhak untuk melihat halaman ini";
        }
    }
}
