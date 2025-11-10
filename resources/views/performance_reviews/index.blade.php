@extends('master')
@section('title', 'Daftar Performance Review')
@section('content')

<link rel="stylesheet" href="{{ asset('css/styleindex.css') }}">
<style>
    /* Style tambahan khusus untuk read more */
    .feedback-short { display: inline; }
    .feedback-full { display: none; }
    .read-more-link { color: #D56989; cursor: pointer; font-size: 0.9em; font-weight: bold; }
    .read-more-link:hover { text-decoration: underline; }
</style>

<h1>Daftar Performance Review</h1>

@if (session('success'))
    <div style="color: green; margin-bottom: 15px; text-align: center;">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div style="color: red; margin-bottom: 15px; text-align: center;">{{ session('error') }}</div>
@endif

{{-- === FILTER BAR === --}}
<div class="search-container">
    <form action="{{ route('performance-reviews.index') }}" method="GET" class="search-form" style="max-width: 600px;">
        {{-- Input 1: Cari Nama --}}
        <input type="text" name="search" placeholder="Cari nama pegawai..." value="{{ request('search') }}" style="flex: 2;">

        {{-- Input 2: Dropdown Tanggal --}}
        <div class="filter-group">
            <label for="search_date" class="filter-label">Tanggal:</label>
            <input type="date" name="search_date" id="search_date" class="filter-input date-input" value="{{ request('search_date') }}">
        </div>

        <button type="submit">Cari</button>

        @if(request('search_name') || request('search_date'))
            <a href="{{ route('attendances.index') }}" style="display: flex; align-items: center; color: #D56989; text-decoration: none; font-weight: bold; margin-left: 10px;">
                ✕
            </a>
        @endif
    </form>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Karyawan</th>
                <th>Tanggal Review</th>
                <th>Skor</th>
                <th>Feedback</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reviews as $review)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td style="text-align: left;">{{ $review->employee->nama_lengkap ?? 'N/A' }}</td>
                    <td>{{ \Carbon\Carbon::parse($review->tanggal_review)->format('d-m-Y') }}</td>
                    <td><strong>{{ $review->skor }}</strong> / 10</td>
                    <td style="text-align: left; max-width: 300px;">
                        @if(strlen($review->catatan_feedback) > 50)
                            <span class="feedback-short">
                                {{ Str::limit($review->catatan_feedback, 50) }}
                            </span>
                            <span class="feedback-full">
                                {{ $review->catatan_feedback }}
                            </span>
                            <br>
                            <span class="read-more-link" onclick="toggleFeedback(this)">Read More</span>
                        @else
                            {{ $review->catatan_feedback }}
                        @endif
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('performance-reviews.edit', $review->id) }}" class="btn btn-edit">Edit</a>
                            <form action="{{ route('performance-reviews.destroy', $review->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus review ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-delete">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">Tidak ada data performance review.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="button-container" style="margin-bottom: 20px;">
    <div class="add-button">
        <a href="{{ route('performance-reviews.create') }}" class="btn-add">Tambah Review</a>
    </div>
</div>

<script>
    function toggleFeedback(element) {
        const cell = element.parentElement;
        const shortText = cell.querySelector('.feedback-short');
        const fullText = cell.querySelector('.feedback-full');

        if (shortText.style.display === 'none') {
            shortText.style.display = 'inline';
            fullText.style.display = 'none';
            element.innerText = 'Read More';
        } else {
            shortText.style.display = 'none';
            fullText.style.display = 'inline';
            element.innerText = 'Read Less';
        }
    }
</script>
@endsection