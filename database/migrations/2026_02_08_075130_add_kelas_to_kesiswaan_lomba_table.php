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
        Schema::table('kesiswaan_lomba', function (Blueprint $table) {
            // Tambahkan kolom kelas setelah nama_siswa
            $table->string('kelas')->after('nama_siswa')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('kesiswaan_lomba', function (Blueprint $table) {
            $table->dropColumn('kelas');
        });
    }
};
