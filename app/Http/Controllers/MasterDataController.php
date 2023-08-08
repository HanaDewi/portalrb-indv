<?php

namespace App\Http\Controllers;

use App\Models\Indikator;
use App\Models\KegiatanUtama;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterDataController extends Controller
{
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
        $aset = KegiatanUtama::find($request->id);
        if ($aset->delete()) {
            return true;
        } else {
            return false;
        }
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
        $aset = Indikator::find($request->id);
        if ($aset->delete()) {
            return true;
        } else {
            return false;
        }
    }
}
