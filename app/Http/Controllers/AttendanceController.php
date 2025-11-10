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
    public function index(Request $request)
    {
        $searchDate = $request->input('search_date', Carbon::now()->format('Y-m-d'));
        $attendances = Attendance::with('employee')
            ->when($request->search_name, function ($query, $name) {
                $query->whereHas('employee', function($q) use($name) {
                    $q->where('nama_lengkap', 'like', "%{$name}%");
                });
            })
            // Filter ini tetap sama, akan otomatis jalan jika ada input tanggal
            ->where('tanggal', $searchDate)
            ->orderBy('karyawan_id')
            ->get();

        return view('attendances.index', compact('attendances', 'searchDate'));
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
        $validated = $request->validate([
            'karyawan_id' => [
                'required',
                'integer',
                'exists:employees,id',
                // Pastikan tidak ada absensi duplikat untuk karyawan di tanggal yg sama
                Rule::unique('attendance')->where(function ($query) use ($request) {
                    return $query->where('karyawan_id', $request->karyawan_id)
                                 ->where('tanggal', $request->tanggal);
                }),
            ],
            'tanggal' => 'required|date',
            'waktu_masuk' => 'nullable|date_format:H:i',
            'status_absensi' => 'nullable|in:I,S', // Hanya I atau S yang bisa diinput manual selain jam masuk
        ]);

        $data = $request->only(['karyawan_id', 'tanggal']);
        $waktuMasukInput = $request->input('waktu_masuk');
        $statusInput = $request->input('status_absensi');

        if ($waktuMasukInput) {
            $data['waktu_masuk'] = $waktuMasukInput;
            $data['waktu_keluar'] = '16:00:00';

            $jamMasuk = Carbon::parse($waktuMasukInput);
            $batasJamMasuk = Carbon::parse('07:00:00');
            $batasJamTelat = Carbon::parse('09:00:00');

            if ($jamMasuk->between($batasJamMasuk, $batasJamTelat, true)) {
                $data['status_absensi'] = 'H';
            } else { // Sebelum jam 7 atau setelah jam 9
                $data['status_absensi'] = $jamMasuk->gt($batasJamTelat) ? 'HT' : 'H';
            }
        } elseif (in_array($statusInput, ['I', 'S'])) {
            $data['status_absensi'] = $statusInput;
            $data['waktu_masuk'] = null;
            $data['waktu_keluar'] = null;
        } else {
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
        // Tidak digunakan
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $attendance = Attendance::findOrFail($id);
        
        // Logika Bisnis: Hanya status 'A' atau 'HT' yang boleh masuk form edit
        if (!in_array($attendance->status_absensi, ['A', 'HT'])) {
            return redirect()->route('attendances.index')
                             ->with('error', 'Hanya absensi dengan status Alpha (A) atau Hadir Terlambat (HT) yang dapat diubah.');
        }

        return view('attendances.edit', compact('attendance'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $attendance = Attendance::findOrFail($id);

        // Pastikan status asal masih A atau HT sebelum update
        if (!in_array($attendance->status_absensi, ['A', 'HT'])) {
             return redirect()->route('attendances.index')
                             ->with('error', 'Gagal update. Status absensi asli bukan Alpha (A) atau Hadir Terlambat (HT).');
        }

        // Validasi: status baru bisa apa saja dari enum
        $request->validate([
            'status_absensi' => ['required', Rule::in(['H', 'HT', 'I', 'S', 'A'])],
        ]);

        $newStatus = $request->input('status_absensi');
        $updateData = ['status_absensi' => $newStatus];

        // Jika status baru adalah I, S, atau A, pastikan waktu masuk/keluar null
        if (in_array($newStatus, ['I', 'S', 'A'])) {
            $updateData['waktu_masuk'] = null;
            $updateData['waktu_keluar'] = null;
        } 
        // Jika status baru H atau HT, biarkan waktu yang ada (jika ada dari HT)
        // atau tetap null (jika berasal dari A). Tidak ada input waktu di form edit.
        // Jika status asal adalah HT dan diubah ke H, waktu tetap tidak berubah.

        $attendance->update($updateData);

        return redirect()->route('attendances.index')
                         ->with('success', 'Status absensi berhasil diperbarui.'); // Pesan lebih umum
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
            // Log::error('Gagal hapus absensi: ' . $e->getMessage()); // Opsional: log error
            return redirect()->route('attendances.index')
                         ->with('error', 'Gagal menghapus data absensi.');
        }
    }
}