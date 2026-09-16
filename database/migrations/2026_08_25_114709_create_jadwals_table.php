<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajarans')->onDelete('cascade');
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->foreignId('jam_pelajaran_id')->constrained('jam_pelajarans')->onDelete('cascade');
            $table->foreignId('pengampu_id')->constrained('pengampus')->onDelete('cascade');
            $table->string('ruangan')->nullable()->default('Ruang Kelas'); // Misal: Lab Komputer, Lapangan
            $table->timestamps();

            // Memastikan tidak ada jadwal ganda di slot jam & kelas yang sama
            $table->unique(['tahun_ajaran_id', 'kelas_id', 'jam_pelajaran_id'], 'unique_jadwal_kelas');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwals');
    }
};