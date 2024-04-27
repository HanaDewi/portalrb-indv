<?php

use App\Http\Controllers\DokumenController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RBGeneralController;
use App\Http\Controllers\RBTematikController;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\HasilController;
use App\Http\Controllers\RuangBelajar\DashboardController;
use App\Http\Controllers\RuangBelajar\AdminController;

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
    // Dokumen
    Route::get('/master-data/dokumen', [MasterDataController::class, 'dokumen'])->name('master-data_dokumen');
    Route::get('/master-data/dokumen/getDataTahun', [MasterDataController::class, 'dokumen_getDataTahun']);
    Route::post('/master-data/dokumen/simpanTahun', [MasterDataController::class, 'dokumen_simpanTahun']);
    Route::post('/master-data/dokumen/hapusTahun', [MasterDataController::class, 'dokumen_hapusTahun']);
    Route::get('/master-data/dokumen/getDataKategoris', [MasterDataController::class, 'dokumen_getDataKategoris']);
    Route::get('/master-data/dokumen/getDataKategori/{id}', [MasterDataController::class, 'dokumen_getDataKategori']);
    Route::post('/master-data/dokumen/simpanKategori', [MasterDataController::class, 'dokumen_simpanKategori']);
    Route::post('/master-data/dokumen/hapusKategori', [MasterDataController::class, 'dokumen_hapusKategori']);


    // Dokumen Upload
    Route::get('/dokumen', [DokumenController::class, 'index'])->name('dokumen');
    Route::get('/dokumen/getDatas', [DokumenController::class, 'getDatas']);
    Route::get('/dokumen/getData/{tahun}/{kategori_id}', [DokumenController::class, 'getData']);
    Route::post('/dokumen/simpan', [DokumenController::class, 'simpan']);
    Route::post('/dokumen/hapus', [DokumenController::class, 'hapus']);

    // RB General Perencanaan
    Route::get('/rb-general/perencanaan', [RBGeneralController::class, 'perencanaan'])->name('perencanaan');
    Route::get('/rb-general/perencanaan/getData/{kegiatan_utama_id}/{indikator_id}', [RBGeneralController::class, 'perencanaan_getData']);
    Route::post('/rb-general/perencanaan/simpanBaseline', [RBGeneralController::class, 'perencanaan_simpanBaseline']);
    Route::post('/rb-general/perencanaan/hapusBaseline', [RBGeneralController::class, 'perencanaan_hapusBaseline']);
    Route::get('/rb-general/perencanaan/getTarget/{kegiatan_utama_id}/{indikator_id}', [RBGeneralController::class, 'perencanaan_getTarget']);
    Route::post('/rb-general/perencanaan/simpanTarget', [RBGeneralController::class, 'perencanaan_simpanTarget']);
    Route::post('/rb-general/perencanaan/simpanMonev', [RBGeneralController::class, 'perencanaan_simpanMonev']);
    Route::get('/rb-general/perencanaan/getDokumen/{id}', [RBGeneralController::class, 'perencanaan_getDokumen']);
    Route::post('/rb-general/perencanaan/simpanDokumen', [RBGeneralController::class, 'perencanaan_simpanDokumen']);
    // RB General Rencana Aksi
    Route::get('/rb-general/perencanaan/{perencanaan_id}/{target_id}/rencana_aksi', [RBGeneralController::class, 'rencana_aksi'])->name('rencana_aksi');
    Route::get('/rb-general/perencanaan/{perencanaan_id}/{target_id}/rencana_aksi/downloadTemplate', [RBGeneralController::class, 'rencana_aksi_downloadTemplate']);
    Route::post('/rb-general/perencanaan/{perencanaan_id}/{target_id}/rencana_aksi/import', [RBGeneralController::class, 'rencana_aksi_import']);
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
    Route::get('/rb-tematik/perencanaan', [RBTematikController::class, 'tema_sasaran'])->name('tema_sasaran');
    Route::get('/rb-tematik/perencanaan/getData/{indikator_id}', [RBTematikController::class, 'indikator_getData']);
    Route::post('/rb-tematik/perencanaan/simpan-sasaran-roadmap', [RBTematikController::class, 'simpanSasaranRoadmap']);
    Route::post('/rb-tematik/perencanaan/simpan-indikator-roadmap', [RBTematikController::class, 'simpanIndikatorRoadmap']);
    Route::post('/rb-tematik/perencanaan/indikator_roadmap/hapus/{indikator_id}', [RBTematikController::class, 'indikatorRoadmapHapus']);
    // RB Tematik Permasalahan
    Route::get('/rb-tematik/permasalahan', [RBTematikController::class, 'permasalahan'])->name('permasalahan');
    Route::get('/rb-tematik/permasalahan/get-indikator-roadmap', [RBTematikController::class, 'getIndikatorRoadmap'])->name('get_indikator_roadmap');
    Route::post('/rb-tematik/permasalahan/simpan-permasalahan', [RBTematikController::class, 'simpanPermasalahan']);
    Route::get('/rb-tematik/permasalahan/get-permasalahan/{indikator_permasalahan_id}', [RBTematikController::class, 'get_permaalahan'])->name('get_permasalahan');
    Route::get('/rb-tematik/permasalahan/get-indikator-permasalahan/{indikator_permasalahan_id}', [RBTematikController::class, 'get_indikator_permaalahan'])->name('get_indikator_permasalahan');
    Route::post('/rb-tematik/permasalahan/simpan-indikator-permasalahan', [RBTematikController::class, 'simpanIndikatorPermasalahan']);
    // RB Tematik Renaksi
    Route::get('/rb-tematik/permasalahan/renaksi/{indikator_id}', [RBTematikController::class, 'rencana_aksi'])->name('rencana_aksi_tematik');
    Route::get('/rb-tematik/permasalahan/renaksi/{indikator_id}/getDatas', [RBTematikController::class, 'rencana_aksi_getDatas']);
    Route::get('/rb-tematik/permasalahan/renaksi/getData/{renaksi_output_id}', [RBTematikController::class, 'rencana_aksi_getData']);
    Route::post('/rb-tematik/permasalahan/renaksi/{indikator_id}/simpan', [RBTematikController::class, 'rencana_aksi_simpan']);
    Route::post('/rb-tematik/permasalahan/renaksi/{renaksi_output_id}/hapus', [RBTematikController::class, 'rencana_aksi_hapus']);
    // RB Tematik MONEV
    Route::post('/rb-tematik/perencanaan/monev/simpan-indikator-roadmap', [RBTematikController::class, 'simpanMonevIndikatorRoadmap']);
    Route::get('/rb-tematik/permasalahan/monev/{indikator_id}', [RBTematikController::class, 'monev'])->name('monev_tematik');
    Route::get('/rb-tematik/permasalahan/monev/{indikator_id}/getDatas', [RBTematikController::class, 'rencana_aksi_getDatas']);
    Route::get('/rb-tematik/permasalahan/monev/getData/{renaksi_output_id}', [RBTematikController::class, 'rencana_aksi_getData']);
    Route::get('/rb-tematik/permasalahan/monev/{indikator_id}/getIndikator', [RBTematikController::class, 'monev_getIndikatorPermasalahan']);
    Route::post('/rb-tematik/permasalahan/monev/{indikator_id}/simpanIndikatorPermasalahan', [RBTematikController::class, 'monev_simpanIndikatorPermasalahan']);
    Route::post('/rb-tematik/permasalahan/monev/{indikator_id}/simpan', [RBTematikController::class, 'monev_simpan']);
    Route::post('/rb-tematik/permasalahan/monev/{renaksi_output_id}/hapus', [RBTematikController::class, 'monev_hapus']);

    // RB Tematik Rekap Data
    Route::get('/rb-tematik/rekap_data', [RBTematikController::class, 'rekap_data']);
    Route::get('/rb-tematik/rekap_data/getPerencanaan/{id}', [RBTematikController::class, 'rekap_data_getPerencanaan']);
    Route::post('/rb-tematik/rekap_data/simpanCatatanEvaluator', [RBTematikController::class, 'rekap_data_simpanCatatanEvaluator']);
    // Hasil
    Route::get('/hasil', [HasilController::class, 'hasil_seluruh'])->name('hasil_seluruh');
    Route::get('/hasil/{KlpdInstansi}', [HasilController::class, 'hasil'])->name('hasil');
    Route::get('/hasil/get_test_tp_line/{id}', [HasilController::class, 'get_test_tp_line']);
    Route::get('/hasil/get_test_tp/{id}', [HasilController::class, 'get_test_tp']);
    Route::post('/hasil/simpan_test_tp_line', [HasilController::class, 'simpan_test_tp_line']);
    Route::post('/hasil/simpan_test_tp', [HasilController::class, 'simpan_test_tp']);
    // Access
    Route::get('/access', [HasilController::class, 'access'])->name('access');
    Route::post('/access/simpan', [HasilController::class, 'access_simpan']);
});

