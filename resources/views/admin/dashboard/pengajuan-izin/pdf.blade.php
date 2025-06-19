@extends('admin.dashboard.laporan.template.pdf-layout')

@section('content')
<!-- Filter Info -->
<table class="info-table">
  <tr>
    <td class="label">Periode</td>
    <td>: {{ $period }}</td>
    <td class="label">Total Pengajuan</td>
    <td>: {{ $pengajuanIzins->count() }} pengajuan</td>
  </tr>
  <tr>
    <td class="label">Filter Status</td>
    <td>: {{ $filterStatus ?? 'Semua Status' }}</td>
    <td class="label">Filter Approval</td>
    <td>: {{ $filterApproval ?? 'Semua Approval' }}</td>
  </tr>
</table>

<!-- Tabel Pengajuan Izin -->
<table class="data-table">
  <thead>
    <tr>
      <th style="width: 5%">No</th>
      <th style="width: 12%">NIK</th>
      <th style="width: 20%">Nama Karyawan</th>
      <th style="width: 15%">Departemen</th>
      <th style="width: 10%">Tanggal</th>
      <th style="width: 8%">Tipe</th>
      <th style="width: 20%">Keterangan</th>
      <th style="width: 10%">Status</th>
    </tr>
  </thead>
  <tbody>
    @foreach($pengajuanIzins as $i => $izin)
      <tr>
        <td>{{ $i + 1 }}</td>
        <td>{{ $izin->nik }}</td>
        <td style="text-align: left;">{{ $izin->karyawan->nama_lengkap }}</td>
        <td style="text-align: left;">{{ $izin->karyawan->departemen->nama_departemen ?? '-' }}</td>
        <td>{{ \Carbon\Carbon::parse($izin->tanggal_izin)->format('d/m/Y') }}</td>
        <td style="text-align: center;">
          @if($izin->status == 'i')
            <span style="background: #e3f2fd; color: #1976d2; padding: 2px 6px; border-radius: 3px; font-size: 9px;">IZIN</span>
          @else
            <span style="background: #fce4ec; color: #c2185b; padding: 2px 6px; border-radius: 3px; font-size: 9px;">SAKIT</span>
          @endif
        </td>
        <td style="text-align: left; font-size: 9px;">{{ Str::limit($izin->keterangan, 50) }}</td>
        <td style="text-align: center; font-size: 9px;">
          @if($izin->status_approved == 0)
            <span style="background: #fff3e0; color: #f57c00; padding: 2px 6px; border-radius: 3px;">PENDING</span>
          @elseif($izin->status_approved == 1)
            <span style="background: #e8f5e8; color: #2e7d32; padding: 2px 6px; border-radius: 3px;">DISETUJUI</span>
          @else
            <span style="background: #ffebee; color: #c62828; padding: 2px 6px; border-radius: 3px;">DITOLAK</span>
          @endif
        </td>
      </tr>
    @endforeach
  </tbody>
</table>

@if($pengajuanIzins->isEmpty())
  <div style="text-align: center; padding: 50px; color: #666; font-style: italic;">
    Tidak ada data pengajuan izin/sakit pada periode yang dipilih
  </div>
@endif

<!-- Summary -->
<div class="summary">
  <h3>Ringkasan Pengajuan</h3>
  <div class="summary-grid">
    <div class="summary-item">
      <div class="summary-value">{{ $summary['total'] ?? 0 }}</div>
      <div class="summary-label">Total Pengajuan</div>
    </div>
    <div class="summary-item">
      <div class="summary-value">{{ $summary['pending'] ?? 0 }}</div>
      <div class="summary-label">Pending</div>
    </div>
    <div class="summary-item">
      <div class="summary-value">{{ $summary['approved'] ?? 0 }}</div>
      <div class="summary-label">Disetujui</div>
    </div>
    <div class="summary-item">
      <div class="summary-value">{{ $summary['rejected'] ?? 0 }}</div>
      <div class="summary-label">Ditolak</div>
    </div>
  </div>
  
  <div style="margin-top: 15px;">
    <div class="summary-grid">
      <div class="summary-item">
        <div class="summary-value">{{ $summary['izin'] ?? 0 }}</div>
        <div class="summary-label">Izin</div>
      </div>
      <div class="summary-item">
        <div class="summary-value">{{ $summary['sakit'] ?? 0 }}</div>
        <div class="summary-label">Sakit</div>
      </div>
      <div class="summary-item">
        <div class="summary-value">
          {{ $summary['total'] > 0 ? round(($summary['approved'] / $summary['total']) * 100, 1) : 0 }}%
        </div>
        <div class="summary-label">Tingkat Persetujuan</div>
      </div>
      <div class="summary-item">
        <div class="summary-value">
          {{ $summary['total'] > 0 ? round(($summary['pending'] / $summary['total']) * 100, 1) : 0 }}%
        </div>
        <div class="summary-label">Menunggu Approval</div>
      </div>
    </div>
  </div>
</div>
@endsection 