<?php

use App\Http\Controllers\DokumenController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RBGeneralController;
use App\Http\Controllers\RBTematikController;
use App\Http\Controllers\RBTematikImportController;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\HasilController;
use App\Http\Controllers\ManageUserController;
use App\Http\Controllers\RuangBelajar\DashboardController;
use App\Http\Controllers\RuangBelajar\AdminController;
use App\Http\Controllers\WebDashboardController;
use App\Http\Controllers\CapaianOutputController;
use App\Http\Controllers\ERenaksiRBGeneralController;
use App\Http\Controllers\DataLKERenaksiController;

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
})->name('home');



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

    // Web Dashboard
    Route::middleware(['auth'])->group(function () {
    Route::get('webdashboard/rb-general/rencana-aksi', [WebDashboardController::class, 'rbGeneral'])->name('webdashboard.rb-general');
    Route::get('webdashboard/rb-general/capaian-output', [CapaianOutputController::class, 'rbGeneralCapaianOutput'])->name('webdashboard.rb-general.capaian-output');
    Route::get('webdashboard/rb-tematik/rencana-aksi', [WebDashboardController::class, 'rbTematik'])->name('webdashboard.rb-tematik');
    Route::get('webdashboard/rb-tematik/capaian-output', [CapaianOutputController::class, 'rbTematikCapaianOutput'])->name('webdashboard.rb-tematik.capaian-output');
    Route::get('webdashboard/hasil-evaluasi', [WebDashboardController::class, 'hasilEvaluasi'])->name('webdashboard.hasil-evaluasi');
    });
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
    Route::get('/rencana_aksi/rb-general/perencanaan', [RBGeneralController::class, 'perencanaan'])->name('perencanaan');
    Route::get('/rencana_aksi/rb-general/perencanaan/getData/{kegiatan_utama_id}/{indikator_id}', [RBGeneralController::class, 'perencanaan_getData']);
    Route::post('/rencana_aksi/rb-general/perencanaan/simpanBaseline', [RBGeneralController::class, 'perencanaan_simpanBaseline']);
    Route::post('/rencana_aksi/rb-general/perencanaan/hapusBaseline', [RBGeneralController::class, 'perencanaan_hapusBaseline']);
    Route::get('/rencana_aksi/rb-general/perencanaan/getTarget/{kegiatan_utama_id}/{indikator_id}', [RBGeneralController::class, 'perencanaan_getTarget']);
    Route::post('/rencana_aksi/rb-general/perencanaan/simpanTarget', [RBGeneralController::class, 'perencanaan_simpanTarget']);
    Route::post('/rencana_aksi/rb-general/perencanaan/simpanMonev', [RBGeneralController::class, 'perencanaan_simpanMonev']);
    Route::get('/rencana_aksi/rb-general/perencanaan/getDokumen/{id}', [RBGeneralController::class, 'perencanaan_getDokumen']);
    Route::post('/rencana_aksi/rb-general/perencanaan/simpanDokumen', [RBGeneralController::class, 'perencanaan_simpanDokumen']);
    // RB General Rencana Aksi
    Route::get('/rencana_aksi/rb-general/perencanaan/{perencanaan_id}/{target_id}/rencana_aksi', [RBGeneralController::class, 'rencana_aksi'])->name('rencana_aksi');
    Route::get('/rencana_aksi/rb-general/perencanaan/{perencanaan_id}/{target_id}/rencana_aksi/downloadTemplate', [RBGeneralController::class, 'rencana_aksi_downloadTemplate']);
    Route::post('/rencana_aksi/rb-general/perencanaan/{perencanaan_id}/{target_id}/rencana_aksi/import', [RBGeneralController::class, 'rencana_aksi_import']);
    Route::get('/rencana_aksi/rb-general/perencanaan/{perencanaan_id}/{target_id}/rencana_aksi/getDatas', [RBGeneralController::class, 'rencana_aksi_getDatas']);
    Route::get('/rencana_aksi/rb-general/perencanaan/{perencanaan_id}/{target_id}/rencana_aksi/getData/{id}', [RBGeneralController::class, 'rencana_aksi_getData']);
    Route::post('/rencana_aksi/rb-general/perencanaan/{perencanaan_id}/{target_id}/rencana_aksi/simpan', [RBGeneralController::class, 'rencana_aksi_simpan']);
    Route::post('/rencana_aksi/rb-general/perencanaan/{perencanaan_id}/{target_id}/rencana_aksi/hapus', [RBGeneralController::class, 'rencana_aksi_hapus']);
    // RB General Evaluasi
    Route::get('/rencana_aksi/rb-general/perencanaan/{perencanaan_id}/{target_id}/monev', [RBGeneralController::class, 'monev'])->name('monev');
    Route::get('/rencana_aksi/rb-general/perencanaan/{perencanaan_id}/{target_id}/monev/getDatas', [RBGeneralController::class, 'rencana_aksi_getDatas']);
    Route::get('/rencana_aksi/rb-general/perencanaan/{perencanaan_id}/{target_id}/monev/getData/{id}', [RBGeneralController::class, 'rencana_aksi_getData']);
    Route::get('/rencana_aksi/rb-general/perencanaan/{perencanaan_id}/{target_id}/monev/getTarget', [RBGeneralController::class, 'monev_getTarget']);
    Route::post('/rencana_aksi/rb-general/perencanaan/{perencanaan_id}/{target_id}/monev/simpan', [RBGeneralController::class, 'monev_simpan']);
    Route::post('/rencana_aksi/rb-general/perencanaan/{perencanaan_id}/{target_id}/monev/simpanTarget', [RBGeneralController::class, 'monev_simpanTarget']);
    // RB General Rekap Data
    Route::get('/rencana_aksi/rb-general/rekap_data', [RBGeneralController::class, 'rekap_data']);
    Route::get('/rencana_aksi/rb-general/rekap_data/getTarget/{id}', [RBGeneralController::class, 'rekap_data_getTarget']);
    Route::post('/rencana_aksi/rb-general/rekap_data/simpanCatatanEvaluator', [RBGeneralController::class, 'rekap_data_simpanCatatanEvaluator']);
    // RB Tematik Sasaran Road Map
    Route::get('/rencana_aksi/rb-tematik/perencanaan', [RBTematikController::class, 'tema_sasaran'])->name('tema_sasaran');
    Route::get('/rencana_aksi/rb-tematik/perencanaan/getSasaran/{sasaran_id}', [RBTematikController::class, 'sasaran_getData']);
    Route::get('/rencana_aksi/rb-tematik/perencanaan/getData/{indikator_id}', [RBTematikController::class, 'indikator_getData']);
    Route::post('/rencana_aksi/rb-tematik/perencanaan/sasaran_roadmap/hapus/{sasaran_id}', [RBTematikController::class, 'sasaranRoadmapHapus']);
    Route::post('/rencana_aksi/rb-tematik/perencanaan/simpan-sasaran-roadmap', [RBTematikController::class, 'simpanSasaranRoadmap']);
    Route::post('/rencana_aksi/rb-tematik/perencanaan/simpan-indikator-roadmap', [RBTematikController::class, 'simpanIndikatorRoadmap']);
    Route::post('/rencana_aksi/rb-tematik/perencanaan/indikator_roadmap/hapus/{indikator_id}', [RBTematikController::class, 'indikatorRoadmapHapus']);
    Route::get('/rencana_aksi/rb-tematik/perencanaan/downloadTemplate', [RBTematikImportController::class, 'rbTematik_downloadTemplate']);
    Route::post('/rencana_aksi/rb-tematik/perencanaan/import', [RBTematikImportController::class, 'rbTematik_import']);
    // RB Tematik Permasalahan
    Route::get('/rencana_aksi/rb-tematik/permasalahan', [RBTematikController::class, 'permasalahan'])->name('permasalahan');
    Route::get('/rencana_aksi/rb-tematik/permasalahan/get-indikator-roadmap', [RBTematikController::class, 'getIndikatorRoadmap'])->name('get_indikator_roadmap');
    Route::post('/rencana_aksi/rb-tematik/permasalahan/simpan-permasalahan', [RBTematikController::class, 'simpanPermasalahan']);
    Route::get('/rencana_aksi/rb-tematik/permasalahan/get-permasalahan/{indikator_permasalahan_id}', [RBTematikController::class, 'get_permasalahan'])->name('get_permasalahan');
    Route::post('/rencana_aksi/rb-tematik/permasalahan/hapus/{permasalahan_id}', [RBTematikController::class, 'permasalahanHapus']);
    Route::get('/rencana_aksi/rb-tematik/permasalahan/get-indikator-permasalahan/{indikator_permasalahan_id}', [RBTematikController::class, 'get_indikator_permasalahan'])->name('get_indikator_permasalahan');
    Route::post('/rencana_aksi/rb-tematik/permasalahan/simpan-indikator-permasalahan', [RBTematikController::class, 'simpanIndikatorPermasalahan']);
    Route::post('/rencana_aksi/rb-tematik/permasalahan/indikator_permasalahan/hapus/{indikator_id}', [RBTematikController::class, 'indikatorPermasalahanHapus']);
    // RB Tematik Renaksi
    Route::get('/rencana_aksi/rb-tematik/permasalahan/renaksi/{indikator_id}', [RBTematikController::class, 'rencana_aksi'])->name('rencana_aksi_tematik');
    Route::get('/rencana_aksi/rb-tematik/permasalahan/renaksi/{indikator_id}/getDatas', [RBTematikController::class, 'rencana_aksi_getDatas']);
    Route::get('/rencana_aksi/rb-tematik/permasalahan/renaksi/getData/{renaksi_output_id}', [RBTematikController::class, 'rencana_aksi_getData']);
    Route::post('/rencana_aksi/rb-tematik/permasalahan/renaksi/{indikator_id}/simpan', [RBTematikController::class, 'rencana_aksi_simpan']);
    Route::post('/rencana_aksi/rb-tematik/permasalahan/renaksi/{renaksi_output_id}/hapus', [RBTematikController::class, 'rencana_aksi_hapus']);
    // RB Tematik MONEV
    Route::post('/rencana_aksi/rb-tematik/perencanaan/monev/simpan-indikator-roadmap', [RBTematikController::class, 'simpanMonevIndikatorRoadmap']);
    Route::get('/rencana_aksi/rb-tematik/permasalahan/monev/{indikator_id}', [RBTematikController::class, 'monev'])->name('monev_tematik');
    Route::get('/rencana_aksi/rb-tematik/permasalahan/monev/{indikator_id}/getDatas', [RBTematikController::class, 'rencana_aksi_getDatas']);
    Route::get('/rencana_aksi/rb-tematik/permasalahan/monev/getData/{renaksi_output_id}', [RBTematikController::class, 'rencana_aksi_getData']);
    Route::get('/rencana_aksi/rb-tematik/permasalahan/monev/{indikator_id}/getIndikator', [RBTematikController::class, 'monev_getIndikatorPermasalahan']);
    Route::post('/rencana_aksi/rb-tematik/permasalahan/monev/{indikator_id}/simpanIndikatorPermasalahan', [RBTematikController::class, 'monev_simpanIndikatorPermasalahan']);
    Route::post('/rencana_aksi/rb-tematik/permasalahan/monev/{indikator_id}/simpan', [RBTematikController::class, 'monev_simpan']);
    Route::post('/rencana_aksi/rb-tematik/permasalahan/monev/{renaksi_output_id}/hapus', [RBTematikController::class, 'monev_hapus']);

    // RB Tematik Rekap Data
    Route::get('/rencana_aksi/rb-tematik/rekap_data', [RBTematikController::class, 'rekap_data']);
    Route::get('/rencana_aksi/rb-tematik/rekap_data/getPerencanaan/{id}', [RBTematikController::class, 'rekap_data_getPerencanaan']);
    Route::post('/rencana_aksi/rb-tematik/rekap_data/simpanCatatanEvaluator', [RBTematikController::class, 'rekap_data_simpanCatatanEvaluator']);

    // Evaluasi
    Route::get('/evaluasi/renaksi-rb-general', [ERenaksiRBGeneralController::class, 'index']);
    Route::get('/evaluasi/data-lke-renaksi', [DataLKERenaksiController::class, 'index']);

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
    
    // Activity Log
    Route::get('/activitylog', [HomeController::class, 'activitylog'])->name('activitylog');
    Route::get('/activitylog/getData', [HomeController::class, 'activitylog_getData']);
    // Kelola user
    Route::get('/manage-user', [ManageUserController::class, 'index'])->name('index');
    Route::get('/manage-user/getDatas', [ManageUserController::class, 'manage_user_getDatas']);
    Route::get('/manage-user/getData/{id}', [ManageUserController::class, 'manage_user_getData']);
    Route::post('/manage-user/simpan', [ManageUserController::class, 'manage_user_simpan']);
    Route::post('/manage-user/hapus', [ManageUserController::class, 'manage_user_hapus']);
});


require __DIR__ . '/auth.php';
