<?php

namespace App\Http\Controllers\ZI;

use App\Http\Controllers\Controller;
use App\Models\ZI\UnitZI;
use App\Models\ZI\InstansiZI;
use App\Models\KlpdInstansi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluatanController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if(Auth::User()){
                return $next($request);     
            }
            abort('403');
        });
    }
    

    public function seleksi_administrasi(Request $request)
    {   
        $instansi_obj = Auth::User()->user_rel->instansi;
        $instansi_id = $instansi_obj->id; 
        $instansi = $instansi_obj->name;
        $group_kld =$instansi_obj->group; 
        $instansiZI = InstansiZI::where("instansi_id", $instansi_obj->id)->first();
        if($instansiZI){
            $syarat_akhir_wbk = $instansiZI->syarat_akhir_wbk;
            $syarat_akhir_wbbm   = $instansiZI->syarat_akhir_wbbm;
            $status_akhir = $instansiZI->status_akhir;
            $unit_wbks = UnitZI::where("instansi_zi_id", $instansiZI->id)->where('wbk',1)->get();
            $unit_wbbms = UnitZI::where("instansi_zi_id", $instansiZI->id)->where('wbbm',1)->get();
            return view('zi.evaluatan.seleksi_administrasi_zi', compact(
                 'instansi_id', 'instansi', 'group_kld', 'instansiZI',
                'unit_wbks', 'unit_wbbms',
                'syarat_akhir_wbk','syarat_akhir_wbbm', 'status_akhir'
            ));
        }else{
            echo "mohon maaf instansi anda belum terdapat penilaian RB di tahun lalu";
        }
    }

    public function hasil_sanggah(Request $request)
    {   
        $instansi_obj = Auth::User()->user_rel->instansi;
        $instansi_id = $instansi_obj->id; 
        $instansi = $instansi_obj->name;
        $group_kld =$instansi_obj->group; 
        $instansiZI = InstansiZI::where("instansi_id", $instansi_obj->id)->first();
        if($instansiZI){
            $syarat_akhir_wbk = $instansiZI->syarat_akhir_wbk;
            $syarat_akhir_wbbm   = $instansiZI->syarat_akhir_wbbm;
            $status_akhir = $instansiZI->status_akhir;
            $unit_wbks = UnitZI::where("instansi_zi_id", $instansiZI->id)->where('wbk',1)->get();
            $unit_wbbms = UnitZI::where("instansi_zi_id", $instansiZI->id)->where('wbbm',1)->get();
            return view('zi.evaluatan.seleksi_sanggah_zi', compact(
                 'instansi_id', 'instansi', 'group_kld', 'instansiZI',
                'unit_wbks', 'unit_wbbms',
                'syarat_akhir_wbk','syarat_akhir_wbbm', 'status_akhir'
            ));
        }else{
            echo "mohon maaf instansi anda belum terdapat penilaian RB di tahun lalu";
        }
    }

    public function seleksi_desk(Request $request)
    {   
        $instansi_obj = Auth::User()->user_rel->instansi;
        $instansi_id = $instansi_obj->id; 
        $instansi = $instansi_obj->name;
        $group_kld =$instansi_obj->group; 
        $instansiZI = InstansiZI::where("instansi_id", $instansi_obj->id)->first();
        if($instansiZI){
            $syarat_akhir_wbk = $instansiZI->syarat_akhir_wbk;
            $syarat_akhir_wbbm   = $instansiZI->syarat_akhir_wbbm;
            $status_akhir = $instansiZI->status_akhir;
            $unit_wbks = UnitZI::where("instansi_zi_id", $instansiZI->id)->where('wbk',1)->get();
            $unit_wbbms = UnitZI::where("instansi_zi_id", $instansiZI->id)->where('wbbm',1)->get();
            return view('zi.evaluatan.seleksi_desk_zi', compact(
                 'instansi_id', 'instansi', 'group_kld', 'instansiZI',
                'unit_wbks', 'unit_wbbms',
                'syarat_akhir_wbk','syarat_akhir_wbbm', 'status_akhir'
            ));
        }else{
            echo "mohon maaf instansi anda belum terdapat penilaian RB di tahun lalu";
        }
    }

    public function seleksi_verifikasi_lapangan(Request $request)
    {   
        $instansi_obj = Auth::User()->user_rel->instansi;
        $instansi_id = $instansi_obj->id; 
        $instansi = $instansi_obj->name;
        $group_kld =$instansi_obj->group; 
        $instansiZI = InstansiZI::where("instansi_id", $instansi_obj->id)->first();
        if($instansiZI){
            $syarat_akhir_wbk = $instansiZI->syarat_akhir_wbk;
            $syarat_akhir_wbbm   = $instansiZI->syarat_akhir_wbbm;
            $status_akhir = $instansiZI->status_akhir;
            $unit_wbks = UnitZI::where("instansi_zi_id", $instansiZI->id)->where('wbk',1)->get();
            $unit_wbbms = UnitZI::where("instansi_zi_id", $instansiZI->id)->where('wbbm',1)->get();
            return view('zi.evaluatan.seleksi_verifikasi_lapangan_zi', compact(
                 'instansi_id', 'instansi', 'group_kld', 'instansiZI',
                'unit_wbks', 'unit_wbbms',
                'syarat_akhir_wbk','syarat_akhir_wbbm', 'status_akhir'
            ));
        }else{
            echo "mohon maaf instansi anda belum terdapat penilaian RB di tahun lalu";
        }
    }

    public function hasil_akhir(Request $request)
    {   
        $instansi_obj = Auth::User()->user_rel->instansi;
        $instansi_id = $instansi_obj->id; 
        $instansi = $instansi_obj->name;
        $group_kld =$instansi_obj->group; 
        $instansiZI = InstansiZI::where("instansi_id", $instansi_obj->id)->first();
        if($instansiZI){
            $syarat_akhir_wbk = $instansiZI->syarat_akhir_wbk;
            $syarat_akhir_wbbm   = $instansiZI->syarat_akhir_wbbm;
            $status_akhir = $instansiZI->status_akhir;
            $unit_wbks = UnitZI::where("instansi_zi_id", $instansiZI->id)->where('wbk',1)->get();
            $unit_wbbms = UnitZI::where("instansi_zi_id", $instansiZI->id)->where('wbbm',1)->get();
            return view('zi.evaluatan.hasil_akhir_zi', compact(
                 'instansi_id', 'instansi', 'group_kld', 'instansiZI',
                'unit_wbks', 'unit_wbbms',
                'syarat_akhir_wbk','syarat_akhir_wbbm', 'status_akhir'
            ));
        }else{
            echo "mohon maaf instansi anda belum terdapat penilaian RB di tahun lalu";
        }
    }
    
}
