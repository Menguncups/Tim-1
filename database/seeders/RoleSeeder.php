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
                'nama_role' => 'dosen',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_role' => 'R002',
                'nama_role' => 'pimpinan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_role' => 'R003',
                'nama_role' => 'operator',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_role' => 'R004',
                'nama_role' => 'tendik',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}