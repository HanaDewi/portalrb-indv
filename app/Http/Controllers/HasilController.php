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
        if (in_array($user->level, ['admin', 'tpn', 'tpm'])) {
            $instansis = KlpdInstansi::all();
            return view('hasil.hasil_semua', compact('instansis'));
        } else {
            return redirect('hasil/'.$user->user_rel->instansi_id);
        }
    }

    public function hasil($instansi_id)
    {
        $user = Auth::User();
        if (!in_array($user->level, ['admin', 'tpn', 'tpm'])) {
            if ($user->user_rel->instansi_id != $instansi_id) {
                abort(403);
            }
        }
        $instansi = KlpdInstansi::find($instansi_id);
        $lkeTestTP = LkeTestTp::where("lke_instansi_id", $instansi_id)->first();
        $lkeTestTPLine = "";
        if (isset($lkeTestTP)) {
            $lkeTestTPLine = LkeTestTpLine::where("test_tp_id", $lkeTestTP->id)->get();
            if ($user->penilai_id && $user->level == 'tpm') {
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

    public function get_test_tp($id)
    {
        $tp = LkeTestTp::find($id);
        return response()->json($tp);
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
                $success = $this->hitung_score_index($tp_line->test_tp_id);
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

    public function simpan_bobot_rb_general_penyesuaian(Request $request)
    {
        DB::beginTransaction();
        $success = false;
        try {
            $tp = LkeTestTp::find($request->test_tp_id);
            $tp->bobot_rb_general_penyesuaian = $request->bobot_rb_general_penyesuaian;
            if ($tp->save()) {
                $success = $this->hitung_score_index($tp->id);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
        if ($success) {
            DB::commit();
            session()->flash('success', 'Data Bobot RB General Penyesuaian berhasil disimpan.');
        } else {
            DB::rollBack();
            session()->flash('error', 'Data Bobot RB General Penyesuaian gagal disimpan! Silahkan dicoba kembali.');
        }
        return redirect('hasil/'.$tp->lke_instansi_id);
    }

    protected function hitung_score_index($test_tp_id)
    {
        $tp = LkeTestTp::find($test_tp_id);
        $rb_general = LkeTestTpLine::where('test_tp_id', $test_tp_id)->whereIn('lke_param_l0', [2126, 2217, 2353])->sum('score_index');
        $rb_tematik = LkeTestTpLine::where('test_tp_id', $test_tp_id)->whereIn('lke_param_l0', [2124, 2213, 2352])->sum('score_index');
        $index_rb = LkeTestTpLine::where('test_tp_id', $test_tp_id)->sum('score_index');
        $rb_general_penyesuaian = $rb_general/$tp->bobot_rb_general_penyesuaian * 100;
        $tp->rb_general = $rb_general;
        $tp->rb_tematik = $rb_tematik;
        $tp->index_rb = $index_rb;
        $tp->rb_general_penyesuaian = $rb_general_penyesuaian;
        $tp->index_rb_penyesuaian = $rb_tematik + $rb_general_penyesuaian;
        if ($tp->save()) {
            return true;
        } else {
            return false;
        }
    }
}
