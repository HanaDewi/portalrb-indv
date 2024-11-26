<?php

namespace App\Http\Controllers;

use App\Exports\ExportLkeTemplate;
use App\Imports\ImportExcel;
use App\Models\KlpdInstansi;
use App\Models\LKE\LkeBobot;
use App\Models\LKE\LkeParameter;
use App\Models\LKE\LkeTestTp;
use App\Models\LKE\LkeTestTpLine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        return view('evaluasi.lke_utama');
    }

    public function lke_utama_getDatas(Request $request)
    {
        $user = Auth::User();
        if (in_array($user->level, ['admin', 'tpn'])) {
            $datas = LkeParameter::where('lke_kegiatan_id', $request->kegiatan_id)->where('level', 'Indikator')->get();
        } else if ($user->level == 'tpm') {
            $datas = LkeParameter::where('lke_kegiatan_id', $request->kegiatan_id)->where('penilai_id', $user->penilai_id)->where('level', 'Indikator')->get();
        }
        foreach ($datas as $data) {
            $data->indikator = $data->rencana_aksi ? $data->nama : '<a href="'.url('evaluasi/lke-utama/'.$data->id).'" style="color:blue;">'.$data->nama.'</a>';
            $data->subkomponen = $data->parent->nama;
            $data->komponen = $data->parent->parent->nama;
            $bobot_ids = LkeBobot::where('lke_parameter_id', $data->id)->pluck('id');
            $data->terisi = LkeTestTpLine::whereIn('lke_bobot_id', $bobot_ids)->count();
            $data->rata_rata_score = LkeTestTpLine::whereIn('lke_bobot_id', $bobot_ids)->avg('score');
            $data->edit = $user->level == 'tpn' && $data->penilai_id != $user->penilai_id ? 0 : 1;
        }
        return response()->json(['data' => $datas]);
    }

    public function lke_utama_score($parameter_id)
    {
        $user = Auth::User();
        if (in_array($user->level, ['admin', 'tpn'])) {
            $parameter = LkeParameter::where('rencana_aksi', 0)->where('id', $parameter_id)->first();
        } else if ($user->level == 'tpm') {
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
        $test_tp_line->score = $request->score;
        $test_tp_line->lke_bobot_id = $request->lke_bobot_id;
        $test_tp_line->instansi_id = $request->instansi_id;
        $test_tp_line->catatan = $request->catatan;
        $test_tp_line->rekomendasi = $request->rekomendasi;
        $test_tp_line->update_user_id = $user->id;
        if ($test_tp_line->save()) {
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
                                if (!$test_tp_line->save()) {
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
}
