@extends('master')
@section('title', 'Tambah Performance Review')
@section('content')
<link rel="stylesheet" href="{{ asset('css/styleform.css') }}">

<h1>Form Tambah Performance Review</h1>

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

<form action="{{ route('performance-reviews.store') }}" method="POST">
    @csrf
    <table>
        <tr>
            <td><label for="karyawan_id">Nama Karyawan:</label></td>
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
            <td><label for="tanggal_review">Tanggal Review:</label></td>
            <td>
                <input type="date" id="tanggal_review" name="tanggal_review" value="{{ old('tanggal_review', date('Y-m-d')) }}" required>
            </td>
        </tr>
        <tr>
            <td><label>Skor (1 - 10):</label></td>
            <td>
                <div class="radio-group">
                    @for ($i = 1; $i <= 10; $i++)
                        <div class="radio-option">
                            <input type="radio" id="skor_{{ $i }}" name="skor" value="{{ $i }}" {{ old('skor') == $i ? 'checked' : '' }} required>
                            <label for="skor_{{ $i }}">{{ $i }}</label>
                        </div>
                    @endfor
                </div>
                <small>Pilih nilai bulat skala 1 sampai 10.</small>
            </td>
        </tr>
        <tr>
            <td><label for="catatan_feedback">Catatan Feedback:</label></td>
            <td>
                <textarea id="catatan_feedback" name="catatan_feedback" rows="5" required placeholder="Masukkan detail feedback kinerja...">{{ old('catatan_feedback') }}</textarea>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align:right;">
                <a href="{{ route('performance-reviews.index') }}" class="btn btn-back">Kembali</a>
                <button type="submit">Simpan</button>
            </td>
        </tr>
    </table>
</form>
@endsection