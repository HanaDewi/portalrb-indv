<?php

namespace App\Http\Controllers;

use App\Exports\ExportLkeTemplate;
use App\Imports\ImportExcel;
use App\Models\KlpdInstansi;
use App\Models\LKE\LkeBobot;
use App\Models\LKE\LkeKegiatan;
use App\Models\LKE\LkeParameter;
use App\Models\LKE\LkeTestTp;
use App\Models\LKE\LkeTestTpFile;
use App\Models\LKE\LkeTestTpLine;
use App\Models\OpenAccessSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;

class LKEController extends Controller
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

    public function lke_utama()
    {
        $user = Auth::User();
        if (in_array($user->level, ['tpn', 'tpm'])) {
            $access = OpenAccessSetting::where('user_level', $user->level)->where('fitur', 'hasil_evaluasi')->first();
            if ($access) {
                $today = date('Y-m-d');
                if ($access->waktu_awal > $today || $access->waktu_akhir < $today) {
                    return view('belumbuka');
                }
            }
        }
        return view('evaluasi.lke_utama');
    }

    public function lke_utama_getDatas(Request $request)
    {
        $user = Auth::User();
        if ($user->level == 'admin') {
            $datas = LkeParameter::where('lke_kegiatan_id', $request->kegiatan_id)->where('level', 'Indikator')->get();
        } else if (in_array($user->level, ['tpm', 'tpn'])) {
            $datas = LkeParameter::where('lke_kegiatan_id', $request->kegiatan_id)->where('rencana_aksi', 0)->where('penilai_id', $user->penilai_id)->where('level', 'Indikator')->get();
        }
        foreach ($datas as $data) {
            $data->indikator = $data->rencana_aksi ? $data->nama : '<a href="'.url('evaluasi/lke-utama/'.$data->id).'" style="color:blue;">'.$data->nama.'</a>';
            $data->subkomponen = $data->parent->nama;
            $data->komponen = $data->parent->parent->nama;
            $bobot_ids = LkeBobot::where('lke_parameter_id', $data->id)->pluck('id');
            $data->terisi = LkeTestTpLine::whereIn('lke_bobot_id', $bobot_ids)->count();
            $data->rata_rata_score = LkeTestTpLine::whereIn('lke_bobot_id', $bobot_ids)->avg('score');
        }
        return response()->json(['data' => $datas]);
    }

    public function lke_utama_score($parameter_id)
    {
        $user = Auth::User();
        if (in_array($user->level, ['tpn', 'tpm'])) {
            $access = OpenAccessSetting::where('user_level', $user->level)->where('fitur', 'hasil_evaluasi')->first();
            if ($access) {
                $today = date('Y-m-d');
                if ($access->waktu_awal > $today || $access->waktu_akhir < $today) {
                    return view('belumbuka');
                }
            }
        }
        if ($user->level == 'admin') {
            $parameter = LkeParameter::where('rencana_aksi', 0)->where('id', $parameter_id)->first();
        } else if (in_array($user->level, ['tpm', 'tpn'])) {
            $parameter = LkeParameter::where('rencana_aksi', 0)->where('id', $parameter_id)->where('penilai_id', $user->penilai_id)->first();
        }
        if (!$parameter) {
            abort('404');
        }
        return view('evaluasi.lke_utama_score', compact('parameter', 'user'));
    }

    public function lke_utama_score_getData($parameter_id, $instansi_id, $lke_bobot_id)
    {
        $bobot = LkeBobot::where('lke_parameter_id', $parameter_id)->where('id', $lke_bobot_id)->first();
        if (!$bobot) {
            abort('403');
        }
        $data = LkeTestTpLine::where('lke_bobot_id', $lke_bobot_id)->where('instansi_id', $instansi_id)->first();
        if (!$data) {
            $data = new LkeTestTpLine();
        }
        $instansi = KlpdInstansi::find($instansi_id);
        $data->nama_instansi = $instansi->name;
        $data->min = $bobot->min_value;
        $data->max = $bobot->max_value;
        return response()->json($data);
    }

    public function lke_utama_score_getDatas($parameter_id, $export = false)
    {
        $group_instansi = LkeBobot::where('lke_parameter_id', $parameter_id)->pluck('group')->toArray();
        
        $datas = DB::table('klpd_instansi as ki')
                    ->select('ki.name as nama_instansi', 'ki.id as instansi_id', 'lb.id as lke_bobot_id', 'lb.bobot', 'lb.target_baik', 'lttl.score', 'lttl.score_index', 'lttl.catatan', 'lttl.rekomendasi', 'lb.min_value', 'lb.max_value')
                    ->selectRaw("case when ki.group = 'kl' then 'Kementerian/Badan' when ki.group = 'provinsi' then 'Provinsi' when ki.group = 'kabupaten' then 'Kabupaten/Kota' end as group_instansi")
                    ->leftJoin('lke_bobot as lb', function ($join) use ($parameter_id) {
                        $join->on('lb.group', '=', 'ki.group')
                            ->where('lb.lke_parameter_id', '=', $parameter_id);
                    })
                    ->leftJoin('lke_test_tp_line as lttl', function($join) {
                        $join->on('lttl.instansi_id', '=', 'ki.id')
                            ->on('lttl.lke_bobot_id', '=', 'lb.id');
                    })
                    ->whereIn('ki.group', $group_instansi)
                    ->orderByRaw("FIELD(ki.group , 'kl', 'provinsi', 'kabupaten') ASC")
                    ->orderBy('ki.name')
                    ->get();
        
        return $export ? $datas : response()->json(['data' => $datas]);
    }

    public function lke_utama_score_simpan($parameter_id, Request $request)
    {
        $user = Auth::User();
        $bobot = LkeBobot::find($request->lke_bobot_id);
        $parameter = LkeParameter::find($parameter_id);
        $success = false;
        $message = 'Data Parameter tidak valid';
        if (!$bobot) {
            return response()->json(['success' => $success, 'message' => $message]);
        }
        if ($bobot->lke_parameter_id != $parameter_id || (in_array($user->level, ['tpn', 'tpm']) && $parameter->penilai_id != $user->penilai_id)) {
            return response()->json(['success' => $success, 'message' => $message]);
        }
        $test_tp_line = LkeTestTpLine::where('lke_bobot_id', $request->lke_bobot_id)->where('instansi_id', $request->instansi_id)->first();
        if (!$test_tp_line) {
            $test_tp_line = new LkeTestTpLine();
            $test_tp_line->penilai_user_id = $user->id;
        }
        $test_tp_line->score = str_replace(',', '.', str_replace('.', '', $request->score));
        $test_tp_line->lke_bobot_id = $request->lke_bobot_id;
        $test_tp_line->instansi_id = $request->instansi_id;
        $test_tp_line->catatan = $request->catatan;
        $test_tp_line->rekomendasi = $request->rekomendasi;
        $test_tp_line->update_user_id = $user->id;
        $test_tp_line->score_index = !empty($test_tp_line->lke_bobot->max_value) ? ($test_tp_line->score / $test_tp_line->lke_bobot->max_value) * $test_tp_line->lke_bobot->bobot : $test_tp_line->score;
        $test_tp_line->score_index = !empty($test_tp_line->lke_bobot->max_value) ? ($test_tp_line->score / $test_tp_line->lke_bobot->max_value) * $test_tp_line->lke_bobot->bobot : $test_tp_line->score;
        if ($pengali_id = $test_tp_line->lke_bobot->lke_parameter->indikator_pengali_id) {
            $bobot_pengali_id = LkeBobot::where('lke_parameter_id', $pengali_id)->where('group', $test_tp_line->lke_bobot->group)->first()->id;
            $test_tp_line_pengali = LkeTestTpLine::where('lke_bobot_id', $bobot_pengali_id)->where('instansi_id', $test_tp_line->instansi_id)->first();
            if ($test_tp_line_pengali) {
                if ($test_tp_line_pengali->score_index) {
                    $test_tp_line->score_index = $test_tp_line->score_index * ($test_tp_line_pengali->score_index / $test_tp_line_pengali->lke_bobot->bobot);
                }
            }
        }
        if ($test_tp_line->save()) {
            calculateTestTp($test_tp_line->instansi_id, $test_tp_line->lke_bobot->lke_parameter->lke_kegiatan_id);
            $success = true;
        } else {
            $success = false;
        }
        return response()->json(['success' => $success]);
    }

    public function lke_utama_score_downloadTemplate($parameter_id)
    {
        $parameter = LkeParameter::find($parameter_id);
        $datas = $this->lke_utama_score_getDatas($parameter_id, true);

        $template = new ExportLkeTemplate($parameter, $datas);

        return Excel::download($template, $parameter->nama.'.xlsx');
    }

    public function lke_utama_score_import($parameter_id, Request $request)
    {
        $user = Auth::User();
        $parameter = LkeParameter::find($parameter_id);
        if (!$parameter) {
            abort(404);
        }
        if (in_array($user->level, ['tpn', 'tpm']) && $parameter->penilai_id != $user->penilai_id) {
            abort(403);
        }
        $success = true;
        $error = false;
        $pesan = '';
        DB::beginTransaction();
        try {
            $headings = (new HeadingRowImport())->toArray($request->file('file_lke'));
            if ($headings[0][0] == heading_template_lke()) {
                $collections = Excel::toCollection(new ImportExcel, $request->file('file_lke'))[0];
                if ($collections[0]['instansi_id'] != $parameter_id) {
                    $success = false;
                    $pesan = 'File yang diimport tidak sesuai dengan Indikator yang dipilih! Periksa kembali halaman/file import!';
                    dd($pesan);
                }
                $collections = $collections->slice(3);
                foreach ($collections as $lke) {
                    if (preg_match("/[^0-9.]+/", $lke['score'])) {
                        $success = false;
                        $pesan = 'Terdapat Format Angka Skor tidak sesuai! Harap perbaiki terlebih dahulu!';
                    } else if ($success) {
                        if (!empty($lke['score'])) {
                            $test_tp_line = LkeTestTpLine::where('instansi_id', $lke['instansi_id'])->where('lke_bobot_id', $lke['lke_bobot_id'])->first();
                            if (!$test_tp_line) {
                                $test_tp_line = new LkeTestTpLine();
                                $test_tp_line->penilai_user_id = $user->id;
                            }
                            $test_tp_line->score = $lke['score'];
                            $test_tp_line->lke_bobot_id = $lke['lke_bobot_id'];
                            $test_tp_line->instansi_id = $lke['instansi_id'];
                            $test_tp_line->catatan = $lke['catatan'];
                            $test_tp_line->rekomendasi = $lke['rekomendasi'];
                            $test_tp_line->update_user_id = $user->id;
                            if ((!empty($test_tp_line->lke_bobot->min_value) && $lke['score'] < $test_tp_line->lke_bobot->min_value) || (!empty($test_tp_line->lke_bobot->max_value) && $lke['score'] > $test_tp_line->lke_bobot->max_value)) {
                                $error = true;
                                $pesan = 'Terdapat nilai skor yang tidak sesuai dengan ketentuan minimal/maksimal. Sebagian data tidak tersimpan!';
                            } else {
                                $test_tp_line->score_index = !empty($test_tp_line->lke_bobot->max_value) ? ($test_tp_line->score / $test_tp_line->lke_bobot->max_value) * $test_tp_line->lke_bobot->bobot : $test_tp_line->score;
                                if ($pengali_id = $test_tp_line->lke_bobot->lke_parameter->indikator_pengali_id) {
                                    $bobot_pengali_id = LkeBobot::where('lke_parameter_id', $pengali_id)->where('group', $test_tp_line->lke_bobot->group)->first()->id;
                                    $test_tp_line_pengali = LkeTestTpLine::where('lke_bobot_id', $bobot_pengali_id)->where('instansi_id', $test_tp_line->instansi_id)->first();
                                    if ($test_tp_line_pengali) {
                                        if ($test_tp_line_pengali->score_index) {
                                            $test_tp_line->score_index = $test_tp_line->score_index * ($test_tp_line_pengali->score_index / $test_tp_line_pengali->lke_bobot->bobot);
                                        }
                                    }
                                }
                                if ($test_tp_line->save()) {
                                    calculateTestTp($test_tp_line->instansi_id, $test_tp_line->lke_bobot->lke_parameter->lke_kegiatan_id);
                                } else {
                                    $success = false;
                                    $pesan = 'Periksa kembali file yang diimport!';
                                }
                            }
                        }
                    }
                }
            } else {
                $success = false;
                $pesan = 'File yang di upload tidak sesuai dengan template. Silahkan gunakan template yang telah disedikan!';
            }
        } catch (\Throwable $th) {
            $success = false;
            throw $th;
        }
        
        if ($success) {
            DB::commit();
            session()->flash('success', 'Data LKE berhasil diimport!');
            if ($error) {
                session()->flash('error', $pesan);
            }
        } else {
            DB::rollBack();
            session()->flash('error', 'Data LKE gagal diimport! '.$pesan);
        }

        return redirect('evaluasi/lke-utama/'.$parameter_id);
    }

    public function database()
    {
        $user = Auth::User();
        if (in_array($user->level, ['tpn', 'tpm'])) {
            $access = OpenAccessSetting::where('user_level', $user->level)->where('fitur', 'hasil_evaluasi')->first();
            if ($access) {
                $today = date('Y-m-d');
                if ($access->waktu_awal > $today || $access->waktu_akhir < $today) {
                    return view('belumbuka');
                }
            }
        }
        return view('evaluasi.database');
    }

    public function database_getDatas(Request $request)
    {
        $datas = LkeParameter::where('lke_kegiatan_id', $request->kegiatan_id)->where('level', 'Indikator')->get();
        foreach ($datas as $data) {
            $data->indikator = $data->nama;
            $data->subkomponen = $data->parent->nama;
            $data->komponen = $data->parent->parent->nama;
            $bobot_ids = LkeBobot::where('lke_parameter_id', $data->id)->pluck('id');
            $data->terisi = LkeTestTpLine::whereIn('lke_bobot_id', $bobot_ids)->count();
            $data->rata_rata_score = LkeTestTpLine::whereIn('lke_bobot_id', $bobot_ids)->avg('score');
        }
        return response()->json(['data' => $datas]);
    }

    public function hasil_evaluasi()
    {
        $user = Auth::User();
        if (in_array($user->level, ['tpn', 'tpm'])) {
            $access = OpenAccessSetting::where('user_level', $user->level)->where('fitur', 'hasil_evaluasi')->first();
            if ($access) {
                $today = date('Y-m-d');
                if ($access->waktu_awal > $today || $access->waktu_akhir < $today) {
                    return view('belumbuka');
                }
            }
        }
        return view('evaluasi.hasil_evaluasi');
    }

    public function hasil_evaluasi_getDatas(Request $request)
    {
        $kegiatan_id = $request->kegiatan_id;
        $datas = DB::table('klpd_instansi as ki')
                    ->selectRaw("CASE 
                            WHEN ltt.rb_general IS NOT NULL THEN 100 
                            ELSE NULL 
                        END as bobot_rb_general, 
                        CASE 
                            WHEN ltt.koefisien IS NOT NULL THEN ltt.rb_general + ltt.koefisien 
                            ELSE ltt.rb_general 
                        END as rb_general_koefisien, 
                        ki.name as nama_instansi, 
                        ki.id as klpd_instansi_id, 
                        ltt.*, 
                        CASE 
                            WHEN ki.group = 'kl' THEN 'Kementerian/Badan' 
                            WHEN ki.group = 'provinsi' THEN 'Provinsi' 
                            WHEN ki.group = 'kabupaten' THEN 'Kabupaten/Kota' 
                        END as group_instansi")
                    ->leftJoin('lke_test_tp as ltt', function ($join) use ($kegiatan_id) {
                        $join->on('ltt.instansi_id', '=', 'ki.id')
                            ->where('ltt.lke_kegiatan_id', '=', $kegiatan_id);
                    })
                    ->whereIn('ki.group', ['kl', 'provinsi', 'kabupaten'])
                    ->orderByRaw("FIELD(ki.group , 'kl', 'provinsi', 'kabupaten') ASC")
                    ->orderBy('ki.name')
                    ->get();
        
        foreach ($datas as $data) {
            $data->nama_instansi = '<a href="'.url('evaluasi/hasil-evaluasi/'.$data->klpd_instansi_id.'/'.$kegiatan_id).'" style="color: blue;">'.$data->nama_instansi.'</a>';
        }
        
        return response()->json(['data' => $datas]);
    }

    public function hasil_evaluasi_instansi($instansi_id, $kegiatan_id)
    {
        $user = Auth::User();
        if (in_array($user->level, ['tpn'])) {
            $access = OpenAccessSetting::where('user_level', $user->level)->where('fitur', 'hasil_evaluasi')->first();
            if ($access) {
                $today = date('Y-m-d');
                if ($access->waktu_awal > $today || $access->waktu_akhir < $today) {
                    return view('belumbuka');
                }
            }
        }

        $instansi = KlpdInstansi::find($instansi_id);
        $kegiatan = LkeKegiatan::find($kegiatan_id);
        if (!$instansi || !$kegiatan) {
            abort(404);
        }
        $test_tp = LkeTestTp::where('instansi_id', $instansi_id)->where('lke_kegiatan_id', $kegiatan_id)->first();
        if (!$test_tp) {
            $test_tp = new LkeTestTp();
            $test_tp->lke_kegiatan_id = $kegiatan_id;
            $test_tp->instansi_id = $instansi_id;
        }
        $test_tp->berkas_list = '';

        if ($test_tp->files) {
            foreach ($test_tp->files as $berkas) {
                $ext = pathinfo($berkas->file, PATHINFO_EXTENSION);
                $src = exts(strtolower($ext));
                $test_tp->berkas_list .= '<div class="col-span-12 lg:col-span-4" id="berkasdiv'.$berkas->id.'" style="position:relative;">
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
        }

        $parameters = LkeBobot::where('group', $instansi->group)->get();
        foreach ($parameters as $parameter) {
            $parameter->komponen = $parameter->lke_parameter->parent->parent->nama;
            $parameter->subkomponen = $parameter->lke_parameter->parent->nama;
            $parameter->indikator = $parameter->lke_parameter->nama;
            $parameter->bobot = $parameter->bobot;
            $tp_line = LkeTestTpLine::where('lke_bobot_id', $parameter->id)->where('instansi_id', $instansi_id)->first();
            if (!$tp_line) {
                $tp_line = new LkeTestTpLine();
            }
            $parameter->score = $tp_line->score;
            $parameter->score_index = $tp_line->score_index;
            $parameter->catatan = $tp_line->catatan;
            $parameter->rekomendasi = $tp_line->rekomendasi;
        }

        return view('evaluasi.hasil_evaluasi_instansi', compact('instansi', 'test_tp', 'parameters'));
    }

    public function hasil_evaluasi_instansi_simpan($instansi_id, $kegiatan_id, Request $request)
    {
        $instansi = KlpdInstansi::find($instansi_id);
        $kegiatan = LkeKegiatan::find($kegiatan_id);
        if (!$instansi || !$kegiatan) {
            abort(404);
        }

        $test_tp = LkeTestTp::where('instansi_id', $instansi_id)->where('lke_kegiatan_id', $kegiatan_id)->first();
        if (!$test_tp) {
            abort(404);
        }
        DB::beginTransaction();
        try {
            $success = false;
            $test_tp->koefisien = $request->koefisien;
            $test_tp->bobot_rb_general_penyesuaian = $request->bobot_rb_general_penyesuaian;
            if ($test_tp->save()) {
                $success = true;
                foreach ($test_tp->files as $berkas) {
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
                        $berkas->test_tp_id = $test_tp->id;
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
            $success = false;
            throw $th;
        }

        if ($success) {
            DB::commit();
            session()->flash('success', 'Data Test TP berhasil disimpan.');
        } else {
            DB::rollBack();
            session()->flash('error', 'Data Test TP gagal disimpan! Silahkan dicoba kembali.');
        }
        return redirect('evaluasi/hasil-evaluasi/'.$instansi_id.'/'.$kegiatan_id);
    }
}
