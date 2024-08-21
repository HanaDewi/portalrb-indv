<?php

namespace App\Http\Controllers\ZI;

use App\Models\ZI\SanggahInstansi;
use App\Models\ZI\UnitZI;
use App\Models\KlpdInstansi;
use Illuminate\Http\Request;
use App\Models\ZI\InstansiZI;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\ZI\SeleksiAdministrasiUnit;
use App\Models\ZI\SeleksiAdministrasiInstansi;

class SanggahController extends Controller
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
        $title = "Sanggah";
        $instansi_sanggahs = SanggahInstansi::get();
        // $wbk_mandiri_count = UnitZI::whereHas('instansiZI', function ($query) {
        //     $query->where('instansi_wbk_mandiri', 1);
        // })->where('wbk', 1)->count();
        

        return view('zi.proses_sanggah.sanggah', compact(
            "title", "instansi_sanggahs"
        ));
                
    }

    public function proses_sanggah($id)
    {   
        $title = "Sanggah";
        $instansi_ZI = InstansiZI::find($id);
        $unit_ZIs = UnitZI::where("instansi_zi_id", $id)->orderBy('wbk','desc')->get();
        
        $sanggahInstansi = SanggahInstansi::where('instansi_zi_id', $id)->first();
        $valSuratUsulan = ($sanggahInstansi)?$sanggahInstansi->surat_usulan:Null;
        $valCatatanSuratUsulan = ($sanggahInstansi)?$sanggahInstansi->catatan_surat_usulan:Null;
        $valSptjm = ($sanggahInstansi)?$sanggahInstansi->sptjm:Null;
        $valCatatanSptjm = ($sanggahInstansi)?$sanggahInstansi->catatan_sptjm:Null;
        return view('zi.proses_sanggah.evaluasi', compact(
            "title","instansi_ZI","unit_ZIs","sanggahInstansi",
            "valSuratUsulan", "valCatatanSuratUsulan", "valSptjm","valCatatanSptjm",
            ));
    }

    public function proses_sanggah_simpan(Request $request){
        dd("belum beres, masih dalam pengerjaan, mohon bersabar yah");
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
