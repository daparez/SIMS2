<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            // Tambahkan semua kolom dokumen (menggunakan nullable)
            if (!Schema::hasColumn('siswas', 'file_kk')) {
                $table->string('file_kk')->nullable();
            }
            if (!Schema::hasColumn('siswas', 'file_ijazah')) {
                $table->string('file_ijazah')->nullable();
            }
            if (!Schema::hasColumn('siswas', 'file_akta')) {
                $table->string('file_akta')->nullable();
            }
            if (!Schema::hasColumn('siswas', 'file_ktp_ayah')) {
                $table->string('file_ktp_ayah')->nullable();
            }
            if (!Schema::hasColumn('siswas', 'file_ktp_ibu')) {
                $table->string('file_ktp_ibu')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->dropColumn(['file_kk', 'file_ijazah', 'file_akta', 'file_ktp_ayah', 'file_ktp_ibu']);
        });
    }
};