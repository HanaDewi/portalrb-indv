<?php

namespace App\Http\Controllers;
use App\Models\KlpdInstansi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WebDashboardController extends Controller
{

    public function rbGeneral(Request $request)
    {
        $user = Auth::User();
        if (in_array($user->level, ['admin', 'tpn', 'tpm'])) {
            $instansis = KlpdInstansi::all();
            return view('webdashboard.rb-general', compact('instansis'));
        }
    }

    public function rbTematik(Request $request)
    {
        $user = Auth::User();
        if (in_array($user->level, ['admin', 'tpn', 'tpm'])) {
            $instansis = KlpdInstansi::all();
            return view('webdashboard.rb-tematik', compact('instansis'));
        }
    }

    public function hasilEvaluasi()
    {
        $user = Auth::User();
        if (in_array($user->level, ['admin', 'tpn', 'tpm'])) {
            $instansis = KlpdInstansi::all();
            return view('webdashboard.hasil-evaluasi', compact('instansis'));
        }
    }
    
}