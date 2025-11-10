<?php

namespace App\Http\Controllers;

use App\Models\PerformanceReview;
use App\Models\Employee;
use Illuminate\Http\Request;

class PerformanceReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $reviews = PerformanceReview::with('employee')
            ->when($search, function ($query, $search) {
                // Cari review berdasarkan nama karyawan terkait
                $query->whereHas('employee', function($q) use($search) {
                    $q->where('nama_lengkap', 'like', "%{$search}%");
                });
            })
            ->latest('tanggal_review')
            ->paginate(10)
            ->appends(['search' => $search]);

        return view('performance_reviews.index', compact('reviews'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::orderBy('nama_lengkap')->where('status', 'aktif')->get();
        return view('performance_reviews.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input sesuai aturan bisnis yang Anda minta
        $request->validate([
            'karyawan_id' => 'required|exists:employees,id',
            'tanggal_review' => 'required|date', // Bisa input datetime-local di view nanti
            'skor' => 'required|numeric|min:0|max:10',
            'catatan_feedback' => 'required|string',
        ]);

        PerformanceReview::create($request->all());

        return redirect()->route('performance-reviews.index')
                         ->with('success', 'Performance review berhasil ditambahkan.');
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
        $performanceReview = PerformanceReview::findOrFail($id);
        $employees = Employee::orderBy('nama_lengkap')->where('status', 'aktif')->get();

        return view('performance_reviews.edit', compact('performanceReview', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $performanceReview = PerformanceReview::findOrFail($id);

        $request->validate([
            'karyawan_id' => 'required|exists:employees,id',
            'tanggal_review' => 'required|date',
            'skor' => 'required|numeric|min:0|max:10',
            'catatan_feedback' => 'required|string',
        ]);

        $performanceReview->update($request->all());

        return redirect()->route('performance-reviews.index')
                         ->with('success', 'Performance review berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $performanceReview = PerformanceReview::findOrFail($id);
        $performanceReview->delete();

        return redirect()->route('performance-reviews.index')
                         ->with('success', 'Performance review berhasil dihapus.');
    }
}
