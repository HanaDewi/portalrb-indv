<?php

namespace App\Http\Controllers;

use App\Models\KlpdInstansi;
use App\Models\LkeTestTp;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Activitylog\Models\Activity;

class HomeController extends Controller
{
    public function profil()
    {
        $user = Auth::user();
        return view('profil', compact('user'));
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

    public function activitylog()
    {
        return view('activitylog');
    }

    public function activitylog_getData()
    {
        $activities = Activity::latest()->get();
        foreach ($activities as $activity) {
            $activity->pretty = '<pre>'.json_encode(json_decode($activity->properties), JSON_PRETTY_PRINT).'</pre>';
            $activity->pelaku = ($activity->causer)->nama.' ('.($activity->causer)->level.')';
            $activity->pada = Carbon::parse($activity->created_at)->diffForHumans().' pada '.Carbon::parse($activity->created_at)->isoFormat('dddd, D MMMM Y HH:mm');
            $activitynya = json_decode($activity->properties);
            if ($activity->subject_type == 'App\Models\LkeTestTp') {
                $activity->instansi = $activity->subject->klpd_instansi->name;
            } else if ($activity->subject_type == 'App\Models\LkeTestTpFile') {
                if ($activity->event == 'deleted') {
                    $test_tp_id = $activitynya->old->test_tp_id;
                } else {
                    $test_tp_id = $activitynya->attributes->test_tp_id;
                }
                $test_tp = LkeTestTp::find($test_tp_id);
                $activity->instansi = $test_tp->klpd_instansi->name;
            } else if ($activity->subject_type == 'App\Models\LkeTestTpLine') {
                $activity->instansi = $activity->subject->lke_test_tp->klpd_instansi->name;
            } else {
                if (isset($activitynya->attributes->instansi_id)) {
                    $instansi = KlpdInstansi::find($activitynya->attributes->instansi_id);
                    $activity->instansi = $instansi->name;
                } else {
                    $activity->instansi = '';
                }
            }
        }
        return response()->json(['data' => $activities]);
    }
}
