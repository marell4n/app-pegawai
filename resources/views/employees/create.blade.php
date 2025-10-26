@extends('master')
@section('title', 'Input Pegawai Baru')
@section('content')

{{-- Link CSS ditempatkan di sini agar konsisten --}}
<link rel="stylesheet" href="{{ asset('css/styleform.css') }}">

{{-- Judul H1 dibuat konsisten --}}
<h1>Form Tambah Pegawai</h1>

@if ($errors->any())
<div style="color: red; margin-bottom: 15px;">
    <strong>Whoops!</strong> Terjadi kesalahan input.<br><br>
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('employees.store') }}" method="POST">
    @csrf
    <table>
        <tr>
            <td><label for="nama_lengkap">Nama Lengkap:</label></td>
            <td><input type="text" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required></td>
        </tr>
        <tr>
            <td><label for="email">Email:</label></td>
            <td><input type="email" id="email" name="email" value="{{ old('email') }}" required></td>
        </tr>
        <tr>
            <td><label for="nomor_telepon">Nomor Telepon:</label></td>
            <td><input type="text" id="nomor_telepon" name="nomor_telepon" value="{{ old('nomor_telepon') }}"></td>
        </tr>
        <tr>
            <td><label for="tanggal_lahir">Tanggal Lahir:</label></td>
            <td><input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}"></td>
        </tr>
        <tr>
            <td><label for="alamat">Alamat:</label></td>
            <td><textarea id="alamat" name="alamat">{{ old('alamat') }}</textarea></td>
        </tr>
        <tr>
            <td><label for="tanggal_masuk">Tanggal Masuk:</label></td>
            <td><input type="date" id="tanggal_masuk" name="tanggal_masuk" value="{{ old('tanggal_masuk') }}" required></td>
        </tr>
        <tr>
            <td><label for="department_id">Departemen:</label></td>
            <td>
                <select id="department_id" name="department_id" required>
                    <option value="">-- Pilih Departemen --</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                            {{ $department->nama_department }}
                        </option>
                    @endforeach
                </select>
            </td>
        </tr>
        <tr>
            <td><label for="jabatan_id">Posisi/Jabatan:</label></td>
            <td>
                <select id="jabatan_id" name="jabatan_id" required>
                    <option value="">-- Pilih Posisi --</option>
                    @foreach($positions as $position)
                        <option value="{{ $position->id }}" {{ old('jabatan_id') == $position->id ? 'selected' : '' }}>
                            {{ $position->nama_jabatan }}
                        </option>
                    @endforeach
                </select>
            </td>
        </tr>
        <tr>
            <td><label for="status">Status:</label></td>
            <td>
                <select id="status" name="status">
                    <option value="aktif" {{ old('status', 'aktif') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align:right;">
                <button type="submit">Simpan</button>
                <a href="{{ route('employees.index') }}" class="btn btn-back" style="margin-left: 10px; text-decoration: none; display: inline-block; background: #EA9CAF; color: white; padding: 15px 30px; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(213, 105, 137, 0.4); text-transform: uppercase; letter-spacing: 1px;">Kembali</a>
            </td>
        </tr>
    </table>
</form>
@endsection