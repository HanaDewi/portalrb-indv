<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\KlpdUserRel;
use App\Models\KlpdInstansi;
use Illuminate\Http\Request;
use App\Models\TimEvaluasiRB;
use Illuminate\Support\Facades\DB;
use App\Models\InstansiTimEvaluasi;
use App\Models\AnggotaTimEvaluasiRB;

class ManageTimController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            foreach (allowed_url() as $allowed) {
                if ($request->is($allowed)) {
                    return $next($request);
                }
            }
            abort('403');
        });
    }

    public function kelola_tim(Request $request)
    {   
        $title = "Kelola Tim";
        return view('kelola_tim.kelola_tim', compact(
             "title"
            ) 
        );
    }

    public function kelola_tim_simpan(Request $request)
    {   
        $success = false;
        $TimEvaluasiRB = new TimEvaluasiRB();
        if ($request->tim_id) {
            $TimEvaluasiRB = TimEvaluasiRB::find($request->tim_id);
        }
        $TimEvaluasiRB->nama = $request->nama;
        $TimEvaluasiRB->keterangan = $request->keterangan;
        if ($TimEvaluasiRB->save()) {
            $success = true;
        };
        return response()->json(['success' => $success]);
    }

    public function tim_evaluasi_getDatas()
    {
        $datas = TimEvaluasiRB::latest()->get();
        return response()->json(['data' => $datas]);
    }

    public function tim_getData($id)
    {
        $data = TimEvaluasiRB::find($id);
        return $data;
    }

    public function kelola_tim_hapus(Request $request)
    {
        $pesan = '';
        $success = true;
        $TimEvaluasiRB = TimEvaluasiRB::find($request->id);
        #if (count($TimEvaluasiRB->indikators) > 0) {
        #    $pesan = 'Tim Evaluasi tidak bisa dihapus, silahkan hapus dulu Anggota yang terhubung dengan Tim Evaluasi ini!';
        #    $success = false;
        #} else {
            if ($TimEvaluasiRB->delete()) {
                $success = true;
            } else {
                $success = false;
            }
        #}
        return response()->json(['success' => $success, 'pesan' => $pesan]);
    }

    public function kelola_anggota_tim(Request $request)
    {   
        $title = "Kelola Anggota Tim";
        $teams = TimEvaluasiRB::get(); 
        $userTimIds = AnggotaTimEvaluasiRB::get()->pluck('user_id');
        $evaluators = User::where('level', 'tpn')->whereNotIn('id', $userTimIds)->get();
        return view('kelola_tim.kelola_anggota_tim', compact(
             "title", "teams","evaluators"
            ) 
        );
    }

    public function anggota_tim_evaluasi_getDatas()
    {
        $anggotaTim = AnggotaTimEvaluasiRB::latest()->get();
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
        $data = AnggotaTimEvaluasiRB::find($id);
        return $data;
    }
    public function kelola_anggota_tim_simpan(Request $request)
    {   
        $success = false;
        $anggotaTimEvaluasiRB = new AnggotaTimEvaluasiRB();
        if ($request->anggota_tim_id) {
            $TimEvaluasiRB = AnggotaTimEvaluasiRB::find($request->anggota_tim_id);
        }
        $userIds= $request->get("userIds"); #ini Untuk baru
        if($userIds){ #pasti user baru
            foreach($userIds as $userId){
                $anggotaTimEvaluasiRB = new AnggotaTimEvaluasiRB();
                $anggotaTimEvaluasiRB->tim_id = $request->timId;
                $anggotaTimEvaluasiRB->user_id = $userId;
                if ($anggotaTimEvaluasiRB->save()) {
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
        $anggotaTimEvaluasiRB = AnggotaTimEvaluasiRB::find($request->id);
        #if (count($TimEvaluasiRB->indikators) > 0) {
        #    $pesan = 'Tim Evaluasi tidak bisa dihapus, silahkan hapus dulu Anggota yang terhubung dengan Tim Evaluasi ini!';
        #    $success = false;
        #} else {
            if ($anggotaTimEvaluasiRB->delete()) {
                $success = true;
            } else {
                $success = false;
            }
        #}
        return response()->json(['success' => $success, 'pesan' => $pesan]);
    }

    public function kelola_instansi_tim(Request $request)
    {   
        
        $title = "Kelola Instansi Tim";
        $teams = TimEvaluasiRB::get(); 
        $instansiIds = [];
        $instansiTims= [];
        
        $instansis = KlpdInstansi::get();
        return view('kelola_tim.kelola_instansi_tim', compact(
            "instansis", "title", "teams", "instansiTims"
            ) 
        );
    }

    public function instansi_tim_evaluasi_getDatas()
    {
        $instansisTim = InstansiTimEvaluasi::latest()->get();
        $datas = [];
        foreach($instansisTim as $instansi){
            $output = array(
                "id" => $instansi->id,
                "instansi" => $instansi->instansi->name,
                "tim" => $instansi->tim->nama
            );
            $datas[] = $output;
        };
        return response()->json(['data' => $datas]);
    }

    public function instansi_tim_evaluasi_getData($id)
    {
        $data = AnggotaTimEvaluasiRB::find($id);
        return $data;
    }
    public function kelola_instansi_tim_simpan(Request $request)
    {   
        $success = false;
        $instansiTim = new InstansiTimEvaluasi();
        if ($request->instansi_tim_id) {
            $instansiTim = InstansiTimEvaluasi::find(instansi_tim_id);
        }
        $tim_id = $request->timId;
        $instansiIds= $request->get("instansiIds"); #ini Untuk baru
        if($instansiIds){
            foreach($instansiIds as $instansi){
                $instansiTim = new InstansiTimEvaluasi();
                $instansiTim->tim_id = $request->timId;
                $instansiTim->instansi_id = $instansi;
                if ($instansiTim->save()) {
                    $success = true;
                };
            }
        }
        return response()->json(['success' => $success]);
    }

    public function kelola_instansi_tim_hapus(Request $request)
    {
        $pesan = 'Berhasil di Hapus';
        $success = true;
        $instansiTim = InstansiTimEvaluasi::find($request->id);
        
            if ($instansiTim->delete()) {
                $success = true;
            } else {
                $success = false;
            }
        #}
        return response()->json(['success' => $success, 'pesan' => $pesan]);
    }

}
