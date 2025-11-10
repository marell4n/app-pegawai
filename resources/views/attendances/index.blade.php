@extends('master')
@section('title', 'Daftar Absensi')
@section('content')

<link rel="stylesheet" href="{{ asset('css/styleindex.css') }}">

<h1>Daftar Absensi</h1>

{{-- Pesan Sukses/Error --}}
@if (session('success'))
    <div style="color: green; margin-bottom: 15px; text-align: center;">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div style="color: red; margin-bottom: 15px; text-align: center;">{{ session('error') }}</div>
@endif

{{-- === FILTER BAR === --}}
<div class="search-container">
    <form action="{{ route('attendances.index') }}" method="GET" class="search-form" style="max-width: 600px;"> 
        {{-- Input 1: Cari Nama --}}
        <input type="text" name="search_name" placeholder="Cari nama pegawai..." value="{{ request('search_name') }}" style="flex: 2;">
        
        {{-- Input 2: Dropdown Tanggal --}}
        <select name="search_date" style="flex: 1; padding: 10px 15px; border: 2px solid #EA9CAF; border-radius: 50px; outline: none; cursor: pointer;">
            <option value="">-- Semua Tanggal --</option>
            @foreach($availableDates as $date)
                <option value="{{ $date }}" {{ request('search_date') == $date ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::parse($date)->format('d M Y') }}
                </option>
            @endforeach
        </select>

        <button type="submit">Filter</button>
        
        {{-- Tombol Reset jika ada filter yang aktif --}}
        @if(request('search_name') || request('search_date'))
            <a href="{{ route('attendances.index') }}" style="display: flex; align-items: center; color: #D56989; text-decoration: none; font-weight: bold; margin-left: 10px;">
                ✕ Reset
            </a>
        @endif
    </form>
</div>

{{-- Logika untuk Mengelompokkan per Tanggal --}}
@php
    $currentDate = null;
@endphp

@forelse($attendances as $attendance)
    {{-- Jika tanggal berubah, buat header tabel baru --}}
    @if ($attendance->tanggal != $currentDate)
        @php
            $currentDate = $attendance->tanggal;
            // Format tanggal (misal: Sabtu, 25 Oktober 2025)
            $formattedDate = \Carbon\Carbon::parse($currentDate)->locale('id')->isoFormat('dddd, D MMMM YYYY');
        @endphp

        {{-- Tutup tabel sebelumnya (jika bukan iterasi pertama) --}}
        @if (!$loop->first)
                </tbody>
            </table>
        </div>
        @endif

        {{-- Header Tanggal Baru --}}
        <h2 style="margin-top: 30px; margin-bottom: 10px; border-bottom: 2px solid #EA9CAF; padding-bottom: 5px;">
            {{ $formattedDate }}
        </h2>

        {{-- Tabel Baru --}}
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Nama Karyawan</th>
                        <th>Waktu Masuk</th>
                        <th>Waktu Keluar</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
    @endif

    {{-- Baris Data Absensi --}}
    <tr>
        <td>{{ $attendance->employee ? $attendance->employee->nama_lengkap : 'Karyawan Dihapus' }}</td>
        <td>{{ $attendance->waktu_masuk ?? '-' }}</td>
        <td>{{ $attendance->waktu_keluar ?? '-' }}</td>

        <td>
            <span style="font-weight: bold;">
                {{ $attendance->status_absensi }}
            </span>
        </td>
        
        <td>
            <div class="action-buttons">
                {{-- Tombol Edit hanya muncul jika status 'A' dan 'HT' --}}
                @if (in_array($attendance->status_absensi, ['A', 'HT']))
                    <a href="{{ route('attendances.edit', $attendance->id) }}" class="btn btn-edit">Edit</a>
                @else
                    {{-- Beri placeholder agar rapi --}}
                    <span style="color: #999; font-size: 12px; padding: 5px 10px;">(Fixed)</span>
                @endif

                <form action="{{ route('attendances.destroy', $attendance->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus data absensi ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-delete">Delete</button>
                </form>
            </div>
        </td>
    </tr>

    {{-- Tutup tabel terakhir setelah loop selesai --}}
    @if ($loop->last)
            </tbody>
        </table>
    </div>
    @endif

@empty
    <div style="text-align: center; margin-top: 20px;">
        <p>Tidak ada data absensi.</p>
    </div>
@endforelse


{{-- Keterangan Status Absensi (Tanpa Warna) --}}
<div class="keterangan" style="margin-top: 30px; padding: 15px; border: 1px solid #ddd; border-radius: 8px; background: tranparant; font-size: small">
    <h3>Keterangan Status Absensi</h3>
    <ul style="list-style-type: none; padding-left: 0;">
        <li><strong>H</strong> : Hadir (Masuk antara jam 07:00 - 09:00)</li>
        <li><strong>HT</strong> : Hadir Terlambat (Masuk setelah jam 09:00)</li>
        <li><strong>I</strong> : Izin</li>
        <li><strong>S</strong> : Sakit</li>
        <li><strong>A</strong> : Alpha / Tidak Hadir (Tanpa Keterangan)</li>
    </ul>
    <p><strong>Catatan:</strong> Jam Keluar otomatis diisi pukul 16:00. Hanya status Alpha (A) atau Hadir Terlambat (HT) yang dapat diubah menjadi Izin (I) atau Sakit (S).</p>
</div>

{{-- Tombol Tambah --}}
<div class="button-container" style="margin-bottom: 20px;">
    <div class="add-button">
        <a href="{{ route('attendances.create') }}" class="btn-add">Tambah Absensi</a>
    </div>
</div>

@endsection