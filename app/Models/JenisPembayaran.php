<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisPembayaran extends Model
{
    protected $fillable = ['nama_pembayaran', 'tipe', 'nominal_default'];

    public function pembayaranSpps()
    {
        return $this->hasMany(PembayaranSpp::class);
    }
}