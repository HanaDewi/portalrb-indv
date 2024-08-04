<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\ZI\PengusulanZIController;


Route::middleware('auth')->group(function () {
    Route::get('/zi', [PengusulanZIController::class, 'index'])->name('pengusulan_zi');
    Route::post('/zi', [PengusulanZIController::class, 'store_bukti_dukung'])->name('pengusulan_zi_store');
    Route::get('/zi-tinjau', [PengusulanZIController::class, 'tinjau'])->name('tinjau_zi');
    #Route::get('/zi/generate_skor', [PengusulanZIController::class, 'generate_rekap_instansi_skor'])->name('pengusulan_zi');
    Route::get('/zi/admin', [PengusulanZIController::class, 'dashboard'])->name('dashboard_zi');
    Route::get('/zi/rekap-pengusulan', [PengusulanZIController::class, 'rekap_pengusulan'])->name('rekap_pengusulan');
    Route::get('/zi/rekap-unit', [PengusulanZIController::class, 'rekap_unit'])->name('rekap_unit');
    Route::get('/zi/update-predikat', [PengusulanZIController::class, 'update_predikat'])->name('update_predikat');
    Route::get('/zi/edit-predikat/{id}', [PengusulanZIController::class, 'edit_predikat'])->name('edit_predikat');
    Route::post('/zi/edit-predikat/', [PengusulanZIController::class, 'store_predikat'])->name('edit_predikat_store');
    Route::get('/zi/rekap-pengusulan-detail/{id}', [PengusulanZIController::class, 'rekap_pengusulan_detail'])->name('rekap_pengusulan_detail');
});