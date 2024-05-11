<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManageUserController extends Controller
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

    public function index()
    {
        return view('manage-user.index');
    }

    public function manage_user_getDatas()
    {
        $datas = User::with(['user_rel.instansi', 'penilai'])->latest()->get();
        return response()->json(['data' => $datas]);
    }

    public function manage_user_getData($id)
    {
        $data = User::find($id);
        return $data;
    }

    public function manage_user_simpan(Request $request)
    {
        $success = false;
        $data = new User();

        $uname = $request->username;
        $email = $request->email;
        $nama = $request->nama;
        $pwda = $request->pwda;
        $pwdb = $request->pwdb;
        $level = $request->level;
        $instansi = $request->instansi_id;
        $penilai = $request->penilai_id;

        echo $uname . '<br/>';
        echo $email . '<br/>';
        echo $nama . '<br/>';
        echo $pwda . '<br/>';
        echo $pwdb . '<br/>';
        echo $level . '<br/>';
        echo $instansi . '<br/>';
        echo $penilai . '<br/>';
        die;
        if ($data->save()) {
            $success = true;
        };
        return response()->json(['success' => $success]);
    }

    public function manage_user_hapus(Request $request)
    {
        $pesan = '';
        $success = true;
        $data = User::find($request->id);
        if ($data->delete()) {
            $success = true;
        } else {
            $success = false;
        }
        return response()->json(['success' => $success, 'pesan' => $pesan]);
    }
}
