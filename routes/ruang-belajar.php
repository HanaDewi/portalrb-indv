<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\RuangBelajar\DashboardController;
use App\Http\Controllers\RuangBelajar\AdminController;


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
