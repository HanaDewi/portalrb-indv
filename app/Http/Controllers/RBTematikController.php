<?php

namespace App\Http\Controllers;


use App\Exports\ExportRBTematikTemplate;
use App\Imports\ImportRBTematik;
use App\Models\FokusIntervensi;
use Illuminate\Http\Request;
use App\Models\Tema;
use App\Models\TematikSasaranRoadmap;
use App\Models\TematikIndikatorRoadmap;
use App\Models\TematikIndikatorPermasalahan;
use App\Models\TematikRencanaAksi;
use App\Models\TematikRencanaAksiOutput;
use App\Models\KlpdInstansi;
use App\Models\OpenAccessSetting;
use App\Models\TematikPermasalahan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;



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
        if (in_array($user->level, ['kabupaten', 'provinsi', 'kl'])) {
            $access = OpenAccessSetting::where('user_level', $user->level)->where('fitur', 'rencana_aksi')->first();
            if ($access) {
                $today = date('Y-m-d');
                if ($access->waktu_awal > $today || $access->waktu_akhir < $today) {
                    return view('belumbuka');
                }
            }
        }
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


    public function permasalahan(Request $request)
    {
        $user = Auth::User();
        if (in_array($user->level, ['kabupaten', 'provinsi', 'kl'])) {
            $access = OpenAccessSetting::where('user_level', $user->level)->where('fitur', 'rencana_aksi')->first();
            if ($access) {
                $today = date('Y-m-d');
                if ($access->waktu_awal > $today || $access->waktu_akhir < $today) {
                    return view('belumbuka');
                }
            }
        }
        $temas = Tema::get();

        $ftema = $request->get('ftema');
        $fsasaranroadmap = $request->get('fsasaranroadmap');
        $findikatorroadmap = $request->get('findikatorroadmap');

        $filterSasaranRoadmap = TematikSasaranRoadmap::where('instansi_id', $user->user_rel->instansi_id)->where('tema_id', $ftema)->orderBy('tema_id')->get();
        if ($fsasaranroadmap) {
            $queryfilterIndikatorRoadmap = TematikIndikatorRoadmap::where('tematik_sasaran_roadmap_id', $fsasaranroadmap);
            $filterIndikatorRoadmap = $queryfilterIndikatorRoadmap->get();
        } else {
            $filterIndikatorRoadmap = [];
        }

        $ftarget = '-';
        $fsatuantarget = '-';
        $findikators = [];
        if ($findikatorroadmap) {
            $select = TematikIndikatorRoadmap::where('id', $findikatorroadmap)->first();
            if ($select) {
                $ftarget = $select->target;
                $fsatuantarget = $select->satuan;
                $findikators = [$select->id];
            }
        }

        $querysasaranRoadmaps = TematikSasaranRoadmap::where('instansi_id', $user->user_rel->instansi_id);
        if ($ftema) {
            $querysasaranRoadmaps->where('tema_id', $ftema);
        }
        if ($fsasaranroadmap) {
            $querysasaranRoadmaps->where('id', $fsasaranroadmap);
        }
        $sasaranRoadmaps = $querysasaranRoadmaps->orderBy('tema_id')->get();

        $tematikDatas = [];
        $jumlahBaris = 0;

        foreach ($sasaranRoadmaps as $sasaran) {
            $check = $sasaran->indikator_roadmap($findikators)->get();
            if (count($check)) {
                foreach ($check as $indikator) {
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
            "ftema" => $ftema,
            "fsasaranroadmap" => $fsasaranroadmap,
            "findikatorroadmap" => $findikatorroadmap,
            "ftarget" => $ftarget,
            "fsatuantarget" => $fsatuantarget,
            "temas" => $temas,
            "filterSasaranRoadmap" => $filterSasaranRoadmap,
            "filterIndikatorRoadmap" => $filterIndikatorRoadmap,
            "sasaranRoadmaps" => $sasaranRoadmaps,
            "tematikDatas" => $tematikDatas
        ]);
    }

    public function simpanSasaranRoadmap(Request $request)
    {
        $user = Auth::User();
        $sasaranRoadmap = TematikSasaranRoadmap::where('instansi_id', $user->user_rel->instansi_id)->where('id', $request->sasaran_roadmap_id)->first();
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
                    return redirect('rencana_aksi/rb-tematik/perencanaan');
                }
            }
        } else {
            $sasaranRoadmap->tema_id = $request->tema_id[0];
            $sasaranRoadmap->nama = $request->nama[0];
            if ($sasaranRoadmap->save()) {
                session()->flash('success', 'Data Sasaran Roadmap Tematik berhasil disimpan.');
            } else {
                session()->flash('success', 'Data Sasaran Roadmap Tematik gagal disimpan! Silahkan dicoba kembali.');
                return redirect('rencana_aksi/rb-tematik/perencanaan');
            }
        }


        return redirect('rencana_aksi/rb-tematik/perencanaan');
    }

    public function sasaranRoadmapHapus(Request $request)
    {
        $pesan = '';

        $user = Auth::User();
        $sasaranRoadmap = TematikSasaranRoadmap::where('id', $request->id)->first();
        if (!$sasaranRoadmap) {
            abort(404);
        }
        if (Gate::denies('modify-sasaran-roadmap', $sasaranRoadmap)) {
            // Unauthorized, handle accordingly
            abort(403, 'Unauthorized');
        }
        $success = false;
        if ($sasaranRoadmap->indikator_roadmap->count() > 0) {
            // Haspus dulu permasalhan sebelum hapus indikatorRoadmap
            $pesan = 'Hapus dahulu indikator roadmap yang terkait dengan sasaran roadmap ini';
            $success = false;
            return response()->json(['success' => $success, 'pesan' => $pesan]);
        } else {
            DB::beginTransaction();
            try {
                if ($sasaranRoadmap->delete()) {
                    $success = true;
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
        return redirect('rencana_aksi/rb-tematik/perencanaan');
    }

    public function getIndikatorRoadmap()
    {
        $tematik_sasaran_roadmap_id = request()->get('tematik_sasaran_roadmap_id');
        $indikators = TematikIndikatorRoadmap::where('tematik_sasaran_roadmap_id', $tematik_sasaran_roadmap_id)->get();
        return response()->json($indikators);
    }

    public function indikatorRoadmapHapus(Request $request)
    {
        $pesan = '';

        $user = Auth::User();
        $indikatorRoadmap = TematikIndikatorRoadmap::where('id', $request->id)->first();
        if (!$indikatorRoadmap) {
            abort(404);
        }
        if (Gate::denies('modify-indikator-roadmap', $indikatorRoadmap)) {
            // Unauthorized, handle accordingly
            abort(403, 'Unauthorized');
        }
        $success = false;
        if ($indikatorRoadmap->permasalahan->count() > 0) {
            // Haspus dulu permasalhan sebelum hapus indikatorRoadmap
            $pesan = 'Hapus dahulu permalasahan yang terkait dengan indikator roadmap ini';
            $success = false;
            return response()->json(['success' => $success, 'pesan' => $pesan]);
        } else {
            DB::beginTransaction();
            try {
                if ($indikatorRoadmap->delete()) {
                    $success = true;
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
    }

    public function sasaran_getData($sasaran_id)
    {
        $sasaran_roadmap = TematikSasaranRoadmap::where('id', $sasaran_id)->first();
        $data = [
            "tema_id" => $sasaran_roadmap->tema->id,
            "tema" => $sasaran_roadmap->tema->nama,
            "sasaran_roadmap_id" => $sasaran_roadmap->nama,
            "sasaran_roadmap" => $sasaran_roadmap->nama,
        ];
        return response()->json($data);
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
        $permasalahan = TematikPermasalahan::where('id', $request->permasalahan_id)->first();
        if (!$permasalahan) {
            $permasalahan = new TematikPermasalahan();
        }
        ;
        $permasalahan->tematik_indikator_roadmap_id = $request->tematik_indikator_roadmap_id;


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
        return redirect('rencana_aksi/rb-tematik/permasalahan');
    }

    public function get_permasalahan($permasalahan_id)
    {
        $permasalahan = TematikPermasalahan::where('id', $permasalahan_id)->first();
        $permasalahan_lengkap = [
            "sasaran_roadmap_id" => $permasalahan->indikator_roadmap->sasaran_roadmap->id,
            "sasaran_roadmap" => $permasalahan->indikator_roadmap->sasaran_roadmap->nama,
            "indikator_roadmap_id" => $permasalahan->indikator_roadmap->id,
            "indikator_roadmap" => $permasalahan->indikator_roadmap->nama,
            "target_roadmap" => $permasalahan->indikator_roadmap->target,
            "target_satuan_roadmap" => $permasalahan->indikator_roadmap->satuan,
            "permasalahan_id" => $permasalahan->id,
            "permasalahan_nama" => $permasalahan->nama,
            "permasalahan_sasaran" => $permasalahan->sasaran_permasalahan,
        ];
        return response()->json($permasalahan_lengkap);
    }

    public function permasalahanHapus(Request $request)
    {
        $pesan = '';

        $user = Auth::User();
        $permasalahan = TematikPermasalahan::where('id', $request->id)->first();
        if (!$permasalahan) {
            abort(404);
        }
        if (Gate::denies('modify-permasalahan', $permasalahan)) {
            // Unauthorized, handle accordingly
            abort(403, 'Unauthorized');
        }
        $success = false;
        if ($permasalahan->indikator_permasalahan->count() > 0) {
            $pesan = 'Hapus dahulu indikator permasalahan yang terkait dengan permasalahan ini';
            $success = false;
            return response()->json(['success' => $success, 'pesan' => $pesan]);
        } else {
            DB::beginTransaction();
            try {
                if ($permasalahan->delete()) {
                    $success = true;
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
    }

    public function simpanIndikatorPermasalahan(Request $request)
    {
        $indikatorPermasalahan = TematikIndikatorPermasalahan::where('id', $request->tematik_indikator_permasalahan_id)->first();
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
        return redirect('rencana_aksi/rb-tematik/permasalahan');
    }

    public function indikatorPermasalahanHapus(Request $request)
    {
        $pesan = '';

        $user = Auth::User();
        $indikatorPermasalahan = TematikIndikatorPermasalahan::where('id', $request->id)->first();
        if (!$indikatorPermasalahan) {
            abort(404);
        }
        if (Gate::denies('modify-indikator', $indikatorPermasalahan)) {
            // Unauthorized, handle accordingly
            abort(403, 'Unauthorized');
        }
        $success = false;
        if ($indikatorPermasalahan->rencana_aksi->count() > 0) {
            $pesan = 'Hapus dahulu rencana aksi yang terkait dengan indikator ini';
            $success = false;
            return response()->json(['success' => $success, 'pesan' => $pesan]);
        } else {
            DB::beginTransaction();
            try {
                if ($indikatorPermasalahan->delete()) {
                    $success = true;
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
    }

    public function get_indikator_permasalahan($indikator_id)
    {
        $indikator_permasalahan = TematikIndikatorPermasalahan::where('id', $indikator_id)->first();
        $indikator_permasalahan_lengkap = [
            "permasalahan" => $indikator_permasalahan->permasalahan->nama,
            "sasaran" => $indikator_permasalahan->permasalahan->sasaran_permasalahan,
            "indikator_permasalahan_id" => $indikator_permasalahan->id,
            "indikator_permasalahan_nama" => $indikator_permasalahan->nama,
            "indikator_permasalahan_target" => $indikator_permasalahan->target,
            "indikator_permasalahan_satuan" => $indikator_permasalahan->satuan,
        ];
        return response()->json($indikator_permasalahan_lengkap);
    }

    public function rencana_aksi($indikator_id)
    {
        $user = Auth::User();
        if (in_array($user->level, ['kabupaten', 'provinsi', 'kl'])) {
            $access = OpenAccessSetting::where('user_level', $user->level)->where('fitur', 'rencana_aksi')->first();
            if ($access) {
                $today = date('Y-m-d');
                if ($access->waktu_awal > $today || $access->waktu_akhir < $today) {
                    return view('belumbuka');
                }
            }
        }
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
            if ($rencana_aksi->output->count()) {
                foreach ($rencana_aksi->output as $output) {
                    $output->no = $no;
                    if (isset($output->get_intervensi->nama)) {
                        $output->nama_intervensi = $output->get_intervensi->nama;
                    } else {
                        $output->nama_intervensi = "";
                    }

                    $output->nama_rencana_aksi = $output->rencana_aksi->nama;
                    $output->target_total = $output->target_total;
                    $output->anggaran_total = currency($output->anggaran_total);
                    $outputs[] = $output;
                    $no++;
                }
            } else {
                $renaksi = [
                    "no" => $no,
                    "tematik_rencana_aksi_id" => $rencana_aksi->id,
                    "rencana_aksi" => [
                        "nama" => $rencana_aksi->nama
                    ],
                    "satuan_output" => "",
                    "indikator_output" => "",
                    "target_tw1" => 0,
                    "target_tw2" => 0,
                    "target_tw3" => 0,
                    "target_tw4" => 0,
                    "target_total" => 0,
                    "anggaran_total" => "Rp. 0",
                    "pelaksana" => "-",
                    "koordinator" => "-",
                    "realisasi_output_tw1" => 0,
                    "realisasi_output_tw2" => 0,
                    "realisasi_output_tw3" => 0,
                    "realisasi_output_tw4" => 0,
                    "realisasi_output_total" => 4,
                    "realisasi_anggaran_total" => "Rp 0",
                    "capaian_output_tw1" => null,
                    "capaian_output_tw2" => null,
                    "capaian_output_tw3" => null,
                    "capaian_output_tw4" => null,
                    "capaian_output_total" => null,
                    "capaian_anggaran_tw1" => null,
                    "capaian_anggaran_tw2" => null,
                    "capaian_anggaran_tw3" => null,
                    "capaian_anggaran_tw4" => null,
                    "capaian_anggaran_total" => null,
                    "nama_intervensi" => "-",
                ];
                $outputs[] = $renaksi;
                $no++;
            }


        }
        return response()->json(['data' => $outputs]);
    }

    public function rencana_aksi_getData($id)
    {
        $output = TematikRencanaAksiOutput::find($id);
        if ($output) {
            $output->rencana_aksi = $output->rencana_aksi;
        }
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
            $rencana_aksi = TematikRencanaAksi::where('tematik_indikator_permasalahan_id', $indikator->id)->where('id', $request->rencana_aksi_id)->first();
            
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
                    $rencana_aksi_output->target_tw1 = str_replace(',', '.', str_replace('.', '', $target_output['target_tw1']));
                    $rencana_aksi_output->target_tw2 = str_replace(',', '.', str_replace('.', '', $target_output['target_tw2']));
                    $rencana_aksi_output->target_tw3 = str_replace(',', '.', str_replace('.', '', $target_output['target_tw3']));
                    $rencana_aksi_output->target_tw4 = str_replace(',', '.', str_replace('.', '', $target_output['target_tw4']));
                    $rencana_aksi_output->target_total = str_replace(',', '.', str_replace('.', '', $target_output['target_total']));
                    $rencana_aksi_output->anggaran_total = $this->removeDot($target_output['anggaran_total']);
                    $rencana_aksi_output->fokus_intervensi = $target_output['fokus_intervensi'];
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
        $user = Auth::User();
        if (in_array($user->level, ['kabupaten', 'provinsi', 'kl'])) {
            $access = OpenAccessSetting::where('user_level', $user->level)->where('fitur', 'rencana_aksi')->first();
            if ($access) {
                $today = date('Y-m-d');
                if ($access->waktu_awal > $today || $access->waktu_akhir < $today) {
                    return view('belumbuka');
                }
            }
        }
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
        return redirect('rencana_aksi/rb-tematik/perencanaan');
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
        $indikator->realisasi_indikator = $this->removeDot($request->realisasi_indikator);
        $indikator->capaian_indikator = $this->removeDot($request->capaian_indikator);
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
        $output->realisasi_output_tw1 = $this->removeDot($request->realisasi_output_tw1);
        $output->realisasi_output_tw2 = $this->removeDot($request->realisasi_output_tw2);
        $output->realisasi_output_tw3 = $this->removeDot($request->realisasi_output_tw3);
        $output->realisasi_output_tw4 = $this->removeDot($request->realisasi_output_tw4);
        $output->realisasi_output_total = $this->removeDot($request->realisasi_output_total);
        //$output->realisasi_anggaran_tw1 = $this->removeDot($request->realisasi_anggaran_tw1);
        // $output->realisasi_anggaran_tw2 = $this->removeDot($request->realisasi_anggaran_tw2);
        // $output->realisasi_anggaran_tw3 = $this->removeDot($request->realisasi_anggaran_tw3);
        // $output->realisasi_anggaran_tw4 = $this->removeDot($request->realisasi_anggaran_tw4);
        $output->realisasi_anggaran_total = $this->removeDot($request->realisasi_anggaran_total);
        $output->capaian_output_tw1 = $this->removeDot($request->capaian_output_tw1);
        $output->capaian_output_tw2 = $this->removeDot($request->capaian_output_tw2);
        $output->capaian_output_tw3 = $this->removeDot($request->capaian_output_tw3);
        $output->capaian_output_tw4 = $this->removeDot($request->capaian_output_tw4);
        $output->capaian_output_total = $this->removeDot($request->capaian_output_total);
        $output->capaian_anggaran_tw1 = $this->removeDot($request->capaian_anggaran_tw1);
        $output->capaian_anggaran_tw2 = $this->removeDot($request->capaian_anggaran_tw2);
        $output->capaian_anggaran_tw3 = $this->removeDot($request->capaian_anggaran_tw3);
        $output->capaian_anggaran_tw4 = $this->removeDot($request->capaian_anggaran_tw4);
        $output->capaian_anggaran_total = $this->removeDot($request->capaian_anggaran_total);
        $output->catatan = $this->removeDot($request->catatan);
        if ($output->save()) {
            $success = true;
        }
        return response()->json(['success' => $success]);
    }

    public function rekap_data(Request $request)
    {
        $user = Auth::User();
        if (in_array($user->level, ['kabupaten', 'provinsi', 'kl'])) {
            $access = OpenAccessSetting::where('user_level', $user->level)->where('fitur', 'rencana_aksi')->first();
            if ($access) {
                $today = date('Y-m-d');
                if ($access->waktu_awal > $today || $access->waktu_akhir < $today) {
                    return view('belumbuka');
                }
            }
        }
        $temas = Tema::get();

        $finstansi = $request->get('instansi_id');
        $ftema = $request->get('ftema');
        if ($finstansi==null && in_array($user->level, ['admin', 'tpn'])) {
            $finstansi = [1];
        }
        if ($ftema==null) {
            $ftema = [];
        }
        if (!is_array($finstansi)) {
            $finstansi = [ $finstansi ];
        }
        if (!is_array($ftema)) {
            $ftema = [ $ftema ];
        }
        $fsasaranroadmap = $request->get('fsasaranroadmap');
        $findikatorroadmap = $request->get('findikatorroadmap');
        $fpermasalahan = $request->get('fpermasalahan');
        $fintervensi = $request->get('fintervensi');

        if (in_array($user->level, ['admin', 'tpn'])) {
            $instansi_id = $finstansi;
        } else {
            if ($request->instansi_id && in_array($user->level, ['admin', 'tpn'])) {
                $instansi_id = [$request->instansi_id];
            } else if ($user->user_rel->instansi_id) {
                $instansi_id = [$user->user_rel->instansi_id];
            } else {
                $instansi_id = [KlpdInstansi::orderBy('id')->first()->id];
            }
        }

        if (count($instansi_id) > 0) {
            $filterSasaranRoadmap = TematikSasaranRoadmap::whereIn('instansi_id', $instansi_id)->whereIn('tema_id', $ftema)->orderBy('tema_id')->get();
            $daftar_instansi = KlpdInstansi::whereIn('id', $instansi_id)->get()->toArray();
            $nama_ins = array_column($daftar_instansi, 'name');
            $nama_instansi = join(', ', $nama_ins);
        } else {
            $filterSasaranRoadmap = TematikSasaranRoadmap::whereIn('tema_id', $ftema)->orderBy('tema_id')->get();
            $nama_instansi = '';
        }
        if ($fsasaranroadmap) {
            $queryfilterIndikatorRoadmap = TematikIndikatorRoadmap::where('tematik_sasaran_roadmap_id', $fsasaranroadmap);
            $filterIndikatorRoadmap = $queryfilterIndikatorRoadmap->get();
        } else {
            $filterIndikatorRoadmap = [];
        }

        if ($findikatorroadmap) {
            $filterTematikPermasalahan = TematikPermasalahan::where('tematik_indikator_roadmap_id', $findikatorroadmap)->get();
        } else {
            $filterTematikPermasalahan = [];
        }

        if ($instansi_id) {
            $model = TematikSasaranRoadmap::whereIn('instansi_id', $instansi_id)->orderBy('tema_id')->orderBy('id');
        } else {
            $model = TematikSasaranRoadmap::orderBy('tema_id')->orderBy('id');
        }

        if (count($ftema) > 0) {
            $model->whereIn('tema_id', $ftema);
        }

        $sasarans = $model->get();
        $key = 0;
        $datas = [];

        $indikators = [];
        foreach ($filterIndikatorRoadmap as $finr) {
            $indikators[] = $finr->id;
        }
        if ($findikatorroadmap) {
            $indikators = [$findikatorroadmap];
        }

        $masalahs = [];
        foreach ($filterTematikPermasalahan as $fmas) {
            $masalahs[] = $fmas->id;
        }
        if ($fpermasalahan) {
            $masalahs = [$fpermasalahan];
        }

        foreach ($sasarans as $sasaran) {
            $dataindikators = $sasaran->indikator_roadmap($indikators)->get();
            if (count($dataindikators)) {
                foreach ($dataindikators as $indikator_roadmap) {
                    $datamasalah = $indikator_roadmap->permasalahan($masalahs)->get();
                    if (count($datamasalah)) {
                        foreach ($datamasalah as $permasalahan) {
                            if (count($permasalahan->indikator_permasalahan)) {
                                foreach ($permasalahan->indikator_permasalahan as $indikator_permasalahan) {
                                    if (count($indikator_permasalahan->rencana_aksi)) {
                                        foreach ($indikator_permasalahan->rencana_aksi as $rencana_aksi) {
                                            if (!empty($fintervensi)) {
                                                $ra_output = $rencana_aksi->output([$fintervensi])->get();
                                            }else{
                                                $ra_output = $rencana_aksi->output()->get();
                                            }
                                            if (count($ra_output)) {
                                                foreach ($ra_output as $output) {
                                                    $datas[$key]['sasaran_roadmap'] = $sasaran;
                                                    $datas[$key]['indikator_roadmap'] = $indikator_roadmap;
                                                    $datas[$key]['permasalahan'] = $permasalahan;
                                                    $datas[$key]['indikator_permasalahan'] = $indikator_permasalahan;
                                                    $datas[$key]['rencana_aksi'] = $rencana_aksi;
                                                    $datas[$key]['output'] = $output;
                                                    $key++;
                                                }
                                            } else {
                                                $datas[$key]['sasaran_roadmap'] = $sasaran;
                                                $datas[$key]['indikator_roadmap'] = $indikator_roadmap;
                                                $datas[$key]['permasalahan'] = $permasalahan;
                                                $datas[$key]['indikator_permasalahan'] = $indikator_permasalahan;
                                                $datas[$key]['rencana_aksi'] = $rencana_aksi;
                                                $datas[$key]['output'] = new TematikRencanaAksiOutput();
                                                $key++;
                                            }
                                        }
                                    } else {
                                        $datas[$key]['sasaran_roadmap'] = $sasaran;
                                        $datas[$key]['indikator_roadmap'] = $indikator_roadmap;
                                        $datas[$key]['permasalahan'] = $permasalahan;
                                        $datas[$key]['indikator_permasalahan'] = $indikator_permasalahan;
                                        $datas[$key]['rencana_aksi'] = new TematikRencanaAksi();
                                        $datas[$key]['output'] = new TematikRencanaAksiOutput();
                                        $key++;
                                    }
                                }
                            } else {
                                $datas[$key]['sasaran_roadmap'] = $sasaran;
                                $datas[$key]['indikator_roadmap'] = $indikator_roadmap;
                                $datas[$key]['permasalahan'] = $permasalahan;
                                $datas[$key]['indikator_permasalahan'] = new TematikIndikatorPermasalahan();
                                $datas[$key]['rencana_aksi'] = new TematikRencanaAksi();
                                $datas[$key]['output'] = new TematikRencanaAksiOutput();
                                $key++;
                            }
                        }
                    } else {
                        $datas[$key]['sasaran_roadmap'] = $sasaran;
                        $datas[$key]['indikator_roadmap'] = $indikator_roadmap;
                        $datas[$key]['permasalahan'] = new TematikPermasalahan();
                        $datas[$key]['indikator_permasalahan'] = new TematikIndikatorPermasalahan();
                        $datas[$key]['rencana_aksi'] = new TematikRencanaAksi();
                        $datas[$key]['output'] = new TematikRencanaAksiOutput();
                        $key++;
                    }
                }
            } else {
                $datas[$key]['sasaran_roadmap'] = $sasaran;
                $datas[$key]['indikator_roadmap'] = new TematikIndikatorRoadmap();
                $datas[$key]['permasalahan'] = new TematikPermasalahan();
                $datas[$key]['indikator_permasalahan'] = new TematikIndikatorPermasalahan();
                $datas[$key]['rencana_aksi'] = new TematikRencanaAksi();
                $datas[$key]['output'] = new TematikRencanaAksiOutput();
                $key++;
            }
        }
        return view(
            'rb-tematik.rekap_data',
            compact(
                'datas',
                'instansi_id',
                'nama_instansi',
                'temas',
                'filterSasaranRoadmap',
                'filterIndikatorRoadmap',
                'filterTematikPermasalahan',
                'finstansi',
                'ftema',
                'fsasaranroadmap',
                'findikatorroadmap',
                'fpermasalahan',
                'fintervensi'
            )
        );
    }

    public function removeDot($i)
    {
        $format_angka = str_replace(',', '.', str_replace('.', '', $i));
        return $format_angka;
    }
}
