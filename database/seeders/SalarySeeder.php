<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Employee; // Import Employee
use Carbon\Carbon;

class SalarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua karyawan beserta relasi jabatannya
        $employees = Employee::with('position')->get();
        $salaries = [];
        $bulanIni = Carbon::now()->format('Y-m'); // Format: '2025-10'

$tunjanganPersen = 0.10; // 10%
        $potonganPersen = 0.04;  // 4%

        foreach ($employees as $employee) {
            // Pastikan relasi position ada dan gaji_pokok ada
            $gajiPokok = $employee->position->gaji_pokok ?? 0; // Ambil gaji dari relasi

            // Hitung Tunjangan dan Potongan berdasarkan persentase tetap
            $tunjangan = $gajiPokok * $tunjanganPersen;
            $potongan = $gajiPokok * $potonganPersen;

            $totalGaji = $gajiPokok + $tunjangan - $potongan;

            $salaries[] = [
                'karyawan_id' => $employee->id,
                'bulan' => $bulanIni,
                'gaji_pokok' => $gajiPokok,
                'tunjangan' => $tunjangan,
                'potongan' => $potongan,
                'total_gaji' => $totalGaji,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        // Hapus data gaji lama
        DB::table('salaries')->truncate(); 
        
        // Masukkan semua data gaji baru ke database
        DB::table('salaries')->insert($salaries);
    }
}