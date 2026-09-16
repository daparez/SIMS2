<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SekolahSettingController extends Controller
{
    public function index()
    {
        $setting = DB::table('sekolah_settings')->first();
        return view('admin.setting.lokasi', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama_sekolah'              => 'required|string|max:255',
            'jam_masuk'                 => 'required',
            'toleransi_terlambat_menit' => 'required|integer|min:0',
            'jam_pulang'                => 'required',
            'latitude'                  => 'required',
            'longitude'                 => 'required',
            'radius_meter'              => 'required|numeric|min:10',
        ]);

        // Format waktu agar tersimpan sempurna sebagai string HH:mm:ss
        $jamMasuk  = Carbon::parse($request->jam_masuk)->format('H:i:s');
        $jamPulang = Carbon::parse($request->jam_pulang)->format('H:i:s');

        // Update data pada tabel sekolah_settings
        DB::table('sekolah_settings')->updateOrInsert(
            ['id' => 1], // Menggunakan ID 1 sebagai row utama setting
            [
                'nama_sekolah'              => $request->nama_sekolah,
                'jam_masuk'                 => $jamMasuk,
                'toleransi_terlambat_menit' => $request->toleransi_terlambat_menit,
                'jam_pulang'                => $jamPulang,
                'latitude'                  => $request->latitude,
                'longitude'                 => $request->longitude,
                'radius_meter'              => $request->radius_meter,
                'updated_at'                => now(),
            ]
        );

        return redirect()->back()->with('success', 'Pengaturan lokasi & jam operasional berhasil diperbarui!');
    }
}