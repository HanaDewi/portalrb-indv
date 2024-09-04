<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ZI\LkeEvaluator;
use App\Http\Controllers\ZI\ZIController;
use App\Http\Controllers\ZI\GenerateDataController;
use App\Http\Controllers\ZI\DokumenController;
use App\Http\Controllers\ZI\SanggahController;
use App\Http\Controllers\ZI\DashboardController;
use App\Http\Controllers\ZI\EvaluatanController;
use App\Http\Controllers\ZI\KonfigurasiController;
use App\Http\Controllers\ZI\AdministrasiController;
use App\Http\Controllers\ZI\LkeEvaluatorController;
use App\Http\Controllers\ZI\PengusulanZIController;


Route::middleware('auth')->group(function () {
    #evaluatan
    Route::get('/zi', [ZIController::class, 'index'])->name('home_zi');
    Route::get('/zi/pengusulan', [PengusulanZIController::class, 'index'])->name('pengusulan_zi');
    //Route::post('/zi/pengusulan/pengusulan', [PengusulanZIController::class, 'store_bukti_dukung'])->name('pengusulan_zi_store');
    Route::get('/zi-tinjau', [PengusulanZIController::class, 'tinjau'])->name('tinjau_zi');
    Route::get('/zi-administrasi', [EvaluatanController::class, 'seleksi_administrasi'])->name('evaluatan_seleksi_administrasi');
    Route::post('/zi-simpan-sanggah', [EvaluatanController::class, 'sanggah_simpan'])->name('evaluatan_simpan_sanggah');
    Route::get('/zi-hasil-sanggah', [EvaluatanController::class, 'hasil_sanggah'])->name('evaluatan_hasil_sanggah');
    Route::get('/zi-desk', [EvaluatanController::class, 'seleksi_desk'])->name('evaluatan_desk');
    Route::get('/zi-verifikasi-lapangan', [EvaluatanController::class, 'seleksi_verifikasi_lapangan'])->name('evaluatan_verifikasi_lapangan');
    Route::get('/zi-hasil-akhir', [EvaluatanController::class, 'hasil_akhir'])->name('evaluatan_hasil_akhir');
    
    #Admin Pengusulan
    Route::get('/zi/admin', [DashboardController::class, 'index'])->name('dashboard_zi');
    Route::get('/zi/rekap-total', [DashboardController::class, 'rekap_total'])->name('rekap_total');
    Route::get('/zi/rekap-pengusulan', [DashboardController::class, 'rekap_pengusulan'])->name('rekap_pengusulan');
    Route::get('/zi/rekap-unit', [DashboardController::class, 'rekap_unit'])->name('rekap_unit');
    Route::get('/zi/rekap-pengusulan-detail/{id}', [DashboardController::class, 'rekap_pengusulan_detail'])->name('rekap_pengusulan_detail');
    Route::get('/zi/rekap-administrasi', [DashboardController::class, 'rekap_administrasi'])->name('rekap_administrasi');
    Route::get('/zi/rekap-sanggah', [DashboardController::class, 'rekap_sanggah'])->name('rekap_sanggah');
    #Seleksi Administrasi
    Route::get('/zi/seleksi-administrasi', [AdministrasiController::class, 'index'])->name('seleksi_administrasi');
    Route::get('/zi/evaluasi-administrasi/{id}', [AdministrasiController::class, 'evaluasi_administrasi'])->name('evaluasi_administrasi');
    Route::post('/zi/evaluasi-administrasi/simpan', [AdministrasiController::class, 'evaluasi_administrasi_simpan'])->name('evaluasi_administrasi_simpan');
    #Proses Sanggah
    Route::get('/zi/sanggah', [SanggahController::class, 'index'])->name('sanggah');
    Route::get('/zi/proses-sanggah/{id}', [SanggahController::class, 'proses_sanggah'])->name('proses_sanggah');
    Route::post('/zi/proses-sanggah/simpan', [SanggahController::class, 'proses_sanggah_simpan'])->name('proses_sanggah_simpan');
    #Seleksi Dokumen
    Route::get('/zi/seleksi-dokumen', [DokumenController::class, 'index'])->name('seleksi_dokumen');
    Route::get('/zi/proses-dokumen/{id}', [DokumenController::class, 'evaluasi_dokumen'])->name('proses_dokumen');
    Route::post('/zi/proses-dokumen/simpan', [DokumenController::class, 'proses_dokumen_simpan'])->name('proses_dokumen_simpan');
    #Seleksi Wawancara
    Route::get('/zi/seleksi-wawancara', [WawancaraController::class, 'index'])->name('seleksi_wawancara');
    #Observasi Lapangan
    Route::get('/zi/observasi-lapangan', [ObservasiController::class, 'index'])->name('observasi-lapangan');
    #Seleksi Panel
    Route::get('/zi/seleksi-panel', [PanelController::class, 'index'])->name('seleksi_panel');
    #Tautkan LKE
    Route::get('/zi/template-lke-evaluator', [LkeEvaluatorController::class, 'template_lke'])->name('template_lke_evaluator');
    Route::get('/zi/lke-evaluator', [LkeEvaluatorController::class, 'index'])->name('lke_evaluator');
    Route::post('/zi/lke-evaluator-update', [LkeEvaluatorController::class, 'lke_evaluator_update'])->name('lke_evaluator_update');
    Route::get('/zi/download-template-lke', [LkeEvaluatorController::class, 'download_template_lke'])->name('download_template_lke');
    

    

    #===================Konfigurasi===================
    Route::get('/zi/update-predikat', [KonfigurasiController::class, 'update_predikat'])->name('update_predikat');
    Route::get('/zi/edit-predikat/{id}', [KonfigurasiController::class, 'edit_predikat'])->name('edit_predikat');
    Route::post('/zi/edit-predikat/', [KonfigurasiController::class, 'store_predikat'])->name('edit_predikat_store');
    #Kelola File
    Route::post('/zi/surat-sanggah/simpan', [KonfigurasiController::class, 'surat_sanggah_simpan'])->name('surat_sanggah_simpan');
    #Kelola Tim
    Route::get('/zi/kelola-tim', [KonfigurasiController::class, 'kelola_tim'])->name('kelola_tim_zi');
    Route::post('/zi/kelola-tim/simpan', [KonfigurasiController::class, 'kelola_tim_simpan'])->name('kelola_tim_zi_simpan');
    Route::get('/zi/kelola-tim/getDatas', [KonfigurasiController::class, 'tim_evaluasi_getDatas'])->name('getData_timEvaluasi');
    Route::get('/zi/kelola-tim/getData/{id}', [KonfigurasiController::class, 'tim_evaluasi_getData']);
    Route::post('/zi/kelola-tim/hapus', [KonfigurasiController::class, 'kelola_tim_hapus'])->name('kelola_tim_zi_hapus');
    #Kelola Anggota Tim
    Route::get('/zi/kelola-anggota-tim', [KonfigurasiController::class, 'kelola_anggota_tim'])->name('kelola_anggota_tim_zi');
    Route::post('/zi/kelola-anggota-tim/simpan', [KonfigurasiController::class, 'kelola_anggota_tim_simpan'])->name('kelola_anggota_tim_zi_simpan');
    Route::get('/zi/kelola-anggota-tim/getDatas', [KonfigurasiController::class, 'anggota_tim_evaluasi_getDatas'])->name('getData_anggotaTimEvaluasi');
    Route::get('/zi/kelola-anggota-tim/getData/{id}', [KonfigurasiController::class, 'anggota_tim_evaluasi_getData']);
    Route::post('/zi/kelola-anggota-tim/hapus', [KonfigurasiController::class, 'kelola_anggota_tim_hapus'])->name('kelola_anggota_tim_zi_hapus');
    #Kelola Unit Tim
    Route::get('/zi/kelola-unit-tim', [KonfigurasiController::class, 'kelola_unit_tim'])->name('kelola_unit_tim_zi');
    Route::post('/zi/kelola-unit-tim/simpan', [KonfigurasiController::class, 'kelola_unit_tim_simpan'])->name('kelola_unit_tim_zi_simpan');
    Route::get('/zi/kelola-unit-tim/getDatas', [KonfigurasiController::class, 'unit_tim_evaluasi_getDatas'])->name('getData_unitTimEvaluasi');
    Route::get('/zi/kelola-unit-tim/getData/{id}', [KonfigurasiController::class, 'unit_tim_evaluasi_getData']);
    Route::post('/zi/kelola-unit-tim/hapus', [KonfigurasiController::class, 'kelola_unit_tim_hapus'])->name('kelola_unit_tim_zi_hapus');
    


    #generate
    //Route::get('/zi/generate_skor', [GenerateDataController::class, 'generate_rekap_instansi_skor'])->name('pengusulan_zi');
    Route::get('/zi/sinkron_final_completed', [GenerateDataController::class, 'sinkron_final_completed']);
    
});