<?php

namespace App\Http\Controllers\ZI;

use App\Models\ZI\Final;
use App\Models\ZI\Panel;
use App\Models\ZI\UnitZI;
use App\Models\KlpdInstansi;
use Illuminate\Http\Request;
use App\Models\ZI\HasilFinal;
use App\Models\ZI\InstansiZI;
use App\Models\ZI\UnggahFile;
use App\Models\ZI\TimEvaluasi;
use GuzzleHttp\Psr7\UploadedFile;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\ZI\SeleksiAdministrasiUnit;
use App\Models\ZI\SeleksiAdministrasiInstansi;

class FinalController extends Controller
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
        $title = "Final";
        $instansiZis = InstansiZi::with(['unit_zi.seleksi_administrasi_unit'])
        ->where('final', 1)
        ->get();
        $jumlah_instansi = 0;
        $jumlah_unit_wbk = 0;
        $jumlah_unit_wbbm = 0;
        
        
        $jumlah_lolos_wbk = 0;
        $jumlah_lolos_wbbm = 0;
        $jumlah_instansi_lolos = 0;
        $uploaded_lhe = 0 ;
        $teams = TimEvaluasi::get();
        $progress_teams = [];
        foreach($teams as $tim){       
            $progress_teams[$tim->nama] = [
                    "id" => $tim->id,
                    "jumlah_instansi" => 0,
                    "jumlah_lhe_completed" => 0
                ] ;
        }
        
        $datas = $instansiZis
                ->map(function($instansiZi) use(&$jumlah_unit_wbk, &$jumlah_unit_wbbm, &$jumlah_lolos_wbk, &$jumlah_lolos_wbbm, &$jumlah_instansi, &$jumlah_instansi_lolos, &$progress_teams, &$uploaded_lhe) {
                    $wbkCount = $instansiZi->unit_zi->where('wbk', true)->count();
                    $wbbmCount = $instansiZi->unit_zi->where('wbbm', true)->count();

                    $total_unit = $wbkCount + $wbbmCount;
                    
                    $lhe_telah_diupload = false;
                    if($instansiZi->lhe){
                        $uploaded_lhe++ ;
                        $lhe_telah_diupload = true;
                    }
                    if($total_unit>0){
                        $jumlah_instansi += 1;
                        $jumlah_unit_wbk += $wbkCount; 
                        $jumlah_unit_wbbm += $wbbmCount; 
                        $nama_teams = $instansiZi->unit_zi->flatMap->unit_tim->map->tim->unique()->pluck('nama')->toArray();
                        $wbkFinalCount = $instansiZi->unit_zi->where('wbk', true)
                        ->filter(function($unitZi) {
                            return  optional($unitZi->panel)->status == 1;
                        })->count();
                        $jumlah_lolos_wbk += $wbkFinalCount;

                        $wbbmFinalCount = $instansiZi->unit_zi->where('wbbm', true)
                        ->filter(function($unitZi) {
                            return  optional($unitZi->panel)->status == 1;
                        })->count();
                        $jumlah_lolos_wbbm += $wbbmFinalCount;

                        $wbkCompletedCount = $instansiZi->unit_zi->where('wbk', true)
                        ->filter(function($unitZi) {
                            return  optional($unitZi->panel)->status > -1;
                        })->count();
        
                        $wbbmCompletedCount = $instansiZi->unit_zi->where('wbbm', true)
                        ->filter(function($unitZi) {
                            return  optional($unitZi->panel)->status > -1;
                        })->count();

                        if($wbkFinalCount>0 || $wbbmFinalCount>0 ){
                            $jumlah_instansi_lolos += 1;
                        }

                        foreach($nama_teams as $tim){   
                            if (array_key_exists($tim, $progress_teams)) {
                                $progress_teams[$tim]["jumlah_instansi"] +=1;
                                if($lhe_telah_diupload){
                                $progress_teams[$tim]["jumlah_lhe_completed"]++;  
                                }
                            }
                        }

                        if($total_unit<1){//mendhindari division by zer0
                            $total_unit = 1;
                        }

                        if($instansiZi->instansi_wbk_mandiri == 1){
                            $nama_instansi = $instansiZi->klpd_instansi->name . " (Wbk Mandiri)" ;
                        }
                        else{
                            $nama_instansi = $instansiZi->klpd_instansi->name;
                        }
                        
                        return [
                            'instansi_nama' => $nama_instansi,
                            'instansi_zi_id' => $instansiZi->id,
                            'instansi_wbk_mandiri' => $instansiZi->instansi_wbk_mandiri,
                            'nama_teams' => $nama_teams,
                            'wbk_count' => $wbkCount,
                            'wbbm_count' => $wbbmCount,
                            'lhe_telah_diupload' => $lhe_telah_diupload,
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

        return view('zi.final.administrasi', compact(
            "title","jumlah_instansi","jumlah_unit_wbk","jumlah_unit_wbbm", 
            'jumlah_lolos_wbk', 'jumlah_lolos_wbbm', 'progress_teams',
            "jumlah_unit_total","datas", "jumlah_instansi_lolos", 'uploaded_lhe'
        ));               
    }

    public function final($id)
    {   
        $title = "Seleksi Panel";
        $instansi_ZI = InstansiZI::find($id);
        $tim_ids = [];
        
    //-------upload LHE             
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
        
        $unit_ZIs = UnitZI::where("instansi_zi_id", $id)->orderBy('wbbm','desc')->get();
        
        
        
        return view('zi.final.evaluasi', compact("status",
            "title","instansi_ZI","unit_ZIs",
            ));
        
    }

    public function final_simpan(Request $request){
        //dd("Proses Seleksi Dokumen Buat Evaluator Masih Belum Dibuka Yah, mau ke mana sih buru-buru amat, Jangan Ya Dek Ya !! :p");
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
        
        foreach($instansi_ZI->unit_zi as $unit_zi){
            $finalUnit = HasilFinal::where('unit_zi_id', $unit_zi->id)->first();
            if (!$finalUnit) {
                $finalUnit = new HasilFinal();
                $finalUnit->unit_zi_id = $unit_zi->id;
            }
            
            #if(!is_null($request->get('jadwal-'.$unit_zi->id)))$finalUnit->jadwal = $request->get('jadwal-'.$unit_zi->id ); 
            #if(!is_null($request->get('bukti-dukung-'.$unit_zi->id )))$finalUnit->bukti_dukung = $request->get('bukti-dukung-'.$unit_zi->id ); 
            if(!is_null($request->get('kondisi-'.$unit_zi->id )))$finalUnit->kondisi = $request->get('kondisi-'.$unit_zi->id );
            if(!is_null($request->get('rekomendasi-'.$unit_zi->id )))$finalUnit->rekomendasi = $request->get('rekomendasi-'.$unit_zi->id ); 
            #if(!is_null($request->get('status-'.$unit_zi->id )))$finalUnit->status = $request->get('status-'.$unit_zi->id ); 
            $finalUnit->updated_by = Auth::User()->id;
            
            $finalUnit->save();  
        };
            
        
        
        return redirect()->route('proses_final',$instansiZIid);
    }

    public function lhe_simpan (Request $request)
    {
        $validated = $request->validate([
            'berkas' => 'required|array',
            'berkas.*' => 'file|mimes:jpg,png,pdf|max:2048', // Validate each file in the array
        ]);

        $success= false;
        try{
            if ($request->hasFile('berkas')) {
                foreach ($request->file('berkas') as $key => $file_berkas) {
                    $instansiZI = InstansiZI::where('id', $request->get('instansi_id'))->first();
                    $deskripsi = $request->deskripsi[$key];
                    $time = time();
                    $filename = "_$time.". $deskripsi ."." .$file_berkas->getClientOriginalExtension();
                    $instansiZI->lhe = $filename;
                    $file_berkas->storeAs('uploads/LHEZI2024', $filename, 'public');
                    if ($instansiZI->save()) {
                        $success = true;
                    } else {
                        $success = false;
                    }
                    break;
                }
            }
        } catch (\Throwable $th) {
             throw $th;
        }
        if ($success) {
             session()->flash('success', 'LHE berhasil disimpan.');
        } else {
             session()->flash('error', 'LHE gagal disimpan! Silahkan dicoba kembali.');
        }
        return redirect('/zi/final/'.$request->get('instansi_id'));
    }

    public function undangan_simpan (Request $request)
    {
        $validated = $request->validate([
            'berkas_undangan' => 'required|array',
            'berkas_undangan.*' => 'file|mimes:jpg,png,pdf|max:2048', // Validate each file in the array
        ]);

        $success= false;
        try{
            if ($request->hasFile('berkas_undangan')) {
                
                foreach ($request->file('berkas_undangan') as $key => $file_berkas) {
                    $instansiZI = InstansiZI::where('id', $request->get('instansi_id'))->first();
                    $deskripsi = $request->deskripsi[$key];
                    $time = time();
                    $filename = "_$time.". $deskripsi ."." .$file_berkas->getClientOriginalExtension();
                    $instansiZI->surat_undangan = $filename;
                    $file_berkas->storeAs('uploads/SuratUndangan2024', $filename, 'public');
                    
                    if ($instansiZI->save()) {
                        $success = true;
                    } else {
                        $success = false;
                    }
                    break;
                }
            }
        } catch (\Throwable $th) {
             throw $th;
             
        }
        if ($success) {
             session()->flash('success', 'Surat Undangan berhasil disimpan.');
        } else {
             session()->flash('error', 'Surat Undangan gagal disimpan! Silahkan dicoba kembali.');
        }
        return redirect('/zi/final/'.$request->get('instansi_id'));
    }


   
    
}
