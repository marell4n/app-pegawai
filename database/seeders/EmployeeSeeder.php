<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID'); // Pakai locale Indonesia

        // Matikan foreign key check sementara untuk truncate
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('employees')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $employees = [];
        // Ambil ID yang valid dari departments dan positions
        // Asumsi seeder Department & Position sudah dijalankan dan ID-nya 1-5 dan 1-4
        $deptIds = [1, 2, 3, 4, 5];
        $posIds = [1, 2, 3, 4]; 

        for ($i = 1; $i <= 30; $i++) {
            $employees[] = [
                'nama_lengkap' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'nomor_telepon' => $faker->phoneNumber,
                'tanggal_lahir' => $faker->date('Y-m-d', '-22 years'), // Umur min 22 thn
                'alamat' => $faker->address,
                'tanggal_masuk' => $faker->date('Y-m-d', '-1 years'), // Masuk dalam 1 tahun terakhir
                'department_id' => $faker->randomElement($deptIds),
                'jabatan_id' => $faker->randomElement($posIds),
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('employees')->insert($employees);
    }
}