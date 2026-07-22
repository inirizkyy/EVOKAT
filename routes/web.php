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
use App\Http\Controllers\Admin\OrganizationController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\SignatoryController;
use App\Http\Controllers\Admin\LeaderController;
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
Route::post('/organisasi/propose', [FrontendController::class, 'proposeOrganization'])->name('organisasi.propose');
Route::get('/permohonan/{nomor_permohonan}/dokumen', [FrontendController::class, 'dokumenList'])->name('permohonan.dokumen-list')->where('nomor_permohonan', '[A-Za-z0-9\/_-]+');
Route::get('/permohonan/{nomor_permohonan}/dokumen/{pemohon_id}', [FrontendController::class, 'dokumenUpload'])->name('permohonan.dokumen-upload')->where('nomor_permohonan', '[A-Za-z0-9\/_-]+');
Route::post('/permohonan/{nomor_permohonan}/dokumen/{pemohon_id}', [FrontendController::class, 'storeDokumenUpload'])->name('permohonan.store-dokumen-upload')->where('nomor_permohonan', '[A-Za-z0-9\/_-]+');
Route::post('/permohonan/{nomor_permohonan}/submit', [FrontendController::class, 'submitPermohonan'])->name('permohonan.submit')->where('nomor_permohonan', '[A-Za-z0-9\/_-]+');
Route::get('/tracking', [FrontendController::class, 'tracking']);
Route::post('/tracking', [FrontendController::class, 'cekTracking']);
Route::get('/permohonan/{nomor_permohonan}/download-final', [FrontendController::class, 'downloadFinalSurat'])->name('permohonan.download-final')->where('nomor_permohonan', '[A-Za-z0-9\/_-]+');

// Chat Routes (Guest)
Route::post('/chat/init', [ChatController::class, 'init']);
Route::post('/chat/send', [ChatController::class, 'sendMessage']);
Route::get('/chat/fetch', [ChatController::class, 'fetchMessages']);
Route::post('/chat/done', [ChatController::class, 'markAsDone']);
Route::get('/chat/admin-status', [ChatController::class, 'adminStatus']);

// Admin Routes
Route::get('/admin/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'role:admin'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Pemeriksa Routes
Route::middleware(['auth', 'verified', 'role:pemeriksa'])->prefix('pemeriksa')->name('pemeriksa.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Pemeriksa\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/buku-registrasi/member/{id}', [\App\Http\Controllers\Pemeriksa\DashboardController::class, 'showMember'])->name('buku-registrasi.show-member');
    Route::post('/buku-registrasi/{id}/approve', [\App\Http\Controllers\Pemeriksa\DashboardController::class, 'approve'])->name('buku-registrasi.approve');
    Route::post('/buku-registrasi/{id}/unlock', [\App\Http\Controllers\Pemeriksa\DashboardController::class, 'unlock'])->name('buku-registrasi.unlock');
});

// Verifikator 1 Routes
Route::middleware(['auth', 'verified', 'role:verifikator1'])->prefix('verifikator1')->name('verifikator1.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Verifikator\PermohonanController::class, 'dashboard'])->name('dashboard');
    Route::get('/permohonan', [\App\Http\Controllers\Verifikator\PermohonanController::class, 'index'])->name('permohonan.index');
    Route::get('/permohonan/{id}', [\App\Http\Controllers\Verifikator\PermohonanController::class, 'show'])->name('permohonan.show');
    Route::post('/permohonan/{id}/approve', [\App\Http\Controllers\Verifikator\PermohonanController::class, 'approve'])->name('permohonan.approve');
    Route::post('/permohonan/{id}/reject', [\App\Http\Controllers\Verifikator\PermohonanController::class, 'reject'])->name('permohonan.reject');
    Route::get('/permohonan/{permohonan_id}/member/{pemohon_id}', [\App\Http\Controllers\Verifikator\PermohonanController::class, 'memberShow'])->name('permohonan.member-show');
    Route::post('/permohonan/{permohonan_id}/member/{pemohon_id}/verifikasi', [\App\Http\Controllers\Verifikator\PermohonanController::class, 'verifikasiMember'])->name('permohonan.member-verifikasi');
    Route::get('/buku-registrasi', [\App\Http\Controllers\Verifikator\PermohonanController::class, 'bukuRegistrasiIndex'])->name('buku-registrasi.index');
    Route::get('/buku-registrasi/member/{id}', [\App\Http\Controllers\Verifikator\PermohonanController::class, 'showBukuMember'])->name('buku-registrasi.show-member');
});

