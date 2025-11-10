<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Attendance;
use Carbon\Carbon;

class DashboardController extends Controller
{
        public function index(Request $request)
    {
        // 1. Filter Bulan & Tahun
        // Kita set default ke waktu sekarang
        $now = Carbon::now();
        $month = (int) $request->input('month', $now->month);
        $year = (int) $request->input('year', $now->year);
        $daysInMonth = Carbon::createFromDate($year, $month)->daysInMonth;

        // 2. Kartu Ringkasan Total
        // Gunakan 'status_absensi' dan array ['H', 'HT'] untuk Hadir
        $totalSummary = [
            'Hadir' => Attendance::whereMonth('tanggal', $month)->whereYear('tanggal', $year)->whereIn('status_absensi', ['H', 'HT'])->count(),
            'Sakit' => Attendance::whereMonth('tanggal', $month)->whereYear('tanggal', $year)->where('status_absensi', 'S')->count(),
            'Izin'  => Attendance::whereMonth('tanggal', $month)->whereYear('tanggal', $year)->where('status_absensi', 'I')->count(),
            'Alpha' => Attendance::whereMonth('tanggal', $month)->whereYear('tanggal', $year)->where('status_absensi', 'A')->count(),
        ];

        // 3. Data untuk Matriks Rekap
        // Ambil pegawai aktif
        $employees = Employee::where('status', 'aktif')->orderBy('nama_lengkap')->get();

        // Ambil data absensi (pastikan kolomnya benar)
        $attendances = Attendance::whereMonth('tanggal', $month)
                                ->whereYear('tanggal', $year)
                                ->get();

        // Build Matrix: [karyawan_id][tanggal] = status_absensi
        $attendanceMatrix = [];
        foreach ($attendances as $att) {
            // Pastikan tanggal diparse dengan benar untuk mendapatkan angka hari (1-31)
            $day = (int) Carbon::parse($att->tanggal)->day;
            // PENTING: Gunakan 'status_absensi' sesuai nama kolom di database Anda
            $attendanceMatrix[$att->karyawan_id][$day] = $att->status_absensi;
        }

        // 4. (Opsional) Hitung ringkasan per pegawai untuk kartu jika diperlukan nanti
        // Code sebelumnya menggunakan withCount di sini, bisa tetap dipakai jika ingin efisien

        return view('dashboard.index', compact(
            'totalSummary',
            'employees',
            'attendanceMatrix',
            'daysInMonth',
            'month',
            'year'
        ));
    }
}