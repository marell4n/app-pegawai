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
                <button type="submit">Update</button>
                 <a href="{{ route('departments.index') }}" class="btn btn-back" style="margin-left: 10px; text-decoration: none; display: inline-block; background: #EA9CAF; color: white; padding: 15px 30px; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(213, 105, 137, 0.4); text-transform: uppercase; letter-spacing: 1px;">Kembali</a>
            </td>
        </tr>
    </table>
</form>
@endsection