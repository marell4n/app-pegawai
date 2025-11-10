<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Employee;
use Carbon\Carbon;
use Faker\Factory as Faker;

class PerformanceReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID'); // Menggunakan locale Indonesia agar feedback lebih relevan
        $employees = Employee::pluck('id')->toArray();

        if (empty($employees)) {
            $this->command->info('Tidak ada data karyawan. Seeder performance_reviews dilewati.');
            return;
        }

        $reviews = [];

        foreach ($employees as $employeeId) {
            // Buat 1 sampai 3 review untuk setiap karyawan agar datanya bervariasi
            $jumlahReview = rand(1, 3);

            for ($i = 0; $i < $jumlahReview; $i++) {
                $tanggalReview = $faker->dateTimeBetween('-1 year', 'now');
                
                // Generate skor desimal antara 5.00 sampai 10.00
                // mt_rand() menghasilkan integer, jadi kita bagi untuk dapat desimal
                $skor = $faker->randomFloat(2, 5, 10); 

                $reviews[] = [
                    'karyawan_id' => $employeeId,
                    'tanggal_review' => $tanggalReview,
                    'skor' => $skor,
                    'catatan_feedback' => $this->generateFeedback($skor, $faker),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }
        }

        // Masukkan data ke database
        DB::table('performance_reviews')->insert($reviews);
    }

    /**
     * Helper kecil untuk membuat feedback yang sedikit lebih relevan dengan skornya.
     */
    private function generateFeedback($skor, $faker)
    {
        if ($skor >= 9) {
            return $faker->randomElement([
                'Kinerja sangat luar biasa, melebihi ekspektasi di semua area.',
                'Menunjukkan kepemimpinan yang kuat dan hasil kerja yang sempurna tahun ini.',
                'Sangat proaktif dan inovatif. Aset berharga bagi tim.',
            ]);
        } elseif ($skor >= 7.5) {
            return $faker->randomElement([
                'Kinerja sangat baik dan konsisten memenuhi target.',
                'Dapat diandalkan dan bekerja sama dengan baik dalam tim.',
                'Telah menunjukkan peningkatan yang signifikan dalam beberapa bulan terakhir.',
            ]);
        } elseif ($skor >= 6) {
            return $faker->randomElement([
                'Kinerja cukup baik, namun perlu peningkatan di beberapa aspek teknis.',
                'Memenuhi sebagian besar target, tetapi konsistensi perlu dijaga.',
                'Komunikasi dalam tim perlu ditingkatkan lagi ke depannya.',
            ]);
        } else {
            return $faker->randomElement([
                'Perlu banyak peningkatan. Disarankan untuk mengikuti pelatihan tambahan.',
                'Sering melewatkan tenggat waktu penting. Perlu evaluasi kinerja lebih lanjut.',
                'Kinerja di bawah rata-rata. Membutuhkan bimbingan intensif dari atasan.',
            ]);
        }
    }
}