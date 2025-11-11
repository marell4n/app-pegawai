@extends ('master')
@section('title', 'Detail Pegawai')
@section('content')
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="{{ asset('css/styledetail.css') }}">

<h1>Detail Pegawai</h1>

<div class="table-detail">
    <table>
        <tr>
            <th>Nama Lengkap</th>
            <td style="font-weight: bold;">{{ $employee->nama_lengkap }}</td>
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
            <th>Departemen</th>
            <td>{{ $employee->department ? $employee->department->nama_department : 'Tidak ada departemen' }}</td>
        </tr>
        <tr>
            <th>Jabatan</th>
            <td>{{ $employee->position ? $employee->position->nama_jabatan : 'Tidak ada posisi' }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>
                <span class="status-{{ strtolower($employee->status) }}">
                    {{ ucfirst($employee->status) }}
                </span>
            </td>
        </tr>
        @php
            $latestSalary = $employee->salaries->first();
        @endphp

        @if($latestSalary)
            <tr>
                <th>Bulan Gaji</th>
                {{-- Format 'YYYY-MM' menjadi 'Nama Bulan Tahun' --}}
                <td>{{ \Carbon\Carbon::parse($latestSalary->bulan . '-01')->locale('id')->isoFormat('MMMM YYYY') }}</td>
            </tr>
            <tr>
                <th>Gaji Pokok</th>
                <td>Rp {{ number_format($latestSalary->gaji_pokok, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Tunjangan (10%)</th>
                <td>Rp {{ number_format($latestSalary->tunjangan, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Potongan (4%)</th>
                <td>Rp {{ number_format($latestSalary->potongan, 0, ',', '.') }}</td>
            </tr>
            <tr style="font-weight: bold;">
                <th>Total Gaji Diterima</th>
                <td style="font-weight: bold;">Rp {{ number_format($latestSalary->total_gaji, 0, ',', '.') }}</td>
            </tr>
        </table>
    @else
        <p style="text-align: left; color: #777; margin-left: 10px;">Data gaji untuk pegawai ini belum dibuat.</p>
    @endif
</div>

<!-- Button container di luar table-detail -->
<div class="button-container">
    <div class="action-buttons">
        <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-edit">Edit</a>
        <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-delete" onclick="return confirm('Yakin ingin menghapus?')">Delete</button>
        </form>
        <a href="{{ route('employees.index') }}" class="btn btn-back">Kembali</a>
    </div>
</div>
@endsection