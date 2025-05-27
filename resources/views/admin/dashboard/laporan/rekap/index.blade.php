@extends('admin.layouts.tabler')

@section('content')
  <div class="page-header d-print-none">
    <div class="container-xl">
      <div class="row g-2 align-items-center">
        <div class="col">
          <div class="page-pretitle">Laporan</div>
          <h2 class="page-title">Rekap Presensi</h2>
        </div>
      </div>
    </div>
  </div>

  <div class="page-body">
    <div class="container-xl">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-body text-center py-5">
              <div class="empty">
                <div class="empty-icon">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-chart-bar">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M3 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                    <path d="M12 8m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                    <path d="M21 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                    <path d="M3 13l9 -4l9 4" />
                  </svg>
                </div>
                <p class="empty-title">Rekap Presensi</p>
                <p class="empty-subtitle text-muted">
                  Halaman ini akan menampilkan rekap statistik presensi karyawan per periode.
                  <br>
                  Fitur ini masih dalam tahap pengembangan.
                </p>
                <div class="empty-action">
                  <a href="{{ route('dashboard.admin.laporan-presensi') }}" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-left">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                      <path d="M5 12l14 0" />
                      <path d="M5 12l6 6" />
                      <path d="M5 12l6 -6" />
                    </svg>
                    Kembali ke Laporan Presensi
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection 