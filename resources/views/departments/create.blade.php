@extends('master')
@section('title', 'Tambah Departemen Baru')
@section('content')
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="{{ asset('css/styleform.css') }}">

<h1>Form Tambah Departemen</h1>

{{-- Tampilkan error validasi jika ada --}}
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

<form action="{{ route('departments.store') }}" method="POST">
    @csrf
    <table>
        <tr>
            <td><label for="nama_department">Nama Departemen:</label></td>
            <td><input type="text" id="nama_department" name="nama_department" value="{{ old('nama_department') }}" required></td>
        </tr>
        <tr>
            <td colspan="2" style="text-align:right;">
                <a href="{{ route('departments.index') }}" class="btn btn-back">Kembali</a>
                <button type="submit">Simpan</button>
            </td>
        </tr>
    </table>
</form>
@endsection