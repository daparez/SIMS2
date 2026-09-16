<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Kategori Barang
        Schema::create('kategori_barangs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kategori')->unique(); // Contoh: ELK, MBL
            $table->string('nama_kategori'); // Contoh: Elektronik, Mebel
            $table->timestamps();
        });

        // 2. Tabel Master Barang & Aset
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang')->unique(); // Contoh: INV-2026-ELK-001
            $table->string('nama_barang');
            $table->foreignId('kategori_barang_id')->constrained('kategori_barangs')->cascadeOnDelete();
            $table->text('merk_spesifikasi')->nullable();
            $table->enum('sumber_dana', ['BOS', 'Yayasan', 'Hibah', 'Lainnya'])->default('BOS');
            $table->year('tahun_pengadaan')->nullable();
            $table->decimal('harga_perolehan', 15, 2)->default(0);
            $table->integer('jumlah')->default(1);
            $table->integer('kondisi_baik')->default(1);
            $table->integer('kondisi_rusak_ringan')->default(0);
            $table->integer('kondisi_rusak_berat')->default(0);
            $table->foreignId('ruangan_id')->nullable()->constrained('ruangans')->nullOnDelete();
            $table->enum('status_aset', ['aktif', 'dipinjam', 'dalam_perbaikan', 'dihapuskan'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barangs');
        Schema::dropIfExists('kategori_barangs');
    }
};