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

    public function bcrypt()
    {
        return bcrypt("CjXtC8#").'<br>'.
        bcrypt("VhLwI0.").'<br>'.
        bcrypt("UsPlJ0'").'<br>'.
        bcrypt("HdGdE6&").'<br>'.
        bcrypt("FoAcI4%").'<br>'.
        bcrypt("IbLjB1.").'<br>'.
        bcrypt("OfXxJ8*").'<br>'.
        bcrypt("EgTxG6'").'<br>'.
        bcrypt("ZmJxE9/").'<br>'.
        bcrypt("CaUrC1(").'<br>'.
        bcrypt("RyBaD6-").'<br>'.
        bcrypt("CdYyK8&").'<br>'.
        bcrypt("TkVjL2*").'<br>'.
        bcrypt("GjLmR8$").'<br>'.
        bcrypt("QoKmA3.").'<br>'.
        bcrypt("TdEsQ0'").'<br>'.
        bcrypt("TuXcW3#").'<br>'.
        bcrypt("NgPnN8#").'<br>'.
        bcrypt("KgRkN8(").'<br>'.
        bcrypt("LnFgK3*").'<br>'.
        bcrypt("LzImT0/").'<br>'.
        bcrypt("FfHcQ5.").'<br>'.
        bcrypt("DkNpS8.").'<br>'.
        bcrypt("VaUrN6$").'<br>'.
        bcrypt("BsZwC1(").'<br>'.
        bcrypt("SxHgK8*").'<br>'.
        bcrypt("OfBrH7,").'<br>'.
        bcrypt("LsZbD1)").'<br>'.
        bcrypt("DtBpZ6+").'<br>'.
        bcrypt("KbVqK4%").'<br>'.
        bcrypt("ObNvX1%").'<br>'.
        bcrypt("JdHpB4(").'<br>'.
        bcrypt("SwZuR4&").'<br>'.
        bcrypt('GwTqO4"').'<br>'.
        bcrypt("IoTnM1,").'<br>'.
        bcrypt("LoIqE4(").'<br>'.
        bcrypt("XlQcB4$").'<br>'.
        bcrypt("QcCqS7&").'<br>'.
        bcrypt("QqZsJ6&").'<br>'.
        bcrypt("WqMhC9$").'<br>'.
        bcrypt("ZqIaV4'").'<br>'.
        bcrypt("QuYsR7&").'<br>'.
        bcrypt("ImEgG4,").'<br>'.
        bcrypt("WzDjJ7%").'<br>'.
        bcrypt('YxPvV9"').'<br>'.
        bcrypt("XiEfD2+").'<br>'.
        bcrypt("UnXqB6-").'<br>'.
        bcrypt("OnJlB7'").'<br>'.
        bcrypt("DaZjI6#").'<br>'.
        bcrypt("TsPkW4(").'<br>'.
        bcrypt("LjYeO3.").'<br>'.
        bcrypt("MpVuS0+").'<br>'.
        bcrypt("BwZcT1*").'<br>'.
        bcrypt("BgZiM4/").'<br>'.
        bcrypt("XeYyF6(").'<br>'.
        bcrypt("WuAeO1*").'<br>'.
        bcrypt("TlXkK0!").'<br>'.
        bcrypt("ZlJdH5/").'<br>'.
        bcrypt("HaNyQ7*").'<br>'.
        bcrypt("BpGgX7.").'<br>'.
        bcrypt("KzEbM5/").'<br>'.
        bcrypt("BuDiO7,").'<br>'.
        bcrypt("KjPuB3#").'<br>'.
        bcrypt('PpCaP9"').'<br>'.
        bcrypt("WkKwJ5.").'<br>'.
        bcrypt("BqYqF1%").'<br>'.
        bcrypt("JnXsK8*").'<br>'.
        bcrypt("GkFbD9-").'<br>'.
        bcrypt('IaTsF5"').'<br>'.
        bcrypt("MjRyG5,").'<br>'.
        bcrypt("TrBgW9/").'<br>'.
        bcrypt("QaUxA3*").'<br>'.
        bcrypt("BcIbE4%").'<br>'.
        bcrypt("CmZiZ4/").'<br>'.
        bcrypt("XwUuI7$").'<br>'.
        bcrypt("HhZwA9(").'<br>'.
        bcrypt("UtMqS7*").'<br>'.
        bcrypt("NpOsI6'").'<br>'.
        bcrypt("LoZkD2'").'<br>'.
        bcrypt("LpTxB8.").'<br>'.
        bcrypt("DeXxV3#").'<br>'.
        bcrypt("EtOmS8$").'<br>'.
        bcrypt("DsZnK8-").'<br>'.
        bcrypt("UjUzF8%").'<br>'.
        bcrypt("NwXzH6.").'<br>'.
        bcrypt("HsVaR4*").'<br>'.
        bcrypt("OoOxX1$").'<br>'.
        bcrypt("BpPcA1-").'<br>';
    }
}
