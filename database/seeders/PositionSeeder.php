<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Import DB facade
use Carbon\Carbon; // Import Carbon untuk timestamps

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('positions')->insert([
            [
                'nama_jabatan' => 'Software Engineer',
                'gaji_pokok' => 10000000.00,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_jabatan' => 'HR Manager',
                'gaji_pokok' => 12000000.00,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_jabatan' => 'Accountant',
                'gaji_pokok' => 8000000.00,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
             [
                'nama_jabatan' => 'Marketing Specialist',
                'gaji_pokok' => 9000000.00,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}