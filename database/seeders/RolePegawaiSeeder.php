<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePegawaiSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('role_pegawai')->insert([
            [
                'role_id_role' => 'R001',
                'pegawai_id_pegawai' => 'PG001',
            ],
            [
                'role_id_role' => 'R002',
                'pegawai_id_pegawai' => 'PG002',
            ],
            [
                'role_id_role' => 'R003',
                'pegawai_id_pegawai' => 'PG003',
            ],
        ]);
    }
}