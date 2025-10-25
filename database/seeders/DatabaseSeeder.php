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
            DepartmentSeeder::class, // Harus dijalankan dulu
            PositionSeeder::class,   // Harus dijalankan dulu
            EmployeeSeeder::class,   // Data employee manual dibuat di sini
            AttendanceSeeder::class, // Sekarang bisa menggunakan data employee
            SalarySeeder::class,     // Sekarang bisa menggunakan data employee & position
        ]);
    }
}