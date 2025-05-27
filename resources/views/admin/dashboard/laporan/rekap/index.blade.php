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
              <h3 class="card-title">Filter Rekap Presensi</h3>
            </div>
            <div class="card-body">
              <form action="{{ route('dashboard.admin.laporan-presensi.rekap') }}" method="GET">
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
                      <label class="form-label">Departemen</label>
                      <select name="departemen" class="form-select">
                        <option value="">Semua Departemen</option>
                        @if(isset($departemens))
                          @foreach($departemens as $departemen)
                            <option value="{{ $departemen->kode_departemen }}" {{ request('departemen') == $departemen->kode_departemen ? 'selected' : '' }}>
                              {{ $departemen->nama_departemen }}
                            </option>
                          @endforeach
                        @endif
                      </select>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group mb-3">
                      <label class="form-label">&nbsp;</label>
                      <div>
                        <button type="submit" class="btn btn-primary">
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-search">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                            <path d="M21 21l-6 -6" />
                          </svg>
                          Tampilkan Rekap
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

      @if(isset($rekap_karyawan))
        <!-- Summary Statistics -->
        <div class="row mb-3">
          <div class="col-md-3">
            <div class="card">
              <div class="card-body">
                <div class="d-flex align-items-center">
                  <div class="subheader">Total Karyawan</div>
                  <div class="ms-auto lh-1">
                    <span class="badge bg-blue text-white">{{ $summary['total_karyawan'] }}</span>
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
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card">
              <div class="card-body">
                <div class="d-flex align-items-center">
                  <div class="subheader">Rata-rata Kehadiran</div>
                  <div class="ms-auto lh-1">
                    <span class="badge bg-success text-white">{{ $summary['rata_rata_kehadiran'] }}%</span>
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
          <div class="col-md-3">
            <div class="card">
              <div class="card-body">
                <div class="d-flex align-items-center">
                  <div class="subheader">Total Keterlambatan</div>
                  <div class="ms-auto lh-1">
                    <span class="badge bg-warning text-dark">{{ $summary['total_keterlambatan'] }}</span>
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
                  <div class="subheader">Hari Kerja</div>
                  <div class="ms-auto lh-1">
                    <span class="badge bg-info text-white">{{ $summary['total_hari_kerja'] }}</span>
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
        </div>

        <!-- Rekap Per Karyawan -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Rekap Presensi Per Karyawan</h3>
                <div class="card-actions">
                  <a href="{{ route('dashboard.admin.laporan-presensi.export-rekap') }}?{{ http_build_query(request()->query()) }}" class="btn btn-success btn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-download">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                      <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2"/>
                      <path d="M7 11l5 5l5 -5"/>
                      <path d="M12 4l0 12"/>
                    </svg>
                    Export Excel
                  </a>
                </div>
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
                        <th>Total Hadir</th>
                        <th>Total Terlambat</th>
                        <th>Persentase Kehadiran</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($rekap_karyawan as $index => $karyawan)
                        <tr>
                          <td>{{ $index + 1 }}</td>
                          <td>{{ $karyawan->nik }}</td>
                          <td>{{ $karyawan->nama_lengkap }}</td>
                          <td>{{ $karyawan->nama_departemen ?? 'Tidak Ada Departemen' }}</td>
                          <td>
                            <span class="badge bg-primary text-white">{{ $karyawan->total_hadir }}</span>
                          </td>
                          <td>
                            @if($karyawan->total_terlambat > 0)
                              <span class="badge bg-warning text-dark">{{ $karyawan->total_terlambat }}</span>
                            @else
                              <span class="badge bg-success text-white">0</span>
                            @endif
                          </td>
                          <td>
                            @php
                              $persentase = $summary['total_hari_kerja'] > 0 ? round(($karyawan->total_hadir / $summary['total_hari_kerja']) * 100, 1) : 0;
                            @endphp
                            <span class="badge {{ $persentase >= 90 ? 'bg-success' : ($persentase >= 75 ? 'bg-warning' : 'bg-danger') }} text-white">
                              {{ $persentase }}%
                            </span>
                          </td>
                          <td>
                            @if($persentase >= 90)
                              <span class="badge bg-success text-white">Sangat Baik</span>
                            @elseif($persentase >= 75)
                              <span class="badge bg-warning text-dark">Baik</span>
                            @else
                              <span class="badge bg-danger text-white">Perlu Perhatian</span>
                            @endif
                          </td>
                        </tr>
                      @endforeach
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
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-chart-bar">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                      <path d="M3 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                      <path d="M12 8m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                      <path d="M21 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                      <path d="M3 13l9 -4l9 4" />
                    </svg>
                  </div>
                  <p class="empty-title">Rekap Presensi Per Karyawan</p>
                  <p class="empty-subtitle text-muted">
                    Pilih periode (bulan dan tahun) untuk melihat rekap statistik presensi karyawan
                  </p>
                  <div class="empty-action">
                    <a href="{{ route('dashboard.admin.laporan-presensi') }}" class="btn btn-secondary">
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
      @endif
    </div>
  </div>
@endsection 