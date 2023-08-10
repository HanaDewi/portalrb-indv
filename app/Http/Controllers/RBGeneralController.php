<?php

namespace App\Http\Controllers;

use App\Models\GeneralPerencanaan;
use App\Models\GeneralPerencanaanTarget;
use App\Models\Indikator;
use App\Models\Perencanaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RBGeneralController extends Controller
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
                $indikator->baseline_tahun = $perencanaan->baseline_tahun;
                $indikator->baseline_target = $perencanaan->baseline_target;
                $indikator->baseline_realisasi = $perencanaan->baseline_realisasi;
                $target = GeneralPerencanaanTarget::where('general_perencanaan_id', $perencanaan->id)->get();
                $indikator->target = $target ? $target : [];
            }
        }
        return view('rb-general.perencanaan', compact('indikators'));
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
                                <td><input type="text" name="tahun['.$idx.']" id="target_tahun'.$idx.'" class="form-control w-full tahun" value="'.$target->tahun.'" required></td>
                                <td><input type="text" name="target['.$idx.']" id="target_target'.$idx.'" class="form-control w-full" value="'.$target->target.'" required></td>
                            </tr>';
                    $idx++;
                }
            } else {
                $input .= '<tr>
                            <td><input type="text" name="tahun[0]" id="target_tahun0" class="form-control w-full tahun" value="'.date('Y').'" required></td>
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
        return redirect('rb-general/perencanaan');
    }

    public function perencanaan_simpanTarget(Request $request)
    {
        $user = Auth::User();
        $perencanaan = GeneralPerencanaan::where('instansi_id', $user->instansi_id)->where('kegiatan_utama_id', $request->kegiatan_utama_id)->where('indikator_id', $request->indikator_id)->first();
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
                }
            } catch (\Throwable $th) {
                throw $th;
            }
            if ($success) {
                DB::commit();
                session()->flash('success', 'Data Target Perencanaan General berhasil disimpan.');
            } else {
                DB::rollBack();
                session()->flash('success', 'Data Target Perencanaan General gagal disimpan! Silahkan dicoba kembali.');
            }
        } else {
            session()->flash('success', 'Data Target Perencanaan General gagal disimpan! Data Baseline tidak ditemukan.');
        }
        return redirect('rb-general/perencanaan');
    }
}
