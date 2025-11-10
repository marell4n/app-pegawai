<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Employee;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $employeeIds = Employee::pluck('id');
        $statuses = ['H', 'HT', 'I', 'S', 'A'];

        // === KONFIGURASI ===
        // Tentukan bulan & tahun yang mau diisi datanya (walau cuma sebagian)
        $targetMonth = now()->month;
        $targetYear = now()->year;
        // ===================

        // Kita mulai isi dari tanggal 1 bulan tersebut
        $startDate = Carbon::createFromDate($targetYear, $targetMonth, 1);

        // Hapus data lama di bulan target biar bersih saat di-seed ulang
        DB::table('attendance')
            ->whereMonth('tanggal', $targetMonth)
            ->whereYear('tanggal', $targetYear)
            ->delete();

        $attendances = [];
        foreach ($employeeIds as $employeeId) {
            // Loop cuma 10 hari pertama saja (TIDAK FULL SEBULAN)
            // Anda bisa ubah angka 10 ini mau berapa hari
            for ($i = 0; $i < 10; $i++) {
                $date = $startDate->copy()->addDays($i); // Hasil: Tgl 1, 2, 3, ... 10

                $status = $statuses[array_rand($statuses)];
                $waktuMasuk = null;
                $waktuKeluar = null;

                if ($status == 'H') {
                    $waktuMasuk = '08:00:00';
                    $waktuKeluar = '16:00:00';
                } elseif ($status == 'HT') {
                    $waktuMasuk = '09:30:00';
                    $waktuKeluar = '16:00:00';
                }

                $attendances[] = [
                    'karyawan_id' => $employeeId,
                    'tanggal' => $date->format('Y-m-d'),
                    'waktu_masuk' => $waktuMasuk,
                    'waktu_keluar' => $waktuKeluar,
                    'status_absensi' => $status,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('attendance')->insert($attendances);
    }
}