// Verifikator 2 Routes
Route::middleware(['auth', 'verified', 'role:verifikator2'])->prefix('verifikator2')->name('verifikator2.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Verifikator\PermohonanController::class, 'dashboard'])->name('dashboard');
    Route::get('/permohonan', [\App\Http\Controllers\Verifikator\PermohonanController::class, 'index'])->name('permohonan.index');
    Route::get('/permohonan/{id}', [\App\Http\Controllers\Verifikator\PermohonanController::class, 'show'])->name('permohonan.show');
    Route::post('/permohonan/{id}/approve', [\App\Http\Controllers\Verifikator\PermohonanController::class, 'approve'])->name('permohonan.approve');
    Route::post('/permohonan/{id}/reject', [\App\Http\Controllers\Verifikator\PermohonanController::class, 'reject'])->name('permohonan.reject');
    Route::get('/permohonan/{permohonan_id}/member/{pemohon_id}', [\App\Http\Controllers\Verifikator\PermohonanController::class, 'memberShow'])->name('permohonan.member-show');
    Route::post('/permohonan/{permohonan_id}/member/{pemohon_id}/verifikasi', [\App\Http\Controllers\Verifikator\PermohonanController::class, 'verifikasiMember'])->name('permohonan.member-verifikasi');
    Route::get('/buku-registrasi', [\App\Http\Controllers\Verifikator\PermohonanController::class, 'bukuRegistrasiIndex'])->name('buku-registrasi.index');
    Route::get('/buku-registrasi/member/{id}', [\App\Http\Controllers\Verifikator\PermohonanController::class, 'showBukuMember'])->name('buku-registrasi.show-member');
});

// Verifikator 3 Routes
Route::middleware(['auth', 'verified', 'role:verifikator3'])->prefix('verifikator3')->name('verifikator3.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Verifikator\PermohonanController::class, 'dashboard'])->name('dashboard');
    Route::get('/permohonan', [\App\Http\Controllers\Verifikator\PermohonanController::class, 'index'])->name('permohonan.index');
    Route::get('/permohonan/{id}', [\App\Http\Controllers\Verifikator\PermohonanController::class, 'show'])->name('permohonan.show');
    Route::post('/permohonan/{id}/approve', [\App\Http\Controllers\Verifikator\PermohonanController::class, 'approve'])->name('permohonan.approve');
    Route::post('/permohonan/{id}/reject', [\App\Http\Controllers\Verifikator\PermohonanController::class, 'reject'])->name('permohonan.reject');
    Route::get('/permohonan/{permohonan_id}/member/{pemohon_id}', [\App\Http\Controllers\Verifikator\PermohonanController::class, 'memberShow'])->name('permohonan.member-show');
    Route::post('/permohonan/{permohonan_id}/member/{pemohon_id}/verifikasi', [\App\Http\Controllers\Verifikator\PermohonanController::class, 'verifikasiMember'])->name('permohonan.member-verifikasi');
    Route::get('/buku-registrasi', [\App\Http\Controllers\Verifikator\PermohonanController::class, 'bukuRegistrasiIndex'])->name('buku-registrasi.index');
    Route::get('/buku-registrasi/member/{id}', [\App\Http\Controllers\Verifikator\PermohonanController::class, 'showBukuMember'])->name('buku-registrasi.show-member');
});

