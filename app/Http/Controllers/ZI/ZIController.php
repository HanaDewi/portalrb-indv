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

        echo "<body style='text-align:center; background-color:bisque'><img src='https://www.portalrb.id/assets/images/zi/zi2025.jpg'>";;
        die;

        $instansi_obj = Auth::User()->user_rel->instansi;
        $instansi_id = $instansi_obj->id;
        $instansiZI = InstansiZI::where("instansi_id", $instansi_obj->id)->where('tahun', $tahun)->first();
        //dd($instansiZI);

        if ($instansiZI->tahap_seleksi == 1) {
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
    }
}
