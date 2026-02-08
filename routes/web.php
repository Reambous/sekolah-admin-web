<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KesiswaanKegiatanController;
use App\Http\Controllers\JurnalRefleksiController;
use App\Http\Controllers\KesiswaanLombaController;
use App\Http\Controllers\KurikulumKegiatanController;

Route::get('/', function () {
    return view('welcome');
});


// Route Dashboard yang sudah dimodifikasi
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Group Route khusus yang sudah Login
Route::middleware(['auth', 'verified'])->group(function () {

    // Route Kegiatan Kesiswaan
    Route::get('/kesiswaan/kegiatan', [KesiswaanKegiatanController::class, 'index'])->name('kesiswaan.kegiatan.index');
    Route::get('/kesiswaan/kegiatan/create', [KesiswaanKegiatanController::class, 'create'])->name('kesiswaan.kegiatan.create');
    Route::post('/kesiswaan/kegiatan', [KesiswaanKegiatanController::class, 'store'])->name('kesiswaan.kegiatan.store');
    Route::patch('/kesiswaan/kegiatan/{id}/approve', [KesiswaanKegiatanController::class, 'approve'])
        ->name('kesiswaan.kegiatan.approve');
    Route::patch('/kesiswaan/kegiatan/{id}/unapprove', [KesiswaanKegiatanController::class, 'unapprove'])->name('kesiswaan.kegiatan.unapprove');

    // Lihat Detail
    Route::get('/kesiswaan/kegiatan/{id}/detail', [KesiswaanKegiatanController::class, 'show'])->name('kesiswaan.kegiatan.show');
    // Edit & Update (Hanya Guru)
    Route::get('/kesiswaan/kegiatan/{id}/edit', [KesiswaanKegiatanController::class, 'edit'])->name('kesiswaan.kegiatan.edit');
    Route::put('/kesiswaan/kegiatan/{id}', [KesiswaanKegiatanController::class, 'update'])->name('kesiswaan.kegiatan.update');

    // Hapus (Hanya Guru)
    Route::delete('/kesiswaan/kegiatan/{id}', [KesiswaanKegiatanController::class, 'destroy'])->name('kesiswaan.kegiatan.destroy');

    // --- ROUTE JURNAL REFLEKSI ---
    Route::resource('jurnal', JurnalRefleksiController::class);

    // --- ROUTE LOMBA KESISWAAN ---
    Route::resource('kesiswaan/lomba', KesiswaanLombaController::class, ['names' => 'kesiswaan.lomba']);

    // Route Khusus Approve Lomba
    Route::patch('/kesiswaan/lomba/{id}/approve', [KesiswaanLombaController::class, 'approve'])
        ->name('kesiswaan.lomba.approve');

    // TAMBAHAN BARU:
    Route::patch('/kesiswaan/lomba/{id}/unapprove', [KesiswaanLombaController::class, 'unapprove'])->name('kesiswaan.lomba.unapprove');

    // --- ROUTE KURIKULUM ---
    Route::resource('kurikulum/kegiatan', KurikulumKegiatanController::class, ['names' => 'kurikulum.kegiatan']);
    Route::patch('/kurikulum/kegiatan/{id}/approve', [KurikulumKegiatanController::class, 'approve'])->name('kurikulum.kegiatan.approve');

    // TAMBAHAN BARU:
    Route::patch('/kurikulum/kegiatan/{id}/unapprove', [KurikulumKegiatanController::class, 'unapprove'])->name('kurikulum.kegiatan.unapprove');
});

require __DIR__ . '/auth.php';
