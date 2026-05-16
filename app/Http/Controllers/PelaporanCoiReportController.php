<?php

namespace App\Http\Controllers;

use App\Models\MasterPertanyaanCoi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PelaporanCoiReportController extends Controller
{
    /**
     * Konstruktor untuk membatasi akses hanya untuk admin/tpn
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::check() || !in_array(Auth::user()->level, ['tpn', 'admin'])) {
                abort(403, 'Akses Ditolak.');
            }
            return $next($request);
        });
    }
    public function index()
    {
        $existingQuestions = MasterPertanyaanCoi::orderBy('urutan', 'asc')->get();
        $totalResponses = \App\Models\JawabanCoi::distinct('instansi_id')->count();

        return view('pelaporan-coi.admin-coi.editor', [
            'existingQuestions' => $existingQuestions,
            'totalResponses' => $totalResponses, 
        ]);
    }
    public function store(Request $request)
    {
        try {
            if (!$request->has('questions') || !is_array($request->questions)) {
                return response()->json(['message' => 'Data kuesioner tidak valid atau kosong.'], 400);
            }
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            MasterPertanyaanCoi::truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            DB::beginTransaction();
            foreach ($request->questions as $index => $q) {
                MasterPertanyaanCoi::create([
                    'teks_pertanyaan' => $q['title'] ?? 'Pertanyaan Tanpa Judul',
                    'tipe_jawaban'    => $q['type'] ?? 'pilihan_ganda',
                    'opsi'            => isset($q['options']) && !empty($q['options']) ? $q['options'] : null, 
                    'is_wajib'        => filter_var($q['required'] ?? false, FILTER_VALIDATE_BOOLEAN),
                    'urutan'          => $index + 1,
                ]);
            }
            DB::commit();
            return response()->json(['message' => 'Konfigurasi formulir berhasil disimpan!'], 200);

        } catch (\Exception $e) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
            DB::statement('SET FOREIGN_KEY_CHECKS=1;'); 
            return response()->json([
                'message' => 'Gagal: ' . $e->getMessage()
            ], 500);
        }
    }
    public function responses()
{
    
    $totalResponses = \App\Models\JawabanCoi::distinct('instansi_id')->count();
    $todayResponses = \App\Models\JawabanCoi::whereDate('created_at', now())->distinct('instansi_id')->count();
    $completionRate = ($totalResponses / 100) * 100;
    $tableData = \App\Models\JawabanCoi::with('instansi')
        ->select('instansi_id', 'created_at')
        ->groupBy('instansi_id', 'created_at')
        ->orderBy('created_at', 'desc')
        ->get();
    $questions = \App\Models\MasterPertanyaanCoi::where('tipe_jawaban', '!=', 'form_header')->get();
    $summary = [];

    foreach ($questions as $q) {
    $answers = \App\Models\JawabanCoi::where('master_pertanyaan_id', $q->id)->get();
    
    $counts = [];
    if ($q->opsi) {
        foreach ($q->opsi as $opt) {
            $counts[$opt] = $answers->filter(function($a) use ($opt, $q) {
                if ($q->tipe_jawaban == 'kotak_centang') {
                    $decoded = json_decode($a->nilai_jawaban, true);
                    return is_array($decoded) && in_array($opt, $decoded);
                }
                return $a->nilai_jawaban == $opt;
            })->count();
        }
    }
    
    $summary[] = [
        'id' => $q->id,
        'question' => $q->teks_pertanyaan,
        'type' => $q->tipe_jawaban,
        'total' => $answers->count(),
        'counts' => $counts
    ];
}

    return view('pelaporan-coi.admin-coi.responses', compact('totalResponses', 'todayResponses', 'completionRate', 'tableData', 'summary'));
    }
    public function destroyQuestion($id)
        {
            $q = \App\Models\MasterPertanyaanCoi::findOrFail($id);
            \App\Models\JawabanCoi::where('master_pertanyaan_id', $id)->delete();
            
            $q->delete();

            return redirect()->back()->with('success', 'Pertanyaan dan jawaban terkait berhasil dihapus.');
        }
}