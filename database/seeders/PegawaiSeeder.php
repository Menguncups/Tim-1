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
            'id_pegawai' => 'PG001',
            'nip' => '198765432100000001',
            'nidn' => null,
            'nama' => 'Operator Fakultas',
            'jenis_kelamin' => 'Laki-laki',
            'tanggal_lahir' => '1990-01-01',
            'no_hp' => '081234567890',
            'no_hp_darurat' => '081234567891',
            'homebase' => 'S1 Teknik Informatika',
            'email' => 'operator@kampus.ac.id',
            'pangkat_golongan' => 'III/a - Penata Muda',
            'jabatan_fungsional' => 'Administrasi Umum',
            'foto' => null,
        ]);

        // PG002: Khusus Dosen Riil Fakultas Teknik UNRI
        Pegawai::create([
            'id_pegawai' => 'PG002',
            'nip' => '198765432100000002',
            'nidn' => '1234567890',
            'nama' => 'Dosen Informatika',
            'jenis_kelamin' => 'Perempuan',
            'tanggal_lahir' => '1988-05-10',
            'no_hp' => '081234567892',
            'no_hp_darurat' => '081234567893',
            'homebase' => 'S1 Teknik Informatika',
            'email' => 'dosen@kampus.ac.id',
            'pangkat_golongan' => 'III/b - Penata Muda Tk. I',
            'jabatan_fungsional' => 'Lektor',
            'foto' => null,
        ]);

        // PG003: Khusus Pimpinan / Dekan / WD
        Pegawai::create([
            'id_pegawai' => 'PG003',
            'nip' => '198765432100000003',
            'nidn' => '2234567890',
            'nama' => 'Pimpinan Fakultas',
            'jenis_kelamin' => 'Laki-laki',
            'tanggal_lahir' => '1975-03-15',
            'no_hp' => '081234567894',
            'no_hp_darurat' => '081234567895',
            'homebase' => 'S1 Teknik Informatika',
            'email' => 'pimpinan@kampus.ac.id',
            'pangkat_golongan' => 'IV/a - Pembina',
            'jabatan_fungsional' => 'Lektor Kepala',
            'foto' => null,
        ]);
    }
}