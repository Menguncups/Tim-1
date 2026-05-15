<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $table = 'pegawai';

    protected $primaryKey = 'id_pegawai';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_pegawai',
        'nip',
        'nidn',
        'nama',
        'jenis_kelamin',
        'tanggal_lahir',
        'no_hp',
        'no_hp_darurat',
        'homebase',
        'email',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'pegawai_id_pegawai', 'id_pegawai');
    }

    public function pengajuan()
    {
        return $this->hasMany(
            Pengajuan::class,
            'pegawai_id_pegawai',
            'id_pegawai'
        );
    }

    public function roles()
    {
        return $this->belongsToMany(
            Role::class,
            'role_pegawai',
            'pegawai_id_pegawai',
            'role_id_role'
        );
    }
}
