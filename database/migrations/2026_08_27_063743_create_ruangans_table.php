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
        Schema::create('ruangans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_ruangan')->unique();
            $table->string('nama_ruangan');
            $table->enum('kategori_ruangan', ['kelas', 'laboratorium', 'perpustakaan', 'kantor', 'aula', 'lapangan', 'lainnya'])->default('kelas');
            $table->integer('luas_ruangan')->nullable();
            $table->integer('kapasitas')->default(0);
            $table->foreignId('penanggung_jawab_id')->nullable()->constrained('gurus')->nullOnDelete();
            $table->enum('status_kondisi', ['baik', 'rusak_ringan', 'rusak_berat', 'dalam_perbaikan'])->default('baik');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ruangans');
    }
};
