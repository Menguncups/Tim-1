<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Verifikasi extends Model
{
    protected $table = 'verifikasi';

    protected $primaryKey = 'id_verifikasi';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_verifikasi',
        'tanggal_verifikasi',
        'tahap_verifikasi',
        'catatan',
        'user_id',
        'pengajuan_id_pengajuan',
    ];

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'user_id',
            'id'
        );
    }

    public function pengajuan()
    {
        return $this->belongsTo(
            Pengajuan::class,
            'pengajuan_id_pengajuan',
            'id_pengajuan'
        );
    }
}