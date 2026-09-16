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
        Schema::create('mata_pelajarans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_mapel', 20)->unique();              // Contoh: "MTK-10", "PWEB"
            $table->string('nama_mapel');                            // Contoh: "Pemrograman Web & Perangkat Bergerak"
            $table->enum('kelompok', ['A', 'B', 'C1', 'C2', 'C3']);   // Kelompok Wajib A/B atau Peminatan/Kejuruan C
            $table->integer('sks_jam')->default(2);                 // Alokasi Jam Pelajaran per Minggu
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mata_pelajarans');
    }
};
