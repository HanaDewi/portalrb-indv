<?php

namespace App\Http\Controllers;

use App\Models\KlpdUserRel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
        $message = '';

        $id = $request->id;

        if (empty($id)) {
            $data = new User();
        } else {
            $data = User::find($id);
        }
        $uname = $request->username;
        $email = $request->email;
        $nama = $request->nama;
        $pwda = $request->pwda;
        $pwdb = $request->pwdb;
        $level = $request->level;
        $instansi = $request->instansi_id;
        $penilai = $request->penilai_id;

        $data->username = $uname;
        $data->email = $email;
        $data->nama = $nama;
        $data->level = $level;
        $data->instansi_id = $instansi;
        $data->penilai_id = $penilai;

        if (empty($id)) {
            if (!empty($pwda) && $pwda==$pwdb) {
                $data->password = Hash::make($pwda);
            } else {
                return response()->json(['success' => false, 'message' => 'Invalid password']);
            }

        } else {
            if (!empty($pwda) && $pwda==$pwdb) {
                $data->password = $pwda;
            }
        }

        try {
            if ($data->save()) {
                KlpdUserRel::where('user_id', '=', $data->id)->delete();
                $userrel = new KlpdUserRel();
                $userrel->user_id = $data->id;
                $userrel->instansi_id = $instansi;
                $userrel->save();
                $success = true;
            }
        } catch (\Exception $e){
            $message = $e->getMessage();
        }

        return response()->json(['success' => $success, 'message' => $message]);
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
