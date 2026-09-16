<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profile_sekolahs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_sekolah')->default('SMA Negeri 1 Simak');
            $table->string('slogan')->nullable()->default('Mewujudkan Generasi Cerdas, Berkarakter, dan Berdaya Saing Global');
            $table->text('sejarah_sekolah')->nullable();
            $table->text('visi')->nullable();
            $table->text('misi')->nullable();
            $table->string('foto_hero')->nullable(); // Foto Banner Utama Depan
            $table->string('foto_gedung')->nullable(); // Foto Gedung / Lingkungan Sekolah
            $table->string('kepala_sekolah_nama')->nullable();
            $table->text('sambutan_kepala_sekolah')->nullable();
            $table->string('foto_kepala_sekolah')->nullable();
            $table->string('alamat_lengkap')->nullable();
            $table->string('no_telepon')->nullable();
            $table->string('email_sekolah')->nullable();
            $table->string('link_facebook')->nullable();
            $table->string('link_instagram')->nullable();
            $table->string('link_youtube')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profile_sekolahs');
    }
};