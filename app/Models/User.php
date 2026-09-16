<?php

namespace App\Models;


// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use App\Models\UserPermission;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relasi ke UserPermission
public function permissions()
{
    return $this->hasMany(UserPermission::class);
}

// Helper untuk mengecek apakah user memiliki hak akses tertentu
public function hasPermission($permissionName)
{
    // Jika role-nya Admin, otomatis punya akses ke SEMUA fitur
    if ($this->role === 'admin') {
        return true;
    }

    // Untuk Guru/Siswa, cek apakah ada di tabel user_permissions
    return $this->permissions()->where('permission', $permissionName)->exists();
}
}
