<?php

namespace App\Http\Controllers;

use App\Models\Indikator;
use App\Models\KegiatanUtama;
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
        if ($kegiatan_utama->indikators) {
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
}
