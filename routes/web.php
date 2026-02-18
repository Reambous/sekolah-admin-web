<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KesiswaanKegiatanController;
use App\Http\Controllers\JurnalRefleksiController;
use App\Http\Controllers\KesiswaanLombaController;
use App\Http\Controllers\KurikulumKegiatanController;
use App\Http\Controllers\HumasKegiatanController;
use App\Http\Controllers\SarprasKegiatanController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\IjinController;
use App\Http\Controllers\UserController;

// Route Welcome


// Hapus atau Ubah bagian ini:
Route::get('/', function () {
    // return view('welcome'); // <-- KODE LAMA
    return redirect()->route('login'); // <-- KODE BARU (Langsung ke Login)
});


// Route Dashboard yang sudah dimodifikasiRoute::get('/dashboard', [DashboardController::class, 'index'])
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


    Route::get('/berita/export-excel', [BeritaController::class, 'exportExcel'])->name('berita.export');

    Route::get('/ijin/export', [IjinController::class, 'exportExcel'])->name('ijin.export');
    Route::get('/jurnal/export', [JurnalRefleksiController::class, 'exportExcel'])->name('jurnal.export');
    Route::get('/sarpras/export', [SarprasKegiatanController::class, 'exportExcel'])->name('sarpras.export');
    Route::get('/humas/kegiatan/export', [HumasKegiatanController::class, 'exportExcel'])->name('humas.kegiatan.export');
    Route::get('/kurikulum/kegiatan/export', [KurikulumKegiatanController::class, 'exportExcel'])->name('kurikulum.kegiatan.export');
    Route::get('/kesiswaan/lomba/export', [KesiswaanLombaController::class, 'exportExcel'])->name('kesiswaan.lomba.export');
    Route::get('/kesiswaan/kegiatan/export', [KesiswaanKegiatanController::class, 'exportExcel'])->name('kesiswaan.kegiatan.export');

    Route::get('/download-semua', [DashboardController::class, 'downloadSemua'])->name('download.semua');

    // Route resource berita yang sudah ada (biarkan saja)
    Route::resource('berita', BeritaController::class);

    Route::post('/kesiswaan/kegiatan/bulk-delete', [KesiswaanKegiatanController::class, 'bulkDestroy'])
        ->name('kesiswaan.kegiatan.bulk_delete');

    // --- KESISWAAN LOMBA ---
    Route::post('/kesiswaan/lomba/bulk-delete', [KesiswaanLombaController::class, 'bulkDestroy'])->name('kesiswaan.lomba.bulk_delete');

    // --- KURIKULUM ---
    Route::post('/kurikulum/kegiatan/bulk-delete', [KurikulumKegiatanController::class, 'bulkDestroy'])->name('kurikulum.kegiatan.bulk_delete');

    // --- HUMAS ---
    Route::post('/humas/kegiatan/bulk-delete', [HumasKegiatanController::class, 'bulkDestroy'])->name('humas.kegiatan.bulk_delete');

    // --- SARPRAS ---
    Route::post('/sarpras/kegiatan/bulk-delete', [SarprasKegiatanController::class, 'bulkDestroy'])->name('sarpras.kegiatan.bulk_delete');

    // --- JURNAL REFLEKSI ---
    Route::post('/jurnal/bulk-delete', [JurnalRefleksiController::class, 'bulkDestroy'])->name('jurnal.bulk_delete');

    // --- BERITA ---
    Route::post('/berita/bulk-delete', [BeritaController::class, 'bulkDestroy'])->name('berita.bulk_delete');

    // --- IJIN ---
    Route::post('/ijin/bulk-delete', [IjinController::class, 'bulkDestroy'])->name('ijin.bulk_delete');

    Route::post('/users/bulk-delete', [UserController::class, 'bulkDestroy'])->name('users.bulk_delete');
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

    // TAMBAHKAN INI UNTUK HAPUS BANYAK (CHECKLIST)



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

    // --- HUMAS ---
    Route::resource('humas/kegiatan', HumasKegiatanController::class, ['names' => 'humas.kegiatan']);
    Route::patch('/humas/kegiatan/{id}/approve', [HumasKegiatanController::class, 'approve'])->name('humas.kegiatan.approve');
    Route::patch('/humas/kegiatan/{id}/unapprove', [HumasKegiatanController::class, 'unapprove'])->name('humas.kegiatan.unapprove');

    // --- SARPRAS ---
    Route::resource('sarpras/kegiatan', SarprasKegiatanController::class, ['names' => 'sarpras.kegiatan']);
    Route::patch('/sarpras/kegiatan/{id}/approve', [SarprasKegiatanController::class, 'approve'])->name('sarpras.kegiatan.approve');
    Route::patch('/sarpras/kegiatan/{id}/unapprove', [SarprasKegiatanController::class, 'unapprove'])->name('sarpras.kegiatan.unapprove');

    // --- BERITA & PENGUMUMAN ---
    Route::get('/berita', [BeritaController::class, 'index'])->name('berita.index');

    // CRUD Berita (Admin)
    Route::get('/berita/create', [BeritaController::class, 'create'])->name('berita.create');
    Route::post('/berita', [BeritaController::class, 'store'])->name('berita.store');
    Route::get('/berita/{id}/edit', [BeritaController::class, 'edit'])->name('berita.edit'); // BARU
    Route::put('/berita/{id}', [BeritaController::class, 'update'])->name('berita.update');   // BARU
    Route::delete('/berita/{id}', [BeritaController::class, 'destroy'])->name('berita.destroy');

    Route::get('/berita/{id}', [BeritaController::class, 'show'])->name('berita.show');

    // Komentar
    Route::post('/berita/{id}/comment', [BeritaController::class, 'postComment'])->name('berita.comment');
    Route::delete('/komentar/{id}', [BeritaController::class, 'deleteComment'])->name('berita.comment.destroy');
    // Edit Komentar (BARU)
    Route::get('/komentar/{id}/edit', [BeritaController::class, 'editComment'])->name('berita.comment.edit');
    Route::put('/komentar/{id}', [BeritaController::class, 'updateComment'])->name('berita.comment.update');

    // --- PERIZINAN ---
    Route::get('/ijin', [IjinController::class, 'index'])->name('ijin.index');
    Route::get('/ijin/create', [IjinController::class, 'create'])->name('ijin.create');
    Route::post('/ijin', [IjinController::class, 'store'])->name('ijin.store');

    // BARU: Detail, Edit, Update
    Route::get('/ijin/{id}/edit', [IjinController::class, 'edit'])->name('ijin.edit');
    Route::put('/ijin/{id}', [IjinController::class, 'update'])->name('ijin.update');
    Route::get('/ijin/{id}', [IjinController::class, 'show'])->name('ijin.show'); // Taruh paling bawah agar tidak bentrok

    Route::delete('/ijin/{id}', [IjinController::class, 'destroy'])->name('ijin.destroy');

    // Approval
    Route::patch('/ijin/{id}/approve', [IjinController::class, 'approve'])->name('ijin.approve');
    Route::patch('/ijin/{id}/reject', [IjinController::class, 'reject'])->name('ijin.reject');

    // --- MANAJEMEN USER (ADMIN) ---
    Route::resource('users', UserController::class);
});

require __DIR__ . '/auth.php';
