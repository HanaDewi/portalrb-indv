<?php

use App\Http\Controllers\Akip\EvaluasiSakipController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'akip', 'as' => 'akip.'], function () {
    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', function () {
            return view('akip.dashboard');
        })->name('dashboard');
        Route::get('/evaluasi/sakip', [EvaluasiSakipController::class, 'evaluasi_sakip'])->name('evaluasi.sakip');
        Route::get('/evaluasi/sakip/{instansi_id}', [EvaluasiSakipController::class, 'evaluasi_sakip_instansi']);
        Route::get('/evaluasi/sakip/{instansi_id}/getData/{id}', [EvaluasiSakipController::class, 'evaluasi_sakip_instansi_getData']);
        Route::post('/evaluasi/sakip/{instansi_id}/simpan', [EvaluasiSakipController::class, 'evaluasi_sakip_instansi_simpan']);
        Route::post('/evaluasi/sakip/{instansi_id}/cekPeriode', [EvaluasiSakipController::class, 'evaluasi_sakip_instansi_cekPeriode']);
        Route::delete('/evaluasi/sakip/{instansi_id}/hapus/{id}', [EvaluasiSakipController::class, 'evaluasi_sakip_instansi_hapus']);
    });
});
