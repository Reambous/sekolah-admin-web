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
        // 1. TABEL BERITA
        Schema::create('berita', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Penulis (Admin)
            $table->string('judul');
            $table->text('isi')->nullable();       // Opsional
            $table->string('gambar')->nullable();  // Opsional (Path gambar)
            $table->integer('views')->default(0);  // Hitung pembaca
            $table->timestamps();
        });

        // 2. TABEL KOMENTAR
        Schema::create('berita_komentar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('berita_id')->constrained('berita')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Pengomentar
            $table->text('isi_komentar');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('berita_komentar');
        Schema::dropIfExists('berita');
    }
};
