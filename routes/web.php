<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KesiswaanKegiatanController;


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
    // Lihat Detail
    Route::get('/kesiswaan/kegiatan/{id}/detail', [KesiswaanKegiatanController::class, 'show'])->name('kesiswaan.kegiatan.show');
    // Edit & Update (Hanya Guru)
    Route::get('/kesiswaan/kegiatan/{id}/edit', [KesiswaanKegiatanController::class, 'edit'])->name('kesiswaan.kegiatan.edit');
    Route::put('/kesiswaan/kegiatan/{id}', [KesiswaanKegiatanController::class, 'update'])->name('kesiswaan.kegiatan.update');

    // Hapus (Hanya Guru)
    Route::delete('/kesiswaan/kegiatan/{id}', [KesiswaanKegiatanController::class, 'destroy'])->name('kesiswaan.kegiatan.destroy');
});

require __DIR__ . '/auth.php';
