<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PangkatGolongan extends Model
{
    protected $table = 'pangkat_golongan';

    protected $primaryKey = 'id_pengajuan';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_pengajuan',
        'id_pegawai',
        'pangkat',    
        'golongan',   
        'tmt',
        'dokumen_sk_cpns',
        'dokumen_sk_pns',
        'dokumen_pak',
        'dokumen_publikasi_ilmiah',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class, 'id_pengajuan', 'id_pengajuan');
    }
}