#########Ruang Belajar
Route::group(['prefix' => 'ruang-belajar', 'as' => 'ruang-belajar.'], function () {
    Route::get('/home', [DashboardController::class, 'index'])->name('home');
    Route::get('/praktek-details/{slug}', [DashboardController::class, 'ShowPraktek'])->name('praktek-details');
    /** Article Details Routes */
    Route::get('article', [DashboardController::class, 'news'])->name('article');
    /** Article Comment Routes */
    Route::post('article-comment', [DashboardController::class, 'handleComment'])->name('article-comment');
    Route::post('article-comment-replay', [DashboardController::class, 'handleReplay'])->name('article-comment-replay');

    Route::middleware('auth')->group(function () {
        /** Admin */
        Route::get('admin-dashboard', [AdminController::class, 'index'])->name('admin-dashboard');
        Route::get('category', [AdminController::class, 'category'])->name('admin-category');
        Route::get('category-edit/{id}', [AdminController::class, 'category_edit'])->name('admin-category-edit');
        Route::get('artikel', [AdminController::class, 'artikel'])->name('admin-artikel');
        Route::get('artikel-pending', [AdminController::class, 'artikel_pending'])->name('admin-artikel-pending');
        Route::get('social-media', [AdminController::class, 'social_media'])->name('admin-social-media');
        Route::get('subscriber', [AdminController::class, 'subscriber'])->name('admin-subscriber');
    });
});

require __DIR__ . '/auth.php';
