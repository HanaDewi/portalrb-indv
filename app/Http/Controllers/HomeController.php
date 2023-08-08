<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    public function profil()
    {
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    public function profil_simpan(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $message = '';
        $success = true;
        $user->email = $request->email;
        if (!empty($request->password)) {
            $cek = Hash::check($request->password_lama, $user->password);
            if (!$cek) {
                $message .= 'password gagal diperbaharui, password lamanya ga sesuai!';
                $success = false;
            }
            $user->password = bcrypt($request->password);
        }
        if ($success) {
            $user->save();
            session()->flash('success', 'Data Profil berhasil diperbaharui.'.$message);
        } else {
            session()->flash('error', 'Data Profil gagal diperbaharui. '.$message);
        }
        return redirect('profil');
    }
}
