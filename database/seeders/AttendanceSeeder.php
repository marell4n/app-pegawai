<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Employee; // Import model Employee
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua ID karyawan yang ada
        $employeeIds = Employee::pluck('id');

        $attendances = [];
        $statuses = ['hadir', 'izin', 'sakit', 'alpha'];
        $today = Carbon::today();

        foreach ($employeeIds as $employeeId) {
            // Buat data absensi dummy untuk 5 hari terakhir untuk setiap karyawan
            for ($i = 0; $i < 5; $i++) {
                $date = $today->copy()->subDays($i);
                $status = $statuses[array_rand($statuses)]; // Pilih status acak

                $waktuMasuk = null;
                $waktuKeluar = null;

                if ($status == 'hadir') {
                    // Buat waktu masuk dan keluar acak jika hadir
                    $masukHour = rand(7, 9); // Masuk antara jam 7-9
                    $keluarHour = rand(16, 18); // Keluar antara jam 16-18
                    $waktuMasuk = $date->copy()->setHour($masukHour)->setMinute(rand(0, 59))->setSecond(rand(0, 59))->format('H:i:s');
                    $waktuKeluar = $date->copy()->setHour($keluarHour)->setMinute(rand(0, 59))->setSecond(rand(0, 59))->format('H:i:s');
                }

                $attendances[] = [
                    'karyawan_id' => $employeeId,
                    'tanggal' => $date->toDateString(),
                    'waktu_masuk' => $waktuMasuk,
                    'waktu_keluar' => $waktuKeluar,
                    'status_absensi' => $status,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }
        }

        // Masukkan semua data absensi ke database
        DB::table('attendance')->insert($attendances);
    }
}