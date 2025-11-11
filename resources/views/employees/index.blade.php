@extends('master')
@section('title', 'Daftar Pegawai')
@section('content')
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="{{ asset('css/styleindex.css') }}">

<h1>Daftar Pegawai</h1>
@if (session('success'))
    <div style="color: green; margin-bottom: 15px; text-align: center;">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div style="color: red; margin-bottom: 15px; text-align: center;">{{ session('error') }}</div>
@endif

<div class="search-container">
    <form action="{{ route('employees.index') }}" method="GET" class="search-form">
        <input type="text" name="search" placeholder="Cari nama pegawai..." value="{{ request('search') }}">
        <button type="submit">Cari</button>
        @if(request('search'))
            {{-- Tombol reset jika sedang mencari --}}
            <a href="{{ route('employees.index') }}" style="display: flex; align-items: center; color: #D56989; text-decoration: none; font-weight: bold; margin-left: 5px;">✕</a>
        @endif
    </form>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Nama Lengkap</th>
                <th>Email</th>
                <th>Nomor Telepon</th>
                <th>Tanggal Masuk</th>
                <th>Departemen</th>
                <th>Jabatan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($employees as $employee)
                <tr>
                    <td>{{ $employee->nama_lengkap }}</td>
                    <td>{{ $employee->email }}</td>
                    <td>{{ $employee->nomor_telepon }}</td>
                    <td>{{ $employee->tanggal_masuk }}</td>
                    <td>{{ $employee->department ? $employee->department->nama_department : '-' }}</td>
                    <td>{{ $employee->position ? $employee->position->nama_jabatan : '-' }}</td>
                    <td>
                        <span class="status-{{ strtolower($employee->status) }}">
                            {{ ucfirst($employee->status) }}
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('employees.show', $employee->id) }}" class="btn btn-detail">Detail</a>
                            <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-edit">Edit</a>
                            <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-delete" onclick="return confirm('Yakin ingin menghapus?')">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <td colspan="6" style="text-align: center; padding: 20px;">Tidak ada data pegawai ditemukan.</td>
            @endforelse
        </tbody>
    </table>
</div>

<div class="button-container">
    <div class="add-button">
        <a href="{{ route('employees.create') }}" class="btn-add">Tambah Pegawai</a>
    </div>
</div>
@endsection