<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Employee; // Import Employee
use App\Models\Position; // Import Position
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

        foreach ($employees as $employee) {
            // Pastikan relasi position ada dan gaji_pokok ada
            $gajiPokok = $employee->position->gaji_pokok ?? 0; // Ambil gaji dari relasi, default 0 jika tidak ada

            // Tunjangan dan potongan dummy (bisa Anda kustomisasi)
            $tunjangan = $gajiPokok * (rand(5, 15) / 100); // Tunjangan 5-15% dari gaji pokok
            $potongan = $gajiPokok * (rand(1, 5) / 100);   // Potongan 1-5% dari gaji pokok

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

        // Masukkan semua data gaji ke database
        DB::table('salaries')->insert($salaries);
    }
}