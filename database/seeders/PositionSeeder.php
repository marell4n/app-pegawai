<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PositionSeeder extends Seeder
{
    public function run(): void
    {

        // Matikan foreign key check sementara agar truncate tidak error jika ada data terkait
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('positions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $positions = [
            ['nama_jabatan' => 'Manager', 'gaji_pokok' => 15000000, 'created_at' => now(), 'updated_at' => now()],
            ['nama_jabatan' => 'Supervisor', 'gaji_pokok' => 10000000, 'created_at' => now(), 'updated_at' => now()],
            ['nama_jabatan' => 'Senior Staff', 'gaji_pokok' => 7500000, 'created_at' => now(), 'updated_at' => now()],
            ['nama_jabatan' => 'Staff', 'gaji_pokok' => 5000000, 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('positions')->insert($positions);
    }
}