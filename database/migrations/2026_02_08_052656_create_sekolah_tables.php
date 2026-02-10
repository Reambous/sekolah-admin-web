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
        // 1. TABEL JURNAL REFLEKSI (Menu Terpisah)
        Schema::create('jurnal_refleksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relasi ke User
            $table->date('tanggal');
            $table->string('kategori'); // kesiswaan/humas/kurikulum/dll
            $table->string('judul_refleksi');
            $table->text('isi_refleksi');
            $table->timestamps();
        });

        // 2. TABEL KESISWAAN LOMBA
        Schema::create('kesiswaan_lomba', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nama_siswa');
            $table->date('tanggal');
            $table->string('jenis_lomba');
            $table->string('prestasi');
            $table->text('refleksi'); // Refleksi Nempel
            $table->enum('status', ['pending', 'disetujui'])->default('pending'); // Sistem Gembok
            $table->timestamps();
        });

        // 3. TABEL KESISWAAN KEGIATAN
        Schema::create('kesiswaan_kegiatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('tanggal');
            $table->string('nama_kegiatan');
            $table->text('refleksi');
            $table->enum('status', ['pending', 'disetujui'])->default('pending');
            $table->timestamps();
        });

        // 4. TABEL KURIKULUM KEGIATAN
        Schema::create('kurikulum_kegiatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('tanggal');
            $table->string('nama_kegiatan');
            $table->text('refleksi');
            $table->enum('status', ['pending', 'disetujui'])->default('pending');
            $table->timestamps();
        });

        Schema::create('humas_kegiatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Guru yang input
            $table->date('tanggal');
            $table->string('nama_kegiatan'); // Misal: Kunjungan Industri, Terima Tamu
            $table->text('refleksi');        // Laporan hasil
            $table->string('status')->default('pending'); // pending, disetujui
            $table->timestamps();
        });

        // 6. TABEL SARPRAS KEGIATAN
        Schema::create('sarpras_kegiatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('tanggal');
            $table->string('nama_kegiatan'); // Misal: Perbaikan AC, Pengecekan Lab
            $table->text('refleksi');
            $table->string('status')->default('pending');
            $table->timestamps();
        });

        // 7. TABEL UMUM
        Schema::create('umum', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('tanggal');
            $table->string('jenis_kegiatan');
            $table->text('keterangan');
            $table->enum('status', ['pending', 'disetujui'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sekolah_tables');
    }
};
