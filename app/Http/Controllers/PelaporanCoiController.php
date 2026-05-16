<?php

namespace App\Http\Controllers;

use App\Models\KlpdInstansi;
use App\Models\PelaporanCoi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PelaporanCoiController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            foreach (allowed_url() as $allowed) {
                if ($request->is($allowed)) {
                    return $next($request);
                }
            }

            abort(403);
        });
    }

    public function index(Request $request)
    {
        $user = $this->guardEligibleUser();
        $instansiId = $this->resolveInstansiId($user);
        if (!$instansiId) {
            return redirect()->back()->with('error', 'Instansi tidak ditemukan untuk pengguna ini.');
        }

        $data = PelaporanCoi::where('instansi_id', $instansiId)->first();
        $instansi = KlpdInstansi::find($instansiId);
        $semuaData = \App\Models\MasterPertanyaanCoi::orderBy('urutan', 'asc')->get();
        $formHeader = $semuaData->where('tipe_jawaban', 'form_header')->first();
        $questions = $semuaData->where('tipe_jawaban', '!=', 'form_header')->values();
        $savedAnswers = \App\Models\JawabanCoi::where('instansi_id', $instansiId)
                    ->pluck('nilai_jawaban', 'master_pertanyaan_id')
                    ->all(); 
        return view('pelaporan-coi.report.index', compact(
            'data', 
            'instansi', 
            'questions', 
            'savedAnswers', 
            'formHeader'
        ));
    }

    public function store(Request $request)
    {
        $user = $this->guardEligibleUser();
        $instansiId = $this->resolveInstansiId($user);
        
        if (!$instansiId) {
            return redirect()->back()->with('error', 'Instansi tidak ditemukan.');
        }
        $request->validate([
            'jawaban' => 'required|array'
        ]);

        \Illuminate\Support\Facades\DB::beginTransaction();

        try {
            \App\Models\JawabanCoi::where('instansi_id', $instansiId)->delete();
            foreach ($request->jawaban as $pertanyaan_id => $nilai) {
                if (is_array($nilai)) {
                    $nilai = json_encode($nilai);
                }

                \App\Models\JawabanCoi::create([
                    'instansi_id'          => $instansiId,
                    'user_id'              => $user->id,
                    'master_pertanyaan_id' => $pertanyaan_id,
                    'nilai_jawaban'        => $nilai,
                ]);
            }

            \Illuminate\Support\Facades\DB::commit();
            return redirect()->back()->with('success', 'Jawaban kuesioner berhasil disimpan!');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan jawaban: ' . $e->getMessage());
        }
    }

    public function finalize(Request $request)
    {
        $user = $this->guardEligibleUser();
        $instansiId = $this->resolveInstansiId($user);
        if (!$instansiId) {
            return redirect()->back()->with('error', 'Instansi tidak ditemukan untuk pengguna ini.');
        }

        $existing = PelaporanCoi::firstOrNew(['instansi_id' => $instansiId]);
        if ($existing->finalized_at) {
            return redirect()->back()->with('error', 'Data sudah disimpan final dan tidak dapat diperbarui.');
        }

        try {
            $validated = $this->validatedData($request, true);
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->with('error', 'Simpan final gagal. Lengkapi semua isian.');
        }
        $payload = $this->normalizeData($validated);

        $existing->fill($payload);
        $existing->instansi_id = $instansiId;
        $existing->finalized_at = now();
        $existing->save();

        return redirect()->back()->with('success', 'Jawaban Pelaporan COI berhasil disimpan final.');
    }

    private function guardEligibleUser()
    {
        $user = Auth::user();
        if (!in_array($user->level, ['kl', 'provinsi', 'kabupaten'])) {
            abort(403);
        }

        return $user;
    }

    private function resolveInstansiId($user)
    {
        return $user->instansi_id ?? optional($user->user_rel)->instansi_id;
    }

    private function validatedData(Request $request, bool $requireComplete): array
    {
        $numberCleaner = function ($value) {
            if ($value === null || $value === '') {
                return $value;
            }

            return str_replace('.', '', $value);
        };

        $request->merge([
            'q61_total_wajib' => $numberCleaner($request->q61_total_wajib),
            'q62_total_lapor' => $numberCleaner($request->q62_total_lapor),
        ]);

        $booleanRule = $requireComplete ? ['required', Rule::in([0, 1])] : ['nullable', Rule::in([0, 1])];

        return $request->validate([
            'q1_peraturan_internal' => $booleanRule,
            'q11_nomor_peraturan' => $requireComplete ? 'required_if:q1_peraturan_internal,1|string' : 'nullable|string',
            'q2_selaras_permepan' => $booleanRule,
            'q21_susun_revisi' => $requireComplete ? 'required_if:q2_selaras_permepan,0|in:0,1' : 'nullable|in:0,1',
            'q211_rencana_penyesuaian' => $requireComplete ? 'required_if:q21_susun_revisi,0|string' : 'nullable|string',
            'q3_pedoman_teknis' => $booleanRule,
            'q31_nomor_pedoman' => $requireComplete ? 'required_if:q3_pedoman_teknis,1|string' : 'nullable|string',
            'q4_penunjukan_pejabat' => $booleanRule,
            'q5_sistem_aplikasi' => $booleanRule,
            'q51_url_sistem' => $requireComplete ? 'required_if:q5_sistem_aplikasi,1|url' : 'nullable|url',
            'q6_pencatatan_register' => $booleanRule,
            'q61_total_wajib' => [
                $requireComplete ? 'required_if:q6_pencatatan_register,1' : 'nullable',
                'exclude_unless:q6_pencatatan_register,1',
                'integer',
                'min:0'
            ],
            'q62_total_lapor' => [
                $requireComplete ? 'required_if:q6_pencatatan_register,1' : 'nullable',
                'exclude_unless:q6_pencatatan_register,1',
                'integer',
                'min:0'
            ],
            'q7_deklarasi_aktual' => $booleanRule,
            'q71_jumlah_deklarasi' => $requireComplete ? 'required_if:q7_deklarasi_aktual,1|string' : 'nullable|string',
            'q8_lini_aduan' => $booleanRule,
            'q81_nama_lini' => $requireComplete ? 'required_if:q8_lini_aduan,1|string' : 'nullable|string',
            'q9_monev' => $booleanRule,
            'q10_laporan' => $booleanRule,
        ]);
    }

    private function normalizeData(array $data): array
    {
        $booleanFields = [
            'q1_peraturan_internal',
            'q2_selaras_permepan',
            'q21_susun_revisi',
            'q3_pedoman_teknis',
            'q4_penunjukan_pejabat',
            'q5_sistem_aplikasi',
            'q6_pencatatan_register',
            'q7_deklarasi_aktual',
            'q8_lini_aduan',
            'q9_monev',
            'q10_laporan',
        ];

        foreach ($booleanFields as $field) {
            if (array_key_exists($field, $data)) {
                $data[$field] = $data[$field] === null || $data[$field] === '' ? null : (int) $data[$field];
            }
        }

        $data = array_map(function ($value) {
            return $value === '' ? null : $value;
        }, $data);

        if (($data['q1_peraturan_internal'] ?? null) != 1) {
            $data['q11_nomor_peraturan'] = null;
        }

        if (($data['q2_selaras_permepan'] ?? null) == 1) {
            $data['q21_susun_revisi'] = null;
            $data['q211_rencana_penyesuaian'] = null;
        } elseif (($data['q21_susun_revisi'] ?? null) == 1) {
            $data['q211_rencana_penyesuaian'] = null;
        }

        if (($data['q3_pedoman_teknis'] ?? null) != 1) {
            $data['q31_nomor_pedoman'] = null;
        }

        if (($data['q5_sistem_aplikasi'] ?? null) != 1) {
            $data['q51_url_sistem'] = null;
        }

        if (($data['q6_pencatatan_register'] ?? null) != 1) {
            $data['q61_total_wajib'] = null;
            $data['q62_total_lapor'] = null;
        }

        if (($data['q7_deklarasi_aktual'] ?? null) != 1) {
            $data['q71_jumlah_deklarasi'] = null;
        }

        if (($data['q8_lini_aduan'] ?? null) != 1) {
            $data['q81_nama_lini'] = null;
        }

        return $data;
    }
}
