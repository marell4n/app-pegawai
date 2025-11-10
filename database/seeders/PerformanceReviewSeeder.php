<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Employee;
use Carbon\Carbon;
use Faker\Factory as Faker;

class PerformanceReviewSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $employeeIds = Employee::pluck('id')->toArray();

        DB::table('performance_reviews')->truncate();

        $reviews = [];
        foreach ($employeeIds as $employeeId) {
            // Setiap pegawai dapat 1 atau 2 review secara acak
            $jumlahReview = rand(1, 2);

            for ($i = 0; $i < $jumlahReview; $i++) {
                // Review dalam 6 bulan terakhir
                $tanggal = $faker->dateTimeBetween('-6 months', 'now');
                $skor = $faker->randomFloat(2, 6.0, 10.0); // Skor antara 6.00 - 10.00

                $reviews[] = [
                    'karyawan_id' => $employeeId,
                    'tanggal_review' => $tanggal,
                    'skor' => $skor,
                    'catatan_feedback' => $faker->paragraph(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('performance_reviews')->insert($reviews);
    }
}