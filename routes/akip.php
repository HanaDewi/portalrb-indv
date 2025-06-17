<?php

use App\Http\Controllers\Akip\EvaluasiSakipController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'akip', 'as' => 'akip.'], function () {
    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', function () {
            return view('akip.dashboard');
        })->name('dashboard');
        Route::get('/evaluasi/sakip', [EvaluasiSakipController::class, 'evaluasi_sakip'])->name('evaluasi.sakip');
    });
});
