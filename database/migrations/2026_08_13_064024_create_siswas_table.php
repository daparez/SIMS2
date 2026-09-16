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
        Schema::create('siswas', function (Blueprint $table) {
            $table->id(); // ID Siswa
            $table->uuid('uuid')->unique(); // UUID Unik
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Relasi ke Akun User

            // 1. Identitas Inti
            $table->string('nisn', 10)->unique(); //
            $table->string('nis', 20)->nullable()->unique(); //
            $table->string('nama_lengkap'); //
            $table->string('nama_panggilan')->nullable(); //

            // 2. Informasi Pribadi & Kontak
            $table->string('tempat_lahir'); //
            $table->date('tgl_lahir'); //
            $table->enum('jenis_kelamin', ['L', 'P']); //
            $table->string('agama', 20)->nullable(); //
            $table->text('alamat')->nullable(); //
            $table->string('no_hp', 20)->nullable(); //
            $table->string('foto')->nullable(); // Foto profil

            // 3. Data Akademik
            $table->enum('status_siswa', ['aktif', 'lulus', 'mutasi', 'keluar'])->default('aktif'); //
            $table->string('kelas'); //
            $table->string('jurusan')->nullable(); //
            $table->year('angkatan'); //
            $table->date('tgl_diterima')->nullable(); //

            // 4. Data Orang Tua / Wali
            $table->string('nama_ayah')->nullable(); //
            $table->string('nama_ibu')->nullable(); //
            $table->string('nama_wali')->nullable(); //
            $table->string('no_hp_ortu', 20)->nullable(); //
            $table->string('pekerjaan_ortu')->nullable(); //

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswas');
    }
};
