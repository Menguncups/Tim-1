<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Operator Fakultas',
            'email' => 'operator@kampus.ac.id',
            'password' => Hash::make('12345678'),
            'pegawai_id_pegawai' => 'PG001',
        ]);

        User::create([
            'name' => 'Dosen Informatika',
            'email' => 'dosen@kampus.ac.id',
            'password' => Hash::make('12345678'),
            'pegawai_id_pegawai' => 'PG002',
        ]);

        User::create([
            'name' => 'Pimpinan Fakultas',
            'email' => 'pimpinan@kampus.ac.id',
            'password' => Hash::make('12345678'),
            'pegawai_id_pegawai' => 'PG003',
        ]);
    }
}