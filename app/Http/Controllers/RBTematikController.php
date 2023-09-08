<?php

namespace App\Http\Controllers;

use App\Models\Instansi;
use App\Models\Indikator;
use Illuminate\Http\Request;
use App\Models\GeneralPerencanaan;
use App\Models\GeneralRencanaAksi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\TematikSasaranRoadmap;
use App\Models\GeneralPerencanaanTarget;
use App\Models\GeneralRencanaAksiOutput;

class RBTematikController extends Controller
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

    public function perencanaan()
    {
        $user = Auth::User();
        $indikators = Indikator::where($user->level, '1')->orderBy('kegiatan_utama_id')->get();
        foreach ($indikators as $indikator) {
            $perencanaan = GeneralPerencanaan::where('instansi_id', $user->instansi_id)->where('kegiatan_utama_id', $indikator->kegiatan_utama_id)->where('indikator_id', $indikator->id)->first();
            $indikator->target = [];
            if ($perencanaan) {
                $indikator->perencanaan_id = $perencanaan->id;
                $indikator->baseline_tahun = $perencanaan->baseline_tahun;
                $indikator->baseline_target = $perencanaan->baseline_target;
                $indikator->baseline_realisasi = $perencanaan->baseline_realisasi;
                $indikator->realisasi_indikator = $perencanaan->realisasi_indikator;
                $indikator->capaian_indikator = $perencanaan->capaian_indikator;
                $indikator->catatan = $perencanaan->catatan;
                $target = GeneralPerencanaanTarget::where('general_perencanaan_id', $perencanaan->id)->get();
                $indikator->target = $target ? $target : [];
            }
        }
        return view('rb-tematik.perencanaan', compact('indikators'));
    }

    public function perencanaan_getData($kegiatan_utama_id, $indikator_id)
    {
        $user = Auth::User();
        $perencanaan = GeneralPerencanaan::where('instansi_id', $user->instansi_id)->where('kegiatan_utama_id', $kegiatan_utama_id)->where('indikator_id', $indikator_id)->first();
        return response()->json($perencanaan);
    }

    public function perencanaan_getTarget($kegiatan_utama_id, $indikator_id)
    {
        $user = Auth::User();
        $perencanaan = GeneralPerencanaan::where('instansi_id', $user->instansi_id)->where('kegiatan_utama_id', $kegiatan_utama_id)->where('indikator_id', $indikator_id)->first();
        $success = true;
        $input = '';
        if ($perencanaan) {
            if (count($perencanaan->target)) {
                $idx = 0;
                foreach ($perencanaan->target as $target) {
                    $input .= '<tr>
                                <td><input type="text" name="tahun[' . $idx . ']" id="target_tahun' . $idx . '" class="form-control w-full tahun" value="' . $target->tahun . '" required></td>
                                <td><input type="text" name="target[' . $idx . ']" id="target_target' . $idx . '" class="form-control w-full" value="' . $target->target . '" required></td>
                            </tr>';
                    $idx++;
                }
            } else {
                $input .= '<tr>
                            <td><input type="text" name="tahun[0]" id="target_tahun0" class="form-control w-full tahun" value="' . date('Y') . '" required></td>
                            <td><input type="text" name="target[0]" id="target_target0" class="form-control w-full" required></td>
                        </tr>';
            }
        } else {
            $success = false;
        }
        return response()->json(['success' => $success, 'input' => $input]);
    }

    public function perencanaan_simpanBaseline(Request $request)
    {
        $user = Auth::User();
        $perencanaan = GeneralPerencanaan::where('instansi_id', $user->instansi_id)->where('kegiatan_utama_id', $request->kegiatan_utama_id)->where('indikator_id', $request->indikator_id)->first();
        if (!$perencanaan) {
            $perencanaan = new GeneralPerencanaan();
            $perencanaan->instansi_id = $user->instansi_id;
            $perencanaan->kegiatan_utama_id = $request->kegiatan_utama_id;
            $perencanaan->indikator_id = $request->indikator_id;
        }
        $perencanaan->baseline_tahun = $request->baseline_tahun;
        $perencanaan->baseline_target = $request->baseline_target;
        $perencanaan->baseline_realisasi = $request->baseline_realisasi;
        if ($perencanaan->save()) {
            session()->flash('success', 'Data Baseline Perencanaan General berhasil disimpan.');
        } else {
            session()->flash('success', 'Data Baseline Perencanaan General gagal disimpan! Silahkan dicoba kembali.');
        }
        return redirect('rb-tematik/perencanaan');
    }

    public function perencanaan_simpanTarget(Request $request)
    {
        $user = Auth::User();
        $perencanaan = GeneralPerencanaan::where('instansi_id', $user->instansi_id)->where('kegiatan_utama_id', $request->kegiatan_utama_id)->where('indikator_id', $request->indikator_id)->first();
        $pesan = '';
        if ($perencanaan) {
            $success = true;
            DB::beginTransaction();
            try {
                foreach ($request->tahun as $key => $tahun) {
                    $target = GeneralPerencanaanTarget::where('general_perencanaan_id', $perencanaan->id)->where('tahun', $tahun)->first();
                    if (!$target) {
                        $target = new GeneralPerencanaanTarget();
                    }
                    $target->general_perencanaan_id = $perencanaan->id;
                    $target->tahun = $tahun;
                    $target->target = $request->target[$key];
                    if (!$target->save()) {
                        $success = false;
                    }
                    if ($tahun <= $perencanaan->baseline_tahun) {
                        $success = false;
                        $pesan .= 'Tahun yang di-input tidak boleh sama atau kurang dari tahun baseline!';
                    }
                }
            } catch (\Throwable $th) {
                throw $th;
            }
            if ($success) {
                DB::commit();
                session()->flash('success', 'Data Target Perencanaan General berhasil disimpan.');
            } else {
                DB::rollBack();
                session()->flash('success', 'Data Target Perencanaan General gagal disimpan! ' . $pesan);
            }
        } else {
            session()->flash('success', 'Data Target Perencanaan General gagal disimpan! Data Baseline tidak ditemukan.');
        }
        return redirect('rb-tematik/perencanaan');
    }

    public function perencanaan_simpanMonev(Request $request)
    {
        $user = Auth::User();
        $perencanaan = GeneralPerencanaan::where('instansi_id', $user->instansi_id)->where('kegiatan_utama_id', $request->kegiatan_utama_id)->where('indikator_id', $request->indikator_id)->first();
        if (!$perencanaan) {
            $perencanaan = new GeneralPerencanaan();
            $perencanaan->instansi_id = $user->instansi_id;
            $perencanaan->kegiatan_utama_id = $request->kegiatan_utama_id;
            $perencanaan->indikator_id = $request->indikator_id;
        }
        $perencanaan->realisasi_indikator = $request->realisasi_indikator;
        $perencanaan->capaian_indikator = $request->capaian_indikator;
        $perencanaan->catatan = $request->catatan;
        if ($perencanaan->save()) {
            session()->flash('success', 'Data Baseline Perencanaan General berhasil disimpan.');
        } else {
            session()->flash('success', 'Data Baseline Perencanaan General gagal disimpan! Silahkan dicoba kembali.');
        }
        return redirect('rb-tematik/perencanaan');
    }

    public function rencana_aksi($perencanaan_id, $target_id)
    {
        $user = Auth::User();
        $target = GeneralPerencanaanTarget::where('id', $target_id)->where('general_perencanaan_id', $perencanaan_id)
            ->whereHas('perencanaan', function ($q) use ($user) {
                $q->where('instansi_id', $user->instansi_id);
            })->first();
        if (!$target) {
            abort(404);
        }
        return view('rb-tematik.rencana_aksi', compact('target'));
    }

    public function rencana_aksi_getDatas($perencanaan_id, $target_id)
    {
        $user = Auth::User();
        $target = GeneralPerencanaanTarget::where('id', $target_id)->where('general_perencanaan_id', $perencanaan_id)
            ->whereHas('perencanaan', function ($q) use ($user) {
                $q->where('instansi_id', $user->instansi_id);
            })->first();
        $rencana_aksis = GeneralRencanaAksi::where('general_perencanaan_target_id', $target->id)->get();
        $outputs = [];
        $no = 1;
        foreach ($rencana_aksis as $rencana_aksi) {
            foreach ($rencana_aksi->output as $output) {
                $output->no = $no;
                $output->nama_rencana_aksi = $output->rencana_aksi->rencana_aksi;
                $output->target_total = fnumber($output->target_total);
                $output->anggaran_total = currency($output->anggaran_total);
                $outputs[] = $output;
            }
            $no++;
        }
        return response()->json(['data' => $outputs]);
    }

    public function rencana_aksi_getData($perencanaan_id, $target_id, $id)
    {
        $user = Auth::User();
        $target = GeneralPerencanaanTarget::where('id', $target_id)->where('general_perencanaan_id', $perencanaan_id)
            ->whereHas('perencanaan', function ($q) use ($user) {
                $q->where('instansi_id', $user->instansi_id);
            })->first();
        $output = GeneralRencanaAksiOutput::find($id);
        $output->rencana_aksi = $output->rencana_aksi;
        return response()->json($output);
    }

    public function rencana_aksi_simpan($perencanaan_id, $target_id, Request $request)
    {
        $user = Auth::User();
        $target = GeneralPerencanaanTarget::where('id', $target_id)->where('general_perencanaan_id', $perencanaan_id)
            ->whereHas('perencanaan', function ($q) use ($user) {
                $q->where('instansi_id', $user->instansi_id);
            })->first();
        if (!$target) {
            abort(403);
        }
        $success = true;
        DB::beginTransaction();
        try {
            $rencana_aksi = new GeneralRencanaAksi();
            $rencana_aksi->general_perencanaan_target_id = $target->id;
            if ($request->rencana_aksi_id) {
                $rencana_aksi = GeneralRencanaAksi::where('general_perencanaan_target_id', $target->id)->where('id', $request->rencana_aksi_id)->first();
            }
            $rencana_aksi->rencana_aksi = $request->rencana_aksi;
            if ($rencana_aksi->save()) {
                foreach ($request->target_output as $target_output) {
                    $rencana_aksi_output = new GeneralRencanaAksiOutput();
                    if (isset($target_output['rencana_aksi_output_id'])) {
                        $rencana_aksi_output = GeneralRencanaAksiOutput::find($target_output['rencana_aksi_output_id']);
                    }
                    $rencana_aksi_output->general_rencana_aksi_id = $rencana_aksi->id;
                    $rencana_aksi_output->satuan_output = $target_output['satuan_output'];
                    $rencana_aksi_output->indikator_output = $target_output['indikator_output'];
                    $rencana_aksi_output->target_tw1 = str_replace('.', '', $target_output['target_tw1']);
                    $rencana_aksi_output->target_tw2 = str_replace('.', '', $target_output['target_tw2']);
                    $rencana_aksi_output->target_tw3 = str_replace('.', '', $target_output['target_tw3']);
                    $rencana_aksi_output->target_tw4 = str_replace('.', '', $target_output['target_tw4']);
                    $rencana_aksi_output->target_total = $rencana_aksi_output->target_tw1 + $rencana_aksi_output->target_tw2 + $rencana_aksi_output->target_tw3 + $rencana_aksi_output->target_tw4;
                    $rencana_aksi_output->anggaran_tw1 = str_replace('.', '', $target_output['anggaran_tw1']);
                    $rencana_aksi_output->anggaran_tw2 = str_replace('.', '', $target_output['anggaran_tw2']);
                    $rencana_aksi_output->anggaran_tw3 = str_replace('.', '', $target_output['anggaran_tw3']);
                    $rencana_aksi_output->anggaran_tw4 = str_replace('.', '', $target_output['anggaran_tw4']);
                    $rencana_aksi_output->anggaran_total = $rencana_aksi_output->anggaran_tw1 + $rencana_aksi_output->anggaran_tw2 + $rencana_aksi_output->anggaran_tw3 + $rencana_aksi_output->anggaran_tw4;
                    $rencana_aksi_output->pelaksana = $target_output['pelaksana'];
                    $rencana_aksi_output->koordinator = $target_output['koordinator'];
                    if (!$rencana_aksi_output->save()) {
                        $success = false;
                    }
                }
            }
        } catch (\Throwable $th) {
            $success = false;
            throw $th;
        }
        if ($success) {
            DB::commit();
        } else {
            DB::rollBack();
        }
        return response()->json(['success' => $success]);
    }

    public function rencana_aksi_hapus($perencanaan_id, $target_id, Request $request)
    {
        $user = Auth::User();
        $target = GeneralPerencanaanTarget::where('id', $target_id)->where('general_perencanaan_id', $perencanaan_id)
            ->whereHas('perencanaan', function ($q) use ($user) {
                $q->where('instansi_id', $user->instansi_id);
            })->first();
        if (!$target) {
            abort(403);
        }
        $success = false;
        DB::beginTransaction();
        try {
            $output = GeneralRencanaAksiOutput::find($request->id);
            $rencana_aksi = $output->rencana_aksi;
            if ($output->delete()) {
                $success = true;
                if (count($rencana_aksi->output) == 0) {
                    if (!$rencana_aksi->delete()) {
                        $success = false;
                    }
                }
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        if ($success) {
            DB::commit();
        } else {
            DB::rollBack();
        }
        return response()->json(['success' => $success]);
    }

    public function monev($perencanaan_id, $target_id)
    {
        $user = Auth::User();
        $target = GeneralPerencanaanTarget::where('id', $target_id)->where('general_perencanaan_id', $perencanaan_id)
            ->whereHas('perencanaan', function ($q) use ($user) {
                $q->where('instansi_id', $user->instansi_id);
            })->first();
        if (!$target) {
            abort(404);
        }
        return view('rb-tematik.monev', compact('target'));
    }

    public function monev_getTarget($perencanaan_id, $target_id)
    {
        $user = Auth::User();
        $target = GeneralPerencanaanTarget::where('id', $target_id)->where('general_perencanaan_id', $perencanaan_id)
            ->whereHas('perencanaan', function ($q) use ($user) {
                $q->where('instansi_id', $user->instansi_id);
            })->first();
        if (!$target) {
            abort(404);
        }
        return response()->json($target);
    }

    public function monev_simpanTarget($perencanaan_id, $target_id, Request $request)
    {
        $user = Auth::User();
        $target = GeneralPerencanaanTarget::where('id', $target_id)->where('general_perencanaan_id', $perencanaan_id)
            ->whereHas('perencanaan', function ($q) use ($user) {
                $q->where('instansi_id', $user->instansi_id);
            })->first();
        if (!$target) {
            abort(403);
        }
        $success = false;
        $target->realisasi_indikator = $request->realisasi_indikator;
        $target->capaian_indikator = $request->capaian_indikator;
        $target->catatan = $request->catatan;
        if ($target->save()) {
            $success = true;
        }
        return response()->json(compact('success', 'target'));
    }

    public function monev_simpan($perencanaan_id, $target_id, Request $request)
    {
        $user = Auth::User();
        $target = GeneralPerencanaanTarget::where('id', $target_id)->where('general_perencanaan_id', $perencanaan_id)
            ->whereHas('perencanaan', function ($q) use ($user) {
                $q->where('instansi_id', $user->instansi_id);
            })->first();
        if (!$target) {
            abort(403);
        }
        $success = false;
        $output = GeneralRencanaAksiOutput::find($request->output_id);
        if (!$output) {
            abort(404);
        }
        $output->realisasi_output_tw1 = $request->realisasi_output_tw1;
        $output->realisasi_output_tw2 = $request->realisasi_output_tw2;
        $output->realisasi_output_tw3 = $request->realisasi_output_tw3;
        $output->realisasi_output_tw4 = $request->realisasi_output_tw4;
        $output->realisasi_output_total = $request->realisasi_output_total;
        $output->realisasi_anggaran_tw1 = $request->realisasi_anggaran_tw1;
        $output->realisasi_anggaran_tw2 = $request->realisasi_anggaran_tw2;
        $output->realisasi_anggaran_tw3 = $request->realisasi_anggaran_tw3;
        $output->realisasi_anggaran_tw4 = $request->realisasi_anggaran_tw4;
        $output->realisasi_anggaran_total = $request->realisasi_anggaran_total;
        $output->capaian_anggaran = $request->capaian_anggaran;
        if ($output->save()) {
            $success = true;
        }
        return response()->json(['success' => $success]);
    }

    public function rekap_data(Request $request)
    {
        $user = Auth::User();
        if ($request->instansi_id && in_array($user->level, ['admin', 'evaluator'])) {
            $instansi_id = $request->instansi_id;
        } else if ($user->instansi_id) {
            $instansi_id = $user->instansi_id;
        } else {
            $instansi_id = Instansi::orderBy('id')->first()->id;
        }
        $sasarans = TematikSasaranRoadmap::where('instansi_id', $instansi_id)->orderBy('tema_id')->get();
        $key = 0;
        $datas = [];
        foreach ($sasarans as $sasaran) {
            if (count($sasaran)) {
                foreach ($perencanaan->indikator_roadmap as $indikator_roadmaps) {
                    if (count($indikator_roadmaps->permasalahan)) {
                        foreach ($target->rencana_aksi as $rencana_aksi) {
                            if (count($rencana_aksi->output)) {
                                foreach ($rencana_aksi->output as $output) {
                                    $datas[$key]['perencanaan'] = $perencanaan;
                                    $datas[$key]['target'] = $target;
                                    $datas[$key]['rencana_aksi'] = $rencana_aksi;
                                    $datas[$key]['output'] = $output;
                                    $key++;
                                }
                            } else {
                                $datas[$key]['perencanaan'] = $perencanaan;
                                $datas[$key]['target'] = $target;
                                $datas[$key]['rencana_aksi'] = $rencana_aksi;
                                $datas[$key]['output'] = new GeneralRencanaAksiOutput();
                                $key++;
                            }
                        }
                    } else {
                        $datas[$key]['perencanaan'] = $perencanaan;
                        $datas[$key]['target'] = $target;
                        $datas[$key]['rencana_aksi'] = new GeneralRencanaAksi();
                        $datas[$key]['output'] = new GeneralRencanaAksiOutput();
                        $key++;
                    }
                }
            } else {
                $datas[$key]['perencanaan'] = $perencanaan;
                $datas[$key]['target'] = new GeneralPerencanaanTarget();
                $datas[$key]['rencana_aksi'] = new GeneralRencanaAksi();
                $datas[$key]['output'] = new GeneralRencanaAksiOutput();
                $key++;
            }
        }
        return view('rb-tematik.rekap_data', compact('datas', 'instansi_id'));
    }

    public function rekap_data_getPerencanaan($id)
    {
        $perencanaan = GeneralPerencanaan::find($id);
        return response()->json($perencanaan);
    }

    public function rekap_data_simpanCatatanEvaluator(Request $request)
    {
        $perencanaan = GeneralPerencanaan::find($request->perencanaan_id);
        $perencanaan->catatan = $request->catatan;
        $perencanaan->save();
        return redirect()->back();
    }
}
