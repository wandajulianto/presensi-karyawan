@extends('admin.layouts.tabler')
@section('content')
  <div class="page-header d-print-none">
    <div class="container-xl">
      <div class="row g-2 align-items-center">
        <div class="col">
          <!-- Page pre-title -->
          <div class="page-pretitle">
            Overview
          </div>
          <h2 class="page-title">
            Monitoring Presensi
          </h2>
        </div>
      </div>
    </div>
  </div>
  <div class="page-body">
    <div class="container-xl">
      @if(session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
          <div class="d-flex">
            <div>
              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-circle-check alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path>
                <path d="M9 12l2 2l4 -4"></path>
              </svg>
            </div>
            <div>
              {{ session('success') }}
            </div>
          </div>
          <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
        </div>
      @endif

      @if(session('error'))
        <div class="alert alert-danger alert-dismissible" role="alert">
          <div class="d-flex">
            <div>
              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-alert-circle alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0"></path>
                <path d="M12 8v4"></path>
                <path d="M12 16h.01"></path>
              </svg>
            </div>
            <div>
              {{ session('error') }}
            </div>
          </div>
          <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
        </div>
      @endif

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

      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <form action="" method="GET">
                <div class="row">
                  <div class="col-md-4">
                    <div class="input-icon mb-3">
                      <span class="input-icon-addon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-calendar"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z" /><path d="M16 3v4" /><path d="M8 3v4" /><path d="M4 11h16" /><path d="M11 15h1" /><path d="M12 15v3" /></svg>
                      </span>
                      <input type="text" class="form-control flatpickr" id="tanggal" name="tanggal" value="{{ request('tanggal') }}" placeholder="Pilih Tanggal Presensi" autocomplete="off">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="input-icon mb-3">
                      <span class="input-icon-addon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                      </span>
                      <input type="text" class="form-control" name="nama_lengkap" value="{{ request('nama_lengkap') }}" placeholder="Cari Nama Karyawan">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="input-icon mb-3">
                      <select name="departemen" class="form-select">
                        <option value="">Semua Departemen</option>
                        @foreach ($departemens as $departemen)
                          <option value="{{ $departemen->kode_departemen }}" {{ request('departemen') == $departemen->kode_departemen ? 'selected' : '' }}>
                            {{ $departemen->nama_departemen }}
                          </option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-search"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
                      Cari
                    </button>
                    <a href="{{ route('dashboard.admin.monitoring-presensi.export-keterlambatan', request()->query()) }}" class="btn btn-success ms-2">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-download">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2"/>
                        <path d="M7 11l5 5l5 -5"/>
                        <path d="M12 4l0 12"/>
                      </svg>
                      Export Laporan Keterlambatan
                    </a>
                  </div>
                </div>
              </form>

              <div class="table-responsive mt-3">
                <table class="table table-vcenter table-striped">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Tanggal</th>
                      <th>NIK</th>
                      <th>Nama</th>
                      <th>Departemen</th>
                      <th>Jam Masuk</th>
                      <th>Foto Masuk</th>
                      <th>Jam Pulang</th>
                      <th>Foto Pulang</th>
                      <th>Jam Keterlambatan</th>
                      <th>Keterangan</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($presensis as $presensi)
                      <tr>
                        <td>{{ $presensis->firstItem() + $loop->index }}</td>
                        <td>{{ $presensi->tanggal_presensi }}</td>
                        <td>{{ $presensi->karyawan->nik }}</td>
                        <td>{{ $presensi->karyawan->nama_lengkap }}</td>
                        <td>{{ $presensi->karyawan->departemen->nama_departemen ?? 'Tidak Ada Departemen' }}</td>
                        <td>{{ $presensi->jam_masuk }}</td>
                        <td>
                          @if($presensi->foto_masuk)
                            <img src="{{ Storage::url('uploads/absention/'.$presensi->foto_masuk) }}" alt="Foto Masuk" class="avatar w-10 h-10">
                          @else
                            -
                          @endif
                        </td>
                        <td>{!! $presensi->jam_keluar ?? '<span class="badge bg-danger">Belum Absen</span>' !!}</td>
                        <td>
                          @if($presensi->foto_keluar)
                            <img src="{{ Storage::url('uploads/absention/'.$presensi->foto_keluar) }}" alt="Foto Pulang" class="avatar w-10 h-10">
                          @else
                            <span class="d-flex justify-content-center">
                              <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-hourglass-high"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6.5 7h11" /><path d="M6 20v-2a6 6 0 1 1 12 0v2a1 1 0 0 1 -1 1h-10a1 1 0 0 1 -1 -1z" /><path d="M6 4v2a6 6 0 1 0 12 0v-2a1 1 0 0 0 -1 -1h-10a1 1 0 0 0 -1 1z" /></svg>
                            </span>
                          @endif
                        </td>
                        <td>
                          @if($presensi->jam_masuk > '07:00:00')
                            <span class="badge bg-warning text-dark">{{ $presensi->jam_keterlambatan }}</span>
                          @else
                            <span class="badge bg-success text-white">Tepat Waktu</span>
                          @endif
                        </td>
                        <td>
                          @if($presensi->jam_masuk > '07:00:00')
                            <span class="badge bg-danger text-white">Terlambat</span>
                          @else
                            <span class="badge bg-success text-white">Tepat Waktu</span>
                          @endif
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
                <div class="d-flex justify-content-center mt-3">
                  {{ $presensis->appends(request()->query())->links('vendor.pagination.bootstrap-5') }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('myScript')
<script>
  $(document).ready(function() {
    // Inisialisasi flatpickr
    flatpickr(".flatpickr", {
      dateFormat: "Y-m-d",
      allowInput: true,
      locale: "id"
    });
  });
</script>
@endpush