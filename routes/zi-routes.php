<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\ZI\PengusulanZIController;
use App\Http\Controllers\ZI\DashboardController;


Route::middleware('auth')->group(function () {
    Route::get('/zi', [PengusulanZIController::class, 'index'])->name('pengusulan_zi');
    Route::post('/zi', [PengusulanZIController::class, 'store_bukti_dukung'])->name('pengusulan_zi_store');
    Route::get('/zi-tinjau', [PengusulanZIController::class, 'tinjau'])->name('tinjau_zi');
    #Route::get('/zi/generate_skor', [GenerateController::class, 'generate_rekap_instansi_skor'])->name('pengusulan_zi');
    Route::get('/zi/admin', [DashboardController::class, 'index'])->name('dashboard_zi');
    Route::get('/zi/rekap-pengusulan', [DashboardController::class, 'rekap_pengusulan'])->name('rekap_pengusulan');
    Route::get('/zi/rekap-unit', [DashboardController::class, 'rekap_unit'])->name('rekap_unit');
    Route::get('/zi/update-predikat', [DashboardIController::class, 'update_predikat'])->name('update_predikat');
    Route::get('/zi/edit-predikat/{id}', [DashboardController::class, 'edit_predikat'])->name('edit_predikat');
    Route::post('/zi/edit-predikat/', [DashboardController::class, 'store_predikat'])->name('edit_predikat_store');
    Route::get('/zi/rekap-pengusulan-detail/{id}', [DashboardController::class, 'rekap_pengusulan_detail'])->name('rekap_pengusulan_detail');
});