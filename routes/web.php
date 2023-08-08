<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('home');
});
Route::get('/emptyDT', function () {
    return response()->json(['data' => []]);
});


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/profil', [HomeController::class, 'profil'])->name('profil');
    Route::post('/profil_simpan', [HomeController::class, 'profil_simpan']);
    // Kegiatan Utama
    Route::get('/master-data/kegiatan_utama', [MasterDataController::class, 'kegiatan_utama'])->name('kegiatan_utama');
    Route::get('/master-data/kegiatan_utama/getDatas', [MasterDataController::class, 'kegiatan_utama_getDatas']);
    Route::get('/master-data/kegiatan_utama/getData/{id}', [MasterDataController::class, 'kegiatan_utama_getData']);
    Route::post('/master-data/kegiatan_utama/simpan', [MasterDataController::class, 'kegiatan_utama_simpan']);
    Route::post('/master-data/kegiatan_utama/hapus', [MasterDataController::class, 'kegiatan_utama_hapus']);
    // Indikator
    Route::get('/master-data/indikator', [MasterDataController::class, 'indikator'])->name('indikator');
    Route::get('/master-data/indikator/getDatas', [MasterDataController::class, 'indikator_getDatas']);
    Route::get('/master-data/indikator/getData/{id}', [MasterDataController::class, 'indikator_getData']);
    Route::post('/master-data/indikator/simpan', [MasterDataController::class, 'indikator_simpan']);
    Route::post('/master-data/indikator/hapus', [MasterDataController::class, 'indikator_hapus']);
});

require __DIR__ . '/auth.php';
