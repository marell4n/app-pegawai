@extends('master')
@section('title', 'Edit Status Absensi Alpha')
@section('content')
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="{{ asset('css/styleform.css') }}">

<h1>Edit Status Absensi</h1>

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

<form action="{{ route('attendances.update', $attendance->id) }}" method="POST">
    @csrf
    @method('PUT')
    <table>
        <tr>
            <td><label>Karyawan:</label></td>
            {{-- Data ini tidak bisa diubah --}}
            <td><input type="text" value="{{ $attendance->employee ? $attendance->employee->nama_lengkap : '' }}" disabled></td>
        </tr>
         <tr>
            <td><label>Tanggal:</label></td>
            {{-- Data ini tidak bisa diubah --}}
            <td><input type="date" value="{{ $attendance->tanggal }}" disabled></td>
        </tr>
        <tr>
            <td><label>Status Asal:</label></td>
            <td>
                @php
                    $statusAsalText = $attendance->status_absensi;
                    $statusColor = 'black';
                    if ($attendance->status_absensi == 'A') {
                        $statusAsalText = 'A (Alpha)';
                        $statusColor = 'red';
                    } elseif ($attendance->status_absensi == 'HT') {
                        $statusAsalText = 'HT (Hadir Terlambat)';
                        $statusColor = 'orange';
                    }
                @endphp
                <span style="font-weight: bold; color: {{ $statusColor }};"> {{ $statusAsalText }} </span>
            </td>
        </tr>

        {{-- Ini satu-satunya input yang bisa diubah --}}
        <tr>
            <td><label for="status_absensi">Ubah Status Menjadi:</label></td>
            <td>
                <select id="status_absensi" name="status_absensi" required>
                    <option value="">-- Pilih Status Baru --</option>
                    
                    <option value="H" {{ old('status_absensi', $attendance->status_absensi) == 'H' ? 'selected' : '' }}>Hadir (H)</option>
                    <option value="HT" {{ old('status_absensi', $attendance->status_absensi) == 'HT' ? 'selected' : '' }}>Hadir Terlambat (HT)</option>
                    <option value="I" {{ old('status_absensi', $attendance->status_absensi) == 'I' ? 'selected' : '' }}>Izin (I)</option>
                    <option value="S" {{ old('status_absensi', $attendance->status_absensi) == 'S' ? 'selected' : '' }}>Sakit (S)</option>
                    <option value="A" {{ old('status_absensi', $attendance->status_absensi) == 'A' ? 'selected' : '' }}>Alpha (A)</option>
                </select>
                <small>Pilih status baru untuk menggantikan status asal.</small>
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