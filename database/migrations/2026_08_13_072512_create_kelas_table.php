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
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel jurusans (Nullable jika sekolah tingkat SD/SMP yang tidak memiliki jurusan)
            $table->foreignId('jurusan_id')->nullable()->constrained('jurusans')->onDelete('cascade');
            $table->string('nama_kelas', 50);          // Contoh: "10 RPL 1"
            $table->enum('tingkat', ['10', '11', '12']); // Atau '7','8','9' jika SMP, '1' s/d '6' jika SD
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
