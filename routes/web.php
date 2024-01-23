<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RBGeneralController;
use App\Http\Controllers\RBTematikController;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\HasilController;

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
    // MASTER DATA
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
    // Tema
    Route::get('/master-data/tema', [MasterDataController::class, 'tema'])->name('tema');
    Route::get('/master-data/tema/getDatas', [MasterDataController::class, 'tema_getDatas']);
    Route::get('/master-data/tema/getData/{id}', [MasterDataController::class, 'tema_getData']);
    Route::post('/master-data/tema/simpan', [MasterDataController::class, 'tema_simpan']);
    Route::post('/master-data/tema/hapus', [MasterDataController::class, 'tema_hapus']);

    // RB General Perencanaan
    Route::get('/rb-general/perencanaan', [RBGeneralController::class, 'perencanaan'])->name('perencanaan');
    Route::get('/rb-general/perencanaan/getData/{kegiatan_utama_id}/{indikator_id}', [RBGeneralController::class, 'perencanaan_getData']);
    Route::post('/rb-general/perencanaan/simpanBaseline', [RBGeneralController::class, 'perencanaan_simpanBaseline']);
    Route::get('/rb-general/perencanaan/getTarget/{kegiatan_utama_id}/{indikator_id}', [RBGeneralController::class, 'perencanaan_getTarget']);
    Route::post('/rb-general/perencanaan/simpanTarget', [RBGeneralController::class, 'perencanaan_simpanTarget']);
    Route::post('/rb-general/perencanaan/simpanMonev', [RBGeneralController::class, 'perencanaan_simpanMonev']);
    // RB General Rencana Aksi
    Route::get('/rb-general/perencanaan/{perencanaan_id}/{target_id}/rencana_aksi', [RBGeneralController::class, 'rencana_aksi'])->name('rencana_aksi');
    Route::get('/rb-general/perencanaan/{perencanaan_id}/{target_id}/rencana_aksi/getDatas', [RBGeneralController::class, 'rencana_aksi_getDatas']);
    Route::get('/rb-general/perencanaan/{perencanaan_id}/{target_id}/rencana_aksi/getData/{id}', [RBGeneralController::class, 'rencana_aksi_getData']);
    Route::post('/rb-general/perencanaan/{perencanaan_id}/{target_id}/rencana_aksi/simpan', [RBGeneralController::class, 'rencana_aksi_simpan']);
    Route::post('/rb-general/perencanaan/{perencanaan_id}/{target_id}/rencana_aksi/hapus', [RBGeneralController::class, 'rencana_aksi_hapus']);
    // RB General Evaluasi
    Route::get('/rb-general/perencanaan/{perencanaan_id}/{target_id}/monev', [RBGeneralController::class, 'monev'])->name('monev');
    Route::get('/rb-general/perencanaan/{perencanaan_id}/{target_id}/monev/getDatas', [RBGeneralController::class, 'rencana_aksi_getDatas']);
    Route::get('/rb-general/perencanaan/{perencanaan_id}/{target_id}/monev/getData/{id}', [RBGeneralController::class, 'rencana_aksi_getData']);
    Route::get('/rb-general/perencanaan/{perencanaan_id}/{target_id}/monev/getTarget', [RBGeneralController::class, 'monev_getTarget']);
    Route::post('/rb-general/perencanaan/{perencanaan_id}/{target_id}/monev/simpan', [RBGeneralController::class, 'monev_simpan']);
    Route::post('/rb-general/perencanaan/{perencanaan_id}/{target_id}/monev/simpanTarget', [RBGeneralController::class, 'monev_simpanTarget']);
    // RB General Rekap Data
    Route::get('/rb-general/rekap_data', [RBGeneralController::class, 'rekap_data']);
    Route::get('/rb-general/rekap_data/getTarget/{id}', [RBGeneralController::class, 'rekap_data_getTarget']);
    Route::post('/rb-general/rekap_data/simpanCatatanEvaluator', [RBGeneralController::class, 'rekap_data_simpanCatatanEvaluator']);
    // RB Tematik Sasaran Road Map
    Route::get('/rb-tematik/perencanaan', [RBTematikController::class, 'perencanaan'])->name('perencanaan');
    Route::get('/rb-tematik/perencanaan/getData/{kegiatan_utama_id}/{indikator_id}', [RBTematikController::class, 'perencanaan_getData']);
    Route::post('/rb-tematik/perencanaan/simpan-sasaran-roadmap', [RBTematikController::class, 'simpanSasaranRoadmap']);
    Route::post('/rb-tematik/perencanaan/simpan-indikator-roadmap', [RBTematikController::class, 'simpanIndikatorRoadmap']);
    Route::post('/rb-tematik/perencanaan/simpan-permasalahan', [RBTematikController::class, 'simpanPermasalahan']);
    Route::post('/rb-tematik/perencanaan/simpan-indikator-permasalahan', [RBTematikController::class, 'simpanIndikatorPermasalahan']);
    Route::post('/rb-tematik/perencanaan/simpanMonev', [RBTematikController::class, 'perencanaan_simpanMonev']);
    // RB Tematik Rekap Data
    Route::get('/rb-tematik/rekap_data', [RBTematikController::class, 'rekap_data']);
    Route::get('/rb-tematik/rekap_data/getPerencanaan/{id}', [RBTematikController::class, 'rekap_data_getPerencanaan']);
    Route::post('/rb-tematik/rekap_data/simpanCatatanEvaluator', [RBTematikController::class, 'rekap_data_simpanCatatanEvaluator']);
    // Hasil
    Route::get('/hasil-seluruh', [HasilController::class, 'hasil_seluruh'])->name('hasil_seluruh');
    Route::get('/hasil/{KlpdInstansi}', [HasilController::class, 'hasil'])->name('hasil');
});

require __DIR__ . '/auth.php';
