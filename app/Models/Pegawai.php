<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;

    protected $table = 'pegawai'; 
    protected $primaryKey = 'id_pegawai';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_pegawai', 'nip', 'nidn', 'nama', 'jenis_kelamin', 
        'tgl_lahir', 'tempat_lahir', 'homebase', 'no_hp', 
        'no_hp_darurat', 'email', 'foto'
    ];

    protected $casts = [
        'tgl_lahir' => 'date',
    ];

    // =========================================================================
    // ACCESSORS LOGIKA (Menghitung Kategori & Field HTML Tanpa Kolom Kategori)
    // =========================================================================

    // Menentukan kategori secara dinamis (Jika NIDN ada = dosen, jika kosong = tendik)
    public function getKategoriAttribute()
    {
        return !empty($this->nidn) ? 'dosen' : 'tendik';
    }

    // Pendukung variasi tampilan Jabatan Fungsional di HTML Dosen
    public function getJabatanFungsionalAttribute()
    {
        return !empty($this->nidn) ? 'Asisten Ahli' : '—';
    }

    // Pendukung variasi tampilan Pangkat Golongan di HTML
    public function getPangkatGolonganAttribute()
    {
        return !empty($this->nidn) ? 'Penata / III c' : 'Pengatur / II c';
    }

    // Pendukung variasi Jenis Pegawai di HTML Tendik
    public function getJenisPegawaiAttribute()
    {
        return empty($this->nidn) ? 'Tenaga Kependidikan Tetap' : '—';
    }
}