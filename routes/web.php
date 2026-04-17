<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\BeritaController;
use App\Http\Controllers\Admin\MLOpsController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\ValidasiController;
use App\Http\Controllers\Pendata\DataRtlhController;
use App\Http\Controllers\Pendata\SurveiController;
use App\Http\Controllers\Public\BeritaPublicController;
use App\Http\Controllers\Public\GuestController;
use App\Http\Controllers\Public\SimulasiController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// ==============================
// PUBLIC ROUTES (TAMU / GUEST)
// ==============================
Route::get('/', [GuestController::class, 'index'])->name('home');
Route::get('/profil', [GuestController::class, 'profil'])->name('profil');
Route::get('/prosedur', [GuestController::class, 'prosedur'])->name('prosedur');
Route::get('/statistik', [GuestController::class, 'statistik'])->name('statistik');
Route::get('/faq', [GuestController::class, 'faq'])->name('faq');
Route::get('/berita', [BeritaPublicController::class, 'index'])->name('berita.index');
Route::get('/berita/{slug}', [BeritaPublicController::class, 'show'])->name('berita.show');
Route::get('/simulasi', [SimulasiController::class, 'index'])->name('simulasi.index');
Route::post('/simulasi', [SimulasiController::class, 'process'])->name('simulasi.process');

// ==============================
// REDIRECT DASHBOARD berdasarkan ROLE
// ==============================
Route::get('/dashboard', function () {
    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('pendata.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ==============================
// ADMINISTRATOR ROUTES
// ==============================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard Eksekutif
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Manajemen Pengguna (RF-ADM-01)
    Route::get('/pengguna', [UserManagementController::class, 'index'])->name('pengguna.index');
    Route::get('/pengguna/tambah', [UserManagementController::class, 'create'])->name('pengguna.create');
    Route::post('/pengguna', [UserManagementController::class, 'store'])->name('pengguna.store');
    Route::get('/pengguna/{user}/edit', [UserManagementController::class, 'edit'])->name('pengguna.edit');
    Route::patch('/pengguna/{user}', [UserManagementController::class, 'update'])->name('pengguna.update');
    Route::patch('/pengguna/{user}/toggle-aktif', [UserManagementController::class, 'toggleAktif'])->name('pengguna.toggle-aktif');
    Route::delete('/pengguna/{user}', [UserManagementController::class, 'destroy'])->name('pengguna.destroy');

    // Manajemen & Validasi Data RTLH (RF-ADM-03)
    Route::get('/data-rtlh', [ValidasiController::class, 'index'])->name('data.index');
    Route::get('/data-rtlh/{dataRtlh}', [ValidasiController::class, 'show'])->name('data.show');
    Route::patch('/data-rtlh/{dataRtlh}/validasi', [ValidasiController::class, 'validasi'])->name('data.validasi');
    Route::delete('/data-rtlh/{dataRtlh}', [ValidasiController::class, 'destroy'])->name('data.destroy');
    Route::get('/data-rtlh/export', [ValidasiController::class, 'export'])->name('data.export');
    Route::post('/data-rtlh/import', [ValidasiController::class, 'import'])->name('data.import');

    // MLOps – Kelola Model (RF-ADM-06)
    Route::get('/mlops', [MLOpsController::class, 'index'])->name('mlops.index');
    Route::post('/mlops/upload', [MLOpsController::class, 'upload'])->name('mlops.upload');

    // CMS Berita (RF-ADM-07)
    Route::resource('/berita', BeritaController::class)->parameters(['berita' => 'berita']);

    // Audit Trail (RF-ADM-08)
    Route::get('/audit-log', [AuditLogController::class, 'index'])->name('audit.index');
});

// ==============================
// PENDATA ROUTES
// ==============================
Route::middleware(['auth', 'role:pendata'])->prefix('pendata')->name('pendata.')->group(function () {
    // Dashboard Pendata
    Route::get('/dashboard', [SurveiController::class, 'dashboard'])->name('dashboard');

    // CRUD Data Survei Milik Sendiri (RF-PND-02, RF-PND-05)
    Route::get('/survei', [SurveiController::class, 'index'])->name('survei.index');
    Route::get('/survei/tambah', [SurveiController::class, 'create'])->name('survei.create');
    Route::post('/survei', [SurveiController::class, 'store'])->name('survei.store');
    Route::get('/survei/{dataRtlh}', [SurveiController::class, 'show'])->name('survei.show');
    Route::get('/survei/{dataRtlh}/edit', [SurveiController::class, 'edit'])->name('survei.edit');
    Route::patch('/survei/{dataRtlh}', [SurveiController::class, 'update'])->name('survei.update');
    Route::delete('/survei/{dataRtlh}', [SurveiController::class, 'destroy'])->name('survei.destroy');
    Route::get('/survei/{dataRtlh}/export-pdf', [SurveiController::class, 'exportPdf'])->name('survei.export-pdf');
});

// ==============================
// PROFILE (SHARED AUTH)
// ==============================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
