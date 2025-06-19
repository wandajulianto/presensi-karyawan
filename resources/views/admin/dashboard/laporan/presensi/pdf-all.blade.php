@extends('admin.dashboard.laporan.template.pdf-layout')

@section('content')

<!-- Informasi Filter -->
@if(!empty($filter_info))
<div class="filter-info">
    <p><strong>Filter yang diterapkan:</strong> {{ $filter_info }}</p>
</div>
@endif

<!-- Tabel Presensi -->
<table class="data-table">
    <thead>
        <tr>
            <th width="5%">No</th>
            <th width="15%">NIK</th>
            <th width="25%">Nama Karyawan</th>
            <th width="15%">Departemen</th>
            <th width="12%">Tanggal</th>
            <th width="10%">Jam Masuk</th>
            <th width="10%">Jam Keluar</th>
            <th width="8%">Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($laporan_presensi as $i => $presensi)
            @php
                $status = $presensi->jam_masuk <= '07:00:00' ? 'Tepat Waktu' : 'Terlambat';
            @endphp
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $presensi->nik }}</td>
                <td>{{ $presensi->karyawan->nama_lengkap ?? '-' }}</td>
                <td>{{ $presensi->karyawan->departemen->nama_departemen ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($presensi->tanggal_presensi)->format('d/m/Y') }}</td>
                <td>{{ $presensi->jam_masuk }}</td>
                <td>{{ $presensi->jam_keluar ?? '-' }}</td>
                <td style="color: {{ $status == 'Tepat Waktu' ? '#28a745' : '#dc3545' }}">{{ $status }}</td>
            </tr>
        @endforeach
        
        @if($laporan_presensi->isEmpty())
            <tr>
                <td colspan="8" style="text-align: center; font-style: italic; color: #666;">
                    Tidak ada data presensi ditemukan
                </td>
            </tr>
        @endif
    </tbody>
</table>

<!-- Summary -->
@if(!$laporan_presensi->isEmpty())
<div class="summary">
    <h3>Ringkasan Laporan</h3>
    <div class="summary-grid">
        <div class="summary-item">
            <div class="summary-value">{{ $laporan_presensi->count() }}</div>
            <div class="summary-label">Total Record</div>
        </div>
        <div class="summary-item">
            <div class="summary-value">{{ $laporan_presensi->where('jam_masuk', '>', '07:00:00')->count() }}</div>
            <div class="summary-label">Total Terlambat</div>
        </div>
        <div class="summary-item">
            <div class="summary-value">{{ $laporan_presensi->where('jam_masuk', '<=', '07:00:00')->count() }}</div>
            <div class="summary-label">Tepat Waktu</div>
        </div>
        <div class="summary-item">
            <div class="summary-value">{{ $laporan_presensi->count() > 0 ? round(($laporan_presensi->where('jam_masuk', '<=', '07:00:00')->count() / $laporan_presensi->count()) * 100, 1) : 0 }}%</div>
            <div class="summary-label">Kedisiplinan</div>
        </div>
    </div>
</div>
@endif

<style>
.filter-info {
    background: #f8f9fa;
    padding: 10px;
    border-left: 4px solid #007bff;
    margin-bottom: 20px;
    border-radius: 4px;
}

.summary {
    margin-top: 30px;
    page-break-inside: avoid;
}

.summary h3 {
    color: #333;
    margin-bottom: 15px;
    font-size: 16px;
}

.summary-grid {
    display: flex;
    justify-content: space-around;
    flex-wrap: wrap;
    gap: 15px;
}

.summary-item {
    text-align: center;
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background: #f8f9fa;
    min-width: 120px;
}

.summary-value {
    font-size: 24px;
    font-weight: bold;
    color: #007bff;
    margin-bottom: 5px;
}

.summary-label {
    font-size: 12px;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
</style>

@endsection 