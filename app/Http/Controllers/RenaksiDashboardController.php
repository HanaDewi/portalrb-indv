<?php

namespace App\Http\Controllers;

use App\Models\LKE\LkeKegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RenaksiDashboardController extends Controller
{
    public function index(Request $request)
    {
        $lkeKegiatanList = LkeKegiatan::orderByDesc('tahun')
            ->orderBy('nama')
            ->get();

        $selectedLkeKegiatanId = $request->input('lke_kegiatan_id');
        $selectedLkeKegiatan = $selectedLkeKegiatanId
            ? $lkeKegiatanList->firstWhere('id', (int) $selectedLkeKegiatanId)
            : $lkeKegiatanList->first();

        $timProgress = $selectedLkeKegiatan ? $this->buildTimProgress($selectedLkeKegiatan) : collect();
        $docProgress = $selectedLkeKegiatan ? $this->buildDocProgress($selectedLkeKegiatan->id) : collect();

        return view('dashboard', [
            'lkeKegiatanList' => $lkeKegiatanList,
            'selectedLkeKegiatan' => $selectedLkeKegiatan,
            'timProgress' => $timProgress,
            'docProgress' => $docProgress,
        ]);
    }

    public function detailInstansi(Request $request)
    {
        $request->validate([
            'tim_id' => 'required|integer',
            'lke_kegiatan_id' => 'required|integer',
        ]);

        $kegiatan = LkeKegiatan::find($request->lke_kegiatan_id);
        if (!$kegiatan) {
            return response()->json([
                'success' => false,
                'message' => 'LKE kegiatan tidak ditemukan.',
            ], 404);
        }

        $year = $kegiatan->tahun;

        $filledInstansi = DB::table('jawaban_renaksi as jr')
            ->join('lke_renaksi as lr', 'jr.lke_renaksi_id', '=', 'lr.id')
            ->where('lr.tahun', $year)
            ->select('jr.instansi_id')
            ->distinct();

        $instansiList = DB::table('instansi_tim as it')
            ->join('tim_evaluasi as te', 'it.tim_id', '=', 'te.id')
            ->join('klpd_instansi_new as ki', function ($join) {
                $join->on('it.instansi_id', '=', 'ki.id')
                    ->whereNull('ki.deleted_at')
                    ->whereIn('ki.group', ['kl', 'provinsi', 'kabupaten']);
            })
            ->leftJoinSub($filledInstansi, 'filled', function ($join) {
                $join->on('it.instansi_id', '=', 'filled.instansi_id');
            })
            ->where('it.tim_id', $request->tim_id)
            ->select(
                'it.tim_id',
                'te.nama as tim_nama',
                'te.keterangan as tim_keterangan',
                'it.instansi_id',
                'ki.name as instansi_name',
                'ki.group as instansi_group',
                DB::raw('CASE WHEN filled.instansi_id IS NOT NULL THEN "Sudah" ELSE "Belum" END as status_pengisian')
            )
            ->orderBy('ki.group')
            ->orderBy('ki.name')
            ->get();

        $timInfo = DB::table('tim_evaluasi')
            ->where('id', $request->tim_id)
            ->first();

        return response()->json([
            'success' => true,
            'data' => [
                'tim_id' => $request->tim_id,
                'tim_nama' => $timInfo->nama ?? ($instansiList->first()->tim_nama ?? ''),
                'tim_keterangan' => $timInfo->keterangan ?? ($instansiList->first()->tim_keterangan ?? ''),
                'total_instansi' => $instansiList->count(),
                'instansi_list' => $instansiList->map(function ($item) {
                    return [
                        'instansi_id' => $item->instansi_id,
                        'instansi_name' => $item->instansi_name,
                        'instansi_group' => $item->instansi_group,
                        'status_pengisian' => $item->status_pengisian,
                    ];
                })->values(),
            ],
        ]);
    }

    public function detailInstansiDokumen(Request $request)
    {
        $request->validate([
            'tim_id' => 'required|integer',
            'lke_kegiatan_id' => 'required|integer',
        ]);

        $kegiatan = LkeKegiatan::find($request->lke_kegiatan_id);
        if (!$kegiatan) {
            return response()->json([
                'success' => false,
                'message' => 'LKE kegiatan tidak ditemukan.',
            ], 404);
        }

        $filledInstansi = DB::table('lke_test_tp_files as ltf')
            ->join('lke_test_tp as ltt', 'ltf.test_tp_id', '=', 'ltt.id')
            ->where('ltt.lke_kegiatan_id', $request->lke_kegiatan_id)
            ->select('ltt.instansi_id')
            ->distinct();

        $instansiList = DB::table('instansi_tim as it')
            ->join('tim_evaluasi as te', 'it.tim_id', '=', 'te.id')
            ->join('klpd_instansi_new as ki', function ($join) {
                $join->on('it.instansi_id', '=', 'ki.id')
                    ->whereNull('ki.deleted_at')
                    ->whereIn('ki.group', ['kl', 'provinsi', 'kabupaten']);
            })
            ->leftJoinSub($filledInstansi, 'filled', function ($join) {
                $join->on('it.instansi_id', '=', 'filled.instansi_id');
            })
            ->where('it.tim_id', $request->tim_id)
            ->select(
                'it.tim_id',
                'te.nama as tim_nama',
                'te.keterangan as tim_keterangan',
                'it.instansi_id',
                'ki.name as instansi_name',
                'ki.group as instansi_group',
                DB::raw('CASE WHEN filled.instansi_id IS NOT NULL THEN "Sudah" ELSE "Belum" END as status_pengisian')
            )
            ->orderBy('ki.group')
            ->orderBy('ki.name')
            ->get();

        $timInfo = DB::table('tim_evaluasi')
            ->where('id', $request->tim_id)
            ->first();

        return response()->json([
            'success' => true,
            'data' => [
                'tim_id' => $request->tim_id,
                'tim_nama' => $timInfo->nama ?? ($instansiList->first()->tim_nama ?? ''),
                'tim_keterangan' => $timInfo->keterangan ?? ($instansiList->first()->tim_keterangan ?? ''),
                'total_instansi' => $instansiList->count(),
                'instansi_list' => $instansiList->map(function ($item) {
                    return [
                        'instansi_id' => $item->instansi_id,
                        'instansi_name' => $item->instansi_name,
                        'instansi_group' => $item->instansi_group,
                        'status_pengisian' => $item->status_pengisian,
                    ];
                })->values(),
            ],
        ]);
    }

    private function buildTimProgress(LkeKegiatan $kegiatan)
    {
        $year = $kegiatan->tahun;

        $filledInstansi = DB::table('jawaban_renaksi as jr')
            ->join('lke_renaksi as lr', 'jr.lke_renaksi_id', '=', 'lr.id')
            ->where('lr.tahun', $year)
            ->select('jr.instansi_id')
            ->distinct();

        $rows = DB::table('instansi_tim as it')
            ->join('tim_evaluasi as te', 'it.tim_id', '=', 'te.id')
            ->join('klpd_instansi_new as ki', function ($join) {
                $join->on('it.instansi_id', '=', 'ki.id')
                    ->whereNull('ki.deleted_at')
                    ->whereIn('ki.group', ['kl', 'provinsi', 'kabupaten']);
            })
            ->leftJoinSub($filledInstansi, 'filled', function ($join) {
                $join->on('it.instansi_id', '=', 'filled.instansi_id');
            })
            ->select(
                'it.tim_id',
                'te.nama',
                'te.keterangan',
                'it.instansi_id',
                'ki.group as instansi_group',
                DB::raw('filled.instansi_id as filled_instansi_id')
            )
            ->orderBy('te.nama')
            ->get();

        $groups = ['kl', 'provinsi', 'kabupaten'];

        return $rows->groupBy('tim_id')->map(function ($items) use ($groups) {
            $first = $items->first();
            $total = [];
            $filled = [];
            $progress = [];

            foreach ($groups as $group) {
                $groupItems = $items->where('instansi_group', $group);
                $total[$group] = $groupItems->unique('instansi_id')->count();
                $filled[$group] = $groupItems->whereNotNull('filled_instansi_id')->unique('instansi_id')->count();
                $progress[$group] = $total[$group] > 0 ? round(($filled[$group] / $total[$group]) * 100, 2) : 0;
            }

            return (object) [
                'tim_id' => $first->tim_id,
                'nama' => $first->nama,
                'keterangan' => $first->keterangan,
                'total' => $total,
                'filled' => $filled,
                'progress' => $progress,
            ];
        })->values();
    }

    private function buildDocProgress($lkeKegiatanId)
    {
        $filledInstansi = DB::table('lke_test_tp_files as ltf')
            ->join('lke_test_tp as ltt', 'ltf.test_tp_id', '=', 'ltt.id')
            ->where('ltt.lke_kegiatan_id', $lkeKegiatanId)
            ->select('ltt.instansi_id')
            ->distinct();

        $rows = DB::table('instansi_tim as it')
            ->join('tim_evaluasi as te', 'it.tim_id', '=', 'te.id')
            ->join('klpd_instansi_new as ki', function ($join) {
                $join->on('it.instansi_id', '=', 'ki.id')
                    ->whereNull('ki.deleted_at')
                    ->whereIn('ki.group', ['kl', 'provinsi', 'kabupaten']);
            })
            ->leftJoinSub($filledInstansi, 'filled', function ($join) {
                $join->on('it.instansi_id', '=', 'filled.instansi_id');
            })
            ->select(
                'it.tim_id',
                'te.nama',
                'te.keterangan',
                'it.instansi_id',
                'ki.group as instansi_group',
                DB::raw('filled.instansi_id as filled_instansi_id')
            )
            ->orderBy('te.nama')
            ->get();

        $groups = ['kl', 'provinsi', 'kabupaten'];

        return $rows->groupBy('tim_id')->map(function ($items) use ($groups) {
            $first = $items->first();
            $total = [];
            $filled = [];
            $progress = [];

            foreach ($groups as $group) {
                $groupItems = $items->where('instansi_group', $group);
                $total[$group] = $groupItems->unique('instansi_id')->count();
                $filled[$group] = $groupItems->whereNotNull('filled_instansi_id')->unique('instansi_id')->count();
                $progress[$group] = $total[$group] > 0 ? round(($filled[$group] / $total[$group]) * 100, 2) : 0;
            }

            return (object) [
                'tim_id' => $first->tim_id,
                'nama' => $first->nama,
                'keterangan' => $first->keterangan,
                'total' => $total,
                'filled' => $filled,
                'progress' => $progress,
            ];
        })->values();
    }
}
