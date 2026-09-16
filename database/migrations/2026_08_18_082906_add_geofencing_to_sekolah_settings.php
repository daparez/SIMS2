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
        Schema::create('sekolah_settings', function (Blueprint $table) {
            $table->id();
            $table->string('nama_sekolah')->default('SMA/SMK Negeri Sekolah');
            $table->decimal('latitude', 10, 8)->default(-6.917464); // Contoh Koordinat Default
            $table->decimal('longitude', 11, 8)->default(107.619123);
            $table->integer('radius_meter')->default(100); // Radius batas presensi
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sekolah_settings', function (Blueprint $table) {
            //
        });
    }
};
