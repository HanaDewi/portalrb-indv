<?php

namespace App\Http\Controllers\ZI;

use App\Http\Controllers\Controller;
use App\Models\ZI\InstansiZI;
use App\Models\KlpdInstansi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ZIController extends Controller
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

    public function index(Request $request)
    {
        $tahun = 2025;

        if (Auth::User()->level == "tpn" || Auth::User()->level == "admin") {
            return redirect()->route('dashboard_zi');
        }

        if (!isset(Auth::User()->user_rel->instansi)) {
            abort('403');
        }

        // echo "<body style='text-align:center; background-color:bisque'><img src='https://www.portalrb.id/assets/images/zi/zi2025.jpg'>";;
        // die;
        if (Auth::User()->user_rel) {
            $instansi_obj = Auth::User()->user_rel->instansi;
        } else {
            $instansi_obj = KlpdInstansi::find(Auth::User()->instansi_id);
        }
        $instansi_id = $instansi_obj->id;
        $instansiZI = InstansiZI::where("instansi_id", $instansi_obj->id)->where('tahun', $tahun)->first();
        if (!$instansiZI) {
            dd("Mohon maaf, instansi anda belum didaftarkan oleh admin");
        }

        if ($instansiZI->tahap_seleksi === 0) {
            return redirect()->route('pengusulan_zi');
        } elseif ($instansiZI->tahap_seleksi == 1) {
            return redirect('zi-tinjau?instansi_id=' . $instansi_id);
        } elseif ($instansiZI->tahap_seleksi == 2) {
            return redirect('zi-administrasi');
        } elseif ($instansiZI->tahap_seleksi == 3) {
            return redirect()->route('evaluatan_hasil_sanggah');
        } elseif ($instansiZI->tahap_seleksi == 4) {
            return redirect()->route('evaluatan_desk');
        } elseif ($instansiZI->tahap_seleksi == 5) {
            return redirect()->route('evaluatan_hasil_akhir');
        }


        echo "waduh";

        //Lupa code dibawah buat apa, kyknya buat pendaftaran tapi sudah dialihkan ke controller pengusulan
        // $date_now = new \DateTime();
        // $date_buka_zi    = new \DateTime("2024/07/18");
        // $date_tutup_zi    = new \DateTime("2024/08/1");

        // #if ($date_now >= $date_buka_zi) {
        // $instansi_id = $request->get("instansi_id");
        // if ($instansi_id && (Auth::User()->level == "admin" || Auth::User()->level == "tpn")) {
        //     $instansi_obj = KlpdInstansi::find($instansi_id);
        //     $instansiZI = InstansiZI::where("instansi_id", $instansi_obj->id)->first();
        //     if ($instansiZI) {
        //         if ($instansiZI->final) {
        //             return redirect('zi-tinjau?instansi_id=' . $instansi_id);
        //         }
        //     }
        // } elseif (Auth::User()->level == "tpn" || Auth::User()->level == "admin") {
        //     #$instansi_obj = KlpdInstansi::find(1); #jangan di delete ini untuk pengujian pengusulan via admin
        //     #$instansiZI = InstansiZI::where("instansi_id", $instansi_obj->id)->first();
        //     return redirect()->route('dashboard_zi');
        // } else {
        //     if ($date_now >= $date_tutup_zi) {
        //         return redirect('zi-tinjau?instansi_id=' . Auth::User()->user_rel->instansi->id);
        //     }
        //     $instansi_obj = Auth::User()->user_rel->instansi;
        //     $instansi_id = $instansi_obj->id;
        // }
        // (Auth::User()->level == "admin" || Auth::User()->level == "tpn") ? $instansis = KlpdInstansi::get() : $instansis = "";

        // $instansi = $instansi_obj->name;
        // $group_kld = $instansi_obj->group;
        // $instansiZI = InstansiZI::where("instansi_id", $instansi_obj->id)->first();
        // if ($instansiZI) {
        //     if ($instansiZI->final && !(Auth::User()->level == "admin" || Auth::User()->level == "tpn")) {
        //         return redirect('zi-tinjau?instansi_id=' . $instansi_id);
        //     }
        //     $skor_opini_bpk = $instansiZI->skor_bpk;
        //     $skor_indeks_rb = $instansiZI->skor_indeks_rb;
        //     $skor_predikat_sakip = $instansiZI->skor_sakip;
        //     $skor_maturitas_spip = $instansiZI->skor_maturitas_spip;
        //     $opini_bpk = $instansiZI->opini_bpk;
        //     $indeks_rb = $instansiZI->indeks_rb;
        //     $predikat_sakip = $instansiZI->predikat_sakip;
        //     $maturitas_spip = $instansiZI->maturitas_spip;
        //     $syarat_bpk = $instansiZI->syarat_bpk;
        //     $syarat_sakip_wbk = $instansiZI->syarat_sakip_wbk;
        //     $syarat_sakip_wbbm = $instansiZI->syarat_sakip_wbbm;
        //     $syarat_indeksrb_wbk = $instansiZI->syarat_indeksrb_wbk;
        //     $syarat_indeksrb_wbbm = $instansiZI->syarat_indeksrb_wbbm;
        //     $syarat_maturitas_spip = $instansiZI->syarat_maturitas_spip;
        //     $syarat_akhir_wbk = $instansiZI->syarat_akhir_wbk;
        //     $syarat_akhir_wbbm   = $instansiZI->syarat_akhir_wbbm;
        //     $keterangan = $instansiZI->keterangan;
        //     $status_akhir = $instansiZI->status_akhir;

        //     return view('zi.pengusulan', compact(
        //         'instansis',
        //         'instansi_id',
        //         'instansi',
        //         'group_kld',
        //         'skor_opini_bpk',
        //         'skor_indeks_rb',
        //         'skor_predikat_sakip',
        //         'skor_maturitas_spip',
        //         'opini_bpk',
        //         'indeks_rb',
        //         'predikat_sakip',
        //         'maturitas_spip',
        //         'syarat_bpk',
        //         'syarat_sakip_wbk',
        //         'syarat_sakip_wbbm',
        //         'syarat_indeksrb_wbk',
        //         'syarat_indeksrb_wbbm',
        //         'syarat_maturitas_spip',
        //         'syarat_akhir_wbk',
        //         'syarat_akhir_wbbm',
        //         'keterangan',
        //         'status_akhir'
        //     ));
        // } else {
        //     echo "mohon maaf instansi anda belum terdapat penilaian RB di tahun lalu";
        // }
        // #}else{
        // #    return view('zibelumbuka');
        // #}
    }
}
