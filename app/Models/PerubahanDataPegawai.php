<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerubahanDataPegawai extends Model
{
    protected $table = 'perubahan_data_pegawai';

    public $incrementing = false;

    protected $fillable = [
        'id_pengajuan',
        'kolom_diubah',
        'nilai_lama',
        'nilai_baru',
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