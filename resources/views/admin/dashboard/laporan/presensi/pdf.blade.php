@extends('admin.dashboard.laporan.template.pdf-layout')

@section('content')

<!-- Biodata Karyawan -->
<table class="info-table">
  <tr>
    <td class="label">NIK</td>
    <td>: {{ $karyawan->nik }}</td>
    <td class="label">Nama Lengkap</td>
    <td>: {{ $karyawan->nama_lengkap }}</td>
  </tr>
  <tr>
    <td class="label">Departemen</td>
    <td>: {{ $karyawan->nama_departemen ?? '-' }}</td>
    <td class="label">Jabatan</td>
    <td>: {{ $karyawan->jabatan ?? '-' }}</td>
  </tr>
</table>

<!-- Tabel Presensi -->
<table class="data-table">
  <thead>
    <tr>
      <th>No</th>
      <th>Tanggal</th>
      <th>Jam Masuk</th>
      <th>Jam Keluar</th>
      <th>Status</th>
      <th>Keterlambatan</th>
    </tr>
  </thead>
  <tbody>
    @foreach($presensi as $i => $p)
      @php
        $status = $p->jam_masuk <= '07:00:00' ? 'Tepat Waktu' : 'Terlambat';
        $keterlambatan = '-';
        if ($status == 'Terlambat') {
          $keterlambatan = \Carbon\Carbon::createFromTimeString($p->jam_masuk)->diff(\Carbon\Carbon::createFromTimeString('07:00:00'))->format('%H:%I:%S');
        }
      @endphp
      <tr>
        <td>{{ $i + 1 }}</td>
        <td>{{ \Carbon\Carbon::parse($p->tanggal_presensi)->format('d/m/Y') }}</td>
        <td>{{ $p->jam_masuk }}</td>
        <td>{{ $p->jam_keluar ?? '-' }}</td>
        <td>{{ $status }}</td>
        <td>{{ $keterlambatan }}</td>
      </tr>
    @endforeach
  </tbody>
</table>

<!-- Summary -->
<div class="summary">
  <h3>Ringkasan Presensi</h3>
  <div class="summary-grid">
    <div class="summary-item">
      <div class="summary-value">{{ $totalHadir }}</div>
      <div class="summary-label">Total Hadir</div>
    </div>
    <div class="summary-item">
      <div class="summary-value">{{ $totalTerlambat }}</div>
      <div class="summary-label">Total Terlambat</div>
    </div>
    <div class="summary-item">
      <div class="summary-value">{{ $totalHadir - $totalTerlambat }}</div>
      <div class="summary-label">Tepat Waktu</div>
    </div>
    <div class="summary-item">
      <div class="summary-value">{{ $totalHadir > 0 ? round((($totalHadir - $totalTerlambat) / $totalHadir) * 100, 1) : 0 }}%</div>
      <div class="summary-label">Kedisiplinan</div>
    </div>
  </div>
</div>
@endsection 