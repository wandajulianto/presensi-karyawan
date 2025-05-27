@extends('admin.layouts.tabler')

@section('content')
  <!-- Include Leaflet CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <!-- SweetAlert2 CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  
  <style>
    .status-aktif {
      background: #22c55e;
      color: white;
      padding: 0.25rem 0.75rem;
      border-radius: 0.375rem;
      font-size: 0.75rem;
      font-weight: 600;
      display: inline-flex;
      align-items: center;
    }
    
    .status-nonaktif {
      background: #ef4444;
      color: white;
      padding: 0.25rem 0.75rem;
      border-radius: 0.375rem;
      font-size: 0.75rem;
      font-weight: 600;
      display: inline-flex;
      align-items: center;
    }

    .btn-group-action {
      white-space: nowrap;
    }

    .btn-group-action .btn {
      margin-right: 0.25rem;
    }

    .coordinate-display {
      font-family: 'Courier New', monospace;
      font-size: 0.85rem;
      background: #f1f5f9;
      padding: 0.25rem 0.5rem;
      border-radius: 0.25rem;
      color: #64748b;
    }

    .card-kantor {
      border: 1px solid #e2e8f0;
      transition: all 0.2s;
    }

    .card-kantor:hover {
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
  </style>

  <div class="page-header d-print-none">
    <div class="container-xl">
      <div class="row g-2 align-items-center">
        <div class="col">
          <div class="page-pretitle">Konfigurasi</div>
          <h2 class="page-title">Lokasi Kantor</h2>
        </div>
        <div class="col-auto ms-auto d-print-none">
          <div class="btn-list">
            <a href="{{ route('admin.kantor.create') }}" class="btn btn-primary d-none d-sm-inline-block">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
              </svg>
              Tambah Kantor
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="page-body">
    <div class="container-xl">
      <div class="row">
        <div class="col-12">
          <div class="card card-kantor">
            <div class="card-header">
              <h3 class="card-title">Daftar Kantor</h3>
              <div class="card-actions">
                <div class="text-muted">
                  Total: {{ $kantors->count() }} kantor
                </div>
              </div>
            </div>
            <div class="card-body">
              @if($kantors->count() > 0)
                <div class="table-responsive">
                  <table class="table table-vcenter table-striped">
                    <thead>
                      <tr>
                        <th>Status</th>
                        <th>Nama Kantor</th>
                        <th>Kode</th>
                        <th>Alamat</th>
                        <th>Koordinat</th>
                        <th>Radius</th>
                        <th>Jam Kerja</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($kantors as $kantor)
                        <tr>
                          <td>
                            @if($kantor->is_active)
                              <span class="status-aktif">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                  <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                  <path d="M5 12l5 5l10 -10"></path>
                                </svg>
                                Aktif
                              </span>
                            @else
                              <span class="status-nonaktif">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                  <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                  <path d="M18 6l-12 12"></path>
                                  <path d="M6 6l12 12"></path>
                                </svg>
                                Nonaktif
                              </span>
                            @endif
                          </td>
                          <td>
                            <div class="fw-bold">{{ $kantor->nama_kantor }}</div>
                            @if($kantor->deskripsi)
                              <div class="text-muted small">{{ Str::limit($kantor->deskripsi, 50) }}</div>
                            @endif
                          </td>
                          <td>
                            <span class="badge bg-blue-lt">{{ $kantor->kode_kantor }}</span>
                          </td>
                          <td>
                            <div class="text-wrap" style="max-width: 200px;">
                              {{ Str::limit($kantor->alamat, 60) }}
                            </div>
                          </td>
                          <td>
                            <div class="coordinate-display">
                              {{ $kantor->latitude }}, {{ $kantor->longitude }}
                            </div>
                          </td>
                          <td>
                            <span class="badge bg-orange-lt">{{ $kantor->radius_meter }}m</span>
                          </td>
                          <td>
                            <div class="small">
                              <div>🌅 {{ $kantor->jam_masuk }}</div>
                              <div>🌆 {{ $kantor->jam_keluar }}</div>
                            </div>
                          </td>
                          <td>
                            <div class="btn-group-action">
                              @if(!$kantor->is_active)
                                <button type="button" class="btn btn-success btn-sm" title="Aktifkan Kantor" 
                                        onclick="confirmSetActive('{{ $kantor->id }}', '{{ $kantor->nama_kantor }}')">
                                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M5 12l5 5l10 -10"></path>
                                  </svg>
                                </button>
                              @endif
                              
                              <a href="{{ route('admin.kantor.edit', $kantor) }}" class="btn btn-primary btn-sm" title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                  <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                  <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"></path>
                                  <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"></path>
                                  <path d="M16 5l3 3"></path>
                                </svg>
                              </a>

                              @if(!$kantor->is_active)
                                <button type="button" class="btn btn-danger btn-sm" title="Hapus" 
                                        onclick="confirmDelete('{{ $kantor->id }}', '{{ $kantor->nama_kantor }}')">
                                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M4 7l16 0"></path>
                                    <path d="M10 11l0 6"></path>
                                    <path d="M14 11l0 6"></path>
                                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path>
                                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path>
                                  </svg>
                                </button>
                              @endif
                            </div>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @else
                <div class="text-center py-5">
                  <div class="empty">
                    <div class="empty-img">
                      <img src="{{ asset('assets/img/undraw_location_search_bqps.svg') }}" height="128" alt="">
                    </div>
                    <p class="empty-title">Belum ada data kantor</p>
                    <p class="empty-subtitle text-muted">
                      Silakan tambah kantor pertama untuk memulai konfigurasi lokasi presensi
                    </p>
                    <div class="empty-action">
                      <a href="{{ route('admin.kantor.create') }}" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                          <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                          <line x1="12" y1="5" x2="12" y2="19"></line>
                          <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        Tambah Kantor Pertama
                      </a>
                    </div>
                  </div>
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>

      @if($kantors->where('is_active', true)->count() > 0)
        <div class="row mt-4">
          <div class="col-12">
            <div class="card card-kantor">
              <div class="card-header">
                <h3 class="card-title">Peta Kantor Aktif</h3>
              </div>
              <div class="card-body">
                <div id="map" style="height: 400px; border-radius: 6px;"></div>
              </div>
            </div>
          </div>
        </div>
      @endif
    </div>
  </div>

  <!-- Hidden Forms for SweetAlert Actions -->
  <form id="form-set-active" method="POST" style="display: none;">
    @csrf
    @method('PATCH')
  </form>

  <form id="form-delete" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
  </form>

@endsection

@push('myScript')
<!-- Include Leaflet JavaScript -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<!-- SweetAlert2 JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  // SweetAlert for Success Messages
  @if(session('success'))
    Swal.fire({
      icon: 'success',
      title: 'Berhasil!',
      text: '{{ session('success') }}',
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true
    });
  @endif

  // SweetAlert for Error Messages
  @if(session('error'))
    Swal.fire({
      icon: 'error',
      title: 'Gagal!',
      text: '{{ session('error') }}',
      showConfirmButton: true
    });
  @endif

  // Confirm Set Active
  function confirmSetActive(kantorId, namaKantor) {
    Swal.fire({
      title: 'Aktifkan Kantor?',
      text: `Yakin ingin mengaktifkan kantor "${namaKantor}"?`,
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#22c55e',
      cancelButtonColor: '#ef4444',
      confirmButtonText: 'Ya, Aktifkan!',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        const form = document.getElementById('form-set-active');
        form.action = `{{ route('admin.kantor.index') }}/${kantorId}/set-active`;
        form.submit();
      }
    });
  }

  // Confirm Delete
  function confirmDelete(kantorId, namaKantor) {
    Swal.fire({
      title: 'Hapus Kantor?',
      text: `Yakin ingin menghapus kantor "${namaKantor}"? Data yang dihapus tidak dapat dikembalikan!`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#ef4444',
      cancelButtonColor: '#6b7280',
      confirmButtonText: 'Ya, Hapus!',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        const form = document.getElementById('form-delete');
        form.action = `{{ route('admin.kantor.index') }}/${kantorId}`;
        form.submit();
      }
    });
  }
