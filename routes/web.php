<?php

use App\Http\Controllers\FrontendController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\PermohonanController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Admin\BeritaController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\PersyaratanController;
use App\Http\Controllers\Admin\PengaturanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Frontend Routes
Route::get('/', [FrontendController::class, 'index']);
Route::get('/informasi', [FrontendController::class, 'informasi']);
Route::get('/persyaratan', [FrontendController::class, 'persyaratan']);
Route::get('/alur', [FrontendController::class, 'alur']);
Route::get('/faq', [FrontendController::class, 'faq']);
Route::get('/kontak', [FrontendController::class, 'kontak']);
Route::get('/permohonan', [FrontendController::class, 'permohonan']);
Route::post('/permohonan', [FrontendController::class, 'storePermohonan']);
Route::get('/tracking', [FrontendController::class, 'tracking']);
Route::post('/tracking', [FrontendController::class, 'cekTracking']);

// Admin Routes
Route::get('/admin/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin CRUD
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('permohonan', PermohonanController::class);
    Route::post('permohonan/{id}/verifikasi', [PermohonanController::class, 'verifikasi'])->name('permohonan.verifikasi');
    Route::resource('jadwal', JadwalController::class);
    Route::resource('berita', BeritaController::class)->parameters(['berita' => 'berita']);
    Route::resource('faq', FaqController::class);
    Route::resource('persyaratan', PersyaratanController::class);
    Route::resource('pengaturan', PengaturanController::class);
});

require __DIR__.'/auth.php';
