<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    protected $fillable = ['judul', 'isi', 'target', 'status', 'created_by'];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}