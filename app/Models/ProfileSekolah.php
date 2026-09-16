<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileSekolah extends Model
{
    use HasFactory;

    protected $table = 'profile_sekolahs';

    protected $fillable = [ 
        'nama_sekolah',
        'slogan',
        'sejarah_sekolah',
        'visi',
        'misi',
        'foto_hero',
        'foto_gedung',
        'kepala_sekolah_nama',
        'sambutan_kepala_sekolah',
        'foto_kepala_sekolah',
        'alamat_lengkap',
        'no_telepon',
        'email_sekolah',
        'link_facebook',
        'link_instagram',
        'link_youtube',
    ];
}
