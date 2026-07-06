<?php

use App\Http\Controllers\FrontendController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\PermohonanController;
use App\Http\Controllers\Admin\BeritaController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\PersyaratanController;
use App\Http\Controllers\Admin\PengaturanController;
use App\Http\Controllers\Admin\BukuRegistrasiController;
use App\Http\Controllers\Admin\SuratTemplateController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Admin\ChatController as AdminChatController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Frontend Routes
Route::get('/', [FrontendController::class, 'index']);
Route::get('/informasi', [FrontendController::class, 'informasi']);
Route::get('/berita/{slug}', [FrontendController::class, 'showBerita'])->name('berita.show');
Route::get('/persyaratan', [FrontendController::class, 'persyaratan']);
Route::get('/alur', [FrontendController::class, 'alur']);
Route::get('/faq', [FrontendController::class, 'faq']);
Route::get('/kontak', [FrontendController::class, 'kontak']);
Route::get('/permohonan', [FrontendController::class, 'permohonan']);
Route::post('/permohonan', [FrontendController::class, 'storePermohonan']);
Route::get('/tracking', [FrontendController::class, 'tracking']);
Route::post('/tracking', [FrontendController::class, 'cekTracking']);
Route::get('/permohonan/{nomor_permohonan}/download-final', [FrontendController::class, 'downloadFinalSurat'])->name('permohonan.download-final');

// Chat Routes (Guest)
Route::post('/chat/init', [ChatController::class, 'init']);
Route::post('/chat/send', [ChatController::class, 'sendMessage']);
Route::get('/chat/fetch', [ChatController::class, 'fetchMessages']);
Route::post('/chat/done', [ChatController::class, 'markAsDone']);
Route::get('/chat/admin-status', [ChatController::class, 'adminStatus']);

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
    Route::get('permohonan/{id}/download-surat', [PermohonanController::class, 'downloadSurat'])->name('permohonan.download-surat');
    Route::get('buku-registrasi/export-pdf', [BukuRegistrasiController::class, 'exportPdf'])->name('buku-registrasi.export-pdf');
    Route::get('buku-registrasi/export-excel', [BukuRegistrasiController::class, 'exportExcel'])->name('buku-registrasi.export-excel');
    Route::get('buku-registrasi/{id}/print', [BukuRegistrasiController::class, 'print'])->name('buku-registrasi.print');
    Route::resource('buku-registrasi', BukuRegistrasiController::class)->except(['create', 'store', 'destroy']);
    Route::resource('berita', BeritaController::class)->parameters(['berita' => 'berita']);
    Route::resource('faq', FaqController::class);
    Route::resource('persyaratan', PersyaratanController::class);
    Route::resource('pengaturan', PengaturanController::class);
    
    // Surat Template
    Route::post('surat-template/{id}/activate', [SuratTemplateController::class, 'activate'])->name('surat-template.activate');
    Route::get('surat-template/{id}/download', [SuratTemplateController::class, 'download'])->name('surat-template.download');
    Route::resource('surat-template', SuratTemplateController::class)->only(['index', 'store', 'destroy']);

    // Chat Routes (Admin)
    Route::get('chat', [AdminChatController::class, 'index'])->name('chat.index');
    Route::get('chat/sessions', [AdminChatController::class, 'getSessions'])->name('chat.sessions');
    Route::get('chat/unread', [AdminChatController::class, 'getUnreadCount'])->name('chat.unread');
    Route::get('chat/messages/{id}', [AdminChatController::class, 'getMessages'])->name('chat.messages');
    Route::post('chat/send/{id}', [AdminChatController::class, 'sendMessage'])->name('chat.send');
    Route::post('chat/done/{id}', [AdminChatController::class, 'markAsDone'])->name('chat.done');
    Route::delete('chat/{id}', [AdminChatController::class, 'destroy'])->name('chat.destroy');

});

require __DIR__.'/auth.php';
