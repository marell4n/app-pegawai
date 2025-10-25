<!DOCTYPE html>
<html>
<head>
    <title>Form Input Pegawai</title>
    <link rel="stylesheet" href="{{ asset('css/styleform.css') }}">
</head>
<body>
    <h1 class="mb-4">Form Pegawai</h1>

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
                <td><input type="text" id="nama_lengkap" name="nama_lengkap"></td>
            </tr>
            <tr>
                <td><label for="email">Email:</label></td>
                <td><input type="email" id="email" name="email"></td>
            </tr>
            <tr>
                <td><label for="nomor_telepon">Nomor Telepon:</label></td>
                <td><input type="text" id="nomor_telepon" name="nomor_telepon"></td>
            </tr>
            <tr>
                <td><label for="tanggal_lahir">Tanggal Lahir:</label></td>
                <td><input type="date" id="tanggal_lahir" name="tanggal_lahir"></td>
            </tr>
            <tr>
                <td><label for="alamat">Alamat:</label></td>
                <td><textarea id="alamat" name="alamat"></textarea></td>
            </tr>
            <tr>
                <td><label for="tanggal_masuk">Tanggal Masuk:</label></td>
                <td><input type="date" id="tanggal_masuk" name="tanggal_masuk"></td>
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
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align:right;">
                    <button type="submit">Simpan</button>
                </td>
            </tr>
        </table>
    </form>
</body>
</html>