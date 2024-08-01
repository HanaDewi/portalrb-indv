<?php

namespace App\Http\Controllers;


use App\Models\UnitZI;
use App\Models\LkeTestTp;
use App\Models\InstansiZI;
use App\Models\KlpdInstansi;
use Illuminate\Http\Request;
use App\Models\LkeTestTpLine;
use Illuminate\Support\Facades\Auth;

class PengusulanZIController extends Controller
{
    public function index(Request $request)
    {
        $date_now = new \DateTime();
        $date_buka_zi    = new \DateTime("2024/07/18");
        $date_tutup_zi    = new \DateTime("2024/08/1");
        
       #if ($date_now >= $date_buka_zi) {
            $instansi_id = $request->get("instansi_id");
            if($instansi_id && (Auth::User()->level =="admin" || Auth::User()->level == "tpn") ){
                $instansi_obj = KlpdInstansi::find($instansi_id);
                $instansiZI = InstansiZI::where("instansi_id", $instansi_obj->id)->first();
                if($instansiZI){
                    if($instansiZI->final ){
                        return redirect('zi-tinjau?instansi_id='.$instansi_id);
                    }
                }
            }elseif(Auth::User()->level == "tpn" || Auth::User()->level =="admin" ){
                #$instansi_obj = KlpdInstansi::find(1);
                #$instansiZI = InstansiZI::where("instansi_id", $instansi_obj->id)->first();
                return redirect()->route('dashboard_zi');
            }else{
                if ($date_now >= $date_tutup_zi) {
                    return redirect('zi-tinjau?instansi_id='.Auth::User()->user_rel->instansi->id);   
                }
                $instansi_obj = Auth::User()->user_rel->instansi;
                $instansi_id = $instansi_obj->id;
            } 
            (Auth::User()->level =="admin" || Auth::User()->level == "tpn")?$instansis = KlpdInstansi::get():$instansis = "";
            
            $instansi = $instansi_obj->name;
            $group_kld =$instansi_obj->group; 
            $instansiZI = InstansiZI::where("instansi_id", $instansi_obj->id)->first();
            if($instansiZI){
                if($instansiZI->final && !(Auth::User()->level =="admin" || Auth::User()->level == "tpn")){
                    return redirect('zi-tinjau?instansi_id='.$instansi_id);
                }
                $skor_opini_bpk = $instansiZI->skor_bpk;
                $skor_indeks_rb = $instansiZI->skor_indeks_rb;
                $skor_predikat_sakip = $instansiZI->skor_sakip;
                $skor_maturitas_spip = $instansiZI->skor_maturitas_spip;
                $opini_bpk = $instansiZI->opini_bpk;
                $indeks_rb = $instansiZI->indeks_rb;
                $predikat_sakip = $instansiZI->predikat_sakip;
                $maturitas_spip = $instansiZI->maturitas_spip;
                $syarat_bpk = $instansiZI->syarat_bpk;
                $syarat_sakip_wbk = $instansiZI->syarat_sakip_wbk;
                $syarat_sakip_wbbm = $instansiZI->syarat_sakip_wbbm;
                $syarat_indeksrb_wbk = $instansiZI->syarat_indeksrb_wbk;
                $syarat_indeksrb_wbbm = $instansiZI->syarat_indeksrb_wbbm;
                $syarat_maturitas_spip = $instansiZI->syarat_maturitas_spip;
                $syarat_akhir_wbk = $instansiZI->syarat_akhir_wbk;
                $syarat_akhir_wbbm   = $instansiZI->syarat_akhir_wbbm;
                $keterangan = $instansiZI->keterangan;
                $status_akhir = $instansiZI->status_akhir;

                return view('pengusulan', compact(
                    'instansis', 'instansi_id', 'instansi', 'group_kld',
                    'skor_opini_bpk', 'skor_indeks_rb', 'skor_predikat_sakip', 'skor_maturitas_spip',
                    'opini_bpk', 'indeks_rb', 'predikat_sakip', 'maturitas_spip',
                    'syarat_bpk','syarat_sakip_wbk', 'syarat_sakip_wbbm', 'syarat_indeksrb_wbk', 'syarat_indeksrb_wbbm', 
                    'syarat_maturitas_spip',
                    'syarat_akhir_wbk','syarat_akhir_wbbm','keterangan', 'status_akhir'
                ));
            }else{
                echo "mohon maaf instansi anda belum terdapat penilaian RB di tahun lalu";
            }
        #}else{
        #    return view('zibelumbuka');
        #}
    }

    public function dashboard(Request $request)
    {   
        if(Auth::User()->level =="admin" || Auth::User()->level == "tpn" ){
            $instansi_id = $request->get("instansi_id");
            $instansi_obj = KlpdInstansi::find($instansi_id);
        }else{
            redirect(URL::to('/'));
        } 
       
        return view('zi.dashboard');
       
    }

