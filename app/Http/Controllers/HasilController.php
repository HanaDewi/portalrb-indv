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
        $user = Auth::User();
        if (in_array($user->level, ['admin', 'tpn'])) {
            $instansis = KlpdInstansi::all();
        } else {
            $instansi_ids = $user->user_rel->pluck('instansi_id');
            $instansis = KlpdInstansi::whereIn('id', $instansi_ids)->get();
        }
        if (count($instansis) > 1) {
            return view('hasil.hasil_semua', compact('instansis'));
        } else if (count($instansis) == 1) {
            return redirect('hasil/'.$instansis[0]->id);
        } else {
            return redirect('dashboard');
        }
    }

    public function hasil($instansi_id)
    {
        $user = Auth::User();
        if (in_array($user->level, ['admin', 'tpn'])) {
            $instansi_ids = KlpdInstansi::pluck('id')->toArray();
        } else {
            $instansi_ids = $user->user_rel->pluck('instansi_id')->toArray();
        }
        if (!in_array($instansi_id, $instansi_ids)) {
            abort('403');
        }
        $instansi = KlpdInstansi::find($instansi_id);
        $lkeTestTP = LkeTestTp::where("lke_instansi_id", $instansi_id)->first();
        $lkeTestTPLine = "";
        if (isset($lkeTestTP)) {
            $lkeTestTPLine = LkeTestTpLine::where("test_tp_id", $lkeTestTP->id)->get();
            if ($user->penilai_id) {
                $lkeTestTPLine = LkeTestTpLine::where("test_tp_id", $lkeTestTP->id)->where('penilai_id', $user->penilai_id)->get();
            }
        }
        return view('hasil.hasil', compact('instansi', 'lkeTestTPLine'));
    }

    public function get_test_tp_line($id)
    {
        $tp_line = LkeTestTpLine::find($id);
        return response()->json($tp_line);
    }

    public function simpan_test_tp_line(Request $request)
    {
        DB::beginTransaction();
        $success = false;
        try {
            $tp_line = LkeTestTpLine::find($request->test_tp_line_id);
            $tp_line->score_index = $request->score_index;
            $tp_line->note = $request->note;
            $tp_line->todo = $request->todo;
            if ($tp_line->save()) {
                $total_score = LkeTestTpLine::where('test_tp_id', $tp_line->test_tp_id)->sum('score_index');
                $tp = LkeTestTp::find($tp_line->test_tp_id);
                $tp->index_rb = $total_score;
                if ($tp->save()) {
                    $success = true;
                }
            }
        } catch (\Throwable $th) {
            throw $th;
        }
        if ($success) {
            DB::commit();
            session()->flash('success', 'Data Hasil berhasil disimpan.');
        } else {
            DB::rollBack();
            session()->flash('error', 'Data Hasil gagal disimpan! Silahkan dicoba kembali.');
        }
        return redirect('hasil/'.$tp_line->lke_test_tp->lke_instansi_id);
    }
}
