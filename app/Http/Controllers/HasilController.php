<?php

namespace App\Http\Controllers;

use App\Models\Instansi;
use App\Models\Indikator;
use App\Models\LkeTestTp;
use App\Models\KlpdInstansi;
use Illuminate\Http\Request;
use App\Models\LkeTestTpLine;
use App\Models\GeneralPerencanaan;
use App\Models\GeneralRencanaAksi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\GeneralPerencanaanTarget;
use App\Models\GeneralRencanaAksiOutput;

class HasilController extends Controller
{


    public function hasil_seluruh()
    {
        $instansis = KlpdInstansi::all();
        return view('hasil.hasil_semua', compact('instansis'));
    }

    public function hasil($instansi_id)
    {
        $instansi = KlpdInstansi::find($instansi_id);
        $lkeTestTP = LkeTestTp::where("lke_instansi_id", $instansi_id)->first();
        $lkeTestTPLine = "";
        if (isset($lkeTestTP)) {
            $lkeTestTPLine = LkeTestTpLine::where("test_tp_id", $lkeTestTP->id)->get();
        }
        return view('hasil.hasil', compact('instansi', 'lkeTestTPLine'));
    }
}
