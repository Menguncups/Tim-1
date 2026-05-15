<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'role';

    protected $primaryKey = 'id_role';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_role',
        'nama_role',
    ];

    public function pegawai()
    {
        return $this->belongsToMany(
            Pegawai::class,
            'role_pegawai',
            'role_id_role',
            'pegawai_id_pegawai'
        );
    }
}