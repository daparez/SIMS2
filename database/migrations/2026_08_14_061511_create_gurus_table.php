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
        Schema::create('gurus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // 1. Identitas Pribadi dan Dasar
            $table->string('nip', 30)->unique();                     // NIP / NIY (Username Login)
            $table->string('nik', 16)->nullable()->unique();         // NIK KTP
            $table->string('nama_lengkap');                          // Nama + Gelar
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir');
            $table->date('tgl_lahir');                               // Password Default (DDMMYYYY)
            $table->string('agama', 20)->nullable();

            // 2. Informasi Kontak dan Alamat
            $table->text('alamat')->nullable();
            $table->string('no_hp', 20)->nullable();
            $table->string('foto')->nullable();

            // 3. Data Kepegawaian dan Jabatan
            $table->enum('status_kepegawaian', ['PNS', 'PPPK', 'GTT', 'GTY', 'Honorer'])->default('GTY');
            $table->string('jenis_ptk')->default('Guru Mata Pelajaran'); // Guru Kelas, Guru Mapel, Guru BK
            $table->date('tgl_masuk')->nullable();
            $table->string('pangkat_golongan', 50)->nullable();          // Contoh: "Penata Muda / III/a"

            // 4. Kualifikasi dan Pendidikan
            $table->string('pendidikan_terakhir', 20)->nullable();       // S1, S2, S3, D3
            $table->string('jurusan_pendidikan')->nullable();            // Contoh: "Pendidikan Matematika"
            $table->string('nama_kampus')->nullable();                   // Contoh: "Universitas Negeri Jakarta"
            $table->boolean('is_sertifikasi')->default(false);            // Sudah sertifikasi / belum
            $table->string('nuptk', 30)->nullable();                     // NUPTK / NRG

            // 5. Penugasan
            $table->foreignId('wali_kelas_id')->nullable()->constrained('kelas')->onDelete('set null'); // Kelas tempat jadi Wali Kelas

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gurus');
    }
};
