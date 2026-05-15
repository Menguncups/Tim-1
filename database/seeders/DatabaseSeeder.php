<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PegawaiSeeder::class,
            UserSeeder::class,
            RolePegawaiSeeder::class,
        ]);
    }
}