    public function store_bukti_dukung(Request $request){

        
        if(Auth::User()->level =="admin" || Auth::User()->level == "tpn"){
            $instansi_id = $request->get("instansi_id");
        }else{
            $instansi_obj = Auth::User()->user_rel->instansi;
            $instansi_id = $instansi_obj->id;
        }
        
        $instansiZI = InstansiZI::firstOrCreate(array('instansi_id'=> $instansi_id));
        $instansiZI->pic = $request->get("pic"); 
        $instansiZI->email = $request->get("email"); 
        $instansiZI->nomor_kontak = $request->get("nomor_kontak"); 
        $instansiZI->surat_usulan = 'http://' . preg_replace('#^.*://#', '', $request->get("surat_usulan")); #kalau gak ada http:// jadinya relatif link yang buka di halaman portalrb bukan di google drivenya
        $instansiZI->sptjm = 'http://' . preg_replace('#^.*://#', '', $request->get("sptjm")); 
        $instansiZI->tlhp = 'http://' . preg_replace('#^.*://#', '', $request->get("tlhp")); 
        $instansiZI->survei_mandiri = 'http://' . preg_replace('#^.*://#', '', $request->get("survei_mandiri")); 
        $instansiZI->jml_wbk = $request->get("jml_wbk"); 
        $instansiZI->jml_wbbm = $request->get("jml_wbbm"); 
        $instansiZI->final = 1; 
        $instansiZI->update_by = Auth::User()->id; 
        $instansiZI->save();


        $unit_wbks = $request->get("unit_wbk");
        $i=0;
        if($unit_wbks){
            foreach($unit_wbks as $key=>$unit_wbk){
                $unit_zi = new UnitZI;
                $unit_zi->instansi_zi_id = $instansiZI->id;
                $unit_zi->nama = $unit_wbk;
                $unit_zi->lke = 'http://' . preg_replace('#^.*://#', '', $request->get("lke_wbk")[$key]);  
                if($request->get("afirmasi")){
                    if(array_key_exists($key,$request->get("afirmasi"))){
                        $unit_zi->afirmasi = $request->get("afirmasi")[$key];
                    }
                }
                $unit_zi->wbk = 1;
                $unit_zi->save();
                $i++;
            }
        }
        #simpan jumlah unit wbk
        $instansiZI->jml_wbk = $i;
        


        $unit_wbbms = $request->get("unit_wbbm");
        $i=0;

        if($unit_wbbms){
            foreach($unit_wbbms as $key2=>$unit_wbbm){
                $unit_zi = new UnitZI;
                $unit_zi->instansi_zi_id = $instansiZI->id;
                $unit_zi->nama = $unit_wbbm;
                $unit_zi->lke = 'http://' . preg_replace('#^.*://#', '', $request->get("lke_wbbm")[$key2]) ;
                $unit_zi->wbbm = 1;
                $unit_zi->save();
                $i++;
            }
        }

        #simpan jumlah unit wbk
        $instansiZI->jml_wbbm = $i;
        $instansiZI->save();

        if(Auth::User()->level =="admin" || Auth::User()->level == "tpn"){
            return redirect('zi-tinjau?instansi_id='.$instansi_id);
        }else{
            return redirect('zi-tinjau');
        }
    }

    public function tinjau(Request $request)
    {   
        if(Auth::User()->level =="admin" || Auth::User()->level == "tpn" ){
            $instansi_id = $request->get("instansi_id");
            $instansi_obj = KlpdInstansi::find($instansi_id);
        }else{
            $instansi_obj = Auth::User()->user_rel->instansi;
            $instansi_id = $instansi_obj->id;
        } 
        $instansi = $instansi_obj->name;
        $group_kld =$instansi_obj->group; 
        $instansiZI = InstansiZI::where("instansi_id", $instansi_obj->id)->first();
        if($instansiZI){
            $syarat_akhir_wbk = $instansiZI->syarat_akhir_wbk;
            $syarat_akhir_wbbm   = $instansiZI->syarat_akhir_wbbm;
            $status_akhir = $instansiZI->status_akhir;
            $unit_wbks = UnitZI::where("instansi_zi_id", $instansiZI->id)->where('wbk',1)->get();
            $unit_wbbms = UnitZI::where("instansi_zi_id", $instansiZI->id)->where('wbbm',1)->get();
            return view('tinjau_zi', compact(
                 'instansi_id', 'instansi', 'group_kld', 'instansiZI',
                'unit_wbks', 'unit_wbbms',
                'syarat_akhir_wbk','syarat_akhir_wbbm', 'status_akhir'
            ));
        }else{
            echo "mohon maaf instansi anda belum terdapat penilaian RB di tahun lalu";
        }
    }

    public function store_final(Request $request){
        if(Auth::User()->level =="admin" || Auth::User()->level == "tpn"){
            $instansi_id = $request->get("instansi_id");
        }
        else{
            $instansi_obj = Auth::User()->user_rel->instansi;
            $instansi_id = $instansi_obj->id;
        }
        $instansiZI = InstansiZI::where ('instansi_id', $instansi_id)->first();
        $instansiZI->final = $request->get("final");  ; 
        $instansiZI->save();



        $unit_wbks = $request->get("unit_wbk");
        $i=0;
        foreach($unit_wbks as $unit_wbk){
            $unit_zi = new UnitZI;
            $unit_zi->instansi_zi_id = $instansiZI->id;
            $unit_zi->nama = $unit_wbk;
            $unit_zi->lke = $request->get("lke_wbk")[$i];
            $unit_zi->afirmasi = $request->get("afirmasi")[$i];
            $unit_zi->wbk = 1;
            $unit_zi->save();
            $i++;
        }

        $unit_wbbms = $request->get("unit_wbbm");
        $i=0;

        foreach($unit_wbbms as $unit_wbbm){
            $unit_zi = new UnitZI;
            $unit_zi->instansi_zi_id = $instansiZI->id;
            $unit_zi->nama = $unit_wbbm;
            $unit_zi->lke = $request->get("lke_wbbm")[$i];
            $unit_zi->wbbm = 1;
            $unit_zi->save();
            $i++;
        }

        return redirect('zi-tinjau');
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
