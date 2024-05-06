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
        $datas = User::latest()->get();
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
