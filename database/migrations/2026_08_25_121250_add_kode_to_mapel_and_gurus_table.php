<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mata_pelajarans', function (Blueprint $table) {
            if (!Schema::hasColumn('mata_pelajarans', 'kode_mapel')) {
                $table->string('kode_mapel', 20)->nullable()->after('nama_mapel');
            }
        });

        Schema::table('gurus', function (Blueprint $table) {
            if (!Schema::hasColumn('gurus', 'kode_guru')) {
                // Diubah ke after('nama_lengkap')
                $table->string('kode_guru', 10)->nullable()->after('nama_lengkap');
            }
        });
    }

    public function down(): void
    {
        Schema::table('mata_pelajarans', function (Blueprint $table) {
            $table->dropColumn('kode_mapel');
        });
        Schema::table('gurus', function (Blueprint $table) {
            $table->dropColumn('kode_guru');
        });
    }
};