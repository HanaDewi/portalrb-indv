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

class DokumenController extends Controller
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
         $title = "Seleksi Dokumen";
        // $instansi_ZIs = InstansiZI::orderBy('updated_at','DESC')->get();
        // $instansi_non_mandiri = InstansiZI::where("instansi_wbk_mandiri",'!=',1)->orWhereNull('instansi_wbk_mandiri')->where("final",1)->get();
        // $instansi_non_mandiri_count = $instansi_non_mandiri->count();
        // $instansi_wbk_mandiri = InstansiZI::where("instansi_wbk_mandiri",1)->where("final",1)->get();
        // $instansi_wbk_mandiri_count = $instansi_wbk_mandiri->count();
        // $wbbm_count = UnitZI::where('wbbm', 1)->count();
        // $wbk_all_count = UnitZI::where('wbk', 1)->count();
        // $wbk_mandiri_count = UnitZI::whereHas('instansiZI', function ($query) {
        //     $query->where('instansi_wbk_mandiri', 1);
        // })->where('wbk', 1)->count();
        // $wbk_non_mandiri_count = $wbk_all_count-$wbk_mandiri_count;
        // $total_unit = $wbk_all_count + $wbbm_count;
        

        // return view('zi.seleksi_administrasi.administrasi', compact(
        //     "title","instansi_ZIs","instansi_non_mandiri_count","instansi_wbk_mandiri_count", 
        //     "wbbm_count","wbk_mandiri_count", "wbk_non_mandiri_count", 'total_unit'
        // ));

        return view('zi.admin.data_belum_bisa_diakses', compact(
                "title"
             ));
                
    }

    public function evaluasi_administrasi($id)
    {   
        $title = "Seleksi Administrasi";
        $instansi_ZI = InstansiZI::find($id);
        $unit_ZIs = UnitZI::where("instansi_zi_id", $id)->orderBy('wbk','desc')->get();
        $seleksiAdministrasiInstansi = SeleksiAdministrasiInstansi::where('instansi_zi_id', $id)->first();
        $valSuratUsulan = ($seleksiAdministrasiInstansi)?$seleksiAdministrasiInstansi->surat_usulan:Null;
        $valCatatanSuratUsulan = ($seleksiAdministrasiInstansi)?$seleksiAdministrasiInstansi->catatan_surat_usulan:Null;
        $valSptjm = ($seleksiAdministrasiInstansi)?$seleksiAdministrasiInstansi->sptjm:Null;
        $valCatatanSptjm = ($seleksiAdministrasiInstansi)?$seleksiAdministrasiInstansi->catatan_sptjm:Null;
        return view('zi.seleksi_administrasi.evaluasi', compact(
            "title","instansi_ZI","unit_ZIs","seleksiAdministrasiInstansi",
            "valSuratUsulan", "valCatatanSuratUsulan", "valSptjm","valCatatanSptjm",
            ));
        
    }

    public function evaluasi_administrasi_simpan(Request $request){
        $instansiZIid = $request->get('instansiZIId');
        $instansi_ZI = InstansiZI::find($instansiZIid);
        $tim_ids = [];
        foreach($instansi_ZI->unit_zi as $unit_zi){
            foreach ( $unit_zi->unit_tim as $unitTim){
                if(!in_array($unitTim->tim_id, $tim_ids)){
                    array_push($tim_ids, $unitTim->tim_id);                            
                }
            }
        }
        $status = "Tidak Berhak";
        if(Auth::User()->userTimZI){                   
            foreach(Auth::User()->userTimZI as $anggotaTim){
                if(in_array($anggotaTim->tim_id,$tim_ids)){
                    $status = "Berhak" ;
                }
            }
        }
        if($status == "Tidak Berhak"){
            abort('403');
        }

        $suratUsulan = $request->get('suratUsulan');
        $catatanSuratUsulan = $request->get('catatanSuratUsulan');
        $sptjm = $request->get('sptjm');
        $catatanSptjm = $request->get('catatanSptjm');
        $seleksiAdministrasiInstansi = SeleksiAdministrasiInstansi::where('instansi_zi_id', $instansiZIid)->first();
        if (!$seleksiAdministrasiInstansi) {
            $seleksiAdministrasiInstansi = new SeleksiAdministrasiInstansi();
        }
        $seleksiAdministrasiInstansi->instansi_zi_id = $instansiZIid;
        $seleksiAdministrasiInstansi->surat_usulan = $suratUsulan;
        $seleksiAdministrasiInstansi->catatan_surat_usulan = $catatanSuratUsulan;
        $seleksiAdministrasiInstansi->sptjm = $sptjm;
        $seleksiAdministrasiInstansi->catatan_sptjm = $catatanSptjm;
        if ($seleksiAdministrasiInstansi->save()) {
            foreach($instansi_ZI->unit_zi as $unit_zi){
                $seleksiAdministrasiUnit = SeleksiAdministrasiUnit::where('unit_zi_id', $unit_zi->id)->first();
                if (!$seleksiAdministrasiUnit) {
                    $seleksiAdministrasiUnit = new SeleksiAdministrasiUnit();
                }
                $seleksiAdministrasiUnit->unit_zi_id = $unit_zi->id;
                $seleksiAdministrasiUnit->status_lke = $request->get('status-lke-'.$unit_zi->id ); 
                $seleksiAdministrasiUnit->catatan_lke = $request->get('catatan-lke-'.$unit_zi->id );
                $seleksiAdministrasiUnit->status_2wbk = $request->get('status-2wbk-'.$unit_zi->id ); 
                $seleksiAdministrasiUnit->catatan_2wbk = $request->get('catatan-2wbk-'.$unit_zi->id );
                $seleksiAdministrasiUnit->status_tlhp = $request->get('tlhp-'.$unit_zi->id );
                $seleksiAdministrasiUnit->catatan_tlhp = $request->get('catatanTlhp-'.$unit_zi->id );
                $seleksiAdministrasiUnit->status_survei_mandiri = $request->get('surveiMandiri-'.$unit_zi->id );
                $seleksiAdministrasiUnit->catatan_survei_mandiri = $request->get('catatanSurveiMandiri-'.$unit_zi->id );
                $seleksiAdministrasiUnit->status_lhkpn = $request->get('lhkpn-'.$unit_zi->id );
                $seleksiAdministrasiUnit->catatan_lhkpn = $request->get('catatanLhkpn-'.$unit_zi->id );
                $seleksiAdministrasiUnit->save();
            }
        };
        return redirect()->route('evaluasi_administrasi',$instansiZIid);
    }

   
    
}
