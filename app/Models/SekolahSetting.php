<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SekolahSetting extends Model
{
    protected $fillable = ['nama_sekolah', 'latitude', 'longitude', 'radius_meter'];
}