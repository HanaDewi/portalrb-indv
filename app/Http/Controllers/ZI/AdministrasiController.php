<?php

namespace App\Http\Controllers\ZI;

use App\Models\ZI\UnitZI;
use App\Models\KlpdInstansi;
use Illuminate\Http\Request;
use App\Models\ZI\InstansiZI;
use App\Models\ZI\FilesUpload;
use App\Models\ZI\TimEvaluasi;
use App\Models\ZI\UploadsFile;
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

        $instansiZis = InstansiZi::with(['unit_zi.seleksi_administrasi_unit'])
        ->where('final', 1)
        ->get();
        $jumlah_instansi = $instansiZis->count();
        $jumlah_unit_wbk = $instansiZis->where('instansi_wbk_mandiri','!=',1)->sum('jml_wbk');
        $jumlah_unit_wbbm =$instansiZis->sum('jml_wbbm');
        $jumlah_unit_total = $jumlah_unit_wbk + $jumlah_unit_wbbm;
        $jumlah_lolos_wbk = 0;
        $jumlah_lolos_wbbm = 0;
        $jumlah_instansi_lolos = 0;
        $teams = TimEvaluasi::get();
        $progress_teams = [];
        foreach($teams as $tim){       
            $progress_teams[$tim->nama] = [
                    "id" => $tim->id,
                    "jumlah_instansi" => 0,
                    "jumlah_wbk" => 0,
                    "jumlah_wbbm" =>0,
                    "jumlah_wbk_completed" => 0,
                    "jumlah_wbbm_completed" =>0,
                    "jumlah_instansi_lulus" => 0,
                    "jumlah_wbk_final_total" => 0,
                    "jumlah_wbbm_final_total" =>0
                ] ;     
        }
        
        
        $datas = $instansiZis
                ->map(function($instansiZi) use(&$jumlah_lolos_wbk, &$jumlah_lolos_wbbm, &$jumlah_instansi_lolos, &$progress_teams) {
                    if(!$instansiZi->instansi_wbk_mandiri){
                        
                        $wbkCount = optional($instansiZi->unit_zi)->where('wbk', true)->count();
                    }else{
                       $wbkCount = 0;
                    }
                    $wbbmCount = optional($instansiZi->unit_zi)->where('wbbm', true)->count();
                    
                    $nama_teams = $instansiZi->unit_zi->flatMap->unit_tim->map->tim->unique()->pluck('nama')->toArray();

                    
                    $wbkFinalCount = $instansiZi->unit_zi->where('wbk', true)
                    ->filter(function($unitZi) {
                        return  optional($unitZi->seleksi_administrasi_unit)->status_final == 1;
                    })->count();
                    
                    $jumlah_lolos_wbk += $wbkFinalCount;

                    $wbbmFinalCount = $instansiZi->unit_zi->where('wbbm', true)
                    ->filter(function($unitZi) {
                        return  optional($unitZi->seleksi_administrasi_unit)->status_final == 1;
                    })->count();
                    $jumlah_lolos_wbbm += $wbbmFinalCount;

                    $wbkCompletedCount = $instansiZi->unit_zi->where('wbk', true)
                    ->filter(function($unitZi) {
                        return  optional($unitZi->seleksi_administrasi_unit)->status_completed == 1;
                    })->count();
    
                    $wbbmCompletedCount = $instansiZi->unit_zi->where('wbbm', true)
                    ->filter(function($unitZi) {
                        return  optional($unitZi->seleksi_administrasi_unit)->status_completed == 1;
                    })->count();

                    if($wbkFinalCount>0 || $wbbmFinalCount>0 ){
                        $jumlah_instansi_lolos += 1;
                    }

                    foreach($nama_teams as $tim){   
                        if (array_key_exists($tim, $progress_teams)) {
                            $progress_teams[$tim]["jumlah_instansi"] +=1;
                            $progress_teams[$tim]["jumlah_wbk"] += $wbkCount ;
                            $progress_teams[$tim]["jumlah_wbbm"] += $wbbmCount ;
                            if($wbkFinalCount>0 || $wbbmFinalCount>0 ){
                                $progress_teams[$tim]["jumlah_instansi_lulus"] +=1;
                                if($wbkFinalCount>0 ) {
                                    $progress_teams[$tim]["jumlah_wbk_final_total"] += $wbkFinalCount ;
                                }
                                
                                if($wbbmFinalCount>0 ){  
                                    $progress_teams[$tim]["jumlah_wbbm_final_total"] += $wbbmFinalCount;
                                }      
                            }

                            if($wbkCompletedCount>0 || $wbbmCompletedCount>0 ){
                                if($wbkCompletedCount>0 ) {
                                    $progress_teams[$tim]["jumlah_wbk_completed"] += $wbkCompletedCount ;
                                }
                                
                                if($wbbmCompletedCount>0 ){  
                                    $progress_teams[$tim]["jumlah_wbbm_completed"] += $wbbmCompletedCount;
                                }      
                            }
                            
                        }
                    }
                    $pembilang = $wbkCompletedCount+$wbbmCompletedCount;
                    $penyebut = $wbkCount + $wbbmCount;
                    ($penyebut==0)?$penyebut=1:Null;
                    return [
                        'instansi_nama' => $instansiZi->klpd_instansi->name,
                        'instansi_zi_id' => $instansiZi->id,
                        'instansi_wbk_mandiri' => $instansiZi->instansi_wbk_mandiri,
                        'nama_teams' => $nama_teams,
                        'wbk_count' => $wbkCount,
                        'wbbm_count' => $wbbmCount,
                        'total_unit' => $wbkCount + $wbbmCount,
                        'wbk_final_count' => $wbkFinalCount,
                        'wbbm_final_count' => $wbbmFinalCount,
                        'total_unit_lulus' => $wbkFinalCount+$wbbmFinalCount,
                        'wbk_completed_count' => $wbkCompletedCount,
                        'wbbm_completed_count' => $wbbmCompletedCount,
                        'persentase' => floor(100*($pembilang)/($penyebut))
                    ];
                });

                $surat_sanggah = FilesUpload::find(1);
                
                return view('zi.seleksi_administrasi.administrasi', compact(
                    "title","jumlah_instansi","jumlah_unit_wbk","jumlah_unit_wbbm", 
                    'jumlah_lolos_wbk', 'jumlah_lolos_wbbm', 'progress_teams',
                    "jumlah_unit_total","datas", "jumlah_instansi_lolos", "surat_sanggah"
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
        $tim_ids = [];
        foreach($instansi_ZI->unit_zi as $unit_zi){
            foreach ( $unit_zi->unit_tim as $unitTim){
                if(!in_array($unitTim->tim_id, $tim_ids)){
                    array_push($tim_ids, $unitTim->tim_id);                            
                }
            }
        }
        $status = "Tidak Berhak";
        // DIDISABLE BIAR SEMUA ORANG TIDAK BISA EDIT
        // if(Auth::User()->userTimZI){                   
        //     foreach(Auth::User()->userTimZI as $anggotaTim){
        //         if(in_array($anggotaTim->tim_id,$tim_ids)){
        //             $status = "Berhak" ;
        //         }
        //     }
        // }
        return view('zi.seleksi_administrasi.evaluasi', compact(
            "title","instansi_ZI","unit_ZIs","seleksiAdministrasiInstansi",
            "valSuratUsulan", "valCatatanSuratUsulan", "valSptjm","valCatatanSptjm","status"
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
        // DI LOCK BIAR SEMUA ORANGG TIDAK BISA SIMPAN
        // if(Auth::User()->userTimZI){                   
        //     foreach(Auth::User()->userTimZI as $anggotaTim){
        //         if(in_array($anggotaTim->tim_id,$tim_ids)){
        //             $status = "Berhak" ;
        //         }
        //     }
        // }
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
                //pakai if karena kalau ada beberapa user berbarengan ngisinya, biar yang masih kosong gak ngereplace yg sudah terisi sebelumnya
                if (!$seleksiAdministrasiUnit) {
                    $seleksiAdministrasiUnit = new SeleksiAdministrasiUnit();
                }
                $seleksiAdministrasiUnit->unit_zi_id = $unit_zi->id;
                if(!is_null($request->get('status-lke-'.$unit_zi->id ))){
                    $seleksiAdministrasiUnit->status_lke = $request->get('status-lke-'.$unit_zi->id ); 
                }
                if(!is_null($request->get('catatan-lke-'.$unit_zi->id ))){
                    $seleksiAdministrasiUnit->catatan_lke = $request->get('catatan-lke-'.$unit_zi->id );
                }
                if(!is_null($request->get('status-2wbk-'.$unit_zi->id))){
                $seleksiAdministrasiUnit->status_2wbk = $request->get('status-2wbk-'.$unit_zi->id); 
                }
                if(!is_null($request->get('catatan-2wbk-'.$unit_zi->id ))){
                    $seleksiAdministrasiUnit->catatan_2wbk = $request->get('catatan-2wbk-'.$unit_zi->id );
                }
                if(!is_null($request->get('tlhp-'.$unit_zi->id ))){
                    $seleksiAdministrasiUnit->status_tlhp = $request->get('tlhp-'.$unit_zi->id );
                }
                if(!is_null($request->get('catatanTlhp-'.$unit_zi->id ))){
                    $seleksiAdministrasiUnit->catatan_tlhp = $request->get('catatanTlhp-'.$unit_zi->id );
                }
                if(!is_null($request->get('surveiMandiri-'.$unit_zi->id ))){
                    $seleksiAdministrasiUnit->status_survei_mandiri = $request->get('surveiMandiri-'.$unit_zi->id );
                }
                if(!is_null($request->get('catatanSurveiMandiri-'.$unit_zi->id ))){
                    $seleksiAdministrasiUnit->catatan_survei_mandiri = $request->get('catatanSurveiMandiri-'.$unit_zi->id );
                }
                $seleksiAdministrasiUnit->updated_by = Auth::User()->id;
                $seleksiAdministrasiUnit->save();
                
                
                
                if(!is_null($seleksiAdministrasiInstansi->surat_usulan) && 
                !is_null($seleksiAdministrasiInstansi->sptjm) && 
                !is_null($seleksiAdministrasiUnit->status_lke) &&
                !is_null($seleksiAdministrasiUnit->status_tlhp) &&
                !is_null($seleksiAdministrasiUnit->status_survei_mandiri) 
                ){
                    $status_final = 1; 
                    ($seleksiAdministrasiInstansi->surat_usulan == 0 )?$status_final = 0:Null;
                    ($seleksiAdministrasiInstansi->sptjm ==0 )?$status_final = 0:Null;
                    ($seleksiAdministrasiUnit->status_lke == 0 )?$status_final = 0:Null;
                    ($seleksiAdministrasiUnit->status_tlhp == 0 )?$status_final = 0:Null;
                    ($seleksiAdministrasiUnit->status_survei_mandiri == 0 )?$status_final = 0:Null;
                    if($unit_zi->wbbm){
                        if(!is_null($seleksiAdministrasiUnit->status_2wbk)){
                            ($seleksiAdministrasiUnit->status_2wbk == 0 )?$status_final = 0:Null;
                            $seleksiAdministrasiUnit->status_final = $status_final;
                            $seleksiAdministrasiUnit->status_completed = 1;
                            $seleksiAdministrasiUnit->save();
                        }
                    }else{
                        $seleksiAdministrasiUnit->status_final = $status_final;
                        $seleksiAdministrasiUnit->status_completed = 1;
                        $seleksiAdministrasiUnit->save();
                    }
                }else{
                    $seleksiAdministrasiUnit->status_completed = 0;
                    $seleksiAdministrasiUnit->save();
                }
            }
        };
        return redirect()->route('evaluasi_administrasi',$instansiZIid);
    }

   
    
}
