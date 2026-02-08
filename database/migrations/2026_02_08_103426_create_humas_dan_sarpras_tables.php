<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tabel HUMAS
        Schema::create('humas_kegiatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Guru yang input
            $table->date('tanggal');
            $table->string('nama_kegiatan'); // Misal: Kunjungan Industri, Terima Tamu
            $table->text('refleksi');        // Laporan hasil
            $table->string('status')->default('pending'); // pending, disetujui
            $table->timestamps();
        });

        // 2. Tabel SARPRAS
        Schema::create('sarpras_kegiatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('tanggal');
            $table->string('nama_kegiatan'); // Misal: Perbaikan AC, Pengecekan Lab
            $table->text('refleksi');
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('humas_kegiatan');
        Schema::dropIfExists('sarpras_kegiatan');
    }
};
