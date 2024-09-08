<?php
namespace App\Http\Controllers;

use App\Models\KlpdInstansi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CapaianOutputController extends Controller
{

    public function rbTematikCapaianOutput(Request $request)
    {
        $user = Auth::User();
        if (in_array($user->level, ['admin', 'tpn'])) {
            $instansis = KlpdInstansi::all();
            foreach ($instansis as &$mm) {
                $mm->{'jumlah_target'} = 0;
                $mm->{'jumlah_realisasi'} = 0;
                $mm->{'prosentase_capaian'} = 0;
            }
            return view('webdashboard.rb-tematik-capaianoutput', compact('instansis'));
        }

    }

}