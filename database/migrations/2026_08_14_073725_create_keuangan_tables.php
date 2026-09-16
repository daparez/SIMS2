<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Buat Tabel Jenis Pembayaran / Pos Keuangan Terlebih Dahulu
        Schema::create('jenis_pembayarans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pembayaran'); // SPP Bulanan, Uang Gedung, Seragam
            $table->enum('tipe', ['bulanan', 'bebas'])->default('bulanan');
            $table->decimal('nominal_default', 12, 2)->default(0);
            $table->timestamps();
        });

        // 2. Baru Buat Tabel Transaksi Pembayaran (Foreign Key Pasti Aman!)
        Schema::create('pembayaran_spps', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi', 30)->unique();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->foreignId('jenis_pembayaran_id')->constrained('jenis_pembayarans')->onDelete('cascade');
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajarans')->onDelete('cascade');
            
            $table->string('bulan_dibayar', 20)->nullable();
            $table->year('tahun_dibayar')->nullable();
            $table->date('tgl_bayar');
            $table->decimal('jumlah_bayar', 12, 2);
            $table->enum('status', ['Lunas', 'Belum Lunas'])->default('Lunas');
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran_spps');
        Schema::dropIfExists('jenis_pembayarans');
    }
};