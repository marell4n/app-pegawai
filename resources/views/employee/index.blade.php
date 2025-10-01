@extends('master')
@section('title', 'Daftar Pegawai')
@section('content')
<h1>Daftar Pegawai</h1>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Nama Lengkap</th>
                <th>Email</th>
                <th>Nomor Telepon</th>
                <th>Tanggal Lahir</th>
                <th>Alamat</th>
                <th>Tanggal Masuk</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($employees as $employee)
                <tr>
                    <td>{{ $employee->nama_lengkap }}</td>
                    <td>{{ $employee->email }}</td>
                    <td>{{ $employee->nomor_telepon }}</td>
                    <td>{{ $employee->tanggal_lahir }}</td>
                    <td>{{ $employee->alamat }}</td>
                    <td>{{ $employee->tanggal_masuk }}</td>
                    <td>
                        <span class="status-{{ strtolower($employee->status) }}">
                            {{ $employee->status }}
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
            @endforeach
        </tbody>
    </table>
</div>

<div class="button-container">
    <div class="add-button">
        <a href="{{ route('employees.create') }}" class="btn-add">Tambah Pegawai</a>
    </div>
</div>
@endsection