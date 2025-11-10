<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Position; // <-- Import model

class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $positions = Position::when($search, function ($query, $search) {
                $query->where('nama_jabatan', 'like', "%{$search}%");
            })
            ->latest()
            ->get();

        return view('positions.index', compact('positions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('positions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_jabatan' => 'required|string|max:100|unique:positions,nama_jabatan', // Nama harus unik
            'gaji_pokok' => 'required|numeric|min:0', // Gaji harus angka >= 0
        ]);

        Position::create($request->all());

        return redirect()->route('positions.index')
                         ->with('success', 'Posisi berhasil ditambahkan.');
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
        $position = Position::findOrFail($id);
        return view('positions.edit', compact('position'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $position = Position::findOrFail($id);

        $request->validate([
            // Nama harus unik, kecuali untuk ID yang sedang diedit ($id)
            'nama_jabatan' => 'required|string|max:100|unique:positions,nama_jabatan,' . $id,
            'gaji_pokok' => 'required|numeric|min:0', // Gaji harus angka >= 0
        ]);

        $position->update($request->all());

        return redirect()->route('positions.index')
                         ->with('success', 'Posisi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $position = Position::findOrFail($id);

        try {
            $position->delete();
            return redirect()->route('positions.index')
                         ->with('success', 'Posisi berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
             // Tangani error jika ada foreign key constraint (misal: masih ada employee dengan posisi ini)
             return redirect()->route('positions.index')
                         ->with('error', 'Posisi tidak dapat dihapus karena masih digunakan oleh karyawan.');
        }
    }
}
