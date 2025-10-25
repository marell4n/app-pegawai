<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Gunakan DB facade
use Carbon\Carbon; // Untuk timestamps

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data lama jika perlu (opsional)
        // DB::table('employees')->truncate();

        DB::table('employees')->insert([
            [
                'nama_lengkap' => 'Ahmad Budi Santoso',
                'email' => 'ahmad.budi@example.com',
                'nomor_telepon' => '081234567890',
                'tanggal_lahir' => '1990-05-15',
                'alamat' => 'Jl. Merdeka No. 10, Jakarta',
                'tanggal_masuk' => '2022-01-10',
                'department_id' => 1, // ID untuk 'Teknologi Informasi' (sesuaikan jika berbeda)
                'jabatan_id' => 1,    // ID untuk 'Software Engineer' (sesuaikan jika berbeda)
                'status' => 'aktif',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_lengkap' => 'Citra Dewi Lestari',
                'email' => 'citra.dewi@example.com',
                'nomor_telepon' => '087654321098',
                'tanggal_lahir' => '1992-08-22',
                'alamat' => 'Jl. Pahlawan No. 5, Surabaya',
                'tanggal_masuk' => '2021-03-20',
                'department_id' => 2, // ID untuk 'Sumber Daya Manusia'
                'jabatan_id' => 2,    // ID untuk 'HR Manager'
                'status' => 'aktif',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_lengkap' => 'Eko Prasetyo',
                'email' => 'eko.prasetyo@example.com',
                'nomor_telepon' => '085511223344',
                'tanggal_lahir' => '1988-12-01',
                'alamat' => 'Jl. Sudirman No. 15, Bandung',
                'tanggal_masuk' => '2023-07-01',
                'department_id' => 3, // ID untuk 'Keuangan'
                'jabatan_id' => 3,    // ID untuk 'Accountant'
                'status' => 'aktif',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
             [
                'nama_lengkap' => 'Fiona Anggraini',
                'email' => 'fiona.anggraini@example.com',
                'nomor_telepon' => '081199887766',
                'tanggal_lahir' => '1994-02-28',
                'alamat' => 'Jl. Thamrin No. 20, Medan',
                'tanggal_masuk' => '2022-11-05',
                'department_id' => 4, // ID untuk 'Pemasaran'
                'jabatan_id' => 4,    // ID untuk 'Marketing Specialist'
                'status' => 'nonaktif', // Contoh status nonaktif
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}