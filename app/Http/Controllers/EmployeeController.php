<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Position;
use App\Models\Salary;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil kata kunci pencarian dari URL parameter '?search=...'
        $search = $request->input('search');

        $employees = Employee::with(['department', 'position'])
            // Jika ada $search, jalankan query filter ini
            ->when($search, function ($query, $search) {
                $query->where('nama_lengkap', 'like', "%{$search}%")
                      ->orWhereHas('department', function ($subQuery) use ($search) {
                          $subQuery->where('nama_department', 'like', "%{$search}%");
                      });
            })
            ->latest()
            ->paginate(10)
            ->appends(['search' => $search]);

        return view('employees.index', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::orderBy('nama_department')->get();
        $positions = Position::orderBy('nama_jabatan')->get();

        return view('employees.create', compact('departments', 'positions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'nomor_telepon' => 'required|string|max:20',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string|max:255',
            'tanggal_masuk' => 'required|date',
            'department_id' => [
                'required',
                'integer',
                Rule::exists('departments', 'id') // Pastikan ID ada di tabel departments
            ],
            'jabatan_id' => [
                'required',
                'integer',
                Rule::exists('positions', 'id') // Pastikan ID ada di tabel positions
            ],
            'status' => 'required|string|max:50',
        ]);
       $newEmployee = Employee::create($request->all());

       // Logika Pembuatan Gaji Otomatis
       $position = Position::find($newEmployee->jabatan_id);
       $gajiPokok = $position->gaji_pokok ?? 0;

       $tunjanganPersen = 0.10; // 10%
       $potonganPersen = 0.04;  // 4%

       $tunjangan = $gajiPokok * $tunjanganPersen;
       $potongan = $gajiPokok * $potonganPersen;
       $totalGaji = $gajiPokok + $tunjangan - $potongan;

       Salary::create([
            'karyawan_id' => $newEmployee->id,
            'bulan' => Carbon::now()->format('Y-m'), // Format: 2025-10
            'gaji_pokok' => $gajiPokok,
            'tunjangan' => $tunjangan,
            'potongan' => $potongan,
            'total_gaji' => $totalGaji,
        ]);
        // ----

        return redirect()->route('employees.index')
                         ->with('success', 'Pegawai berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $employee = Employee::with(['department',
                                               'position',
                                               'salaries' => function ($query) {
                                                    // Mengambil data gaji terbaru berdasarkan 'bulan'
                                                    $query->orderBy('bulan', 'desc')->take(1);
                                                }
        ])->findOrFail($id);
        return view('employees.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $employee = Employee::findOrFail($id);
        $departments = Department::orderBy('nama_department')->get();
        $positions = Position::orderBy('nama_jabatan')->get();
        
        $employee = Employee::find($id);
        return view('employees.edit', compact('employee', 'departments', 'positions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $employee = Employee::findOrFail($id);
        $oldJabatanId = $employee->jabatan_id;

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:employees,email',
            'nomor_telepon' => 'required|string|max:15',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string|max:255',
            'tanggal_masuk' => 'required|date',
            'department_id' => [ 'required', 'integer', Rule::exists('departments', 'id') ],
            'jabatan_id' => [ 'required', 'integer', Rule::exists('positions', 'id') ],
            'status' => 'required|string|max:50',
        ]);

        $newJabatanId = $request->input('jabatan_id');
        
        $employee->update($request->all());

        // Logika Update Gaji Jika Jabatan Berubah
        if ($oldJabatanId != $newJabatanId) {
            $position = Position::find($newJabatanId);
            $gajiPokok = $position->gaji_pokok ?? 0;

            $tunjanganPersen = 0.10; // 10%
            $potonganPersen = 0.04;  // 4%

            $tunjangan = $gajiPokok * $tunjanganPersen;
            $potongan = $gajiPokok * $potonganPersen;
            $totalGaji = $gajiPokok + $tunjangan - $potongan;

            Salary::updateOrCreate(
                [
                    'karyawan_id' => $employee->id,
                    'bulan'       => Carbon::now()->format('Y-m') // Mencari berdasarkan bulan ini
                ],
                [
                    'gaji_pokok'  => $gajiPokok, // Data yang di-update atau di-create
                    'tunjangan'   => $tunjangan,
                    'potongan'    => $potongan,
                    'total_gaji'  => $totalGaji,
                ]
            );

            return redirect()->route('employees.index')
                             ->with('success', 'Data pegawai berhasil diperbarui dan gaji telah disesuaikan.');
        }
        // -----
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $employee = Employee::findOrFail($id);
        
        try {
            $employee->salaries()->delete();
            $employee->attendance()->delete();
            $employee->delete();
            return redirect()->route('employees.index')
                         ->with('success', 'Data pegawai berhasil dihapus.'); // Tambahkan pesan sukses
        } catch (\Illuminate\Database\QueryException $e) {
            // Tangani jika ada relasi lain yang menghalangi penghapusan
             return redirect()->route('employees.index')
                         ->with('error', 'Data pegawai tidak dapat dihapus karena terkait dengan data lain.');
        }
    }
}