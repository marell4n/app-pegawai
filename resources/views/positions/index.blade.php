@extends('master')
@section('title', 'Daftar Posisi / Jabatan')
@section('content')
<h1>Daftar Posisi / Jabatan</h1>

{{-- Tombol Tambah --}}
<div class="button-container" style="margin-bottom: 20px;">
    <div class="add-button">
        <a href="{{ route('positions.create') }}" class="btn-add">Tambah Posisi</a>
    </div>
</div>

{{-- Pesan Sukses/Error --}}
@if (session('success'))
    <div style="color: green; margin-bottom: 15px; text-align: center;">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div style="color: red; margin-bottom: 15px; text-align: center;">{{ session('error') }}</div>
@endif

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Jabatan</th>
                <th>Gaji Pokok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($positions as $position)
                <tr>
                    <td>{{ $position->id }}</td>
                    <td>{{ $position->nama_jabatan }}</td>
                    {{-- Format gaji pokok sebagai mata uang (misal: Rupiah) --}}
                    <td>Rp {{ number_format($position->gaji_pokok, 0, ',', '.') }}</td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('positions.edit', $position->id) }}" class="btn btn-edit">Edit</a>
                            <form action="{{ route('positions.destroy', $position->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus posisi ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-delete">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center;">Tidak ada data posisi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination Links --}}
<div style="margin-top: 20px;">
    {{ $positions->links() }}
</div>
@endsection