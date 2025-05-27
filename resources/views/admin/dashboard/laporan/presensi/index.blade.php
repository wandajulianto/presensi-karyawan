@extends('admin.layouts.tabler')

@section('content')
  <div class="page-header d-print-none">
    <div class="container-xl">
      <div class="row g-2 align-items-center">
        <div class="col">
          <div class="page-pretitle">Laporan</div>
          <h2 class="page-title">Presensi</h2>
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
            <div>{{ session('success') }}</div>
          </div>
          <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
        </div>
      @endif

      <!-- Filter Form -->
      <div class="row mb-3">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Filter Laporan Presensi</h3>
            </div>
            <div class="card-body">
              <form action="{{ route('dashboard.admin.laporan-presensi') }}" method="GET">
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group mb-3">
                      <label class="form-label">Bulan</label>
                      <select name="bulan" class="form-select">
                        <option value="">Pilih Bulan</option>
                        @for($i = 1; $i <= 12; $i++)
                          <option value="{{ sprintf('%02d', $i) }}" {{ request('bulan') == sprintf('%02d', $i) ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                          </option>
                        @endfor
                      </select>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group mb-3">
                      <label class="form-label">Tahun</label>
                      <select name="tahun" class="form-select">
                        <option value="">Pilih Tahun</option>
                        @for($year = date('Y'); $year >= date('Y') - 5; $year--)
                          <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>
                            {{ $year }}
                          </option>
                        @endfor
                      </select>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group mb-3">
                      <label class="form-label">Nama Karyawan</label>
                      <input type="text" class="form-control" name="nama_karyawan" value="{{ request('nama_karyawan') }}" placeholder="Cari nama karyawan...">
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group mb-3">
                      <label class="form-label">Departemen</label>
                      <select name="departemen" class="form-select">
                        <option value="">Semua Departemen</option>
                        @foreach($departemens as $departemen)
                          <option value="{{ $departemen->kode_departemen }}" {{ request('departemen') == $departemen->kode_departemen ? 'selected' : '' }}>
                            {{ $departemen->nama_departemen }}
                          </option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-search">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                        <path d="M21 21l-6 -6" />
                      </svg>
                      Tampilkan Laporan
                    </button>
                    @if(request()->anyFilled(['bulan', 'tahun', 'nama_karyawan', 'departemen']))
                      <a href="{{ route('dashboard.admin.laporan-presensi.export') }}?{{ http_build_query(request()->query()) }}" class="btn btn-success ms-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-download">
                          <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                          <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2"/>
                          <path d="M7 11l5 5l5 -5"/>
                          <path d="M12 4l0 12"/>
                        </svg>
                        Export Excel
                      </a>
                    @endif
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

      <!-- Summary Cards -->
      @if(isset($summary))
        <div class="row mb-3">
          <div class="col-md-3">
            <div class="card">
              <div class="card-body">
                <div class="d-flex align-items-center">
                  <div class="subheader">Total Hari Kerja</div>
                  <div class="ms-auto lh-1">
                    <span class="badge bg-blue text-white">{{ $summary['total_hari_kerja'] }}</span>
                  </div>
                </div>
                <div class="h1 mb-3">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-calendar">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z" />
                    <path d="M16 3v4" />
                    <path d="M8 3v4" />
                    <path d="M4 11h16" />
                  </svg>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card">
              <div class="card-body">
                <div class="d-flex align-items-center">
                  <div class="subheader">Total Hadir</div>
                  <div class="ms-auto lh-1">
                    <span class="badge bg-success text-white">{{ $summary['total_hadir'] }}</span>
                  </div>
                </div>
                <div class="h1 mb-3">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user-check">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                    <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                    <path d="M15 10l2 2l4 -4" />
                  </svg>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card">
              <div class="card-body">
                <div class="d-flex align-items-center">
                  <div class="subheader">Total Terlambat</div>
                  <div class="ms-auto lh-1">
                    <span class="badge bg-warning text-dark">{{ $summary['total_terlambat'] }}</span>
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
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card">
              <div class="card-body">
                <div class="d-flex align-items-center">
                  <div class="subheader">Persentase Kehadiran</div>
                  <div class="ms-auto lh-1">
                    <span class="badge bg-info text-white">{{ $summary['persentase_kehadiran'] }}%</span>
                  </div>
                </div>
                <div class="h1 mb-3">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-chart-pie">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M10 3.2a9 9 0 1 0 10.8 10.8a1 1 0 0 0 -1 -1h-6.8a2 2 0 0 1 -2 -2v-7a.9 .9 0 0 0 -1 -.8" />
                    <path d="M15 3.5a9 9 0 0 1 5.5 5.5h-4.5a1 1 0 0 1 -1 -1v-4.5" />
                  </svg>
                </div>
              </div>
            </div>
          </div>
        </div>
      @endif

      <!-- Data Table -->
      @if(isset($laporan_presensi))
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Data Laporan Presensi</h3>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-vcenter table-striped">
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>NIK</th>
                        <th>Nama Karyawan</th>
                        <th>Departemen</th>
                        <th>Tanggal</th>
                        <th>Jam Masuk</th>
                        <th>Jam Keluar</th>
                        <th>Status</th>
                        <th>Keterlambatan</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse($laporan_presensi as $index => $presensi)
                        <tr>
                          <td>{{ $index + 1 }}</td>
                          <td>{{ $presensi->nik }}</td>
                          <td>{{ $presensi->karyawan->nama_lengkap }}</td>
                          <td>{{ $presensi->karyawan->departemen->nama_departemen ?? 'Tidak Ada Departemen' }}</td>
                          <td>{{ \Carbon\Carbon::parse($presensi->tanggal_presensi)->format('d/m/Y') }}</td>
                          <td>
                            <span class="badge {{ $presensi->jam_masuk <= '07:00:00' ? 'bg-success' : 'bg-warning' }} text-white">
                              {{ $presensi->jam_masuk }}
                            </span>
                          </td>
                          <td>
                            @if($presensi->jam_keluar)
                              <span class="badge bg-primary text-white">{{ $presensi->jam_keluar }}</span>
                            @else
                              <span class="badge bg-danger text-white">Belum Pulang</span>
                            @endif
                          </td>
                          <td>
                            @if($presensi->jam_masuk <= '07:00:00')
                              <span class="badge bg-success text-white">Tepat Waktu</span>
                            @else
                              <span class="badge bg-danger text-white">Terlambat</span>
                            @endif
                          </td>
                          <td>
                            @if($presensi->jam_masuk > '07:00:00')
                              @php
                                $jamStandar = \Carbon\Carbon::createFromTimeString('07:00:00');
                                $jamMasuk = \Carbon\Carbon::createFromTimeString($presensi->jam_masuk);
                                $keterlambatan = $jamMasuk->diff($jamStandar)->format('%H:%I:%S');
                              @endphp
                              <span class="badge bg-warning text-dark">{{ $keterlambatan }}</span>
                            @else
                              <span class="badge bg-success text-white">-</span>
                            @endif
                          </td>
                          <td>
                            @if(request()->filled('bulan') && request()->filled('tahun'))
                              <a href="{{ route('dashboard.admin.laporan-presensi.cetak', ['nik' => $presensi->nik, 'bulan' => request('bulan'), 'tahun' => request('tahun')]) }}" class="btn btn-primary btn-sm" target="_blank">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-printer">
                                  <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                  <path d="M17 17h-10v4h10v-4z" />
                                  <path d="M17 9h-10v-5h10v5z" />
                                  <path d="M7 17v-5h-2a2 2 0 0 1 -2 -2v-2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v2a2 2 0 0 1 -2 2h-2v5" />
                                </svg>
                                Cetak
                              </a>
                            @endif
                          </td>
                        </tr>
                      @empty
                        <tr>
                          <td colspan="9" class="text-center">
                            <div class="empty">
                              <div class="empty-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-database-x">
                                  <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                  <path d="M4 6c0 1.657 3.582 3 8 3s8 -1.343 8 -3s-3.582 -3 -8 -3s-8 1.343 -8 3" />
                                  <path d="M4 6v6c0 1.657 3.582 3 8 3c1.118 0 2.183 -.086 3.15 -.241" />
                                  <path d="M20 12v-6" />
                                  <path d="M4 12v6c0 1.657 3.582 3 8 3c.157 0 .312 -.002 .466 -.005" />
                                  <path d="M22 22l-5 -5" />
                                  <path d="M17 22l5 -5" />
                                </svg>
                              </div>
                              <p class="empty-title">Tidak ada data presensi</p>
                              <p class="empty-subtitle text-muted">Silakan ubah filter untuk menampilkan data presensi</p>
                            </div>
                          </td>
                        </tr>
                      @endforelse
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      @else
        <!-- Initial State -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body text-center py-5">
                <div class="empty">
                  <div class="empty-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file-search">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                      <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                      <path d="M12 21h-5a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v4.5" />
                      <path d="M16.5 17.5m-2.5 0a2.5 2.5 0 1 0 5 0a2.5 2.5 0 1 0 -5 0" />
                      <path d="M18.5 19.5l2.5 2.5" />
                    </svg>
                  </div>
                  <p class="empty-title">Pilih periode laporan</p>
                  <p class="empty-subtitle text-muted">Gunakan filter di atas untuk menampilkan laporan presensi karyawan</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      @endif
    </div>
  </div>
@endsection

@push('myScript')
<script>
  // Auto-submit form ketika filter berubah (opsional)
  $(document).ready(function() {
    // Jika ingin auto-submit saat pilihan berubah
    // $('.form-select').on('change', function() {
    //   $(this).closest('form').submit();
    // });
  });
</script>
@endpush