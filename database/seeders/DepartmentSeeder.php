<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        // Matikan pengecekan foreign key sementara
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('departments')->truncate();
        // Nyalakan kembali
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $departments = [
            ['nama_department' => 'Teknologi Informasi (IT)', 'created_at' => now(), 'updated_at' => now()],
            ['nama_department' => 'Human Resources (HR)', 'created_at' => now(), 'updated_at' => now()],
            ['nama_department' => 'Keuangan & Akuntansi', 'created_at' => now(), 'updated_at' => now()],
            ['nama_department' => 'Pemasaran & Penjualan', 'created_at' => now(), 'updated_at' => now()],
            ['nama_department' => 'Operasional Umum', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('departments')->insert($departments);
    }
}