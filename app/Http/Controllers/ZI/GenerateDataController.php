<?php

namespace App\Http\Controllers\ZI;

use App\Models\KlpdInstansi;
use Illuminate\Http\Request;
use App\Models\LkeTestTpLine;
use App\Models\ZI\InstansiZI;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\ZI\SeleksiAdministrasiUnit;
use App\Models\ZI\SeleksiAdministrasiInstansi;


class GenerateDataController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if(Auth::User()->level =="admin"){
                    return $next($request);     
            }
            abort('403');
        });
    }
    
    public function sinkron_final_completed(){
        $seleksi_administrasi_units = SeleksiAdministrasiUnit::all();
        foreach($seleksi_administrasi_units as $unit_administrasi){
            $instansi_zi_id = $unit_administrasi->unitZI->instansi_zi_id;
            $seleksi_administrasi_instansi = SeleksiAdministrasiInstansi::where('instansi_zi_id', $instansi_zi_id)->first();
            $unit_administrasi->status_completed = 0;
            if( 
                !is_null($seleksi_administrasi_instansi->surat_usulan) && 
                !is_null($seleksi_administrasi_instansi->sptjm) && 
                !is_null($unit_administrasi->status_lke) && 
                !is_null($unit_administrasi->status_tlhp) && 
                !is_null($unit_administrasi->status_survei_mandiri)){
                    $status_final = 1;
                    if($unit_administrasi->unitZI->wbbm){
                        if(!is_null($unit_administrasi->status_2wbk)){
                            $unit_administrasi->status_completed = 1;
                            ($unit_administrasi->status_2wbk===0)?$status_final=0:null;        
                        }
                    }

                    if($unit_administrasi->unitZI->wbk){
                        $unit_administrasi->status_completed = 1;
                    }

                    ($unit_administrasi->status_lke===0)?$status_final=0:null;
                    ($unit_administrasi->status_tlhp===0)?$status_final=0:null;
                    ($unit_administrasi->status_survei_mandiri===0)?$status_final=0:null;
                    $unit_administrasi->status_final = $status_final;
            };
            $unit_administrasi->save();
        }
        echo "Sinkronisasi Selesai";
    }
    
    public function generate_rekap_instansi_skor(){
        $instansis = KlpdInstansi::get();
        foreach($instansis as $instansi){
            $instansi_id = $instansi->id;
            $group_kld = $instansi->group; 
            $lkeTestTP = $instansi->lke_test_tp;
            $skor_opini_bpk =0;
            $skor_predikat_sakip = 0 ;
            $skor_maturitas_spip =0;
            $opini_bpk =0;
            $predikat_sakip = "" ;
            $indeks_rb = "";
            $maturitas_spip ="";
            $syarat_maturitas_spip = "";
            $syarat_akhir_wbk = "";
            $syarat_akhir_wbbm = "";
            $status_akhir = 0; #0 untuk tidak lolos, 1 untuk wbk saja, 2 untuk wbk dan wbbm, 3 untuk afirmasi
            $keterangan ="";

            if (isset($lkeTestTP)) {
                $skor_indeks_rb = $lkeTestTP->index_rb_penyesuaian;
                $lkeTestTPLine = LkeTestTpLine::where("test_tp_id", $lkeTestTP->id)->get();
                foreach($lkeTestTPLine as$tpLine){
                    if($tpLine->paramL4->name == "Opini BPK"){
                        ($tpLine->score)?$skor_opini_bpk = $tpLine->score_index: $skor_opini_bpk = 0 ; #khusus opini BPK nilai yang diambil adalah skor opini bpk
                    }elseif($tpLine->paramL4->name == "Nilai Sistem Akuntabilitas Kinerja Instansi Pemerintah (SAKIP)" || 
                            $tpLine->paramL4->name == "Nilai Sistem Akuntabilitas Kinerja Instansi Pemerintah" || 
                            $tpLine->paramL4->name == "Nilai Sitem Akuntabilitas Kinerja Instansi Pemerintah (SAKIP)"){ // di database paramerter l4 nya ada dua
                        ($tpLine->score)?$skor_predikat_sakip = $tpLine->score:$skor_predikat_sakip =0;
                    }elseif($tpLine->paramL4->name == "Tingkat Maturitas Sistem Pengendalian Intern Pemerintah (SPIP)" || $tpLine->paramL4->name == "Tingkat Maturitas Sistem Pengendalian Intern Pemerintah"){
                        ($tpLine->score)?$skor_maturitas_spip = $tpLine->score: $skor_maturitas_spip;
                    }
                }

                #konversi Skor ke Predikat
                $indeks_rb = $this->konversi_predikat_rb($skor_indeks_rb);
                $predikat_sakip = $this->konversi_predikat_sakip($skor_predikat_sakip);
                $opini_bpk = $this->konversi_predikat_bpk($skor_opini_bpk);
                $maturitas_spip = "Level ". (int)$skor_maturitas_spip;

                #Syarat Instansi 
                ($opini_bpk == "WTP")?$syarat_bpk = "LULUS":$syarat_bpk= "GAGAL";#syarat BPK WTP
                ($skor_predikat_sakip > 60)?$syarat_sakip_wbk = "LULUS":$syarat_sakip_wbk = "GAGAL";#Syarat SAKIP predikat wbk B keatas 
                ($skor_predikat_sakip > 70)?$syarat_sakip_wbbm = "LULUS":$syarat_sakip_wbbm = "GAGAL";#Syarat SAKIT predikat wbbm BB keatas
                if( $group_kld == "prov" || $group_kld == "kab" ){ #Jika Pemda
                    ($skor_indeks_rb) > 50?$syarat_indeksrb_wbk = "LULUS": $syarat_indeksrb_wbk = "GAGAL";#wbk CC keatas, 
                    ($skor_indeks_rb > 60)?$syarat_indeksrb_wbbm = "LULUS": $syarat_indeksrb_wbbm = "GAGAL";#wbbm B keatas
                }elseif($group_kld  == "kl"){#Jika KL
                    ($skor_indeks_rb > 60)?$syarat_indeksrb_wbk = "LULUS":$syarat_indeksrb_wbk="GAGAL";#wbk index B keatas
                    ($skor_indeks_rb > 70)?$syarat_indeksrb_wbbm = "LULUS":$syarat_indeksrb_wbbm="GAGAL";#wbbm index BB
                }else{
                    continue;
                }
                
                ($skor_maturitas_spip >= 3)?$syarat_maturitas_spip = "LULUS":$syarat_maturitas_spip="GAGAL" ; #predikat B keatas
                
                #Perhitungan Akhir
                if($syarat_bpk == "LULUS" && $syarat_maturitas_spip == "LULUS"){
                    #WBK
                    if($syarat_indeksrb_wbk == "LULUS" && $syarat_sakip_wbk == "LULUS" ){
                        $syarat_akhir_wbk = "LULUS";
                    }else{
                        $syarat_akhir_wbk = "GAGAL";
                    }
                    #WBBM
                    if($syarat_indeksrb_wbbm == "LULUS" && $syarat_sakip_wbbm == "LULUS" ){
                        $syarat_akhir_wbbm = "LULUS";
                    }else{
                        $syarat_akhir_wbbm = "GAGAL";
                    }
                    
                    if($syarat_akhir_wbk == "LULUS" || $syarat_akhir_wbbm == "LULUS" )
                    {
                        if($syarat_akhir_wbk == "LULUS"){
                            $status_akhir = 1 ;
                            $keterangan = "Selamat anda dapat mengusulkan unit penerima WBK";
                            if($syarat_akhir_wbbm == "LULUS"){
                                $status_akhir = 2;
                                $keterangan = $keterangan . " dan WBBM";
                            }else{
                                $keterangan = $keterangan . " namun belum bisa mengajukan unit penerima WBBM";       
                            }
                        }else{
                            $keterangan = "Mohon Maaf Anda belum memenuhi persyaratan untuk mengusulkan unit penerima WBK maupun WBBM";
                        }
                    }elseif(in_array($instansi->id , [628, 412, 551, 554, 555, 437, 607, 539, 541])){#Jika termasuk instansi wbk mandiri 
                        $keterangan = "Anda tidak dapat mengajukan WBBM tapi anda dapat mengajukan WBK mandiri";
                        $status_akhir = 4;
                    }else{
                        $keterangan = "Mohon Maaf Anda belum memenuhi persyaratan untuk mengusulkan unit penerima WBK maupun WBBM. ";
                        #cek kalau pemda afirmasi
                        if($group_kld == "prov" || $group_kld == "kab"){
                            $keterangan = $keterangan . ". Namun anda masih bisa mengajukan Unit Afirmasi untuk nominasi penerima WBK";
                            $status_akhir = 3;
                        }
                    }
                }else{
                    if(in_array($instansi->id , [628, 412, 551, 554, 555, 437, 607, 539, 541])){#Jika termasuk instansi wbk mandiri 
                        $keterangan = "Anda tidak dapat mengajukan WBBM tapi anda dapat mengajukan WBK mandiri";
                        $syarat_akhir_wbk = "GAGAL";
                        $syarat_akhir_wbbm = "GAGAL";
                        $status_akhir = 4;
                    }else{
                        $keterangan = "Anda tidak dapat mengajukan ZI karena tidak memenuhi persyaratan ";
                        $syarat_akhir_wbk = "GAGAL";
                        $syarat_akhir_wbbm = "GAGAL";
                        if($syarat_bpk != "LULUS"){
                            $keterangan = $keterangan . " opini BPK " ;
                            if($syarat_maturitas_spip != "LULUS" ){
                                $keterangan = $keterangan . "dan maturitas SPIP" ;
                            }
                        }elseif($syarat_maturitas_spip != "LULUS" ){
                            $keterangan = $keterangan . " maturitas SPIP" ;
                        }
                    }
                    #cek kalau pemda afirmasi
                    if($group_kld == "prov" || $group_kld == "kab"){
                        $keterangan = $keterangan . "Anda hanya bisa mengajukan Unit Afirmasi untuk nominasi penerima WBK";
                        $status_akhir = 3;
                    }
                }
                $instansiZI = InstansiZI::firstOrCreate(array('instansi_id'=> $instansi_id));
                if(in_array($instansi->id , [628, 412, 551, 554, 555, 437, 607, 539, 541])){#Jika termasuk instansi wbk mandiri 
                    $instansiZI->instansi_wbk_mandiri = true; 
                }
                $instansiZI->tahun = "2024";
                $instansiZI->skor_bpk = $skor_opini_bpk;
                $instansiZI->skor_indeks_rb = $skor_indeks_rb;  
                $instansiZI->skor_sakip = $skor_predikat_sakip;
                $instansiZI->skor_maturitas_spip = $skor_maturitas_spip;
                $instansiZI->opini_bpk = $opini_bpk;
                $instansiZI->indeks_rb = $indeks_rb;
                $instansiZI->predikat_sakip = $predikat_sakip;
                $instansiZI->maturitas_spip = $maturitas_spip;
                $instansiZI->syarat_bpk = $syarat_bpk;
                $instansiZI->syarat_sakip_wbk = $syarat_sakip_wbk;
                $instansiZI->syarat_sakip_wbbm = $syarat_sakip_wbbm;
                $instansiZI->syarat_indeksrb_wbk = $syarat_indeksrb_wbk;
                $instansiZI->syarat_indeksrb_wbbm= $syarat_indeksrb_wbbm;
                $instansiZI->syarat_maturitas_spip = $syarat_maturitas_spip;
                $instansiZI->syarat_akhir_wbk = $syarat_akhir_wbk;
                $instansiZI->syarat_akhir_wbbm = $syarat_akhir_wbbm;
                $instansiZI->keterangan = $keterangan;
                $instansiZI->status_akhir = $status_akhir;
                $instansiZI->save();
            }else{#belum ada penilaian RB tahun 2023
                    $instansiZI = InstansiZI::firstOrCreate(array('instansi_id'=> $instansi_id));
                    $instansiZI->tahun = "2024";
                    $instansiZI->skor_bpk = 0;
                    $instansiZI->skor_indeks_rb = 0;  
                    $instansiZI->skor_sakip = 0;
                    $instansiZI->skor_maturitas_spip = 0;
                    $instansiZI->opini_bpk = "-";
                    $instansiZI->indeks_rb = "-";
                    $instansiZI->predikat_sakip = "-";
                    $instansiZI->maturitas_spip = "-";
                    $instansiZI->syarat_bpk = "GAGAL";
                    $instansiZI->syarat_sakip_wbk = "GAGAL";
                    $instansiZI->syarat_sakip_wbbm = "GAGAL";
                    $instansiZI->syarat_indeksrb_wbk = "GAGAL";
                    $instansiZI->syarat_indeksrb_wbbm= "GAGAL";
                    $instansiZI->syarat_maturitas_spip = "GAGAL";
                    $instansiZI->syarat_akhir_wbk = "GAGAL";
                    $instansiZI->syarat_akhir_wbbm = "GAGAL";
                if($group_kld == "prov" || $group_kld == "kab"){
                    $instansiZI->keterangan = "Mohon Maaf anda hanya bisa mendaftarkan unit Afirmasi saja";
                    $instansiZI->status_akhir = 3;
                    $instansiZI->save();
                }else{
                    $instansiZI->keterangan = "Mohon Maaf anda belum melakukan evaluasi RB tahun 2023 sehingga belum bisa mengusulkan unit penerima WBK maupun WBBM";
                    $instansiZI->status_akhir = 0;
                    $instansiZI->save();
                }
            }
        }
        echo "berhasil";
    }

    public function konversi_predikat_rb($skor_indeks_rb){
        if($skor_indeks_rb > 100){
            $indeks_rb = "AA";
        }elseif($skor_indeks_rb > 80){
            $indeks_rb = "A";
        }elseif($skor_indeks_rb > 70){
            $indeks_rb = "BB";
        }elseif($skor_indeks_rb > 60){
            $indeks_rb = "B";
        }elseif($skor_indeks_rb > 50){
            $indeks_rb = "CC";
        }elseif($skor_indeks_rb > 30){
            $indeks_rb = "C";
        }elseif($skor_indeks_rb > 0){
            $indeks_rb = "D";
        }else{
            $indeks_rb = "-";
        }
        return $indeks_rb;
    }

    public function konversi_predikat_sakip($skor_sakip){
        if($skor_sakip > 90){
            $predikat_sakip = "AA";
        }elseif($skor_sakip >= 80){
            $predikat_sakip = "A";
        }elseif($skor_sakip >= 70){
            $predikat_sakip = "BB";
        }elseif($skor_sakip >= 60){
            $predikat_sakip = "B";
        }elseif($skor_sakip >= 50){
            $predikat_sakip = "CC";
        }elseif($skor_sakip >= 30){
            $predikat_sakip = "C";
        }elseif($skor_sakip >= 0){
            $predikat_sakip = "D";
        }else{
            $predikat_sakip = "-";
        }
        return $predikat_sakip;
    }

    public function konversi_predikat_bpk($skor_opini_bpk){
        if($skor_opini_bpk > 3.33){
            $opini_bpk = "WTP"; #Wajar Tanpa Pengecualian
        }elseif($skor_opini_bpk > 1.67){
            $opini_bpk = "WDP"; #Wajar Dengan  Pengecualian
        }elseif($skor_opini_bpk > 0 ){
            $opini_bpk = "TW";
        }else{
            $opini_bpk = "-";
        }
        return $opini_bpk;
    }
}
