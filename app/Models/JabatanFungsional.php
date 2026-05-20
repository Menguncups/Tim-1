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
        'id_pegawai',
        'nama_jabatan',
        'tmt',
        'dokumen_sk_cpns',
        'dokumen_sk_pns',
        'dokumen_pak',
        'dokumen_publikasi_ilmiah',
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