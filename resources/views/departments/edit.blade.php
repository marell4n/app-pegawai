@extends('master')
@section('title', 'Edit Departemen')
@section('content')
<link rel="stylesheet" href="{{ asset('css/styleform.css') }}">

<h1>Form Edit Departemen</h1>

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

<form action="{{ route('departments.update', $department->id) }}" method="POST">
    @csrf
    @method('PUT') {{-- Method spoofing untuk update --}}
    <table>
        <tr>
            <td><label for="nama_department">Nama Departemen:</label></td>
            <td><input type="text" id="nama_department" name="nama_department" value="{{ old('nama_department', $department->nama_department) }}" required></td>
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