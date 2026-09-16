<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembayaranSpp extends Model
{
    protected $fillable = [
        'kode_transaksi',
        'siswa_id',
        'jenis_pembayaran_id',
        'tahun_ajaran_id',
        'bulan_dibayar',
        'tahun_dibayar',
        'tgl_bayar',
        'jumlah_bayar',
        'status',
        'admin_id',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function jenisPembayaran()
    {
        return $this->belongsTo(JenisPembayaran::class);
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}