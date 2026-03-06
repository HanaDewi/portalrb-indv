<?php

namespace App\Http\Controllers\ZI;

use App\Models\KlpdInstansi;
use Illuminate\Http\Request;
use App\Models\ZI\InstansiZI;
use App\Models\ZI\TahunEvaluasi;
use App\Http\Controllers\Controller;
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
        $tahun = TahunEvaluasi::orderBy('tahun', 'desc')->first()->tahun;

        if (Auth::User()->level == "tpn" || Auth::User()->level == "admin") {
            return redirect()->route('dashboard_zi');
        }

        if (!isset(Auth::User()->instansi_id)) {
            abort('403');
        }

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
    }
}
