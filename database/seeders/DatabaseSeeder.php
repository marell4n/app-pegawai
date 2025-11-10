<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call([
            DepartmentSeeder::class,      // 1. Master Data Departemen
            PositionSeeder::class,        // 2. Master Data Jabatan
            EmployeeSeeder::class,        // 3. Data Pegawai (butuh Dept & Pos)
            AttendanceSeeder::class,      // 4. Data Absensi (butuh Pegawai)
            PerformanceReviewSeeder::class, // 5. Data Review (butuh Pegawai)
            SalarySeeder::class,          // 6. Data Gaji (jika ada, butuh Pegawai & Posisi)
        ]);
    }
}