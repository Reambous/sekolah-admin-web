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
        Schema::table('berita', function (Blueprint $table) {
            // Kolom untuk menyimpan nama asli, misal: "Surat Edaran.pdf"
            $table->string('nama_file_asli')->nullable()->after('lampiran');
        });
    }

    public function down(): void
    {
        Schema::table('berita', function (Blueprint $table) {
            $table->dropColumn('nama_file_asli');
        });
    }
};
