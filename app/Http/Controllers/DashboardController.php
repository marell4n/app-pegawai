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
        $now = Carbon::now();
        $month = (int) $request->input('month', $now->month);
        $year = (int) $request->input('year', $now->year);
        $daysInMonth = Carbon::createFromDate($year, $month)->daysInMonth;

        // 2. Kartu Ringkasan Total (Tetap sama)
        $totalSummary = [
            'Hadir' => Attendance::whereMonth('tanggal', $month)->whereYear('tanggal', $year)->whereIn('status_absensi', ['H', 'HT'])->count(),
            'Sakit' => Attendance::whereMonth('tanggal', $month)->whereYear('tanggal', $year)->where('status_absensi', 'S')->count(),
            'Izin'  => Attendance::whereMonth('tanggal', $month)->whereYear('tanggal', $year)->where('status_absensi', 'I')->count(),
            'Alpha' => Attendance::whereMonth('tanggal', $month)->whereYear('tanggal', $year)->where('status_absensi', 'A')->count(),
        ];

        // 3. Data Pegawai + Rekap Hitungan per Pegawai
        // Tambahkan withCount untuk menghitung jumlah H/S/I/A spesifik bulan & tahun ini
        $employees = Employee::where('status', 'aktif')
            ->withCount([
                'attendance as hadir_count' => function ($query) use ($month, $year) {
                    $query->whereYear('tanggal', $year)
                          ->whereMonth('tanggal', $month)
                          ->whereIn('status_absensi', ['H', 'HT']);
                },
                'attendance as sakit_count' => function ($query) use ($month, $year) {
                    $query->whereYear('tanggal', $year)
                          ->whereMonth('tanggal', $month)
                          ->where('status_absensi', 'S');
                },
                'attendance as izin_count' => function ($query) use ($month, $year) {
                    $query->whereYear('tanggal', $year)
                          ->whereMonth('tanggal', $month)
                          ->where('status_absensi', 'I');
                },
                'attendance as alpha_count' => function ($query) use ($month, $year) {
                    $query->whereYear('tanggal', $year)
                          ->whereMonth('tanggal', $month)
                          ->where('status_absensi', 'A');
                }
            ])
            ->orderBy('nama_lengkap')
            ->get();

        // 4. Data Matrix untuk Tabel (Tetap sama)
        $attendances = Attendance::whereMonth('tanggal', $month)
                                ->whereYear('tanggal', $year)
                                ->get();

        $attendanceMatrix = [];
        foreach ($attendances as $att) {
            $day = (int) Carbon::parse($att->tanggal)->day;
            $attendanceMatrix[$att->karyawan_id][$day] = $att->status_absensi;
        }

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