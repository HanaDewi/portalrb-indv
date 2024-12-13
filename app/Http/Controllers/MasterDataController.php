<?php

namespace App\Http\Controllers;

use App\Models\DokumenKategori;
use App\Models\Indikator;
use App\Models\KegiatanUtama;
use App\Models\LKE\LkeBobot;
use App\Models\LKE\LkeParameter;
use App\Models\Tahun;
use App\Models\Tema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterDataController extends Controller
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

    public function kegiatan_utama()
    {
        return view('master-data.kegiatan_utama');
    }

    public function kegiatan_utama_getDatas()
    {
        $datas = KegiatanUtama::latest()->get();
        return response()->json(['data' => $datas]);
    }

    public function kegiatan_utama_getData($id)
    {
        $data = KegiatanUtama::find($id);
        return $data;
    }

    public function kegiatan_utama_simpan(Request $request)
    {
        $success = false;
        $kegiatan_utama = new KegiatanUtama();
        if ($request->kegiatan_utama_id) {
            $kegiatan_utama = KegiatanUtama::find($request->kegiatan_utama_id);
        }
        $kegiatan_utama->nama = $request->nama;
        if ($kegiatan_utama->save()) {
            $success = true;
        };
        return response()->json(['success' => $success]);
    }

    public function kegiatan_utama_hapus(Request $request)
    {
        $pesan = '';
        $success = true;
        $kegiatan_utama = KegiatanUtama::find($request->id);
        if (count($kegiatan_utama->indikators) > 0) {
            $pesan = 'Kegiatan Utama tidak bisa dihapus, silahkan hapus dulu Indikator yang menggunakan Kegiatan Utama ini!';
            $success = false;
        } else {
            if ($kegiatan_utama->delete()) {
                $success = true;
            } else {
                $success = false;
            }
        }
        return response()->json(['success' => $success, 'pesan' => $pesan]);
    }

    public function indikator()
    {
        return view('master-data.indikator');
    }

    public function indikator_getDatas()
    {
        $datas = Indikator::orderBy('kegiatan_utama_id')->get();
        foreach ($datas as $data) {
            $data->nama_kegiatan_utama = $data->kegiatan_utama->nama;
            $pengguna_indikator = [];
            if ($data->kl == 1) {
                $pengguna_indikator[] = 'Kementrian/Lembaga';
            }
            if ($data->provinsi == 1) {
                $pengguna_indikator[] = 'Provinsi';
            }
            if ($data->kabupaten == 1) {
                $pengguna_indikator[] = 'Kabupaten';
            }
            if (count($pengguna_indikator)) {
                $data->pengguna_indikator = implode('<br>', $pengguna_indikator);
            } else {
                $data->pengguna_indikator = '';
            }
        }
        return response()->json(['data' => $datas]);
    }

    public function indikator_getData($id)
    {
        $data = Indikator::find($id);
        return $data;
    }

    public function indikator_simpan(Request $request)
    {
        $success = true;
        DB::beginTransaction();
        try {
            foreach ($request->nama as $key => $nama) {
                $indikator = new Indikator();
                if (isset($request->indikator_id)) {
                    $indikator = Indikator::find($request->indikator_id);
                }
                $indikator->kegiatan_utama_id = $request->kegiatan_utama_id;
                $indikator->nama = $nama;
                $indikator->tipe = $request->tipe[$key];
                if (isset($request->min[$key]) && isset($request->max[$key])) {
                    $indikator->min = $request->min[$key];
                    $indikator->max = $request->max[$key];
                }
                $indikator->kl = isset($request->kl[$key]) ? 1 : 0;
                $indikator->provinsi = isset($request->provinsi[$key]) ? 1 : 0;
                $indikator->kabupaten = isset($request->kabupaten[$key]) ? 1 : 0;
                if (!$indikator->save()) {
                    $success = false;
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

    public function indikator_hapus(Request $request)
    {
        $indikator = Indikator::find($request->id);
        if ($indikator->delete()) {
            return true;
        } else {
            return false;
        }
    }

    public function tema()
    {
        return view('master-data.tema');
    }

    public function tema_getDatas()
    {
        $datas = Tema::latest()->get();
        return response()->json(['data' => $datas]);
    }

    public function tema_getData($id)
    {
        $data = Tema::find($id);
        return $data;
    }

    public function tema_simpan(Request $request)
    {
        $success = false;
        $tema = new Tema();
        if ($request->tema_id) {
            $tema = Tema::find($request->tema_id);
        }
        $tema->nama = $request->nama;
        if ($tema->save()) {
            $success = true;
        };
        return response()->json(['success' => $success]);
    }

    public function tema_hapus(Request $request)
    {
        $tema = Tema::find($request->id);
        if ($tema->delete()) {
            return true;
        } else {
            return false;
        }
    }

    public function dokumen()
    {
        return view('master-data.dokumen');
    }

    public function dokumen_getDataTahun()
    {
        $datas = Tahun::orderBy('tahun', 'desc')->get();
        return response()->json(['data' => $datas]);
    }

    public function dokumen_simpanTahun(Request $request)
    {
        $success = false;
        $tahun = Tahun::where('tahun', $request->tahun)->first();
        if (!$tahun) {
            $tahun = new Tahun();
        }
        $tahun->tahun = $request->tahun;
        if ($tahun->save()) {
            $success = true;
        };
        return response()->json(['success' => $success]);
    }

    public function dokumen_hapusTahun(Request $request)
    {
        $tahun = Tahun::where('tahun', $request->tahun);
        if ($tahun->delete()) {
            return true;
        } else {
            return false;
        }
    }

    public function dokumen_getDataKategoris()
    {
        $datas = DokumenKategori::all();
        return response()->json(['data' => $datas]);
    }

    public function dokumen_getDataKategori($id)
    {
        $data = DokumenKategori::find($id);
        return $data;
    }

    public function dokumen_simpanKategori(Request $request)
    {
        $success = false;
        $kategori = new DokumenKategori();
        if ($request->kategori_id) {
            $kategori = DokumenKategori::find($request->kategori_id);
        }
        $kategori->nama = $request->nama;
        if ($kategori->save()) {
            $success = true;
        };
        return response()->json(['success' => $success]);
    }

    public function dokumen_hapusKategori(Request $request)
    {
        $kategori = DokumenKategori::find($request->id);
        if ($kategori->delete()) {
            return true;
        } else {
            return false;
        }
    }

    public function lke_parameter()
    {
        return view('master-data.lke_parameter');
    }

    public function lke_parameter_getDatas()
    {
        $parameters = LkeParameter::get();
        foreach ($parameters as $parameter) {
            $parameter->parent = $parameter->parent;
            $parameter->nama_kegiatan = '['.$parameter->kegiatan->tahun.'] '.$parameter->kegiatan->nama;
            $parameter->tim_penilai = $parameter->penilai_id ? '['.strtoupper($parameter->penilai->tipe).'] '.$parameter->penilai->nama.' ('.$parameter->penilai->kode.')' : '';
            $parameter->indikator_pengali = $parameter->indikator_pengali;
            $parameter->bobot = '';
            if ($parameter->level == 'Indikator') {
                if ($parameter->bobots) {
                    $bobotnya = [];
                    foreach ($parameter->bobots as $key => $bobot) {
                        $bobotnya[$key] = '<strong>Group : </strong>'.group_instansi($bobot->group).'<br>';
                        $bobotnya[$key] .= '<strong>Target Baik : </strong>'.$bobot->target_baik.'<br>';
                        $bobotnya[$key] .= '<strong>Min Value : </strong>'.$bobot->min_value.'<br>';
                        $bobotnya[$key] .= '<strong>Max Value : </strong>'.$bobot->max_value.'<br>';
                        $bobotnya[$key] .= '<strong>Bobot : </strong>'.$bobot->bobot.'<br>';
                    }
                    $parameter->bobot = implode('<hr>', $bobotnya);
                }
                if ($parameter->rencana_aksi) {
                    $parameter->bobot .= '<hr><strong><i>Rencana Aksi</i></strong>';
                }
            } else if ($parameter->level == 'Sub Komponen') {
                $parameter_ids = LkeParameter::where('level', 'Indikator')->where('parent_id', $parameter->id)->pluck('id');
                $bobot_kl = LkeBobot::whereIn('lke_parameter_id', $parameter_ids)->where('group', 'kl')->sum('bobot');
                $parameter->bobot .= '<strong>'.group_instansi('kl').': </strong>'.$bobot_kl.'<br>';
                $bobot_provinsi = LkeBobot::whereIn('lke_parameter_id', $parameter_ids)->where('group', 'provinsi')->sum('bobot');
                $parameter->bobot .= '<strong>'.group_instansi('provinsi').': </strong>'.$bobot_provinsi.'<br>';
                $bobot_kabupaten = LkeBobot::whereIn('lke_parameter_id', $parameter_ids)->where('group', 'kabupaten')->sum('bobot');
                $parameter->bobot .= '<strong>'.group_instansi('kabupaten').': </strong>'.$bobot_kabupaten;
            } else if ($parameter->level == 'Komponen') {
                $sub_parameter_ids = LkeParameter::where('level', 'Sub Komponen')->where('parent_id', $parameter->id)->pluck('id');
                $parameter_ids = LkeParameter::where('level', 'Indikator')->whereIn('parent_id', $sub_parameter_ids)->pluck('id');
                $bobot_kl = LkeBobot::whereIn('lke_parameter_id', $parameter_ids)->where('group', 'kl')->sum('bobot');
                $parameter->bobot .= '<strong>'.group_instansi('kl').': </strong>'.$bobot_kl.'<br>';
                $bobot_provinsi = LkeBobot::whereIn('lke_parameter_id', $parameter_ids)->where('group', 'provinsi')->sum('bobot');
                $parameter->bobot .= '<strong>'.group_instansi('provinsi').': </strong>'.$bobot_provinsi.'<br>';
                $bobot_kabupaten = LkeBobot::whereIn('lke_parameter_id', $parameter_ids)->where('group', 'kabupaten')->sum('bobot');
                $parameter->bobot .= '<strong>'.group_instansi('kabupaten').': </strong>'.$bobot_kabupaten;
            }
        }
        return response()->json(['data' => $parameters]);
    }

    public function lke_parameter_getData($id)
    {
        $data = LkeParameter::find($id);
        $data->bobots = $data->bobots;
        $data->subkomponens = '';
        if ($data->level == 'Indikator') {
            $data->komponen_id = $data->parent->parent_id;
            $data->subkomponen_id = $data->parent_id;
            $data->subkomponens = set_options(parameter('subkomponen', $data->komponen_id), '-- Pilih Sub Komponen --');
            $data->tim_penilai = set_options(timpenilai());
            $data->indikators = set_options(indikator($data->parent->parent->id, $data->id), '-- Pilih Indikator Pengali --');
        } else if ($data->level == 'Sub Komponen') {
            $data->komponen_id = $data->parent_id;
        }
        return response()->json($data);
    }

    public function lke_parameter_getSubKomponen($komponen_id)
    {
        return set_options(parameter('subkomponen', $komponen_id), '-- Pilih Sub Komponen --');
    }

    public function lke_parameter_getIndikatorPengali($komponen_id)
    {
        return set_options(indikator($komponen_id), '-- Pilih Indikator Pengali --');
    }

    public function lke_parameter_simpan(Request $request)
    {
        $success = true;
        DB::beginTransaction();
        try {
            $lke_parameter = new LkeParameter();
            if (isset($request->lke_parameter_id)) {
                $lke_parameter = LkeParameter::find($request->lke_parameter_id);
            }
            $lke_parameter->nama = $request->nama;
            $lke_parameter->lke_kegiatan_id = $request->kegiatan_id;
            $lke_parameter->level = $request->level;
            if ($request->rencana_aksi) {
                LkeParameter::where('lke_kegiatan_id', $request->kegiatan_id)->update(['rencana_aksi' => 0]);
                $lke_parameter->rencana_aksi = 1;
            } else {
                $lke_parameter->rencana_aksi = 0;
            }
            if ($request->level == 'Sub Komponen') {
                $lke_parameter->parent_id = $request->komponen;
            } else if ($request->level == 'Indikator') {
                $lke_parameter->parent_id = $request->subkomponen;
                $lke_parameter->penilai_id = $request->penilai_id;
                $lke_parameter->indikator_pengali_id = $request->indikator_pengali_id;
            }
            if ($lke_parameter->save()) {
                if ($request->kl) {
                    $lke_bobot = LkeBobot::where('lke_parameter_id', $lke_parameter->id)->where('group', 'kl')->first();
                    if (!$lke_bobot) {
                        $lke_bobot = new LkeBobot();
                    }
                    $lke_bobot->lke_parameter_id = $lke_parameter->id;
                    $lke_bobot->group = 'kl';
                    $lke_bobot->bobot = $request->kl_bobot ? str_replace(',', '.', $request->kl_bobot) : null;
                    $lke_bobot->target_baik = $request->kl_target_baik ? str_replace(',', '.', $request->kl_target_baik) : null;
                    $lke_bobot->min_value = $request->kl_min_value ? str_replace(',', '.', $request->kl_min_value) : null;
                    $lke_bobot->max_value = $request->kl_max_value ? str_replace(',', '.', $request->kl_max_value) : null;
                    if (!$lke_bobot->save()) {
                        $success = false;
                    }
                } else {
                    LkeBobot::where('lke_parameter_id', $lke_parameter->id)->where('group', 'kl')->delete();
                }
                if ($request->provinsi) {
                    $lke_bobot = LkeBobot::where('lke_parameter_id', $lke_parameter->id)->where('group', 'provinsi')->first();
                    if (!$lke_bobot) {
                        $lke_bobot = new LkeBobot();
                    }
                    $lke_bobot->lke_parameter_id = $lke_parameter->id;
                    $lke_bobot->group = 'provinsi';
                    $lke_bobot->bobot = $request->provinsi_bobot ? str_replace(',', '.', $request->provinsi_bobot) : null;
                    $lke_bobot->target_baik = $request->provinsi_target_baik ? str_replace(',', '.', $request->provinsi_target_baik) : null;
                    $lke_bobot->min_value = $request->provinsi_min_value ? str_replace(',', '.', $request->provinsi_min_value) : null;
                    $lke_bobot->max_value = $request->provinsi_max_value ? str_replace(',', '.', $request->provinsi_max_value) : null;
                    if (!$lke_bobot->save()) {
                        $success = false;
                    }
                } else {
                    LkeBobot::where('lke_parameter_id', $lke_parameter->id)->where('group', 'provinsi')->delete();
                }
                if ($request->kabupaten) {
                    $lke_bobot = LkeBobot::where('lke_parameter_id', $lke_parameter->id)->where('group', 'kabupaten')->first();
                    if (!$lke_bobot) {
                        $lke_bobot = new LkeBobot();
                    }
                    $lke_bobot->lke_parameter_id = $lke_parameter->id;
                    $lke_bobot->group = 'kabupaten';
                    $lke_bobot->bobot = $request->kabupaten_bobot ? str_replace(',', '.', $request->kabupaten_bobot) : null;
                    $lke_bobot->target_baik = $request->kabupaten_target_baik ? str_replace(',', '.', $request->kabupaten_target_baik) : null;
                    $lke_bobot->min_value = $request->kabupaten_min_value ? str_replace(',', '.', $request->kabupaten_min_value) : null;
                    $lke_bobot->max_value = $request->kabupaten_max_value ? str_replace(',', '.', $request->kabupaten_max_value) : null;
                    if (!$lke_bobot->save()) {
                        $success = false;
                    }
                } else {
                    LkeBobot::where('lke_parameter_id', $lke_parameter->id)->where('group', 'kabupaten')->delete();
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

    public function lke_parameter_hapus(Request $request)
    {
        $lke_parameter = LkeParameter::find($request->id);
        if ($lke_parameter->delete()) {
            LkeBobot::where('lke_parameter_id', $lke_parameter->id)->delete();
            return true;
        } else {
            return false;
        }
    }
}
