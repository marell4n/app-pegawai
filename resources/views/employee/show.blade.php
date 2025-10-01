<!DOCTYPE html>
<html>
<head>
    <title>Detail Pegawai</title>
    <link rel="stylesheet" href="{{ asset('css/styledetail.css') }}">
</head>
<body>
    <h1>Detail Pegawai</h1>

    <div class="table-detail">
        <table>
            <tr>
                <th>Nama Lengkap</th>
                <td>{{ $employee->nama_lengkap }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{ $employee->email }}</td>
            </tr>
            <tr>
                <th>Nomor Telepon</th>
                <td>{{ $employee->nomor_telepon }}</td>
            </tr>
            <tr>
                <th>Tanggal Lahir</th>
                <td>{{ $employee->tanggal_lahir }}</td>
            </tr>
            <tr>
                <th>Alamat</th>
                <td>{{ $employee->alamat }}</td>
            </tr>
            <tr>
                <th>Tanggal Masuk</th>
                <td>{{ $employee->tanggal_masuk }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>{{ $employee->status }}</td>
            </tr>
        </table>
    </div>

    <!-- Button container di luar table-detail -->
    <div class="button-container">
        <div class="action-buttons">
            <a href="{{ route('employees.index') }}" class="btn btn-back">Kembali</a>
        </div>
    </div>
</body>
</html>