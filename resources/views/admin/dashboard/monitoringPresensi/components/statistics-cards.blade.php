<!-- Card Statistik Keterlambatan -->
<div class="row mb-3">
  <div class="col-md-4">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div class="subheader">Total Jam Keterlambatan</div>
          <div class="ms-auto lh-1">
            <span class="badge bg-warning text-dark">{{ $totalJamKeterlambatan }}</span>
          </div>
        </div>
        <div class="h1 mb-3">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-clock-exclamation">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M20.986 12.502a9 9 0 1 0 -5.973 7.98"/>
            <path d="M12 7v5l3 3"/>
            <path d="M19 16v3"/>
            <path d="M19 22v.01"/>
          </svg>
        </div>
        <div class="text-muted">
          Dari {{ $jumlahKaryawanTerlambat }} karyawan yang terlambat
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div class="subheader">Karyawan Terlambat</div>
          <div class="ms-auto lh-1">
            <span class="badge bg-danger text-white">{{ $jumlahKaryawanTerlambat }}</span>
          </div>
        </div>
        <div class="h1 mb-3">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-users">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"/>
            <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"/>
            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            <path d="M21 21v-2a4 4 0 0 0 -3 -3.85"/>
          </svg>
        </div>
        <div class="text-muted">
          Pada periode yang dipilih
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div class="subheader">Rata-rata Keterlambatan</div>
          <div class="ms-auto lh-1">
            <span class="badge bg-info text-white">{{ $rataRataJamKeterlambatan }}</span>
          </div>
        </div>
        <div class="h1 mb-3">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-chart-line">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M4 19l16 0"/>
            <path d="M4 15l4 -6l4 2l4 -5l4 4"/>
          </svg>
        </div>
        <div class="text-muted">
          Per karyawan yang terlambat
        </div>
      </div>
    </div>
  </div>
</div> 