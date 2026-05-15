<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pegawai;

class PegawaiSeeder extends Seeder
{
    public function run(): void
    {
        Pegawai::create([
            'id_pegawai' => 'PG001',
            'nip' => '1987654321001',
            'nidn' => '1234567890',
            'nama' => 'Operator Fakultas',
            'jenis_kelamin' => 'Laki-laki',
            'tanggal_lahir' => '1990-01-01',
            'no_hp' => '081234567890',
            'no_hp_darurat' => '081234567891',
            'homebase' => 'Teknik Informatika',
            'email' => 'operator@kampus.ac.id',
        ]);

        Pegawai::create([
            'id_pegawai' => 'PG002',
            'nip' => '1987654321002',
            'nidn' => '2234567890',
            'nama' => 'Dosen Informatika',
            'jenis_kelamin' => 'Perempuan',
            'tanggal_lahir' => '1988-05-10',
            'no_hp' => '081234567892',
            'no_hp_darurat' => '081234567893',
            'homebase' => 'Teknik Informatika',
            'email' => 'dosen@kampus.ac.id',
        ]);

        Pegawai::create([
            'id_pegawai' => 'PG003',
            'nip' => '1987654321003',
            'nidn' => '3234567890',
            'nama' => 'Pimpinan Fakultas',
            'jenis_kelamin' => 'Laki-laki',
            'tanggal_lahir' => '1975-03-15',
            'no_hp' => '081234567894',
            'no_hp_darurat' => '081234567895',
            'homebase' => 'Teknik Informatika',
            'email' => 'pimpinan@kampus.ac.id',
        ]);
    }
}