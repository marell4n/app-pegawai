<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Position;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = Employee::latest()->paginate(10);
        return view('employees.index', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::orderBy('nama_department')->get();
        $positions = Position::orderBy('nama_jabatan')->get();

        return view('employee.create', compact('departments', 'positions'));
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
        Employee::create($request->all());
        return redirect()->route('employees.index')
                         ->with('success', 'Pegawai berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $employee = Employee::with(['department', 'position'])->findOrFail($id);
        return view('employee.show', compact('employee'));
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
        return view('employee.edit', compact('employee', 'departments', 'positions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $employee = Employee::findOrFail($id);

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:employees,email',
            'nomor_telepon' => 'required|string|max:15',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string|max:255',
            'tanggal_masuk' => 'required|date',
             'department_id' => [
                'required',
                'integer',
                Rule::exists('departments', 'id')
            ],
            'jabatan_id' => [
                'required',
                'integer',
                Rule::exists('positions', 'id')
            ],
            'status' => 'required|string|max:50',
        ]);
        $employee->update($request->all());
        return redirect()->route('employees.index')
                         ->with('success', 'Data pegawai berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $employee = Employee::findOrFail($id);
        
        try {
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