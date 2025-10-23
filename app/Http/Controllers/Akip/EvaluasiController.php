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
            $allowed_urls = allowed_url('akip');
            \Log::info('Checking URL access for: ' . $request->path());
            \Log::info('Allowed URLs: ' . json_encode($allowed_urls));
            
            // Check if URL matches any allowed pattern
            foreach ($allowed_urls as $allowed) {
                if ($request->is($allowed)) {
                    \Log::info('URL matched: ' . $allowed);
                    return $next($request);
                }
            }
            
            // Additional check for evaluasi sakip routes (including detail pages)
            if ($request->is('akip/evaluasi/sakip') || 
                $request->is('akip/evaluasi/sakip/*')) {
                \Log::info('URL matched via evaluasi sakip wildcard');
                return $next($request);
            }
            
            \Log::warning('URL not allowed: ' . $request->path());
            abort('403');
        });
    }

    /**
     * Display the evaluasi SAKIP page
     */
    public function index(Request $request)
    {
        \Log::info('EvaluasiController::index called with request: ' . $request->fullUrl());
        
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
        \Log::info('EvaluasiController::show called with instansi_id: ' . $instansi_id);
        
        $cek = $this->currentUser->anggota ? $this->currentUser->anggota->tim->instansi_tim->where('instansi_id', $instansi_id)->first() : false;
        if (!$cek && $this->currentUser->level != 'admin') {
            \Log::info('User does not have access to instansi_id: ' . $instansi_id);
            abort('404');
        }
        
        $instansi = KlpdInstansi::find($instansi_id);
        if (!$instansi) {
            \Log::info('Instansi not found with id: ' . $instansi_id);
            abort('404');
        }
        
        $evaluasi_sakip = EvaluasiSakip::where('instansi_id', $instansi_id)->orderBy('tahun')->orderBy('periode')->get();
        \Log::info('Found ' . $evaluasi_sakip->count() . ' evaluasi records for instansi_id: ' . $instansi_id);
        \Log::info('Instansi data: ' . json_encode($instansi->toArray()));
        \Log::info('About to return view: akip.evaluasi.instansi.show');

        try {
            $view = view('akip.evaluasi.instansi.show', compact('instansi', 'evaluasi_sakip'));
            \Log::info('View created successfully');
            
            // Debug: Check if view is being returned as JSON
            $content = $view->render();
            \Log::info('View content length: ' . strlen($content));
            \Log::info('View content preview: ' . substr($content, 0, 200));
            
            return $view;
        } catch (\Exception $e) {
            \Log::error('Error rendering view: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            throw $e;
        }
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
        \Log::info('EvaluasiController::store called with instansi_id: ' . $instansi_id);
        \Log::info('Request data: ' . json_encode($request->all()));
        
        $cek = $this->currentUser->anggota ? $this->currentUser->anggota->tim->instansi_tim->where('instansi_id', $instansi_id)->first() : false;
        if (!$cek) {
            \Log::warning('User does not have access to instansi_id: ' . $instansi_id);
            abort('404');
        }
        $success = false;
        $instansi = KlpdInstansi::find($instansi_id);
        if (!$instansi) {
            \Log::warning('Instansi not found with id: ' . $instansi_id);
            abort('404');
        }
        DB::beginTransaction();
        try {
            if (!$request->id_evaluasi) {
                $evaluasi_sakip = new EvaluasiSakip();
                $evaluasi_sakip->instansi_id = $instansi_id;
                $evaluasi_sakip->tahun = $request->tahun;
                $evaluasi_sakip->periode = $instansi->group == 'kl' ? 'Final' : $request->periode;
                $evaluasi_sakip->penanggung_jawab = $request->penanggung_jawab;
                $evaluasi_sakip->pic_lke = $request->pic_lke;
                $evaluasi_sakip->link_lke = $request->link_lke;
                $evaluasi_sakip->input_user_id = $this->currentUser->id;
                $evaluasi_sakip->last_update_user_id = $this->currentUser->id;
                
                // Handle file upload
                if ($request->hasFile('file_evaluasi')) {
                    $file = $request->file('file_evaluasi');
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $file->storeAs('akip', $filename);
                    $evaluasi_sakip->file_evaluasi = $filename;
                }
                
                // Set all evaluation data
                $evaluasi_sakip->nilai_komponen_perencanaan_kinerja_tahun_lalu = $request->nilai_komponen_perencanaan_kinerja_tahun_lalu;
                $evaluasi_sakip->nilai_komponen_perencanaan_kinerja = $request->nilai_komponen_perencanaan_kinerja;
                $evaluasi_sakip->catatan_komponen_perencanaan_kinerja = $request->catatan_komponen_perencanaan_kinerja;
                $evaluasi_sakip->rekomendasi_komponen_perencanaan_kinerja = $request->rekomendasi_komponen_perencanaan_kinerja;
                
                $evaluasi_sakip->nilai_komponen_pengukuran_kinerja_tahun_lalu = $request->nilai_komponen_pengukuran_kinerja_tahun_lalu;
                $evaluasi_sakip->nilai_komponen_pengukuran_kinerja = $request->nilai_komponen_pengukuran_kinerja;
                $evaluasi_sakip->catatan_komponen_pengukuran_kinerja = $request->catatan_komponen_pengukuran_kinerja;
                $evaluasi_sakip->rekomendasi_komponen_pengukuran_kinerja = $request->rekomendasi_komponen_pengukuran_kinerja;
                
                $evaluasi_sakip->nilai_komponen_pelaporan_kinerja_tahun_lalu = $request->nilai_komponen_pelaporan_kinerja_tahun_lalu;
                $evaluasi_sakip->nilai_komponen_pelaporan_kinerja = $request->nilai_komponen_pelaporan_kinerja;
                $evaluasi_sakip->catatan_komponen_pelaporan_kinerja = $request->catatan_komponen_pelaporan_kinerja;
                $evaluasi_sakip->rekomendasi_komponen_pelaporan_kinerja = $request->rekomendasi_komponen_pelaporan_kinerja;
                
                $evaluasi_sakip->nilai_komponen_evaluasi_internal_tahun_lalu = $request->nilai_komponen_evaluasi_internal_tahun_lalu;
                $evaluasi_sakip->nilai_komponen_evaluasi_internal = $request->nilai_komponen_evaluasi_internal;
                $evaluasi_sakip->catatan_komponen_evaluasi_internal = $request->catatan_komponen_evaluasi_internal;
                $evaluasi_sakip->rekomendasi_komponen_evaluasi_internal = $request->rekomendasi_komponen_evaluasi_internal;
                
                $evaluasi_sakip->nilai_total_evaluasi_akip_tahun_lalu = $request->nilai_total_evaluasi_akip_tahun_lalu;
                $evaluasi_sakip->nilai_total_evaluasi_akip = $request->nilai_total_evaluasi_akip;
                
                // For Pemda only
                if ($instansi->group != 'kl') {
                    $evaluasi_sakip->angka_kemiskinan_tahun_lalu = $request->angka_kemiskinan_tahun_lalu;
                    $evaluasi_sakip->angka_kemiskinan = $request->angka_kemiskinan;
                    $evaluasi_sakip->laju_pertumbuhan_ekonomi_tahun_lalu = $request->laju_pertumbuhan_ekonomi_tahun_lalu;
                    $evaluasi_sakip->laju_pertumbuhan_ekonomi = $request->laju_pertumbuhan_ekonomi;
                    $evaluasi_sakip->tingkat_pengangguran_terbuka_tahun_lalu = $request->tingkat_pengangguran_terbuka_tahun_lalu;
                    $evaluasi_sakip->tingkat_pengangguran_terbuka = $request->tingkat_pengangguran_terbuka;
                    $evaluasi_sakip->penurunan_emisi_grk_tahun_lalu = $request->penurunan_emisi_grk_tahun_lalu;
                    $evaluasi_sakip->penurunan_emisi_grk = $request->penurunan_emisi_grk;
                    $evaluasi_sakip->indeks_pembangunan_manusia_tahun_lalu = $request->indeks_pembangunan_manusia_tahun_lalu;
                    $evaluasi_sakip->indeks_pembangunan_manusia = $request->indeks_pembangunan_manusia;
                    $evaluasi_sakip->indeks_gini_ratio_tahun_lalu = $request->indeks_gini_ratio_tahun_lalu;
                    $evaluasi_sakip->indeks_gini_ratio = $request->indeks_gini_ratio;
                    $evaluasi_sakip->pendapatan_perkapita_tahun_lalu = $request->pendapatan_perkapita_tahun_lalu;
                    $evaluasi_sakip->pendapatan_perkapita = $request->pendapatan_perkapita;
                }
                
                $evaluasi_sakip->save();
                $success = true;
            } else {
                $evaluasi_sakip = EvaluasiSakip::find($request->id_evaluasi);
                if ($evaluasi_sakip) {
                    // Update existing record with same fields as above
                    $evaluasi_sakip->penanggung_jawab = $request->penanggung_jawab;
                    $evaluasi_sakip->pic_lke = $request->pic_lke;
                    $evaluasi_sakip->link_lke = $request->link_lke;
                    $evaluasi_sakip->last_update_user_id = $this->currentUser->id;
                    
                    // Handle file upload for update
                    if ($request->hasFile('file_evaluasi')) {
                        $file = $request->file('file_evaluasi');
                        $filename = time() . '_' . $file->getClientOriginalName();
                        $file->storeAs('akip', $filename);
                        $evaluasi_sakip->file_evaluasi = $filename;
                    }
                    
                    // Update all evaluation data (same as above)
                    $evaluasi_sakip->nilai_komponen_perencanaan_kinerja_tahun_lalu = $request->nilai_komponen_perencanaan_kinerja_tahun_lalu;
                    $evaluasi_sakip->nilai_komponen_perencanaan_kinerja = $request->nilai_komponen_perencanaan_kinerja;
                    $evaluasi_sakip->catatan_komponen_perencanaan_kinerja = $request->catatan_komponen_perencanaan_kinerja;
                    $evaluasi_sakip->rekomendasi_komponen_perencanaan_kinerja = $request->rekomendasi_komponen_perencanaan_kinerja;
                    
                    $evaluasi_sakip->nilai_komponen_pengukuran_kinerja_tahun_lalu = $request->nilai_komponen_pengukuran_kinerja_tahun_lalu;
                    $evaluasi_sakip->nilai_komponen_pengukuran_kinerja = $request->nilai_komponen_pengukuran_kinerja;
                    $evaluasi_sakip->catatan_komponen_pengukuran_kinerja = $request->catatan_komponen_pengukuran_kinerja;
                    $evaluasi_sakip->rekomendasi_komponen_pengukuran_kinerja = $request->rekomendasi_komponen_pengukuran_kinerja;
                    
                    $evaluasi_sakip->nilai_komponen_pelaporan_kinerja_tahun_lalu = $request->nilai_komponen_pelaporan_kinerja_tahun_lalu;
                    $evaluasi_sakip->nilai_komponen_pelaporan_kinerja = $request->nilai_komponen_pelaporan_kinerja;
                    $evaluasi_sakip->catatan_komponen_pelaporan_kinerja = $request->catatan_komponen_pelaporan_kinerja;
                    $evaluasi_sakip->rekomendasi_komponen_pelaporan_kinerja = $request->rekomendasi_komponen_pelaporan_kinerja;
                    
                    $evaluasi_sakip->nilai_komponen_evaluasi_internal_tahun_lalu = $request->nilai_komponen_evaluasi_internal_tahun_lalu;
                    $evaluasi_sakip->nilai_komponen_evaluasi_internal = $request->nilai_komponen_evaluasi_internal;
                    $evaluasi_sakip->catatan_komponen_evaluasi_internal = $request->catatan_komponen_evaluasi_internal;
                    $evaluasi_sakip->rekomendasi_komponen_evaluasi_internal = $request->rekomendasi_komponen_evaluasi_internal;
                    
                    $evaluasi_sakip->nilai_total_evaluasi_akip_tahun_lalu = $request->nilai_total_evaluasi_akip_tahun_lalu;
                    $evaluasi_sakip->nilai_total_evaluasi_akip = $request->nilai_total_evaluasi_akip;
                    
                    // For Pemda only
                    if ($instansi->group != 'kl') {
                        $evaluasi_sakip->angka_kemiskinan_tahun_lalu = $request->angka_kemiskinan_tahun_lalu;
                        $evaluasi_sakip->angka_kemiskinan = $request->angka_kemiskinan;
                        $evaluasi_sakip->laju_pertumbuhan_ekonomi_tahun_lalu = $request->laju_pertumbuhan_ekonomi_tahun_lalu;
                        $evaluasi_sakip->laju_pertumbuhan_ekonomi = $request->laju_pertumbuhan_ekonomi;
                        $evaluasi_sakip->tingkat_pengangguran_terbuka_tahun_lalu = $request->tingkat_pengangguran_terbuka_tahun_lalu;
                        $evaluasi_sakip->tingkat_pengangguran_terbuka = $request->tingkat_pengangguran_terbuka;
                        $evaluasi_sakip->penurunan_emisi_grk_tahun_lalu = $request->penurunan_emisi_grk_tahun_lalu;
                        $evaluasi_sakip->penurunan_emisi_grk = $request->penurunan_emisi_grk;
                        $evaluasi_sakip->indeks_pembangunan_manusia_tahun_lalu = $request->indeks_pembangunan_manusia_tahun_lalu;
                        $evaluasi_sakip->indeks_pembangunan_manusia = $request->indeks_pembangunan_manusia;
                        $evaluasi_sakip->indeks_gini_ratio_tahun_lalu = $request->indeks_gini_ratio_tahun_lalu;
                        $evaluasi_sakip->indeks_gini_ratio = $request->indeks_gini_ratio;
                        $evaluasi_sakip->pendapatan_perkapita_tahun_lalu = $request->pendapatan_perkapita_tahun_lalu;
                        $evaluasi_sakip->pendapatan_perkapita = $request->pendapatan_perkapita;
                    }
                    
                    $evaluasi_sakip->save();
                    $success = true;
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error saving evaluasi: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            $success = false;
        }
        
        \Log::info('Store method completed with success: ' . ($success ? 'true' : 'false'));
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
