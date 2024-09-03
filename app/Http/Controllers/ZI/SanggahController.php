<?php

namespace App\Http\Controllers\ZI;

use App\Models\ZI\UnitZI;
use App\Models\KlpdInstansi;
use Illuminate\Http\Request;
use App\Models\ZI\InstansiZI;
use App\Models\ZI\SanggahUnit;
use App\Models\ZI\TimEvaluasi;
use App\Models\ZI\SanggahInstansi;
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
        
        $instansiZis = InstansiZi::with(['unit_zi.seleksi_administrasi_unit'])
        ->where('final', 1)
        ->get();
        $jumlah_instansi = 0;
        $jumlah_unit_wbk = 0;
        $jumlah_unit_wbbm = 0;
        
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
                ->map(function($instansiZi) use(&$jumlah_unit_wbk, &$jumlah_unit_wbbm, &$jumlah_lolos_wbk, &$jumlah_lolos_wbbm, &$jumlah_instansi, &$jumlah_instansi_lolos, &$progress_teams) {
                    $wbkCount = $instansiZi->unit_zi->where('wbk', true)
                    ->filter(function($unitZi) {
                        return  optional($unitZi->seleksi_administrasi_unit)->status_final === 0;
                    })->count();
                    $wbbmCount = $instansiZi->unit_zi->where('wbbm', true)
                    ->filter(function($unitZi) {
                        return  optional($unitZi->seleksi_administrasi_unit)->status_final === 0;
                    })->count();

                    $total_unit = $wbkCount + $wbbmCount;

                    if($total_unit>0){
                        $jumlah_instansi += 1;
                        $jumlah_unit_wbk += $wbkCount; 
                        $jumlah_unit_wbbm += $wbbmCount; 
                        $nama_teams = $instansiZi->unit_zi->flatMap->unit_tim->map->tim->unique()->pluck('nama')->toArray();

                        $wbkFinalCount = $instansiZi->unit_zi->where('wbk', true)
                        ->filter(function($unitZi) {
                            return  optional($unitZi->sanggah_unit)->status_final == 1;
                        })->count();
                        $jumlah_lolos_wbk += $wbkFinalCount;

                        $wbbmFinalCount = $instansiZi->unit_zi->where('wbbm', true)
                        ->filter(function($unitZi) {
                            return  optional($unitZi->sanggah_unit)->status_final == 1;
                        })->count();
                        $jumlah_lolos_wbbm += $wbbmFinalCount;

                        $wbkCompletedCount = $instansiZi->unit_zi->where('wbk', true)
                        ->filter(function($unitZi) {
                            return  optional($unitZi->sanggah_unit)->status_completed == 1;
                        })->count();
        
                        $wbbmCompletedCount = $instansiZi->unit_zi->where('wbbm', true)
                        ->filter(function($unitZi) {
                            return  optional($unitZi->sanggah_unit)->status_completed == 1;
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
                                    if($wbkFinalCount>0 ) {
                                        $progress_teams[$tim]["jumlah_wbk_completed"] += $wbkCompletedCount ;
                                    }
                                    
                                    if($wbbmFinalCount>0 ){  
                                        $progress_teams[$tim]["jumlah_wbbm_completed"] += $wbkCompletedCount;
                                    }      
                                }
                            }
                        }

                        if($total_unit<1){//mendhindari division by zer0
                            $total_unit = 1;
                        }

                        
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
                            'persentase' => floor(100*($wbkCompletedCount+$wbbmCompletedCount)/$total_unit)
                        ];
                    }else{
                        return false;
                    }
                })
                ->reject(function ($value) {
                    return $value === false;
                });

                $jumlah_unit_total = $jumlah_unit_wbk + $jumlah_unit_wbbm;

                return view('zi.proses_sanggah.administrasi', compact(
                    "title","jumlah_instansi","jumlah_unit_wbk","jumlah_unit_wbbm", 
                    'jumlah_lolos_wbk', 'jumlah_lolos_wbbm', 'progress_teams',
                    "jumlah_unit_total","datas", "jumlah_instansi_lolos"
                ));        

        
                
    }

    public function proses_sanggah($id)
    {   
        $title = "Sanggah";
        $instansi_ZI = InstansiZI::find($id);
        $tim_ids = [];
        foreach($instansi_ZI->unit_zi as $unit_zi){
            foreach ( $unit_zi->unit_tim as $unitTim){
                if(!in_array($unitTim->tim_id, $tim_ids)){
                    array_push($tim_ids, $unitTim->tim_id);                            
                }
            }
        }
        $status = "Tidak Berhak";
        //DI LOCK BIAR SEMUA ORANGG TIDAK BISA SIMPAN
        if(Auth::User()->userTimZI){                   
            foreach(Auth::User()->userTimZI as $anggotaTim){
                if(in_array($anggotaTim->tim_id,$tim_ids)){
                    $status = "Berhak" ;
                }
            }
        }
        //$status = "Berhak"  //untuk kebutuhan testing;
        $unit_ZIs = UnitZI::where("instansi_zi_id", $id)->orderBy('wbk','desc')->get();
        
        $sanggahInstansi = SanggahInstansi::where('instansi_zi_id', $id)->first();
        $valSuratUsulan = ($sanggahInstansi)?$sanggahInstansi->status_surat_usulan:Null;
        $valCatatanSuratUsulan = ($sanggahInstansi)?$sanggahInstansi->catatan_surat_usulan:Null;
        $valSptjm = ($sanggahInstansi)?$sanggahInstansi->status_sptjm:Null;
        $valCatatanSptjm = ($sanggahInstansi)?$sanggahInstansi->catatan_sptjm:Null;
        return view('zi.proses_sanggah.evaluasi', compact(
            "title","instansi_ZI","unit_ZIs","sanggahInstansi",
            "valSuratUsulan", "valCatatanSuratUsulan", "valSptjm","valCatatanSptjm","status"
            ));
    }

    public function proses_sanggah_simpan(Request $request){
        //dd("Proses Evaluasi Sanggah Buat Evaluator Masih Belum Dibuka Yah, mau ke mana sih buru-buru amat, Jangan Ya Dek Ya !! :p");
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
        
        
        
        $seleksiSanggahInstansi = SanggahInstansi::where('instansi_zi_id', $instansiZIid)->first();
        if (!$seleksiSanggahInstansi) {
            $seleksiSanggahInstansi = new SanggahInstansi();
            $seleksiSanggahInstansi->instansi_zi_id = $instansiZIid;
        }
        
        if(!is_null($request->get('status-surat-usulan')))$seleksiSanggahInstansi->status_surat_usulan = $request->get('status-surat-usulan');
        if(!is_null($request->get('catatanSuratUsulan')))$seleksiSanggahInstansi->catatan_surat_usulan = $request->get('catatanSuratUsulan');
        if(!is_null($request->get('status-sptjm')))$seleksiSanggahInstansi->status_sptjm = $request->get('status-sptjm');;
        if(!is_null($request->get('catatanSptjm')))$seleksiSanggahInstansi->catatan_sptjm = $request->get('catatanSptjm');
        $seleksiSanggahInstansi->save();

        //cek Surat Usulan
        if($seleksiSanggahInstansi->instansi_ZI->administrasi_instansi->surat_usulan ===0){
            $status_surat_usulan = $seleksiSanggahInstansi->status_surat_usulan;
        }elseif($seleksiSanggahInstansi->instansi_ZI->administrasi_instansi->surat_usulan ==1){
            $status_surat_usulan = 1;
        }else{
            $status_surat_usulan = null;
        }


        //cek SPTJM
        if($seleksiSanggahInstansi->instansi_ZI->administrasi_instansi->sptjm ===0){
            $status_sptjm = $seleksiSanggahInstansi->status_sptjm;
        }elseif($seleksiSanggahInstansi->instansi_ZI->administrasi_instansi->sptjm ==1){
            $status_sptjm = 1;
        }else{
            $status_sptjm = null;
        }
        
        

        foreach($instansi_ZI->unit_zi as $unit_zi){
            $seleksiSanggahUnit = SanggahUnit::where('unit_zi_id', $unit_zi->id)->first();
            if($unit_zi->seleksi_administrasi_unit->status_final ===0){
                if (!$seleksiSanggahUnit) {
                    $seleksiSanggahUnit = new SanggahUnit();
                    $seleksiSanggahUnit->unit_zi_id = $unit_zi->id;
                }
                if(!is_null($request->get('status-lke-'.$unit_zi->id )))$seleksiSanggahUnit->status_lke = $request->get('status-lke-'.$unit_zi->id ); 
                if(!is_null($request->get('catatan-lke-'.$unit_zi->id )))$seleksiSanggahUnit->catatan_lke = $request->get('catatan-lke-'.$unit_zi->id );
                if(!is_null($request->get('status-2wbk-'.$unit_zi->id )))$seleksiSanggahUnit->status_2wbk = $request->get('status-2wbk-'.$unit_zi->id ); 
                if(!is_null($request->get('catatan-2wbk-'.$unit_zi->id)))$seleksiSanggahUnit->catatan_2wbk = $request->get('catatan-2wbk-'.$unit_zi->id );
                if(!is_null($request->get('tlhp-'.$unit_zi->id )))$seleksiSanggahUnit->status_tlhp = $request->get('tlhp-'.$unit_zi->id );
                if(!is_null($request->get('catatanTlhp-'.$unit_zi->id )))$seleksiSanggahUnit->catatan_tlhp = $request->get('catatanTlhp-'.$unit_zi->id );
                if(!is_null($request->get('surveiMandiri-'.$unit_zi->id )))$seleksiSanggahUnit->status_survei_mandiri = $request->get('surveiMandiri-'.$unit_zi->id );
                if(!is_null($request->get('catatanSurveiMandiri-'.$unit_zi->id )))$seleksiSanggahUnit->catatan_survei_mandiri = $request->get('catatanSurveiMandiri-'.$unit_zi->id );    
                $seleksiSanggahUnit->updated_by = Auth::User()->id;
                $seleksiSanggahUnit->save();
            
                //cek status LKE
                if($seleksiSanggahUnit->unitZI->seleksi_administrasi_unit->status_lke ===0){
                    $status_lke = $seleksiSanggahUnit->status_lke;
                }elseif($seleksiSanggahUnit->unitZI->seleksi_administrasi_unit->status_lke ==1){
                    $status_lke = 1;
                }else{
                    $status_lke = null;
                }
                
                //cek status TLHP
                if($seleksiSanggahUnit->unitZI->seleksi_administrasi_unit->status_tlhp ===0){
                    $status_tlhp = $seleksiSanggahUnit->status_tlhp;
                }elseif($seleksiSanggahUnit->unitZI->seleksi_administrasi_unit->status_tlhp ==1){
                    $status_tlhp = 1;
                }else{
                    $status_tlhp = null;
                }
                
                //cek status survei mandiri
                if($seleksiSanggahUnit->unitZI->seleksi_administrasi_unit->status_survei_mandiri ===0){
                    $status_survei_mandiri = $seleksiSanggahUnit->status_survei_mandiri;
                }elseif($seleksiSanggahUnit->unitZI->seleksi_administrasi_unit->status_survei_mandiri ==1){
                    $status_survei_mandiri = 1;
                }else{
                    $status_survei_mandiri = null;
                }
                
                //2wbk
                if($seleksiSanggahUnit->unitZI->seleksi_administrasi_unit->status_2wbk ===0){
                    $status_2wbk = $seleksiSanggahUnit->status_2wbk;
                }elseif($seleksiSanggahUnit->unitZI->seleksi_administrasi_unit->status_2wbk ==1){
                    $status_2wbk = 1;
                }else{
                    $status_2wbk = null;
                }

                if(!is_null($status_surat_usulan) && 
                    !is_null($status_sptjm) && 
                    !is_null($status_lke) &&
                    !is_null($status_tlhp) &&
                    !is_null($status_survei_mandiri) 
                    )
                {
                        $status_final = 1; 
                        ($status_surat_usulan== 0 )?$status_final = 0:Null;
                        ($status_sptjm ==0 )?$status_final = 0:Null;
                        ($status_lke == 0 )?$status_final = 0:Null;
                        ($status_tlhp == 0 )?$status_final = 0:Null;
                        ($status_survei_mandiri == 0 )?$status_final = 0:Null;
                        if($seleksiSanggahUnit->unitZI->wbbm){
                            if(!is_null($status_2wbk)){
                                ($status_2wbk == 0 )?$status_final = 0:Null;
                                $seleksiSanggahUnit->status_final = $status_final;
                                $seleksiSanggahUnit->status_completed = 1;
                                $seleksiSanggahUnit->save();
                            }
                        }else{
                            $seleksiSanggahUnit->status_final = $status_final;
                            $seleksiSanggahUnit->status_completed = 1;
                            $seleksiSanggahUnit->save();
                        }
                }else{
                    $seleksiSanggahUnit->status_completed = null;
                    $seleksiSanggahUnit->status_final = null;
                    $seleksiSanggahUnit->save();
                };
            };
        };
            
        
        return redirect()->route('proses_sanggah',$instansiZIid);
    }

    
    
}
