<?php

namespace App\Http\Controllers\Akip;

use App\Http\Controllers\Controller;
use App\Models\Akip\EvaluasiSakip;
use App\Models\InstansiTimEvaluasi;
use App\Models\KlpdInstansi;
use App\Models\OpenAccessSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EvaluasiController extends Controller
{
    protected $currentUser;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->currentUser = auth()->user();
            foreach (allowed_url('akip') as $allowed) {
                if ($request->is($allowed)) {
                    return $next($request);
                }
            }
            abort('403');
        });
    }

    /**
     * Display the evaluasi SAKIP page
     */
    public function index(Request $request)
    {
        if (in_array($this->currentUser->level, ['tpn', 'admin'])) {
            $tim = $this->currentUser->anggota ? $this->currentUser->anggota->tim : false;
            $anggota_tims = $tim ? $tim->instansi_tim : [];
            if ($this->currentUser->level == 'admin') {
                $anggota_tims = InstansiTimEvaluasi::with('instansi')->whereHas('instansi', function ($query) {
                    $query->whereIn('group', ['kl', 'provinsi', 'kabupaten'])->where('deleted_at', null);
                })->get();
            } else {
                // Load the relationship for non-admin users too
                $anggota_tims = $tim ? $tim->instansi_tim()->with('instansi')->get() : collect();
            }

            // Filter berdasarkan tahun dengan validasi
            $tahun = $request->input('tahun', date('Y'));
            $tahun = is_numeric($tahun) && $tahun >= 2020 && $tahun <= date('Y') ? (int) $tahun : date('Y');

            // Get search parameters
            $search_kl = $request->input('search_kl', '');
            $search_pemda = $request->input('search_pemda', '');

            // Pisahkan data K/L dan Pemda
            $anggota_kl = collect();
            $anggota_pemda = collect();

            foreach ($anggota_tims as $anggota_tim) {
                $instansi = $anggota_tim->instansi;

                if ($instansi->group == 'kl') {
                    // Apply search filter for K/L
                    if (!empty($search_kl) && stripos($instansi->nama_instansi, $search_kl) === false) {
                        continue;
                    }

                    // Untuk K/L, cek status evaluasi Final
                    $evaluasi_final = EvaluasiSakip::where('instansi_id', $instansi->id)
                        ->where('tahun', $tahun)
                        ->where('periode', 'Final')
                        ->first();

                    $anggota_tim->status_evaluasi = $evaluasi_final ? 'sudah' : 'belum';
                    $anggota_tim->evaluasi_data = $evaluasi_final;
                    $anggota_tim->tahun_evaluasi = $tahun;

                    $anggota_kl->push($anggota_tim);
                } else {
                    // Apply search filter for Pemda
                    if (!empty($search_pemda) && stripos($instansi->nama_instansi, $search_pemda) === false) {
                        continue;
                    }

                    // Untuk Pemda, cek status evaluasi per TW
                    $evaluasi_tw1 = EvaluasiSakip::where('instansi_id', $instansi->id)
                        ->where('tahun', $tahun)
                        ->where('periode', 'TW 1')
                        ->first();

                    $evaluasi_tw2 = EvaluasiSakip::where('instansi_id', $instansi->id)
                        ->where('tahun', $tahun)
                        ->where('periode', 'TW 2')
                        ->first();

                    $evaluasi_tw3 = EvaluasiSakip::where('instansi_id', $instansi->id)
                        ->where('tahun', $tahun)
                        ->where('periode', 'TW 3')
                        ->first();

                    $evaluasi_tw4 = EvaluasiSakip::where('instansi_id', $instansi->id)
                        ->where('tahun', $tahun)
                        ->where('periode', 'TW 4')
                        ->first();

                    $anggota_tim->status_tw1 = $evaluasi_tw1 ? 'sudah' : 'belum';
                    $anggota_tim->status_tw2 = $evaluasi_tw2 ? 'sudah' : 'belum';
                    $anggota_tim->status_tw3 = $evaluasi_tw3 ? 'sudah' : 'belum';
                    $anggota_tim->status_tw4 = $evaluasi_tw4 ? 'sudah' : 'belum';

                    $anggota_tim->evaluasi_tw1 = $evaluasi_tw1;
                    $anggota_tim->evaluasi_tw2 = $evaluasi_tw2;
                    $anggota_tim->evaluasi_tw3 = $evaluasi_tw3;
                    $anggota_tim->evaluasi_tw4 = $evaluasi_tw4;
                    $anggota_tim->tahun_evaluasi = $tahun;

                    $anggota_pemda->push($anggota_tim);
                }
            }

            // Pagination untuk K/L
            $perPage = 10;
            $currentPageKl = $request->input('page_kl', 1);
            $klPaginated = $anggota_kl->forPage($currentPageKl, $perPage);
            $klTotalPages = ceil($anggota_kl->count() / $perPage);

            // Pagination untuk Pemda
            $currentPagePemda = $request->input('page_pemda', 1);
            $pemdaPaginated = $anggota_pemda->forPage($currentPagePemda, $perPage);
            $pemdaTotalPages = ceil($anggota_pemda->count() / $perPage);

            return view('akip.evaluasi.index', compact(
                'tim',
                'anggota_kl',
                'anggota_pemda',
                'klPaginated',
                'pemdaPaginated',
                'klTotalPages',
                'pemdaTotalPages',
                'currentPageKl',
                'currentPagePemda',
                'tahun',
                'search_kl',
                'search_pemda'
            ));
        } else if (in_array($this->currentUser->level, ['kl', 'kabupaten', 'provinsi'])) {
            $access = OpenAccessSetting::where('user_level', $this->currentUser->level)->where('fitur', 'evaluasi_akip')->first();
            if ($access) {
                $today = date('Y-m-d');
                if ($access->waktu_awal > $today || $access->waktu_akhir < $today) {
                    return view('belumbuka');
                }
            }
            $instansi = KlpdInstansi::find($this->currentUser->instansi_id);
            if (!$instansi) {
                abort('404');
            }
            $evaluasi_sakip = EvaluasiSakip::where('instansi_id', $this->currentUser->instansi_id)->orderBy('tahun')->orderBy('periode')->get();
            return view('akip.evaluasi.instansi.show', compact('instansi', 'evaluasi_sakip'));
        }
    }

    /**
     * Show evaluasi for specific instansi
     */
    public function show($instansi_id)
    {
        $cek = $this->currentUser->anggota ? $this->currentUser->anggota->tim->instansi_tim->where('instansi_id', $instansi_id)->first() : false;
        if (!$cek && $this->currentUser->level != 'admin') {
            abort('404');
        } else {
            $instansi = KlpdInstansi::find($instansi_id);
        }
        $evaluasi_sakip = EvaluasiSakip::where('instansi_id', $instansi_id)->orderBy('tahun')->orderBy('periode')->get();

        return view('akip.evaluasi.instansi.show', compact('instansi', 'evaluasi_sakip'));
    }

    /**
     * Get data for specific evaluasi
     */
    public function getData($instansi_id, $id)
    {
        $cek = $this->currentUser->anggota ? $this->currentUser->anggota->tim->instansi_tim->where('instansi_id', $instansi_id)->first() : false;
        if (!$cek) {
            abort('404');
        }
        $evaluasi_sakip = EvaluasiSakip::find($id);
        if ($evaluasi_sakip) {
            return response()->json(['evaluasi_sakip' => $evaluasi_sakip]);
        } else {
            abort('404');
        }
    }

    /**
     * Store evaluasi data
     */
    public function store(Request $request, $instansi_id)
    {
        $cek = $this->currentUser->anggota ? $this->currentUser->anggota->tim->instansi_tim->where('instansi_id', $instansi_id)->first() : false;
        if (!$cek) {
            abort('404');
        }
        $success = false;
        $instansi = KlpdInstansi::find($instansi_id);
        if (!$instansi) {
            abort('404');
        }
        DB::beginTransaction();
        try {
            if (!$request->id_evaluasi) {
                $evaluasi_sakip = new EvaluasiSakip();
                $evaluasi_sakip->instansi_id = $instansi_id;
                $evaluasi_sakip->tahun = $request->tahun;
                $evaluasi_sakip->periode = $instansi->group == 'kl' ? 'Final' : $request->periode;
                $evaluasi_sakip->input_user_id = $this->currentUser->id;
                $evaluasi_sakip->data = json_encode($request->data);
                $evaluasi_sakip->save();
                $success = true;
            } else {
                $evaluasi_sakip = EvaluasiSakip::find($request->id_evaluasi);
                if ($evaluasi_sakip) {
                    $evaluasi_sakip->data = json_encode($request->data);
                    $evaluasi_sakip->save();
                    $success = true;
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $success = false;
        }
        return response()->json(['success' => $success]);
    }

    /**
     * Check periode for evaluasi
     */
    public function checkPeriode(Request $request, $instansi_id)
    {
        $cek = $this->currentUser->anggota ? $this->currentUser->anggota->tim->instansi_tim->where('instansi_id', $instansi_id)->first() : false;
        if (!$cek) {
            abort('404');
        }
        $evaluasi_sakip = EvaluasiSakip::where('instansi_id', $instansi_id)
            ->where('tahun', $request->tahun)
            ->where('periode', $request->periode)
            ->first();
        if ($evaluasi_sakip) {
            return response()->json(['exists' => true]);
        } else {
            $evaluasi_sakip = EvaluasiSakip::where('instansi_id', $instansi_id)
                ->where('tahun', $request->tahun)
                ->orderBy('periode', 'desc')
                ->first();
            return response()->json(['exists' => false, 'evaluasi_sakip' => $evaluasi_sakip]);
        }
    }

    /**
     * Delete evaluasi
     */
    public function destroy($instansi_id, $id)
    {
        $cek = $this->currentUser->anggota ? $this->currentUser->anggota->tim->instansi_tim->where('instansi_id', $instansi_id)->first() : false;
        if (!$cek) {
            abort('404');
        }
        $evaluasi_sakip = EvaluasiSakip::find($id);
        if ($evaluasi_sakip) {
            $evaluasi_sakip->delete();
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    /**
     * Search evaluasi
     */
    public function search(Request $request)
    {
        try {
            // Validate request
            $request->validate([
                'tahun' => 'required|integer|min:2020|max:' . date('Y'),
                'search_kl' => 'nullable|string|max:255',
                'search_pemda' => 'nullable|string|max:255',
                'page_kl' => 'nullable|integer|min:1',
                'page_pemda' => 'nullable|integer|min:1'
            ]);

            if (!in_array($this->currentUser->level, ['tpn', 'admin'])) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            $tim = $this->currentUser->anggota ? $this->currentUser->anggota->tim : false;
            $anggota_tims = $tim ? $tim->instansi_tim : [];

            if ($this->currentUser->level == 'admin') {
                $anggota_tims = InstansiTimEvaluasi::whereHas('instansi', function ($query) {
                    $query->whereIn('group', ['kl', 'provinsi', 'kabupaten'])->where('deleted_at', null);
                })->get();
            }

            // Get parameters with proper validation
            $tahun = (int) $request->input('tahun');
            $search_kl = trim($request->input('search_kl', ''));
            $search_pemda = trim($request->input('search_pemda', ''));
            $currentPageKl = max(1, (int) $request->input('page_kl', 1));
            $currentPagePemda = max(1, (int) $request->input('page_pemda', 1));

            // Pisahkan data K/L dan Pemda
            $anggota_kl = collect();
            $anggota_pemda = collect();

            foreach ($anggota_tims as $anggota_tim) {
                $instansi = $anggota_tim->instansi;

                if ($instansi->group == 'kl') {
                    // Apply search filter for K/L
                    if (!empty($search_kl) && stripos($instansi->nama_instansi, $search_kl) === false) {
                        continue;
                    }

                    // Untuk K/L, cek status evaluasi Final
                    $evaluasi_final = EvaluasiSakip::where('instansi_id', $instansi->id)
                        ->where('tahun', $tahun)
                        ->where('periode', 'Final')
                        ->first();

                    $anggota_tim->status_evaluasi = $evaluasi_final ? 'sudah' : 'belum';
                    $anggota_tim->evaluasi_data = $evaluasi_final;
                    $anggota_tim->tahun_evaluasi = $tahun;

                    $anggota_kl->push($anggota_tim);
                } else {
                    // Apply search filter for Pemda
                    if (!empty($search_pemda) && stripos($instansi->nama_instansi, $search_pemda) === false) {
                        continue;
                    }

                    // Untuk Pemda, cek status evaluasi per TW
                    $evaluasi_tw1 = EvaluasiSakip::where('instansi_id', $instansi->id)
                        ->where('tahun', $tahun)
                        ->where('periode', 'TW 1')
                        ->first();

                    $evaluasi_tw2 = EvaluasiSakip::where('instansi_id', $instansi->id)
                        ->where('tahun', $tahun)
                        ->where('periode', 'TW 2')
                        ->first();

                    $evaluasi_tw3 = EvaluasiSakip::where('instansi_id', $instansi->id)
                        ->where('tahun', $tahun)
                        ->where('periode', 'TW 3')
                        ->first();

                    $evaluasi_tw4 = EvaluasiSakip::where('instansi_id', $instansi->id)
                        ->where('tahun', $tahun)
                        ->where('periode', 'TW 4')
                        ->first();

                    $anggota_tim->status_tw1 = $evaluasi_tw1 ? 'sudah' : 'belum';
                    $anggota_tim->status_tw2 = $evaluasi_tw2 ? 'sudah' : 'belum';
                    $anggota_tim->status_tw3 = $evaluasi_tw3 ? 'sudah' : 'belum';
                    $anggota_tim->status_tw4 = $evaluasi_tw4 ? 'sudah' : 'belum';

                    $anggota_tim->evaluasi_tw1 = $evaluasi_tw1;
                    $anggota_tim->evaluasi_tw2 = $evaluasi_tw2;
                    $anggota_tim->evaluasi_tw3 = $evaluasi_tw3;
                    $anggota_tim->evaluasi_tw4 = $evaluasi_tw4;
                    $anggota_tim->tahun_evaluasi = $tahun;

                    $anggota_pemda->push($anggota_tim);
                }
            }

            // Pagination untuk K/L
            $perPage = 10;
            $klPaginated = $anggota_kl->forPage($currentPageKl, $perPage);
            $klTotalPages = ceil($anggota_kl->count() / $perPage);

            // Pagination untuk Pemda
            $pemdaPaginated = $anggota_pemda->forPage($currentPagePemda, $perPage);
            $pemdaTotalPages = ceil($anggota_pemda->count() / $perPage);

            return response()->json([
                'success' => true,
                'data' => [
                    'anggota_kl' => $klPaginated->values(),
                    'anggota_pemda' => $pemdaPaginated->values(),
                    'klTotalPages' => $klTotalPages,
                    'pemdaTotalPages' => $pemdaTotalPages,
                    'currentPageKl' => $currentPageKl,
                    'currentPagePemda' => $currentPagePemda,
                    'tahun' => $tahun,
                    'search_kl' => $search_kl,
                    'search_pemda' => $search_pemda
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
