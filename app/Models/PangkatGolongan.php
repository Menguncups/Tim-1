<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PangkatGolongan extends Model
{
    protected $table = 'pangkat_golongan';

    protected $primaryKey = 'id_pengajuan';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_pengajuan',
        'pangkat',
        'golongan',
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