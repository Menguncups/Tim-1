<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'pegawai_id_pegawai',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id_pegawai', 'id_pegawai');
    }

    public function verifikasi()
    {
        return $this->hasMany(Verifikasi::class, 'user_id_user', 'id');
    }
}