<?php

namespace App\Http\Controllers\ZI;

use App\Http\Controllers\Controller;
use App\Models\UnitZI;
use App\Models\InstansiZI;
use App\Models\KlpdInstansi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if(Auth::User()->level =="admin" || Auth::User()->level == "tpn" ){
            $instansi_id = $request->get("instansi_id");
            $instansi_obj = KlpdInstansi::find($instansi_id);
        }else{
            redirect(URL::to('/'));
        } 
       
        return view('zi.dashboard');
    }


    public function rekap_pengusulan(Request $request)
    {   
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
                "instansi_ZIs","instansi_non_mandiri_count","instansi_wbk_mandiri_count", 
                "wbbm_count","wbk_mandiri_count", "wbk_non_mandiri_count", 'total_unit'
            ));
        }else{
            return(URL::to('/'));
        }
    }

    public function rekap_unit(Request $request)
    {   
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
                "unit_ZIs","instansi_ZIs","instansi_non_mandiri_count","instansi_wbk_mandiri_count", 
                "wbbm_count","wbk_mandiri_count", "wbk_non_mandiri_count", 'total_unit'
            ));
        }else{
            return(URL::to('/'));
        }
    }

    public function rekap_pengusulan_detail($id)
    {   
        $instansi_ZI = InstansiZI::find($id);
        $unit_wbk_ZIs = UnitZI::where("instansi_zi_id", $id)->where("wbk",1)->get();
        $unit_wbbm_ZIs = UnitZI::where("instansi_zi_id", $id)->where("wbbm",1)->get();
        return view('zi.rekap_pengusulan_detail', compact("instansi_ZI","unit_wbk_ZIs","unit_wbbm_ZIs") );
        
    }

    public function update_predikat(Request $request)
    {   
        if(Auth::User()->level =="admin" || in_array(Auth::User()->id, [10060, 10048])){
            $instansi_ZIs = InstansiZI::get();
            return view('zi.update_predikat', compact("instansi_ZIs") );
        }else{
            return redirect()->route("pengusulan_zi");
        }
    }

    public function edit_predikat($id)
    {   

        if(Auth::User()->level =="admin" || in_array(Auth::User()->id, [10060, 10048])){
            $instansi_ZI = InstansiZI::find($id);
            return view('zi.edit_predikat', compact("instansi_ZI") );
        }else{
            return redirect()->route("pengusulan_zi");
        }
    }

    public function store_predikat(Request $request){
        if(Auth::User()->level =="admin" || in_array(Auth::User()->id, [10060, 10048])){
            $instansiZI = InstansiZI::find($request->get("pic"));
            $opini_bpk = $request->get("opini_bpk");
            $skor_predikat_sakip = $request->get("skor_sakip");
            $skor_indeks_rb = $request->get("skor_index_rb");
            $skor_maturitas_spip = $request->get("skor_maturitas_spip");
            
            $instansiZI->update_predikat_by = Auth::User()->id; 
            $instansiZI->save();
        
            return view('zi.edit_predikat', compact("instansi_ZI") );
        }else{
            return redirect()->route("pengusulan_zi");
        }
    }

}
