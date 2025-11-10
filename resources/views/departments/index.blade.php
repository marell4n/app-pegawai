@extends('master')
@section('title', 'Daftar Departemen')
@section('content')

<link rel="stylesheet" href="{{ asset('css/styleindex.css') }}">

<h1>Daftar Departemen</h1>

{{-- Pesan Sukses/Error --}}
@if (session('success'))
    <div style="color: green; margin-bottom: 15px; text-align: center;">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div style="color: red; margin-bottom: 15px; text-align: center;">{{ session('error') }}</div>
@endif

<div class="search-container">
    <form action="{{ route('departments.index') }}" method="GET" class="search-form">
        <input type="text" name="search" placeholder="Cari nama departemen..." value="{{ request('search') }}">
        <button type="submit">Cari</button>
        @if(request('search'))
            {{-- Tombol reset jika sedang mencari --}}
            <a href="{{ route('departments.index') }}" style="display: flex; align-items: center; color: #D56989; text-decoration: none; font-weight: bold; margin-left: 5px;">✕ Reset</a>
        @endif
    </form>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Departemen</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($departments as $department)
                <tr>
                    <td >{{ $department->id }}</td>
                    <td>{{ $department->nama_department }}</td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('departments.edit', $department->id) }}" class="btn btn-edit">Edit</a>
                            <form action="{{ route('departments.destroy', $department->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus departemen ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-delete">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" style="text-align: center;">Tidak ada data departemen.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Tombol Tambah --}}
<div class="button-container" style="margin-bottom: 20px;">
    <div class="add-button">
        <a href="{{ route('departments.create') }}" class="btn-add">Tambah Departemen</a>
    </div>
</div>
@endsection