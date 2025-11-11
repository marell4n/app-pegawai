@extends('master')
@section('title', 'Dashboard HR')
@section('content')
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="{{ asset('css/styledashboard.css') }}">

{{-- === FILTER SECTION === --}}
<form action="{{ route('dashboard') }}" method="GET" class="filter-container">
    <div class="filter-group">
        <label for="month" class="filter-label">Periode:</label>
        <select name="month" id="month" class="filter-select">
            @for($m = 1; $m <= 12; $m++)
                <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                </option>
            @endfor
        </select>
        <select name="year" id="year" class="filter-select">
            @for($y = date('Y'); $y >= date('Y') - 3; $y--)
                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endfor
        </select>
    </div>
    <button type="submit" class="btn-filter">Tampilkan Data</button>
</form>

{{-- === SUMMARY CARDS SECTION === --}}
<h3 class="section-title">
    Ringkasan Total: {{ \Carbon\Carbon::create()->month($month)->translatedFormat('F') }} {{ $year }}
</h3>
<div class="summary-cards">
    <div class="card card-hadir">
        <div class="card-title">Total Hadir</div>
        <div class="card-number">{{ $totalSummary['Hadir'] }}</div>
    </div>
    <div class="card card-sakit">
        <div class="card-title">Total Sakit</div>
        <div class="card-number">{{ $totalSummary['Sakit'] }}</div>
    </div>
    <div class="card card-izin">
        <div class="card-title">Total Izin</div>
        <div class="card-number">{{ $totalSummary['Izin'] }}</div>
    </div>
    <div class="card card-alpha">
        <div class="card-title">Total Alpha</div>
        <div class="card-number">{{ $totalSummary['Alpha'] }}</div>
    </div>
</div>

{{-- === RECAP SECTION === --}}
<h3 class="section-title section-title-spacing">
    Rekap Absensi Bulanan
</h3>

<div class="matrix-container">
    <div class="table-responsive">
        <table class="matrix-table">
            <thead>
                <tr>
                    <th class="col-sticky">Nama Pegawai</th>
                    @for($d=1; $d<=$daysInMonth; $d++)
                        <th>{{ $d }}</th>
                    @endfor
                    <th class="col-summary" title="Hadir">H</th>
                    <th class="col-summary" title="Sakit">S</th>
                    <th class="col-summary" title="Izin">I</th>
                    <th class="col-summary" title="Alpha">A</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $emp)
                    <tr>
                        <td class="col-sticky">{{ $emp->nama_lengkap }}</td>
                        @for($d=1; $d<=$daysInMonth; $d++)
                            @php
                                $status = $attendanceMatrix[$emp->id][$d] ?? '-';
                                
                                // Gunakan kode singkatan sesuai database ('H', 'HT', dst)
                                $cls = match($status) {
                                    'H' => 'st-H',
                                    'HT' => 'st-HT',
                                    'S' => 'st-S',
                                    'I' => 'st-I',
                                    'A' => 'st-A',
                                    default => ''
                                };
                                $txt = match($status) {
                                    'H' => 'H',
                                    'HT' => 'HT',
                                    'S' => 'S',
                                    'I' => 'I',
                                    'A' => 'A',
                                    default => '-'
                                };
                            @endphp
                            <td class="status-cell {{ $cls }}">{{ $txt }}</td>
                        @endfor
                        <td class="col-summary sum-H">{{ $emp->hadir_count }}</td>
                        <td class="col-summary sum-S">{{ $emp->sakit_count }}</td>
                        <td class="col-summary sum-I">{{ $emp->izin_count }}</td>
                        <td class="col-summary sum-A">{{ $emp->alpha_count }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $daysInMonth + 5 }}" style="padding: 20px; color: #999;">
                            Tidak ada data pegawai aktif.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="matrix-legend">
    <div class="legend-item"><span class="legend-color st-H"></span> Hadir (H)</div>
    <div class="legend-item"><span class="legend-color st-HT"></span> Hadir Terlambat (HT)</div>
    <div class="legend-item"><span class="legend-color st-S"></span> Sakit (S)</div>
    <div class="legend-item"><span class="legend-color st-I"></span> Izin (I)</div>
    <div class="legend-item"><span class="legend-color st-A"></span> Alpha (A)</div>
</div>

@endsection