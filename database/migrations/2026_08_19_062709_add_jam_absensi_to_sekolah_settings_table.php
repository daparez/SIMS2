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
        Schema::table('sekolah_settings', function (Blueprint $table) {
            $table->time('jam_masuk')->default('07:30:00')->after('radius_meter');
            $table->integer('toleransi_terlambat_menit')->default(10)->after('jam_masuk'); // 10 Menit Toleransi
            $table->time('jam_pulang')->default('14:30:00')->after('toleransi_terlambat_menit');
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