</script>

@if($kantors->where('is_active', true)->count() > 0)
<script>
  // Initialize map
  @php
    $kantorAktif = $kantors->where('is_active', true)->first();
  @endphp
  
  const map = L.map('map').setView([{{ $kantorAktif->latitude }}, {{ $kantorAktif->longitude }}], 16);

  // Add tile layer
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
  }).addTo(map);

  // Add marker for active office
  const officeIcon = L.divIcon({
    html: '<div style="background-color: #059669; color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; font-size: 14px; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3);">🏢</div>',
    iconSize: [30, 30],
    className: 'custom-office-marker'
  });

  L.marker([{{ $kantorAktif->latitude }}, {{ $kantorAktif->longitude }}], {icon: officeIcon})
    .addTo(map)
    .bindPopup(`
      <div class="text-center">
        <h6>{{ $kantorAktif->nama_kantor }}</h6>
        <p class="mb-1">{{ $kantorAktif->alamat }}</p>
        <small class="text-muted">Radius: {{ $kantorAktif->radius_meter }}m</small>
      </div>
    `)
    .openPopup();

  // Add circle for office radius
  L.circle([{{ $kantorAktif->latitude }}, {{ $kantorAktif->longitude }}], {
    color: '#ef4444',
    fillColor: '#ef4444',
    fillOpacity: 0.2,
    radius: {{ $kantorAktif->radius_meter }},
    weight: 2
  }).addTo(map);
</script>
@endif
@endpush 