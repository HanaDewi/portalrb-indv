<?php

namespace App\Http\Controllers\ZI;

use App\Models\ZI\UnitZI;
use App\Models\KlpdInstansi;
use Illuminate\Http\Request;
use App\Models\ZI\InstansiZI;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\ZI\SeleksiAdministrasiUnit;
use App\Models\ZI\SeleksiAdministrasiInstansi;

class AdministrasiController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if(Auth::User()->level =="admin" || Auth::User()->level =="tpn"){
                    return $next($request);     
            }
            abort('403');
        });
    }
    public function index(Request $request)
    {
        $title = "Seleksi Administrasi";
        $instansi_ZIs = InstansiZI::orderBy('updated_at','DESC')->get();
        $instansi_non_mandiri = InstansiZI::where("instansi_wbk_mandiri",'!=',1)->orWhereNull('instansi_wbk_mandiri')->where("final",1)->get();
        $instansi_non_mandiri_count = $instansi_non_mandiri->count();
        $instansi_wbk_mandiri = InstansiZI::where("instansi_wbk_mandiri",1)->where("final",1)->get();
        $instansi_wbk_mandiri_count = $instansi_wbk_mandiri->count();
        $wbbm_count = UnitZI::where('wbbm', 1)->count();
        $wbk_all_count = UnitZI::where('wbk', 1)->count();
        $wbk_mandiri_count = UnitZI::whereHas('instansiZI', function ($query) {
            $query->where('instansi_wbk_mandiri', 1);
        })->where('wbk', 1)->count();
        $wbk_non_mandiri_count = $wbk_all_count-$wbk_mandiri_count;
        $total_unit = $wbk_all_count + $wbbm_count;
        

        return view('zi.seleksi_administrasi.administrasi', compact(
            "title","instansi_ZIs","instansi_non_mandiri_count","instansi_wbk_mandiri_count", 
            "wbbm_count","wbk_mandiri_count", "wbk_non_mandiri_count", 'total_unit'
        ));
                
    }

   
    
}
