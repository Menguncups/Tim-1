<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanSuratTugas extends Model
{
    protected $table = 'pengajuan_surat_tugas';

    protected $primaryKey = 'id_pengajuan';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_pengajuan',
        'nama_pengusul',
        'waktu_pelaksana',
        'lama_pelaksanaan',
        'perihal',
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