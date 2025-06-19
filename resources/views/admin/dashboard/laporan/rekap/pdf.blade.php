@extends('admin.dashboard.laporan.template.pdf-layout')

@section('content')
<!-- Filter Info -->
<table class="info-table">
  <tr>
    <td class="label">Periode</td>
    <td>: {{ $period }}</td>
    <td class="label">Total Karyawan</td>
    <td>: {{ $summary['total_karyawan'] ?? 0 }} orang</td>
  </tr>
  <tr>
    <td class="label">Departemen</td>
    <td>: {{ $departemenFilter ?? 'Semua Departemen' }}</td>
    <td class="label">Total Keterlambatan</td>
    <td>: {{ $summary['total_keterlambatan'] ?? 0 }} kali</td>
  </tr>
</table>

<!-- Tabel Rekap -->
<table class="data-table">
  <thead>
    <tr>
      <th style="width: 5%">No</th>
      <th style="width: 15%">NIK</th>
      <th style="width: 25%">Nama Karyawan</th>
      <th style="width: 20%">Departemen</th>
      <th style="width: 10%">Total Hadir</th>
      <th style="width: 10%">Terlambat</th>
      <th style="width: 10%">Kehadiran (%)</th>
      <th style="width: 5%">Status</th>
    </tr>
  </thead>
  <tbody>
    @foreach($rekap_karyawan as $i => $karyawan)
      @php
        $persentase = $summary['total_hari_kerja'] > 0 ? round(($karyawan->total_hadir / $summary['total_hari_kerja']) * 100, 1) : 0;
        
        if ($persentase >= 90) {
            $status = 'Sangat Baik';
            $statusClass = 'text-success';
        } elseif ($persentase >= 75) {
            $status = 'Baik';
            $statusClass = 'text-warning';
        } else {
            $status = 'Perlu Perhatian';
            $statusClass = 'text-danger';
        }
      @endphp
      <tr>
        <td>{{ $i + 1 }}</td>
        <td>{{ $karyawan->nik }}</td>
        <td style="text-align: left;">{{ $karyawan->nama_lengkap }}</td>
        <td style="text-align: left;">{{ $karyawan->nama_departemen ?? 'Tidak Ada Departemen' }}</td>
        <td>{{ $karyawan->total_hadir }}</td>
        <td>{{ $karyawan->total_terlambat }}</td>
        <td>{{ $persentase }}%</td>
        <td style="font-size: 9px;">{{ $status }}</td>
      </tr>
    @endforeach
  </tbody>
</table>

<!-- Summary -->
<div class="summary">
  <h3>Ringkasan Laporan</h3>
  <div class="summary-grid">
    <div class="summary-item">
      <div class="summary-value">{{ $summary['total_karyawan'] ?? 0 }}</div>
      <div class="summary-label">Total Karyawan</div>
    </div>
    <div class="summary-item">
      <div class="summary-value">{{ $summary['total_hari_kerja'] ?? 0 }}</div>
      <div class="summary-label">Hari Kerja</div>
    </div>
    <div class="summary-item">
      <div class="summary-value">{{ $summary['total_keterlambatan'] ?? 0 }}</div>
      <div class="summary-label">Total Keterlambatan</div>
    </div>
    <div class="summary-item">
      <div class="summary-value">{{ $summary['rata_rata_kehadiran'] ?? 0 }}%</div>
      <div class="summary-label">Rata-rata Kehadiran</div>
    </div>
  </div>
</div>
@endsection 