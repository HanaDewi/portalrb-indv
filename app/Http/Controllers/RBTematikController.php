<?php

namespace App\Http\Controllers;

use App\Models\FokusIntervensi;
use App\Models\Instansi;
use App\Models\Indikator;
use Illuminate\Http\Request;
use App\Models\GeneralPerencanaan;
use App\Models\Tema;
use App\Models\TematikSasaranRoadmap;
use App\Models\TematikIndikatorRoadmap;
use App\Models\TematikIndikatorPermasalahan;
use App\Models\TematikRencanaAksi;
use App\Models\TematikRencanaAksiOutput;
use App\Models\GeneralPerencanaanTarget;
use App\Models\GeneralRencanaAksiOutput;
use App\Models\GeneralRencanaAksi;
use App\Models\TematikPermasalahan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;


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

    public function tema_sasaran()
    {
        $user = Auth::User();
        $temas = Tema::get();
        $sasaranRoadmaps = TematikSasaranRoadmap::where('instansi_id', $user->user_rel->instansi_id)->orderBy('tema_id')->get();
        $tematikDatas = [];
        $jumlahBaris = 0;
        foreach ($sasaranRoadmaps as $sasaran) {
            if (count($sasaran->indikator_roadmap)) {
                foreach ($sasaran->indikator_roadmap as $indikator) {
                    $tematikDatas[$jumlahBaris]["indikator_permasalahan_id"] = null;
                    $tematikDatas[$jumlahBaris]["indikator_permasalahan_nama"] = null;
                    $tematikDatas[$jumlahBaris]["indikator_permasalahan_target"] = null;
                    $tematikDatas[$jumlahBaris]["permasalahan_id"] = null;
                    $tematikDatas[$jumlahBaris]["permasalahan_nama"] = null;
                    $tematikDatas[$jumlahBaris]["permasalahan_sasaran"] = null;
                    $tematikDatas[$jumlahBaris]["indikator_id"] = $indikator->id;
                    $tematikDatas[$jumlahBaris]["indikator_nama"] = $indikator->nama;
                    $tematikDatas[$jumlahBaris]["indikator_target"] = $indikator->target;
                    $tematikDatas[$jumlahBaris]["indikator_satuan"] = $indikator->satuan;
                    $tematikDatas[$jumlahBaris]["indikator_realisasi_indikator"] = $indikator->realisasi_indikator;
                    $tematikDatas[$jumlahBaris]["indikator_capaian_indikator"] = $indikator->capaian_indikator;
                    $tematikDatas[$jumlahBaris]["indikator_catatan"] = $indikator->catatan;
                    $tematikDatas[$jumlahBaris]["sasaran_id"] = $sasaran->id;
                    $tematikDatas[$jumlahBaris]["sasaran_nama"] = $sasaran->nama;
                    $tematikDatas[$jumlahBaris]["tema_id"] = $sasaran->tema->id;
                    $tematikDatas[$jumlahBaris]["tema_nama"] = $sasaran->tema->nama;
                    $jumlahBaris++;
                }
            } else {
                $tematikDatas[$jumlahBaris]["indikator_permasalahan_id"] = null;
                $tematikDatas[$jumlahBaris]["indikator_permasalahan_nama"] = null;
                $tematikDatas[$jumlahBaris]["indikator_permasalahan_target"] = null;
                $tematikDatas[$jumlahBaris]["permasalahan_id"] = null;
                $tematikDatas[$jumlahBaris]["permasalahan_nama"] = null;
                $tematikDatas[$jumlahBaris]["permasalahan_sasaran"] = null;
                $tematikDatas[$jumlahBaris]["indikator_id"] = null;
                $tematikDatas[$jumlahBaris]["indikator_nama"] = null;
                $tematikDatas[$jumlahBaris]["indikator_target"] = null;
                $tematikDatas[$jumlahBaris]["indikator_satuan"] = null;
                $tematikDatas[$jumlahBaris]["indikator_realisasi_indikator"] = null;
                $tematikDatas[$jumlahBaris]["indikator_capaian_indikator"] = null;
                $tematikDatas[$jumlahBaris]["indikator_catatan"] = null;
                $tematikDatas[$jumlahBaris]["sasaran_id"] = $sasaran->id;
                $tematikDatas[$jumlahBaris]["sasaran_nama"] = $sasaran->nama;
                $tematikDatas[$jumlahBaris]["tema_id"] = $sasaran->tema->id;
                $tematikDatas[$jumlahBaris]["tema_nama"] = $sasaran->tema->nama;
                $jumlahBaris++;
            }
        }
        return view('rb-tematik.perencanaan', [
            "temas" => $temas,
            "sasaranRoadmaps" => $sasaranRoadmaps,
            "tematikDatas" => $tematikDatas
        ]);
    }

    public function permasalahan()
    {
        $user = Auth::User();
        $temas = Tema::get();
        $sasaranRoadmaps = TematikSasaranRoadmap::where('instansi_id', $user->user_rel->instansi_id)->orderBy('tema_id')->get();
        $tematikDatas = [];
        $jumlahBaris = 0;
        foreach ($sasaranRoadmaps as $sasaran) {
            if (count($sasaran->indikator_roadmap)) {
                foreach ($sasaran->indikator_roadmap as $indikator) {
                    if (count($indikator->permasalahan)) {
                        foreach ($indikator->permasalahan as $permasalahan) {
                            if (count($permasalahan->indikator_permasalahan)) {
                                foreach ($permasalahan->indikator_permasalahan as $indikator_permasalahan) {
                                    $tematikDatas[$jumlahBaris]["indikator_permasalahan_id"] = $indikator_permasalahan->id;
                                    $tematikDatas[$jumlahBaris]["indikator_permasalahan_nama"] = $indikator_permasalahan->nama;
                                    $tematikDatas[$jumlahBaris]["indikator_permasalahan_target"] = $indikator_permasalahan->target;
                                    $tematikDatas[$jumlahBaris]["indikator_permasalahan_satuan_target"] = $indikator_permasalahan->satuan;
                                    $tematikDatas[$jumlahBaris]["permasalahan_id"] = $permasalahan->id;
                                    $tematikDatas[$jumlahBaris]["permasalahan_nama"] = $permasalahan->nama;
                                    $tematikDatas[$jumlahBaris]["permasalahan_sasaran"] = $permasalahan->sasaran_permasalahan;
                                    $tematikDatas[$jumlahBaris]["indikator_id"] = $indikator->id;
                                    $tematikDatas[$jumlahBaris]["indikator_nama"] = $indikator->nama;
                                    $tematikDatas[$jumlahBaris]["indikator_target"] = $indikator->target;
                                    $tematikDatas[$jumlahBaris]["indikator_satuan"] = $indikator->satuan;
                                    $tematikDatas[$jumlahBaris]["sasaran_id"] = $sasaran->id;
                                    $tematikDatas[$jumlahBaris]["sasaran_nama"] = $sasaran->nama;
                                    $tematikDatas[$jumlahBaris]["tema_id"] = $sasaran->tema->id;
                                    $tematikDatas[$jumlahBaris]["tema_nama"] = $sasaran->tema->nama;
                                    $jumlahBaris++;
                                }
                            } else {
                                $tematikDatas[$jumlahBaris]["indikator_permasalahan_id"] = null;
                                $tematikDatas[$jumlahBaris]["indikator_permasalahan_nama"] = null;
                                $tematikDatas[$jumlahBaris]["indikator_permasalahan_target"] = null;
                                $tematikDatas[$jumlahBaris]["indikator_permasalahan_satuan_target"] = null;
                                $tematikDatas[$jumlahBaris]["permasalahan_id"] = $permasalahan->id;
                                $tematikDatas[$jumlahBaris]["permasalahan_nama"] = $permasalahan->nama;
                                $tematikDatas[$jumlahBaris]["permasalahan_sasaran"] = $permasalahan->sasaran_permasalahan;
                                $tematikDatas[$jumlahBaris]["indikator_id"] = $indikator->id;
                                $tematikDatas[$jumlahBaris]["indikator_nama"] = $indikator->nama;
                                $tematikDatas[$jumlahBaris]["indikator_target"] = $indikator->target;
                                $tematikDatas[$jumlahBaris]["indikator_satuan"] = $indikator->satuan;
                                $tematikDatas[$jumlahBaris]["sasaran_id"] = $sasaran->id;
                                $tematikDatas[$jumlahBaris]["sasaran_nama"] = $sasaran->nama;
                                $tematikDatas[$jumlahBaris]["tema_id"] = $sasaran->tema->id;
                                $tematikDatas[$jumlahBaris]["tema_nama"] = $sasaran->tema->nama;
                                $jumlahBaris++;
                            }
                        }
                    } else {
                        $tematikDatas[$jumlahBaris]["indikator_permasalahan_id"] = null;
                        $tematikDatas[$jumlahBaris]["indikator_permasalahan_nama"] = null;
                        $tematikDatas[$jumlahBaris]["indikator_permasalahan_target"] = null;
                        $tematikDatas[$jumlahBaris]["indikator_permasalahan_satuan_target"] = null;
                        $tematikDatas[$jumlahBaris]["permasalahan_id"] = null;
                        $tematikDatas[$jumlahBaris]["permasalahan_nama"] = null;
                        $tematikDatas[$jumlahBaris]["permasalahan_sasaran"] = null;
                        $tematikDatas[$jumlahBaris]["indikator_id"] = $indikator->id;
                        $tematikDatas[$jumlahBaris]["indikator_nama"] = $indikator->nama;
                        $tematikDatas[$jumlahBaris]["indikator_target"] = $indikator->target;
                        $tematikDatas[$jumlahBaris]["indikator_satuan"] = $indikator->satuan;
                        $tematikDatas[$jumlahBaris]["sasaran_id"] = $sasaran->id;
                        $tematikDatas[$jumlahBaris]["sasaran_nama"] = $sasaran->nama;
                        $tematikDatas[$jumlahBaris]["tema_id"] = $sasaran->tema->id;
                        $tematikDatas[$jumlahBaris]["tema_nama"] = $sasaran->tema->nama;
                        $jumlahBaris++;
                    }
                }
            } else {
                $tematikDatas[$jumlahBaris]["indikator_permasalahan_id"] = null;
                $tematikDatas[$jumlahBaris]["indikator_permasalahan_nama"] = null;
                $tematikDatas[$jumlahBaris]["indikator_permasalahan_target"] = null;
                $tematikDatas[$jumlahBaris]["indikator_permasalahan_satuan_target"] = null;
                $tematikDatas[$jumlahBaris]["permasalahan_id"] = null;
                $tematikDatas[$jumlahBaris]["permasalahan_nama"] = null;
                $tematikDatas[$jumlahBaris]["permasalahan_sasaran"] = null;
                $tematikDatas[$jumlahBaris]["indikator_id"] = null;
                $tematikDatas[$jumlahBaris]["indikator_nama"] = null;
                $tematikDatas[$jumlahBaris]["indikator_target"] = null;
                $tematikDatas[$jumlahBaris]["indikator_satuan"] = null;
                $tematikDatas[$jumlahBaris]["sasaran_id"] = $sasaran->id;
                $tematikDatas[$jumlahBaris]["sasaran_nama"] = $sasaran->nama;
                $tematikDatas[$jumlahBaris]["tema_id"] = $sasaran->tema->id;
                $tematikDatas[$jumlahBaris]["tema_nama"] = $sasaran->tema->nama;
                $jumlahBaris++;
            }
        }
        return view('rb-tematik.permasalahan', [
            "temas" => $temas,
            "sasaranRoadmaps" => $sasaranRoadmaps,
            "tematikDatas" => $tematikDatas
        ]);
    }

    public function simpanSasaranRoadmap(Request $request)
    {
        $user = Auth::User();
        $sasaranRoadmap = TematikSasaranRoadmap::where('instansi_id', $user->user_rel->instansi_id)->where('tema_id', $request->tema_id)->where('nama', $request->nama)->first();
        if (!$sasaranRoadmap) {
            foreach ($request->tema_id as $idx => $tema_id) {
                $sasaranRoadmap = new TematikSasaranRoadmap();
                $sasaranRoadmap->instansi_id = $user->user_rel->instansi_id;
                $sasaranRoadmap->tema_id = $tema_id;
                $sasaranRoadmap->nama = $request->nama[$idx];
                if ($sasaranRoadmap->save()) {
                    session()->flash('success', 'Data Sasaran Roadmap Tematik berhasil disimpan.');
                } else {
                    session()->flash('success', 'Data Sasaran Roadmap Tematik gagal disimpan! Silahkan dicoba kembali.');
                    return redirect('rb-tematik/perencanaan');
                }
            }
        } else {
            //untuk update

            if (Gate::denies('modify-sasaran-roadmap', $sasaranRoadmap)) {
                // Unauthorized, handle accordingly
                abort(403, 'Unauthorized');
            }
        }


        return redirect('rb-tematik/perencanaan');
    }

    public function simpanIndikatorRoadmap(Request $request)
    {
        $user = Auth::User();
        $indikatorRoadmap = TematikIndikatorRoadmap::where('id', $request->indikator_roadmap_id)->first();
        if (!$indikatorRoadmap) {
            $indikatorRoadmap = new TematikIndikatorRoadmap();
            $indikatorRoadmap->tematik_sasaran_roadmap_id = $request->sasaran_id;
        }
        //hanya user dan instansi terkait saja yang bisa ngedit
        if (Gate::denies('modify-indikator-roadmap', $indikatorRoadmap)) {
            // Unauthorized, handle accordingly
            abort(403, 'Unauthorized');
        }
        $indikatorRoadmap->nama = $request->indikator_roadmap;
        $indikatorRoadmap->target = $request->target_roadmap;
        $indikatorRoadmap->satuan = $request->target_satuan;

        if ($indikatorRoadmap->save()) {
            session()->flash('success', 'Data Indikator Roadmap Tematik berhasil disimpan.');
        } else {
            session()->flash('success', 'Data Indikator Roadmap Tematik gagal disimpan! Silahkan dicoba kembali.');
        }
        return redirect('rb-tematik/perencanaan');
    }

    public function getIndikatorRoadmap()
    {
        $tematik_sasaran_roadmap_id = request()->get('tematik_sasaran_roadmap_id');
        $indikators = TematikIndikatorRoadmap::where('tematik_sasaran_roadmap_id', $tematik_sasaran_roadmap_id)->get();
        return response()->json($indikators);
    }

    public function indikator_getData($indikator_id)
    {
        $indikator = TematikIndikatorRoadmap::where('id', $indikator_id)->first();
        $data = [
            "tema" => $indikator->sasaran_roadmap->tema->nama,
            "sasaran_roadmap" => $indikator->sasaran_roadmap->nama,
            "indikator_roadmap" => $indikator->nama,
            "target_roadmap" => $indikator->target,
            "target_satuan" => $indikator->satuan,
            "realisasi_indikator" => $indikator->realisasi_indikator,
            "capaian_indikator" => $indikator->capaian_indikator,
            "catatan" => $indikator->catatan,
        ];
        return response()->json($data);
    }

    public function simpanPermasalahan(Request $request)
    {
        $permasalahan = TematikPermasalahan::where('tematik_indikator_roadmap_id',)->where('nama', $request->indikator_roadmap)->first();
        if (!$permasalahan) {
            $permasalahan = new TematikPermasalahan();
            $permasalahan->tematik_indikator_roadmap_id = $request->tematik_indikator_roadmap_id;
        }

        //hanya user dan instansi terkait saja yang bisa ngedit
        if (Gate::denies('modify-permasalahan', $permasalahan)) {
            // Unauthorized, handle accordingly
            abort(403, 'Unauthorized');
        }

        $permasalahan->nama = $request->permasalahan;
        $permasalahan->sasaran_permasalahan = $request->sasaran_permasalahan;

        if ($permasalahan->save()) {
            session()->flash('success', 'Data Permasalahan Indikator Roadmap Tematik berhasil disimpan.');
        } else {
            session()->flash('success', 'Data Permasalahan Indikator Roadmap Tematik gagal disimpan! Silahkan dicoba kembali.');
        }
        return redirect('rb-tematik/permasalahan');
    }

    public function simpanIndikatorPermasalahan(Request $request)
    {
        $indikatorPermasalahan = TematikIndikatorPermasalahan::where('tematik_permasalahan_id',)->where('nama', $request->permasalahan)->first();
        if (!$indikatorPermasalahan) {
            $indikatorPermasalahan = new TematikIndikatorPermasalahan();
            $indikatorPermasalahan->tematik_permasalahan_id = $request->tematik_permasalahan_id_onIndikatorPermasalahan;
        }

        //hanya user dan instansi terkait saja yang bisa ngedit
        if (Gate::denies('modify-indikator', $indikatorPermasalahan)) {
            // Unauthorized, handle accordingly
            abort(403, 'Unauthorized');
        }

        $indikatorPermasalahan->nama = $request->indikator_permasalahan;
        $indikatorPermasalahan->target = $request->target_permasalahan;
        $indikatorPermasalahan->satuan = $request->satuan_target_permasalahan;

        if ($indikatorPermasalahan->save()) {
            session()->flash('success', 'Data Indikator berhasil disimpan.');
        } else {
            session()->flash('success', 'Data Indikator gagal disimpan! Silahkan dicoba kembali.');
        }
        return redirect('rb-tematik/permasalahan');
    }

    public function rencana_aksi($indikator_id)
    {
        $indikator = TematikIndikatorPermasalahan::where('id', $indikator_id)->first();
        $fokus_intervensi = FokusIntervensi::all();
        if (!$indikator) {
            abort(404);
        }
        return view('rb-tematik.rencana_aksi', compact('indikator', 'fokus_intervensi'));
    }

    public function rencana_aksi_getDatas($indikator_id)
    {
        $rencana_aksis = TematikRencanaAksi::where('tematik_indikator_permasalahan_id', $indikator_id)->get();
        $outputs = [];
        $no = 1;
        foreach ($rencana_aksis as $rencana_aksi) {
            foreach ($rencana_aksi->output as $output) {

                $output->no = $no;
                if (isset($output->get_intervensi->nama)) {
                    $output->nama_intervensi = $output->get_intervensi->nama;
                } else {
                    $output->nama_intervensi = "";
                }

                $output->nama_rencana_aksi = $output->rencana_aksi->nama;
                $output->target_total = fnumber($output->target_total);
                $output->anggaran_total = currency($output->anggaran_total);
                $outputs[] = $output;
            }
            $no++;
        }
        return response()->json(['data' => $outputs]);
    }

    public function rencana_aksi_getData($id)
    {
        $user = Auth::User();
        $output = TematikRencanaAksiOutput::find($id);
        $output->rencana_aksi = $output->rencana_aksi;
        return response()->json($output);
    }

    public function rencana_aksi_simpan($indikator_id, Request $request)
    {
        $indikator = TematikIndikatorPermasalahan::where('id', $indikator_id)->first();
        if (!$indikator) {
            abort(403);
        }
        $success = true;
        DB::beginTransaction();
        try {
            $rencana_aksi = TematikRencanaAksi::where('tematik_indikator_permasalahan_id', $indikator->id)->first();
            if (!$rencana_aksi) {
                $rencana_aksi = new TematikRencanaAksi();
                $rencana_aksi->tematik_indikator_permasalahan_id = $indikator->id;
            }
            //hanya user dan instansi terkait saja yang bisa ngedit
            if (Gate::denies('modify-rencana-aksi', $rencana_aksi)) {
                // Unauthorized, handle accordingly
                abort(403, 'Unauthorized');
            }
            $rencana_aksi->nama = $request->rencana_aksi;
            if ($rencana_aksi->save()) {
                foreach ($request->target_output as $target_output) {
                    $rencana_aksi_output = new TematikRencanaAksiOutput();
                    if (isset($target_output['rencana_aksi_output_id'])) {
                        $rencana_aksi_output = TematikRencanaAksiOutput::find($target_output['rencana_aksi_output_id']);
                    }
                    $rencana_aksi_output->tematik_rencana_aksi_id = $rencana_aksi->id;
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
                    $rencana_aksi_output->fokus_intervensi = $request->fokus_intervensi;
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

    public function rencana_aksi_hapus($renaksi_output_id, Request $request)
    {
        $user = Auth::User();
        $renaksiOutput = TematikRencanaAksiOutput::where('id', $renaksi_output_id)->first();
        if (!$renaksiOutput) {
            abort(403);
        }
        if (Gate::denies('modify-rencana-aksi', $renaksiOutput->rencana_aksi)) {
            // Unauthorized, handle accordingly
            abort(403, 'Unauthorized');
        }
        $success = false;
        DB::beginTransaction();
        try {
            $rencana_aksi = $renaksiOutput->rencana_aksi;
            if ($renaksiOutput->delete()) {
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

    public function monev($indikator_id)
    {
        $indikator = TematikIndikatorPermasalahan::where('id', $indikator_id)->first();
        $fokus_intervensi = FokusIntervensi::all();
        if (!$indikator) {
            abort(404);
        }
        return view('rb-tematik.monev', compact('indikator'));
    }

    public function simpanMonevIndikatorRoadmap(Request $request)
    {
        $user = Auth::User();
        $indikatorRoadmap = TematikIndikatorRoadmap::where('id', $request->monev_indikator_roadmap_id)->first();
        if (!$indikatorRoadmap) {
            abort(404);
        }
        if (Gate::denies('modify-indikator-roadmap', $indikatorRoadmap)) {
            // Unauthorized, handle accordingly
            abort(403, 'Unauthorized');
        }

        $indikatorRoadmap->realisasi_indikator = $request->realisasi_indikator;
        $indikatorRoadmap->capaian_indikator = $request->capaian_indikator;
        $indikatorRoadmap->catatan = $request->catatan;

        if ($indikatorRoadmap->save()) {
            session()->flash('success', 'Data Indikator Roadmap Tematik berhasil disimpan.');
        } else {
            session()->flash('success', 'Data Indikator Roadmap Tematik gagal disimpan! Silahkan dicoba kembali.');
        }
        return redirect('rb-tematik/perencanaan');
    }

    public function monev_getIndikatorPermasalahan($indikator_id)
    {
        $user = Auth::User();
        $target = TematikIndikatorPermasalahan::where('id', $indikator_id)->first();
        if (!$target) {
            abort(404);
        }
        return response()->json($target);
    }

    public function monev_simpanIndikatorPermasalahan($indikator_id, Request $request)
    {
        $indikator = TematikIndikatorPermasalahan::where('id', $indikator_id)->first();
        if (!$indikator) {
            abort(403);
        }
        if (Gate::denies('modify-indikator', $indikator)) {
            // Unauthorized, handle accordingly
            abort(403, 'Unauthorized');
        }
        $success = false;
        $indikator->realisasi_indikator = $request->realisasi_indikator;
        $indikator->capaian_indikator = $request->capaian_indikator;
        $indikator->catatan = $request->catatan;
        if ($indikator->save()) {
            $success = true;
        }
        return response()->json(compact('success', 'indikator'));
    }

    public function monev_simpan($indikator_id, Request $request)
    {
        $user = Auth::User();
        $success = false;
        $output = TematikRencanaAksiOutput::find($request->output_id);
        if (!$output) {
            abort(404);
        }
        if (Gate::denies('modify-rencana-aksi', $output->rencana_aksi)) {
            // Unauthorized, handle accordingly
            abort(403, 'Unauthorized');
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
        $output->capaian_output_tw1 = $request->capaian_output_tw1;
        $output->capaian_output_tw2 = $request->capaian_output_tw2;
        $output->capaian_output_tw3 = $request->capaian_output_tw3;
        $output->capaian_output_tw4 = $request->capaian_output_tw4;
        $output->capaian_output_total = $request->capaian_output_total;
        $output->capaian_anggaran_tw1 = $request->capaian_anggaran_tw1;
        $output->capaian_anggaran_tw2 = $request->capaian_anggaran_tw2;
        $output->capaian_anggaran_tw3 = $request->capaian_anggaran_tw3;
        $output->capaian_anggaran_tw4 = $request->capaian_anggaran_tw4;
        $output->capaian_anggaran_total = $request->capaian_anggaran_total;
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
