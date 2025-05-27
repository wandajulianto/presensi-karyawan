@extends('admin.layouts.tabler')

@section('content')
  <!-- Include Leaflet CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  
  <style>
    .custom-marker {
      border: none !important;
      background: transparent !important;
    }
    
    #map-lokasi {
      border: 2px solid #e2e8f0;
      border-radius: 6px;
    }
    
    .leaflet-popup-content-wrapper {
      border-radius: 6px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .modal-dialog {
      max-width: 600px;
    }
    
    .table-borderless td {
      padding: 0.15rem 0.3rem;
      border: none;
      font-size: 0.85rem;
    }
    
    .card-body.p-2 {
      background: #f8fafc;
      border-radius: 4px;
      padding: 0.5rem !important;
    }
    
    .modal-body {
      max-height: 70vh;
      overflow-y: auto;
    }
    
    .fs-6 {
      font-size: 0.9rem !important;
    }
  </style>

  <div class="page-header d-print-none">
    <div class="container-xl">
      <div class="row g-2 align-items-center">
        <div class="col">
          <div class="page-pretitle">Overview</div>
          <h2 class="page-title">Monitoring Presensi</h2>
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
            <div>{{ session('error') }}</div>
          </div>
          <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
        </div>
      @endif

      @include('admin.dashboard.monitoringPresensi.components.statistics-cards')

      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              @include('admin.dashboard.monitoringPresensi.components.filter-form')

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
                      <th>Aksi</th>
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
                              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-hourglass-high">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M6.5 7h11" />
                                <path d="M6 20v-2a6 6 0 1 1 12 0v2a1 1 0 0 1 -1 1h-10a1 1 0 0 1 -1 -1z" />
                                <path d="M6 4v2a6 6 0 1 0 12 0v-2a1 1 0 0 0 -1 -1h-10a1 1 0 0 0 -1 1z" />
                              </svg>
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
                        <td>
                          <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#lokasiModal" 
                                  onclick="showLokasiModal('{{ $presensi->karyawan->nama_lengkap }}', '{{ $presensi->lokasi_masuk }}', '{{ $presensi->lokasi_keluar ?? '' }}', '{{ $presensi->tanggal_presensi }}', '{{ $presensi->jam_masuk }}', '{{ $presensi->jam_keluar ?? 'Belum Absen' }}')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-map-2">
                              <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                              <path d="M12 18.5l-3 -1.5l-6 3v-13l6 -3l6 3l6 -3v7.5" />
                              <path d="M9 4v13" />
                              <path d="M15 7v5.5" />
                              <path d="M21.121 20.121a3 3 0 1 0 -4.242 0c.418 .419 1.125 1.045 2.121 1.879c1.051 -.89 1.759 -1.516 2.121 -1.879z" />
                              <path d="M19 18v.01" />
                            </svg>
                            Lokasi
                          </button>
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

@include('admin.dashboard.monitoringPresensi.components.modal-lokasi')

@endsection

@push('myScript')
<!-- Include Leaflet JavaScript -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="{{ asset('assets/js/monitoring-presensi.js') }}"></script>

<script>
// Set data kantor untuk JavaScript
@if($kantor)
  setKantorData(@json($kantor));
@endif
</script>
@endpush