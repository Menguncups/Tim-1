<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    protected $table = 'pengajuan';

    protected $primaryKey = 'id_pengajuan';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_pengajuan',
        'tanggal_pengajuan',
        'jenis_pengajuan',
        'status',
        'pegawai_id_pegawai',
    ];

    public function pegawai()
    {
        return $this->belongsTo(
            Pegawai::class,
            'pegawai_id_pegawai',
            'id_pegawai'
        );
    }

    public function verifikasi()
    {
        return $this->hasMany(
            Verifikasi::class,
            'pengajuan_id_pengajuan',
            'id_pengajuan'
        );
    }

    public function suratTugas()
    {
        return $this->hasOne(
            PengajuanSuratTugas::class,
            'id_pengajuan',
            'id_pengajuan'
        );
    }

    public function perubahanData()
    {
        return $this->hasMany(
            PerubahanDataPegawai::class,
            'id_pengajuan',
            'id_pengajuan'
        );
    }

    public function jabatanFungsional()
    {
        return $this->hasOne(
            JabatanFungsional::class,
            'id_pengajuan',
            'id_pengajuan'
        );
    }

    public function pangkatGolongan()
    {
        return $this->hasOne(
            PangkatGolongan::class,
            'id_pengajuan',
            'id_pengajuan'
        );
    }
}