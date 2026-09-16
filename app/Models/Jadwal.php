<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    protected $fillable = ['tahun_ajaran_id', 'kelas_id', 'jam_pelajaran_id', 'pengampu_id', 'ruangan'];

    public function jamPelajaran()
    {
        return $this->belongsTo(JamPelajaran::class);
    }

    public function pengampu()
    {
        return $this->belongsTo(Pengampu::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }
}