<?php

namespace App\Http\Controllers\ZI;

use App\Models\User;
use App\Models\ZI\UnitZI;
use App\Models\KlpdInstansi;
use Illuminate\Http\Request;
use App\Models\ZI\InstansiZI;
use App\Models\ZI\TimEvaluasi;
use App\Models\ZI\UnitTimEvaluasi;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\ZI\AnggotaTimEvaluasi;

class KonfigurasiController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
           // Gita 10059, Wahyu 10060 Rheza  10046 Arina 10053 Canggih 10056 Afif 10048 Auffi 10052
            if(Auth::User()->level =="admin" || in_array(Auth::User()->id, [10060, 10048, 10059, 10046, 10053,
            10056, 10052])){
                    return $next($request);     
            }
            abort('403');
        });
    }

    
       

    public function update_predikat(Request $request)
    {   
        $instansi_ZIs = InstansiZI::get();
        return view('zi.update_predikat', compact("instansi_ZIs") );
    }

    public function edit_predikat($id)
    {   
        $instansi_ZI = InstansiZI::find($id);
        return view('zi.edit_predikat', compact("instansi_ZI") );
    }

    public function store_predikat(Request $request){
        $instansiZI = InstansiZI::find($request->get("pic"));
        $opini_bpk = $request->get("opini_bpk");
        $skor_predikat_sakip = $request->get("skor_sakip");
        $skor_indeks_rb = $request->get("skor_index_rb");
        $skor_maturitas_spip = $request->get("skor_maturitas_spip");
        $instansiZI->update_predikat_by = Auth::User()->id; 
        $instansiZI->save();
        return view('zi.edit_predikat', compact("instansi_ZI") );
    }


    public function kelola_tim(Request $request)
    {   
        $instansi_ZIs = InstansiZI::get();
        $title = "Kelola Tim";
        return view('zi.konfigurasi.kelola_tim', compact(
            "instansi_ZIs", "title"
            ) 
        );
    }

    public function tim_evaluasi_getDatas()
    {
        $datas = TimEvaluasi::latest()->get();
        return response()->json(['data' => $datas]);
    }

    public function tim_evaluasi_getData($id)
    {
        $data = TimEvaluasi::find($id);
        return $data;
    }
    public function kelola_tim_simpan(Request $request)
    {   
        $success = false;
        $timEvaluasi = new TimEvaluasi();
        if ($request->tim_id) {
            $timEvaluasi = TimEvaluasi::find($request->tim_id);
        }
        $timEvaluasi->nama = $request->nama;
        $timEvaluasi->keterangan = $request->keterangan;
        if ($timEvaluasi->save()) {
            $success = true;
        };
        return response()->json(['success' => $success]);
    }

    public function kelola_tim_hapus(Request $request)
    {
        $pesan = '';
        $success = true;
        $timEvaluasi = TimEvaluasi::find($request->id);
        #if (count($timEvaluasi->indikators) > 0) {
        #    $pesan = 'Tim Evaluasi tidak bisa dihapus, silahkan hapus dulu Anggota yang terhubung dengan Tim Evaluasi ini!';
        #    $success = false;
        #} else {
            if ($timEvaluasi->delete()) {
                $success = true;
            } else {
                $success = false;
            }
        #}
        return response()->json(['success' => $success, 'pesan' => $pesan]);
    }

    public function kelola_anggota_tim(Request $request)
    {   
        $instansi_ZIs = InstansiZI::get();
        $title = "Kelola Anggota Tim";
        $teams = TimEvaluasi::get(); 
        $userTimIds = AnggotaTimEvaluasi::get()->pluck('user_id');
        $evaluators = User::where('level', 'tpn')->whereNotIn('id', $userTimIds)->get();
        return view('zi.konfigurasi.kelola_anggota_tim', compact(
            "instansi_ZIs", "title", "teams","evaluators"
            ) 
        );
    }

    public function anggota_tim_evaluasi_getDatas()
    {
        $anggotaTim = AnggotaTimEvaluasi::latest()->get();
        $datas = [];
        foreach($anggotaTim as $anggota){
            $output = array(
                "id" => $anggota->id,
                "nama" => $anggota->user->nama,
                "tim" => $anggota->tim->nama
            );
            $datas[] = $output;
        };
        return response()->json(['data' => $datas]);
    }

    public function anggota_tim_evaluasi_getData($id)
    {
        $data = AnggotaTimEvaluasi::find($id);
        return $data;
    }
    public function kelola_anggota_tim_simpan(Request $request)
    {   
        $success = false;
        $anggotaTimEvaluasi = new AnggotaTimEvaluasi();
        if ($request->anggota_tim_id) {
            $timEvaluasi = AnggotaTimEvaluasi::find($request->anggota_tim_id);
        }
        $userIds= $request->get("userIds"); #ini Untuk baru
        if($userIds){ #pasti user baru
            foreach($userIds as $userId){
                $anggotaTimEvaluasi = new AnggotaTimEvaluasi();
                $anggotaTimEvaluasi->tim_id = $request->timId;
                $anggotaTimEvaluasi->user_id = $userId;
                if ($anggotaTimEvaluasi->save()) {
                    $success = true;
                };
            }
        }
        return response()->json(['success' => $success]);
    }

    public function kelola_anggota_tim_hapus(Request $request)
    {
        $pesan = '';
        $success = true;
        $anggotaTimEvaluasi = AnggotaTimEvaluasi::find($request->id);
        #if (count($timEvaluasi->indikators) > 0) {
        #    $pesan = 'Tim Evaluasi tidak bisa dihapus, silahkan hapus dulu Anggota yang terhubung dengan Tim Evaluasi ini!';
        #    $success = false;
        #} else {
            if ($anggotaTimEvaluasi->delete()) {
                $success = true;
            } else {
                $success = false;
            }
        #}
        return response()->json(['success' => $success, 'pesan' => $pesan]);
    }

    public function kelola_unit_tim(Request $request)
    {   
        
        $title = "Kelola Unit Tim";
        $teams = TimEvaluasi::get(); 
        $instansiZIIDs = InstansiZI::where('final',1)->get()->pluck('instansi_id');
        #$userTimIds = UnitTimEvaluasi::get()->pluck('unit_id');
        $unitTeams = UnitTimEvaluasi::get();
        $instansiIds = [];
        $instansiTims= [];
        foreach($teams as $tim){
            $instansiTims[$tim->id] = [];
        }
        foreach($unitTeams as $unittim){
            $value= $unittim->unit->instansiZI->klpd_instansi->id;
            if (!in_array($value, $instansiIds))
            {
                $instansiIds[] = $value; 
            }
            $nama_instansi = $unittim->unit->instansiZI->klpd_instansi->name;
            if(!in_array($nama_instansi, $instansiTims[$unittim->tim_id])){
                array_push($instansiTims[$unittim->tim_id], $nama_instansi );
            }
        }
        $instansis = KlpdInstansi::whereIn('id', $instansiZIIDs )->whereNotIn('id', $instansiIds)->get();
        return view('zi.konfigurasi.kelola_unit_tim', compact(
            "instansis", "title", "teams", "instansiTims"
            ) 
        );
    }

    public function unit_tim_evaluasi_getDatas()
    {
        $unitTim = UnitTimEvaluasi::latest()->get();
        $datas = [];
        foreach($unitTim as $unit){
            $output = array(
                "id" => $unit->id,
                "unit" => $unit->unit->nama,
                "instansi" => $unit->unit->instansiZI->klpd_instansi->name,
                "tim" => $unit->tim->nama
            );
            $datas[] = $output;
        };
        return response()->json(['data' => $datas]);
    }

    public function unit_tim_evaluasi_getData($id)
    {
        $data = AnggotaTimEvaluasi::find($id);
        return $data;
    }
    public function kelola_unit_tim_simpan(Request $request)
    {   
        $success = false;
        $unitTimEvaluasi = new UnitTimEvaluasi();
        if ($request->unit_tim_id) {
            $unitTimEvaluasi = UnitTimEvaluasi::find($request->anggota_tim_id);
        }
        
        $tim_id = $request->timId;
        $instansiIds= $request->get("instansiIds"); #ini Untuk baru
        if($instansiIds){
            foreach($instansiIds as $instansi){
                $instansiZI = InstansiZI::where("instansi_id", $instansi )->first();
                $is_instansiMandiri = $instansiZI->instansi_wbk_mandiri;
                $unitZIs = $instansiZI->unit_zi;
                foreach($unitZIs as $unitZI){
                    $unitTimEvaluasi = new UnitTimEvaluasi();
                    $unitTimEvaluasi->tim_id = $tim_id;
                    $unitTimEvaluasi->unit_id = $unitZI->id;
                    if($is_instansiMandiri){#hanya unit wbbm saja yang di assign ke tim
                        if($unitZI->wbbm){
                            if ($unitTimEvaluasi->save()) {
                                $success = true;
                            };
                        }
                    }else{
                        if ($unitTimEvaluasi->save()) {
                            $success = true;
                        };
                    }
                }
            }
        }
        return response()->json(['success' => $success]);
    }

    public function kelola_unit_tim_hapus(Request $request)
    {
        
    }


}
