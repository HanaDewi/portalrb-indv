<?php

use App\Http\Controllers\Akip\DashboardController;
use App\Http\Controllers\Akip\EvaluasiController;
use App\Http\Controllers\Akip\TimEvaluasiController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'akip', 'as' => 'akip.'], function () {
    Route::middleware('auth')->group(function () {
        // Dashboard routes
        Route::controller(DashboardController::class)->prefix('dashboard')->group(function () {
            Route::get('/', 'index')->name('dashboard');
            Route::get('/filter', 'filter')->name('dashboard.filter');
            Route::get('/filter/kl', 'filterKl')->name('dashboard.filter.kl');
            Route::get('/detail-instansi', 'getDetailInstansi')->name('dashboard.detail-instansi');
        });
        
        // Evaluasi SAKIP routes
        Route::controller(EvaluasiController::class)->prefix('evaluasi')->group(function () {
            Route::get('/sakip/{instansi}/data/{id}', 'getData')->name('evaluasi.data');
            Route::post('/sakip/{instansi}/check-periode', 'checkPeriode')->name('evaluasi.check');
            Route::post('/sakip/{instansi}', 'store')->name('evaluasi.store');
            Route::put('/sakip/{instansi}/{id}', 'update')->name('evaluasi.update');
            Route::delete('/sakip/{instansi}/{id}', 'destroy')->name('evaluasi.destroy');
            Route::post('/sakip/search', 'search')->name('evaluasi.search');
            Route::get('/sakip/{instansi}', 'show')->name('evaluasi.show');
            Route::get('/sakip', 'index')->name('evaluasi.index');
        });
        
        // Tim Evaluasi routes
        Route::controller(TimEvaluasiController::class)->prefix('tim')->group(function () {
            Route::get('/all-teams', 'allTeams')->name('tim.all-teams');
            Route::get('/our-team', 'ourTeam')->name('tim.our-team');
            Route::get('/data', 'getDatas')->name('tim.data');
            Route::get('/{tim_id}/instansi', 'getInstansiByTim')->name('tim.instansi-by-tim');
            Route::get('/{user}/instansi', 'getInstansi')->name('tim.instansi');
        });
    });
});