// Verifikator 4 Routes
Route::middleware(['auth', 'verified', 'role:verifikator4'])->prefix('verifikator4')->name('verifikator4.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Verifikator\PermohonanController::class, 'dashboard'])->name('dashboard');
    Route::get('/permohonan', [\App\Http\Controllers\Verifikator\PermohonanController::class, 'index'])->name('permohonan.index');
    Route::get('/permohonan/{id}', [\App\Http\Controllers\Verifikator\PermohonanController::class, 'show'])->name('permohonan.show');
    Route::post('/permohonan/{id}/approve', [\App\Http\Controllers\Verifikator\PermohonanController::class, 'approve'])->name('permohonan.approve');
    Route::post('/permohonan/{id}/reject', [\App\Http\Controllers\Verifikator\PermohonanController::class, 'reject'])->name('permohonan.reject');
    Route::get('/permohonan/{permohonan_id}/member/{pemohon_id}', [\App\Http\Controllers\Verifikator\PermohonanController::class, 'memberShow'])->name('permohonan.member-show');
    Route::post('/permohonan/{permohonan_id}/member/{pemohon_id}/verifikasi', [\App\Http\Controllers\Verifikator\PermohonanController::class, 'verifikasiMember'])->name('permohonan.member-verifikasi');
    Route::get('/buku-registrasi', [\App\Http\Controllers\Verifikator\PermohonanController::class, 'bukuRegistrasiIndex'])->name('buku-registrasi.index');
    Route::get('/buku-registrasi/member/{id}', [\App\Http\Controllers\Verifikator\PermohonanController::class, 'showBukuMember'])->name('buku-registrasi.show-member');
});

// Admin CRUD
Route::middleware(['auth', 'verified', 'role:admin', \App\Http\Middleware\TrackAdminActivity::class])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('permohonan', PermohonanController::class);
    Route::get('permohonan/{id}/penjadwalan', [PermohonanController::class, 'penjadwalan'])->name('permohonan.penjadwalan');
    Route::post('permohonan/{id}/verifikasi', [PermohonanController::class, 'verifikasi'])->name('permohonan.verifikasi');
    Route::get('permohonan/member/{pemohon_id}', [PermohonanController::class, 'memberShow'])->name('permohonan.member-show');
    Route::post('permohonan/member/{pemohon_id}/verifikasi', [PermohonanController::class, 'verifikasiMember'])->name('permohonan.verifikasi-member');
    Route::get('permohonan/{id}/download-surat', [PermohonanController::class, 'downloadSurat'])->name('permohonan.download-surat');
    Route::get('buku-registrasi/export-pdf', [BukuRegistrasiController::class, 'exportPdf'])->name('buku-registrasi.export-pdf');
    Route::get('buku-registrasi/export-excel', [BukuRegistrasiController::class, 'exportExcel'])->name('buku-registrasi.export-excel');
    Route::get('buku-registrasi/{id}/print', [BukuRegistrasiController::class, 'print'])->name('buku-registrasi.print');
    Route::get('buku-registrasi/member/{id}', [BukuRegistrasiController::class, 'showMember'])->name('buku-registrasi.show-member');
    Route::resource('buku-registrasi', BukuRegistrasiController::class)->except(['create', 'store', 'destroy']);
    Route::resource('berita', BeritaController::class)->parameters(['berita' => 'berita']);
    Route::resource('faq', FaqController::class);
    Route::resource('persyaratan', PersyaratanController::class);
    Route::resource('organisasi', OrganizationController::class);
    Route::resource('pengaturan', PengaturanController::class);
    Route::resource('room', RoomController::class)->only(['index', 'store', 'destroy']);
    Route::resource('signatory', SignatoryController::class)->only(['index', 'store', 'destroy']);
    Route::resource('leader', LeaderController::class)->only(['index', 'store', 'destroy']);
    
    // Surat Template
    Route::post('surat-template/{id}/activate', [SuratTemplateController::class, 'activate'])->name('surat-template.activate');
    Route::get('surat-template/{id}/download', [SuratTemplateController::class, 'download'])->name('surat-template.download');
    Route::resource('surat-template', SuratTemplateController::class)->only(['index', 'store', 'destroy']);

    // Beacon to set offline
    Route::post('set-offline', function() {
        if (Auth::check() && Auth::user()->role === 'admin') {
            \Illuminate\Support\Facades\Cache::forget('admin-online');
        }
        return response()->json(['success' => true]);
    })->name('set-offline');

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
