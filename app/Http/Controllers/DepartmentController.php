<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department; // <-- Import model

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $departments = Department::when($search, function ($query, $search) {
                $query->where('nama_department', 'like', "%{$search}%");
            })
            ->latest()
            ->get();

        return view('departments.index', compact('departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('departments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_department' => 'required|string|max:100|unique:departments,nama_department', // Nama harus unik
        ]);
        Department::create($request->all());
        return redirect()->route('departments.index')
                         ->with('success', 'Departemen berhasil ditambahkan.');
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
        $department = Department::findOrFail($id);
        return view('departments.edit', compact('department'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $department = Department::findOrFail($id);

        $request->validate([
            // Nama harus unik, kecuali untuk dirinya sendiri ($department->id)
            'nama_department' => 'required|string|max:100|unique:departments,nama_department,' . $id,
        ]);

        $department->update($request->all());

        // Redirect kembali ke halaman daftar departemen dengan pesan sukses
        return redirect()->route('departments.index')
                         ->with('success', 'Departemen berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $department = Department::findOrFail($id);
        
        try {
            $department->delete();
            return redirect()->route('departments.index')
                         ->with('success', 'Departemen berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Tangani error jika ada foreign key constraint (misal: masih ada employee di departemen ini)
             return redirect()->route('departments.index')
                         ->with('error', 'Departemen tidak dapat dihapus karena masih memiliki karyawan.');
        }
    }
}
