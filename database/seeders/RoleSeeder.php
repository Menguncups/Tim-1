<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('role')->insert([
            [
                'id_role' => 'R001',
                'nama_role' => 'operator',
            ],
            [
                'id_role' => 'R002',
                'nama_role' => 'dosen',
            ],
            [
                'id_role' => 'R003',
                'nama_role' => 'pimpinan',
            ],
        ]);
    }
}