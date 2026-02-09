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
        Schema::create('ijin', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Siapa yang ijin
            $table->string('jenis_ijin'); // Sakit, Izin Pribadi, Dinas Luar, Cuti
            $table->date('mulai');
            $table->date('selesai'); // Biar bisa ijin lebih dari 1 hari
            $table->text('alasan');
            $table->string('bukti_foto')->nullable(); // Foto surat dokter / kegiatan
            $table->string('status')->default('pending'); // pending, disetujui, ditolak
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ijin');
    }
};
