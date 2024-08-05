<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ZI\DashboardController;
use App\Http\Controllers\ZI\KonfigurasiController;
use App\Http\Controllers\ZI\PengusulanZIController;


Route::middleware('auth')->group(function () {
    #evaluatan
    Route::get('/zi', [PengusulanZIController::class, 'index'])->name('pengusulan_zi');
    Route::post('/zi', [PengusulanZIController::class, 'store_bukti_dukung'])->name('pengusulan_zi_store');
    Route::get('/zi-tinjau', [PengusulanZIController::class, 'tinjau'])->name('tinjau_zi');
    #generate
    #Route::get('/zi/generate_skor', [GenerateController::class, 'generate_rekap_instansi_skor'])->name('pengusulan_zi');
    #Admin Pengusulan
    Route::get('/zi/admin', [DashboardController::class, 'index'])->name('dashboard_zi');
    Route::get('/zi/rekap-pengusulan', [DashboardController::class, 'rekap_pengusulan'])->name('rekap_pengusulan');
    Route::get('/zi/rekap-unit', [DashboardController::class, 'rekap_unit'])->name('rekap_unit');
    Route::get('/zi/rekap-pengusulan-detail/{id}', [DashboardController::class, 'rekap_pengusulan_detail'])->name('rekap_pengusulan_detail');
    #SeleksiAdministrasi

    #===================Konfigurasi===================
    Route::get('/zi/update-predikat', [KonfigurasiController::class, 'update_predikat'])->name('update_predikat');
    Route::get('/zi/edit-predikat/{id}', [KonfigurasiController::class, 'edit_predikat'])->name('edit_predikat');
    Route::post('/zi/edit-predikat/', [KonfigurasiController::class, 'store_predikat'])->name('edit_predikat_store');
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
    
});