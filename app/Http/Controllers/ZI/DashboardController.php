<?php

namespace App\Http\Controllers\ZI;

use App\Http\Controllers\Controller;
use App\Models\ZI\UnitZI;
use App\Models\ZI\InstansiZI;
use App\Models\KlpdInstansi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if(Auth::User()->level =="admin" || in_array(Auth::User()->id, [10060, 10048])){
                    return $next($request);     
            }
            abort('403');
        });
    }


    public function index(Request $request)
    {
        $title = "Dashboard";
        return view('zi.dashboard', compact('title'));
    }


    public function rekap_pengusulan(Request $request)
    {   
        $title = "Rekap Pengusulan";
        if(Auth::User()->level =="admin" || Auth::User()->level == "tpn" ){
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
            

            return view('zi.rekap_pengusulan', compact(
                "title","instansi_ZIs","instansi_non_mandiri_count","instansi_wbk_mandiri_count", 
                "wbbm_count","wbk_mandiri_count", "wbk_non_mandiri_count", 'total_unit'
            ));
        }else{
            return(URL::to('/'));
        }
    }

    public function rekap_unit(Request $request)
    {   
        $title = "Rekap Unit";
        if(Auth::User()->level =="admin" || Auth::User()->level == "tpn" ){
            $unit_ZIs = UnitZI::orderBy('instansi_zi_id','ASC')->get();
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

            return view('zi.rekap_unit', compact(
                "title","unit_ZIs","instansi_ZIs","instansi_non_mandiri_count","instansi_wbk_mandiri_count", 
                "wbbm_count","wbk_mandiri_count", "wbk_non_mandiri_count", 'total_unit'
            ));
        }else{
            return(URL::to('/'));
        }
    }

    public function rekap_pengusulan_detail($id)
    {   
        $title = "Rekap Pengusulan";
        $instansi_ZI = InstansiZI::find($id);
        $unit_wbk_ZIs = UnitZI::where("instansi_zi_id", $id)->where("wbk",1)->get();
        $unit_wbbm_ZIs = UnitZI::where("instansi_zi_id", $id)->where("wbbm",1)->get();
        return view('zi.rekap_pengusulan_detail', compact("title","instansi_ZI","unit_wbk_ZIs","unit_wbbm_ZIs") );
        
    }
}
