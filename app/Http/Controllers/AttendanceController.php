<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attendances = Attendance::with('employee')
                         ->orderBy('tanggal', 'desc')
                         ->orderBy('karyawan_id')
                         ->get();
                         
        return view('attendances.index', compact('attendances'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::orderBy('nama_lengkap')->get();

        return view('attendances.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'karyawan_id' => 'required|integer|exists:employees,id',
            'tanggal' => 'required|date',
            'waktu_masuk' => 'nullable|date_format:H:i', // Jam masuk (opsional)
            'status_absensi' => 'nullable|in:I,S,A', // Status (opsional, jika tidak masuk kerja)
             // Pastikan tidak ada absensi duplikat untuk karyawan di tanggal yg sama
            Rule::unique('attendance')->where(function ($query) use ($request) {
                return $query->where('karyawan_id', $request->karyawan_id)
                             ->where('tanggal', $request->tanggal);
            }),
        ]);

        $data = $request->only(['karyawan_id', 'tanggal']);
        $waktuMasukInput = $request->input('waktu_masuk');
        $statusInput = $request->input('status_absensi');

        if ($waktuMasukInput) {
            // --- Logika 1: Jika Karyawan Masuk (Hadir / Telat) ---
            $data['waktu_masuk'] = $waktuMasukInput;
            $data['waktu_keluar'] = '16:00:00'; // Jam keluar otomatis

            // Tentukan status H (Hadir) atau HT (Hadir Terlambat)
            $jamMasuk = Carbon::parse($waktuMasukInput);
            $batasJamMasuk = Carbon::parse('07:00:00');
            $batasJamTelat = Carbon::parse('09:00:00');

            if ($jamMasuk->between($batasJamMasuk, $batasJamTelat, true)) { // true = inklusif (tepat jam 7/9)
                $data['status_absensi'] = 'H';
            } else if ($jamMasuk->gt($batasJamTelat)) { // Lebih dari jam 9
                $data['status_absensi'] = 'HT';
            } else {
                 // Masuk sebelum jam 7 (dianggap Hadir)
                 $data['status_absensi'] = 'H';
            }

        } elseif (in_array($statusInput, ['I', 'S'])) {
            // --- Logika 2: Jika Karyawan Izin atau Sakit ---
            $data['status_absensi'] = $statusInput;
            $data['waktu_masuk'] = null;
            $data['waktu_keluar'] = null;
        } else {
            // --- Logika 3: Jika tidak input jam masuk atau I/S, dianggap Alpha ---
            $data['status_absensi'] = 'A';
            $data['waktu_masuk'] = null;
            $data['waktu_keluar'] = null;
        }

        Attendance::create($data);

        return redirect()->route('attendances.index')
                         ->with('success', 'Data absensi berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $attendance = Attendance::findOrFail($id);
        
        // Logika Bisnis: Hanya status 'A' (Alpha) yang boleh diubah
        if ($attendance->status_absensi !== 'A') {
            return redirect()->route('attendances.index')
                             ->with('error', 'Hanya absensi dengan status Alpha (A) yang dapat diubah.');
        }

        return view('attendances.edit', compact('attendance'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $attendance = Attendance::findOrFail($id);

        // Pastikan status asal masih 'A' (jika admin buka 2 tab)
        if ($attendance->status_absensi !== 'A') {
             return redirect()->route('attendances.index')
                             ->with('error', 'Gagal update. Status absensi asli bukan Alpha (A).');
        }

        // Validasi status baru
        $request->validate([
            // Status baru hanya boleh 'I' atau 'S'
            'status_absensi' => 'required|in:I,S',
        ]);

        // Update status (waktu masuk/keluar tetap null)
        $attendance->update([
            'status_absensi' => $request->input('status_absensi'),
        ]);

        return redirect()->route('attendances.index')
                         ->with('success', 'Status absensi Alpha (A) berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $attendance = Attendance::findOrFail($id);
        try {
            $attendance->delete();
            return redirect()->route('attendances.index')
                         ->with('success', 'Data absensi berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('attendances.index')
                         ->with('error', 'Gagal menghapus data absensi.');
        }
    }
}
