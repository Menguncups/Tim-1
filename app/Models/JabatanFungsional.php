<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JabatanFungsional extends Model
{
    protected $table = 'jabatan_fungsional';

    protected $primaryKey = 'id_pengajuan';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_pengajuan',
        'nama_jabatan',
        'tmt',
        'berkas_pendukung',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(
            Pengajuan::class,
            'id_pengajuan',
            'id_pengajuan'
        );
    }
}