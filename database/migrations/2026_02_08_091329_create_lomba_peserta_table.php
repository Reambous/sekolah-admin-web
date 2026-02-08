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
        // 1. Buat Tabel Anak (Menampung Nama & Kelas Banyak Siswa)
        Schema::create('kesiswaan_lomba_peserta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kesiswaan_lomba_id')->constrained('kesiswaan_lomba')->onDelete('cascade');
            $table->string('nama_siswa');
            $table->string('kelas');
            $table->timestamps();
        });

        // 2. Hapus Kolom Nama & Kelas di Tabel Induk (Sudah tidak dipakai)
        Schema::table('kesiswaan_lomba', function (Blueprint $table) {
            $table->dropColumn(['nama_siswa', 'kelas']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kesiswaan_lomba_peserta');
    }
};
