<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Employee;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua ID pegawai yang baru saja dibuat
        $employeeIds = Employee::pluck('id')->toArray();

        if (empty($employeeIds)) {
            $this->command->warn('Tidak ada data karyawan. Jalankan EmployeeSeeder dulu.');
            return;
        }

        DB::table('attendance')->truncate();

        $attendances = [];
        // Mulai dari 5 bulan yang lalu sampai hari ini
        $startDate = Carbon::now()->subMonths(5)->startOfMonth();
        $endDate = Carbon::now();

        while ($startDate <= $endDate) {
            // Skip hari Sabtu (6) dan Minggu (0)
            if ($startDate->isWeekend()) {
                $startDate->addDay();
                continue;
            }

            foreach ($employeeIds as $employeeId) {
                // Random status dengan bobot agar realistis
                $rand = rand(1, 100);
                if ($rand <= 85) {      // 85% Hadir Tepat Waktu
                    $status = 'H'; $masuk = '08:00:00'; $keluar = '16:00:00';
                } elseif ($rand <= 90) { // 5% Terlambat
                    $status = 'HT'; $masuk = '09:15:00'; $keluar = '16:00:00';
                } elseif ($rand <= 95) { // 5% Sakit
                    $status = 'S'; $masuk = null; $keluar = null;
                } elseif ($rand <= 98) { // 3% Izin
                    $status = 'I'; $masuk = null; $keluar = null;
                } else {                 // 2% Alpha
                    $status = 'A'; $masuk = null; $keluar = null;
                }

                $attendances[] = [
                    'karyawan_id' => $employeeId,
                    'tanggal' => $startDate->format('Y-m-d'),
                    'waktu_masuk' => $masuk,
                    'waktu_keluar' => $keluar,
                    'status_absensi' => $status,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            $startDate->addDay();
        }

        // Insert per 500 data agar tidak overload memory
        foreach (array_chunk($attendances, 500) as $chunk) {
            DB::table('attendance')->insert($chunk);
        }
    }
}