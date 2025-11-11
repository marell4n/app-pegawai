@extends('master')
@section('title', 'Tambah Data Absensi')
@section('content')
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="{{ asset('css/styleform.css') }}">
<h1>Form Tambah Absensi</h1>

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

<form action="{{ route('attendances.store') }}" method="POST">
    @csrf
    <table>
        <tr>
            <td><label for="karyawan_id">Karyawan:</label></td>
            <td>
                <select id="karyawan_id" name="karyawan_id" required>
                    <option value="">-- Pilih Karyawan --</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" {{ old('karyawan_id') == $employee->id ? 'selected' : '' }}>
                            {{ $employee->nama_lengkap }}
                        </option>
                    @endforeach
                </select>
            </td>
        </tr>
        <tr>
            <td><label for="tanggal">Tanggal:</label></td>
            <td>
                {{-- Default tanggal hari ini --}}
                <input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal', now()->format('Y-m-d')) }}" required>
            </td>
        </tr>
        
        <tr>
            <td colspan="2" style="padding-top: 15px; border-bottom: 1px solid #ddd;">
                <strong>Pilih salah satu skenario di bawah ini:</strong>
            </td>
        </tr>

        {{-- Skenario 1: Hadir / Telat --}}
        <tr>
            <td><label for="waktu_masuk">1. Waktu Masuk (H/HT):</label></td>
            <td>
                <input type="time" id="waktu_masuk" name="waktu_masuk" value="{{ old('waktu_masuk') }}">
                <small>Isi jam masuk (misal: 08:30) untuk status Hadir (H) atau Hadir Terlambat (HT).</small>
            </td>
        </tr>

        {{-- Skenario 2: Izin / Sakit --}}
        <tr>
            <td><label for="status_absensi">2. Status (I/S):</label></td>
            <td>
                <select id="status_absensi" name="status_absensi">
                    <option value="">-- Pilih Status (Jika Izin/Sakit) --</option>
                    <option value="I" {{ old('status_absensi') == 'I' ? 'selected' : '' }}>Izin (I)</option>
                    <option value="S" {{ old('status_absensi') == 'S' ? 'selected' : '' }}>Sakit (S)</option>
                </select>
                <small>Pilih jika karyawan Izin atau Sakit.</small>
            </td>
        </tr>

        {{-- Skenario 3: Alpha --}}
        <tr>
            <td colspan="2" style="padding-top: 10px;">
                <small><strong>3. Untuk Status Alpha (A):</strong> Biarkan Waktu Masuk dan Status di atas tetap kosong.</small>
            </td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:right; padding-top: 20px;">
                <a href="{{ route('attendances.index') }}" class="btn btn-back">Kembali</a>
                <button type="submit">Simpan</button>
            </td>
        </tr>
    </table>
</form>
@endsection