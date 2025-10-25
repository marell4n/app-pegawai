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
        // Status baru: H=Hadir, HT=Hadir Terlambat, I=Izin, S=Sakit, A=Alpha
        $statuses = ['H', 'HT', 'I', 'S', 'A'];
        $today = Carbon::today();
        
        $jamMasukHadir = Carbon::parse('08:15:00'); // Contoh Hadir (antara 7-9)
        $jamMasukTelat = Carbon::parse('09:30:00'); // Contoh Telat (> 9)

        foreach ($employeeIds as $employeeId) {
            // Buat data absensi dummy untuk 5 hari terakhir
            for ($i = 0; $i < 5; $i++) {
                $date = $today->copy()->subDays($i);
                $status = $statuses[array_rand($statuses)]; // Pilih status acak

                $waktuMasuk = null;
                $waktuKeluar = null;

                if ($status == 'H') {
                    // Jika status Hadir
                    $waktuMasuk = $jamMasukHadir->format('H:i:s');
                    $waktuKeluar = '16:00:00';
                } elseif ($status == 'HT') {
                     // Jika status Hadir Terlambat
                    $waktuMasuk = $jamMasukTelat->format('H:i:s');
                    $waktuKeluar = '16:00:00';
                }
                // Jika status I, S, atau A, $waktuMasuk dan $waktuKeluar tetap null

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
        
        // Hapus data lama sebelum insert
        DB::table('attendance')->truncate();

        // Masukkan semua data absensi baru ke database
        DB::table('attendance')->insert($attendances);
    }
}