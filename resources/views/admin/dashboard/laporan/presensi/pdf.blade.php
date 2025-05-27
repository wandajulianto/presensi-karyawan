<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Laporan Presensi Karyawan</title>
  <style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #333; }
    .header {
      display: flex;
      align-items: center;
      margin-bottom: 12px;
      border-bottom: 2px solid #0d6efd;
      padding-bottom: 6px;
    }
    .header-logo img { width: 70px; height: auto; }
    .header-text { margin-left: 10px; }
    .title { font-size: 16px; font-weight: bold; text-transform: uppercase; color: #0d6efd; }
    .subtitle { font-size: 12px; margin-top: 2px; }

    .employee-info {
      border: 1px solid #ddd;
      padding: 8px;
      margin-top: 10px;
      border-radius: 4px;
    }
    .employee-info td { padding: 4px 6px; vertical-align: top; font-size: 10.5px; }

    table.data-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
    }
    table.data-table th {
      background-color: #0d6efd;
      color: #fff;
      font-weight: bold;
      padding: 6px 4px;
      border: 1px solid #0b5ed7;
      font-size: 10.5px;
    }
    table.data-table td {
      border: 1px solid #dee2e6;
      padding: 5px 4px;
      font-size: 10px;
    }
    table.data-table tbody tr:nth-child(even) { background-color: #f2f4f7; }

    .summary {
      margin-top: 12px;
      font-size: 11px;
    }
    .summary p { margin: 2px 0; }
  </style>
</head>
<body>
  <!-- Header / Kop Surat -->
  <div class="header">
    <div class="header-logo">
      <img src="{{ public_path('logo.png') }}" alt="Logo">
    </div>
    <div class="header-text">
      <div class="title">Laporan Presensi Karyawan</div>
      <div class="subtitle" style="font-weight:bold;">{{ strtoupper($instansi) }}</div>
      <div class="subtitle">Periode: {{ strtoupper($bulan) }} {{ $tahun }}</div>
    </div>
  </div>

  <!-- Biodata Karyawan -->
  <table class="employee-info" style="width:100%; border:none;">
    <tr>
      <td style="border: none; width: 20%" class="text-left"><strong>NIK</strong></td>
      <td style="border: none; width: 30%" class="text-left">: {{ $karyawan->nik }}</td>
      <td style="border: none; width: 20%" class="text-left"><strong>Nama</strong></td>
      <td style="border: none; width: 30%" class="text-left">: {{ $karyawan->nama_lengkap }}</td>
    </tr>
    <tr>
      <td style="border: none;" class="text-left"><strong>Departemen</strong></td>
      <td style="border: none;" class="text-left">: {{ $karyawan->nama_departemen ?? '-' }}</td>
      <td style="border: none;" class="text-left"><strong>Jabatan</strong></td>
      <td style="border: none;" class="text-left">: {{ $karyawan->jabatan ?? '-' }}</td>
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
    <p><strong>Total Hadir:</strong> {{ $totalHadir }} hari</p>
    <p><strong>Total Terlambat:</strong> {{ $totalTerlambat }} hari</p>
  </div>

  <!-- Tanda Tangan Pimpinan -->
  <div style="margin-top: 40px; width: 100%; display: flex; justify-content: flex-end;">
    <div style="text-align: center; width: 220px;">
      <p style="margin-bottom: 50px;">Ciamis, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
      <p style="margin: 0; font-weight: bold;">Pimpinan</p>
      <br><br><br>
      <p style="margin: 0; text-decoration: underline;">_____________________</p>
      <p style="margin: 0;">(Nama Pimpinan)</p>
    </div>
  </div>
</body>
</html> 