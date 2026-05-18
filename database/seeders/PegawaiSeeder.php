<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pegawai;

class PegawaiSeeder extends Seeder
{
    public function run(): void
    {
        // PG001: Khusus Operator / Admin Fakultas
        Pegawai::create([
            'id_pegawai'         => 'PG001',
            'nip'                => '199508122023011001',
            'nidn'               => '1234567890', 
            'nama'               => 'Operator Kepegawaian FT UNRI',
            'jenis_kelamin'      => 'Laki-laki',
            'tanggal_lahir'      => '1995-08-12',
            'no_hp'              => '081234567890', 
            'no_hp_darurat'      => '081234567891', 
            'homebase'           => 'Bagian Tata Usaha',
            'email'              => 'operator@lecturer.unri.ac.id',
            'jabatan_fungsional' => 'Pengadministrasi Umum', 
            'pangkat_golongan'   => 'Pengatur - II/c',
        ]);

        // PG002: Khusus Dosen Riil Fakultas Teknik UNRI
        Pegawai::create([
            'id_pegawai'         => 'PG002',
            'nip'                => '199206212024061001',
            'nidn'               => '1021069203',
            'nama'               => 'Khairul Umam Syaliman, S.T., M.Kom.',
            'jenis_kelamin'      => 'Laki-laki',
            'tanggal_lahir'      => '1992-06-21',
            'no_hp'              => '081234567892',
            'no_hp_darurat'      => '081234567893',
            'homebase'           => 'Teknik Informatika',
            'email'              => 'khairul.umam@lecturer.unri.ac.id',
            'jabatan_fungsional' => 'Asisten Ahli', 
            'pangkat_golongan'   => 'Penata Muda Tk. I - III/b',
        ]);

        // PG003: Khusus Pimpinan / Dekan / WD
        Pegawai::create([
            'id_pegawai'         => 'PG003',
            'nip'                => '198503152015041003', 
            'nidn'               => '0015038501',
            'nama'               => 'Pimpinan / Dekan Fakultas Teknik',
            'jenis_kelamin'      => 'Laki-laki',
            'tanggal_lahir'      => '1985-03-15',
            'no_hp'              => '081234567894',
            'no_hp_darurat'      => '081234567895',
            'homebase'           => 'Fakultas Teknik',
            'email'              => 'pimpinan@lecturer.unri.ac.id',
            'jabatan_fungsional' => 'Lektor Kepala',
            'pangkat_golongan'   => 'Pembina - IV/a',
        ]);
    }
}