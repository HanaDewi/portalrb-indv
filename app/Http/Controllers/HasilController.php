<?php

namespace App\Http\Controllers;

use App\Models\LkeTestTp;
use App\Models\KlpdInstansi;
use Illuminate\Http\Request;
use App\Models\LkeTestTpLine;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\LkeTestTpFile;
use App\Models\OpenAccessSetting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Activity;

class HasilController extends Controller
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

    public function hasil_seluruh()
    {
        $user = Auth::User();
        if (in_array($user->level, ['kabupaten', 'provinsi', 'kl', 'tpn', 'tpm'])) {
            $access = OpenAccessSetting::where('user_level', $user->level)->where('fitur', 'hasil_evaluasi')->first();
            if ($access) {
                $today = date('Y-m-d');
                if ($access->waktu_awal > $today || $access->waktu_akhir < $today) {
                    return view('belumbuka');
                }
            }
        }
        if (in_array($user->level, ['admin', 'tpn', 'tpm'])) {
            $instansis = KlpdInstansi::all();
            return view('hasil.hasil_semua', compact('instansis'));
        } else {
            return redirect('evaluasi/hasil-2023/'.$user->user_rel->instansi_id);
        }
    }

    public function hasil($instansi_id)
    {
        $user = Auth::User();
        if (in_array($user->level, ['kabupaten', 'provinsi', 'kl', 'tpn', 'tpm'])) {
            $access = OpenAccessSetting::where('user_level', $user->level)->where('fitur', 'hasil_evaluasi')->first();
            if ($access) {
                $today = date('Y-m-d');
                if ($access->waktu_awal > $today || $access->waktu_akhir < $today) {
                    return view('belumbuka');
                }
            }
        }
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
        $tp_line->min = $tp_line->pertanyaan->min_value;
        $tp_line->max = $tp_line->pertanyaan->max_value;
        return response()->json($tp_line);
    }

    public function get_test_tp($id)
    {
        $tp = LkeTestTp::find($id);
        $tp->berkas_list = '';
        foreach ($tp->files as $berkas) {
            $ext = pathinfo($berkas->file, PATHINFO_EXTENSION);
            $src = exts(strtolower($ext));
            $tp->berkas_list .= '<div class="col-span-12 lg:col-span-4" id="berkasdiv'.$berkas->id.'" style="position:relative;">
                <div style="height: 100px;">
                    <img class="img-fluid card-img-top" src="'.asset('images').'/'.$src.'" alt="Berkas'.$berkas->id.'" style="max-height: 100px; max-width:100%; padding: 5px 0;">
                </div>
                <div class="form-group mb-0">
                    <input type="text" name="deskripsi_existing['.$berkas->id.']" class="form-control" id="deskripsi'.$berkas->id.'" placeholder="Deskripsi" value="'.$berkas->deskripsi.'">
                    <input type="hidden" name="berkas_existing['.$berkas->id.']" value="'.$berkas->file.'">
                </div>
                <a href="javascript:void(0);" onclick="removeBerkas('.$berkas->id.')" class="remove-button text-danger">
                    <div class="tooltip w-5 h-5 flex items-center justify-center absolute rounded-full text-white bg-danger right-0 top-0 -mr-2 -mt-2"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x" data-lucide="x" class="lucide lucide-x w-4 h-4"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg> </div>
                </a>
            </div>';
        }
        return response()->json($tp);
    }

    public function simpan_test_tp_line(Request $request)
    {
        DB::beginTransaction();
        $success = false;
        try {
            $tp_line = LkeTestTpLine::find($request->test_tp_line_id);
            if ($request->score) {
                $tp_line->score = $request->score;
            }
            $tp_line->note = $request->note;
            $tp_line->todo = $request->todo;
            $score_index = ($tp_line->score/$tp_line->pertanyaan->max_value) * $tp_line->pertanyaan->weight;
            if ($tp_line->pertanyaan->indikator_pengali_id) {
                $tp_line_pengali = LkeTestTpLine::where('test_tp_id', $tp_line->test_tp_id)->where('test_line', $tp_line->pertanyaan->indikator_pengali_id)->first();
                $score_index = $score_index * ($tp_line_pengali->score_index/$tp_line_pengali->pertanyaan->weight);
            }
            $tp_line->score_index = round($score_index, 2);
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

    public function simpan_test_tp(Request $request)
    {
        DB::beginTransaction();
        $success = false;
        try {
            $tp = LkeTestTp::find($request->test_tp_id);
            $tp->bobot_rb_general_penyesuaian = $request->bobot_rb_general_penyesuaian;
            if ($tp->save()) {
                $success = $this->hitung_score_index($tp->id);
                foreach ($tp->files as $berkas) {
                    if (!isset($request->berkas_existing[$berkas->id])) {
                        Storage::disk('public')->delete('berkas/' . $berkas->file);
                        $berkas->delete();
                    } else {
                        $berkas->deskripsi = $request->deskripsi_existing[$berkas->id];
                        $berkas->save();
                    }
                }
                if ($request->hasFile('berkas')) {
                    foreach ($request->file('berkas') as $key => $file_berkas) {
                        $berkas = new LkeTestTpFile();
                        $berkas->test_tp_id = $tp->id;
                        $berkas->deskripsi = $request->deskripsi[$key];
                        $time = time();
                        $filename = $berkas->deskripsi."_$time." . $file_berkas->getClientOriginalExtension();
                        $file_berkas->storeAs('berkas', $filename, 'public');
                        $berkas->file = $filename;
                        if ($berkas->save()) {
                            $success = true;
                        } else {
                            $success = false;
                        }
                    }
                }
            }
        } catch (\Throwable $th) {
            throw $th;
        }
        if ($success) {
            DB::commit();
            session()->flash('success', 'Data Test TP berhasil disimpan.');
        } else {
            DB::rollBack();
            session()->flash('error', 'Data Test TP gagal disimpan! Silahkan dicoba kembali.');
        }
        return redirect('hasil/'.$tp->lke_instansi_id);
    }

    protected function hitung_score_index($test_tp_id)
    {
        $tp = LkeTestTp::find($test_tp_id);
        $rb_general = LkeTestTpLine::where('test_tp_id', $test_tp_id)->whereIn('lke_param_l0', [2124, 2213, 2352])->sum('score_index');
        $rb_tematik = LkeTestTpLine::where('test_tp_id', $test_tp_id)->whereIn('lke_param_l0', [2126, 2217, 2353])->sum('score_index');
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

    public function access()
    {
        $access = OpenAccessSetting::all();
        return view('access', compact('access'));
    }

    public function access_simpan(Request $request)
    {
        $access = OpenAccessSetting::where('user_level', $request->user_level)->where('fitur', $request->fitur)->first();
        if (!$access) {
            $access = new OpenAccessSetting();
        }
        $access->user_level = $request->user_level;
        $access->fitur = $request->fitur;
        $access->waktu_awal = $request->waktu_awal;
        $access->waktu_akhir = $request->waktu_akhir;
        if ($access->save()) {
            session()->flash('success', 'Data Akses berhasil diperbaharui.');
        } else {
            session()->flash('error', 'Data Akses gagal diperbaharui.');
        }
        return redirect('access');
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
            if ($activity->subject_type == 'App\Models\LkeTestTp') {
                $activity->instansi = $activity->subject->klpd_instansi->name;
            } else if ($activity->subject_type == 'App\Models\LkeTestTpFile') {
                $activitynya = json_decode($activity->properties);
                if ($activity->event == 'deleted') {
                    $test_tp_id = $activitynya->old->test_tp_id;
                } else {
                    $test_tp_id = $activitynya->attributes->test_tp_id;
                }
                $test_tp = LkeTestTp::find($test_tp_id);
                $activity->instansi = $test_tp->klpd_instansi->name;
            } else {
                $activity->instansi = $activity->subject->lke_test_tp->klpd_instansi->name;
            }
        }
        return response()->json(['data' => $activities]);
    }
}
