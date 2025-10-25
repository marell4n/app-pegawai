@extends('master')
@section('title', 'Edit Status Absensi Alpha')
@section('content')
<h1>Edit Status Absensi Alpha (A)</h1>

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
            {{-- Data ini tidak bisa diubah --}}
            <td><input type="text" value="A (Alpha)" disabled style="color: red; font-weight: bold;"></td>
        </tr>

        {{-- Ini satu-satunya input yang bisa diubah --}}
        <tr>
            <td><label for="status_absensi">Ubah Status Menjadi:</label></td>
            <td>
                <select id="status_absensi" name="status_absensi" required>
                    <option value="">-- Pilih Status Baru --</option>
                    <option value="I" {{ old('status_absensi') == 'I' ? 'selected' : '' }}>Izin (I)</option>
                    <option value="S" {{ old('status_absensi') == 'S' ? 'selected' : '' }}>Sakit (S)</option>
                </select>
                <small style="display: block; color: #777;">Hanya dapat diubah menjadi Izin atau Sakit.</small>
            </td>
        </tr>

        <tr>
            <td colspan="2" style="text-align:right; padding-top: 20px;">
                <button type="submit">Update Status</button>
                <a href="{{ route('attendances.index') }}" class="btn btn-back" style="margin-left: 10px; text-decoration: none; display: inline-block; background: #EA9CAF; color: white; padding: 15px 30px; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(213, 105, 137, 0.4); text-transform: uppercase; letter-spacing: 1px;">Kembali</a>
            </td>
        </tr>
    </table>
</form>
@endsection