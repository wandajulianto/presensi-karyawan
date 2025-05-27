@extends('admin.layouts.tabler')

@section('content')
  <!-- Include Leaflet CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <!-- SweetAlert2 CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  
  <style>
    #map {
      height: 400px;
      border-radius: 6px;
      border: 2px solid #e2e8f0;
      cursor: crosshair;
    }

    .coordinate-input {
      font-family: 'Courier New', monospace;
      background: #f8fafc;
      border: 1px solid #e2e8f0;
    }

    .map-instructions {
      background: linear-gradient(45deg, #3b82f6, #1d4ed8);
      color: white;
      padding: 1rem;
      border-radius: 6px;
      margin-bottom: 1rem;
    }

    .form-section {
      background: #f8fafc;
      padding: 1.5rem;
      border-radius: 8px;
      margin-bottom: 1.5rem;
      border: 1px solid #e2e8f0;
    }

    .section-title {
      color: #1e293b;
      font-weight: 600;
      margin-bottom: 1rem;
      padding-bottom: 0.5rem;
      border-bottom: 2px solid #e2e8f0;
    }

    .custom-office-marker {
      border: none !important;
      background: transparent !important;
    }

    .status-badge {
      display: inline-flex;
      align-items: center;
      padding: 0.5rem 1rem;
      border-radius: 1rem;
      font-size: 0.875rem;
      font-weight: 600;
    }

    .status-active {
      background: linear-gradient(45deg, #22c55e, #16a34a);
      color: white;
    }

    .status-inactive {
      background: linear-gradient(45deg, #f59e0b, #d97706);
      color: white;
    }
  </style>

  <div class="page-header d-print-none">
    <div class="container-xl">
      <div class="row g-2 align-items-center">
        <div class="col">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ route('admin.kantor.index') }}">Lokasi Kantor</a></li>
              <li class="breadcrumb-item active" aria-current="page">Edit {{ $kantor->nama_kantor }}</li>
            </ol>
          </nav>
          <h2 class="page-title">Edit Kantor: {{ $kantor->nama_kantor }}</h2>
        </div>
        <div class="col-auto ms-auto d-print-none">
          <div class="btn-list">
            <span class="status-badge {{ $kantor->is_active ? 'status-active' : 'status-inactive' }}">
              @if($kantor->is_active)
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                  <path d="M5 12l5 5l10 -10"></path>
                </svg>
                Kantor Aktif
              @else
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                  <path d="M18 6l-12 12"></path>
                  <path d="M6 6l12 12"></path>
                </svg>
                Kantor Nonaktif
              @endif
            </span>
            <a href="{{ route('admin.kantor.index') }}" class="btn btn-outline-secondary">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <path d="M9 11l-4 4l4 4m-4 -4h11a4 4 0 0 0 0 -8h-1"></path>
              </svg>
              Kembali
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="page-body">
    <div class="container-xl">
      <form action="{{ route('admin.kantor.update', $kantor) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="row">
          <div class="col-md-8">
            <!-- Informasi Dasar Kantor -->
            <div class="form-section">
              <h3 class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                  <path d="M3 21l18 0"></path>
                  <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16"></path>
                  <path d="M9 9h1v1h-1z"></path>
                  <path d="M14 9h1v1h-1z"></path>
                  <path d="M9 13h1v1h-1z"></path>
                  <path d="M14 13h1v1h-1z"></path>
                  <path d="M9 17h1v1h-1z"></path>
                  <path d="M14 17h1v1h-1z"></path>
                </svg>
                Informasi Dasar Kantor
              </h3>
              
              <div class="row">
                <div class="col-md-8">
                  <div class="mb-3">
                    <label class="form-label required">Nama Kantor</label>
                    <input type="text" class="form-control @error('nama_kantor') is-invalid @enderror" 
                           name="nama_kantor" value="{{ old('nama_kantor', $kantor->nama_kantor) }}" 
                           placeholder="Contoh: Kantor Pusat Jakarta">
                    @error('nama_kantor')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="mb-3">
                    <label class="form-label required">Kode Kantor</label>
                    <input type="text" class="form-control @error('kode_kantor') is-invalid @enderror" 
                           name="kode_kantor" value="{{ old('kode_kantor', $kantor->kode_kantor) }}" 
                           placeholder="Contoh: KP001" maxlength="10" style="text-transform: uppercase;">
                    @error('kode_kantor')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label required">Alamat Lengkap</label>
                <textarea class="form-control @error('alamat') is-invalid @enderror" 
                          name="alamat" rows="3" placeholder="Masukkan alamat lengkap kantor">{{ old('alamat', $kantor->alamat) }}</textarea>
                @error('alamat')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                          name="deskripsi" rows="2" placeholder="Deskripsi tambahan (opsional)">{{ old('deskripsi', $kantor->deskripsi) }}</textarea>
                @error('deskripsi')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <!-- Lokasi & Koordinat -->
            <div class="form-section">
              <h3 class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                  <path d="M21 3l-6.5 18a.55 .55 0 0 1 -1 0l-3.5 -7l-7 -3.5a.55 .55 0 0 1 0 -1l18 -6.5"></path>
                </svg>
                Lokasi & Koordinat
              </h3>

              <div class="map-instructions">
                <div class="d-flex align-items-center">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg me-3" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M12 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0"></path>
                    <path d="M12 1l0 6"></path>
                    <path d="M12 17l0 6"></path>
                    <path d="M1 12l6 0"></path>
                    <path d="M17 12l6 0"></path>
                  </svg>
                  <div>
                    <div class="fw-bold">Cara Menentukan Lokasi:</div>
                    <div class="small">Klik pada peta di bawah atau drag marker untuk mengubah koordinat kantor.</div>
                  </div>
                </div>
              </div>

              <div id="map"></div>

              <div class="row mt-3">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label required">Latitude</label>
                    <input type="number" step="any" class="form-control coordinate-input @error('latitude') is-invalid @enderror" 
                           name="latitude" id="latitude" value="{{ old('latitude', $kantor->latitude) }}" 
                           placeholder="-7.333174936756437" readonly>
                    @error('latitude')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label required">Longitude</label>
                    <input type="number" step="any" class="form-control coordinate-input @error('longitude') is-invalid @enderror" 
                           name="longitude" id="longitude" value="{{ old('longitude', $kantor->longitude) }}" 
                           placeholder="108.2197967875599" readonly>
                    @error('longitude')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label required">Radius Presensi (meter)</label>
                <div class="input-group">
                  <input type="number" class="form-control @error('radius_meter') is-invalid @enderror" 
                         name="radius_meter" id="radius_meter" value="{{ old('radius_meter', $kantor->radius_meter) }}" 
                         min="1" max="1000" placeholder="20">
                  <span class="input-group-text">meter</span>
                </div>
                @error('radius_meter')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-hint">Radius area dimana karyawan dapat melakukan presensi (1-1000 meter)</div>
              </div>
            </div>

            <!-- Jam Kerja & Timezone -->
            <div class="form-section">
              <h3 class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                  <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path>
                  <path d="M12 7l0 5l3 3"></path>
                </svg>
                Jam Kerja & Timezone
              </h3>

              <div class="row">
                <div class="col-md-4">
                  <div class="mb-3">
                    <label class="form-label required">Timezone</label>
                    <select class="form-select @error('timezone') is-invalid @enderror" name="timezone">
                      <option value="Asia/Jakarta" {{ old('timezone', $kantor->timezone) == 'Asia/Jakarta' ? 'selected' : '' }}>Asia/Jakarta (WIB)</option>
                      <option value="Asia/Makassar" {{ old('timezone', $kantor->timezone) == 'Asia/Makassar' ? 'selected' : '' }}>Asia/Makassar (WITA)</option>
                      <option value="Asia/Jayapura" {{ old('timezone', $kantor->timezone) == 'Asia/Jayapura' ? 'selected' : '' }}>Asia/Jayapura (WIT)</option>
                    </select>
                    @error('timezone')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="mb-3">
                    <label class="form-label required">Jam Masuk</label>
                    <input type="time" class="form-control @error('jam_masuk') is-invalid @enderror" 
                           name="jam_masuk" value="{{ old('jam_masuk', $kantor->jam_masuk ? \Carbon\Carbon::parse($kantor->jam_masuk)->format('H:i') : '07:00') }}">
                    @error('jam_masuk')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="mb-3">
                    <label class="form-label required">Jam Keluar</label>
                    <input type="time" class="form-control @error('jam_keluar') is-invalid @enderror" 
                           name="jam_keluar" value="{{ old('jam_keluar', $kantor->jam_keluar ? \Carbon\Carbon::parse($kantor->jam_keluar)->format('H:i') : '17:00') }}">
                    @error('jam_keluar')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <!-- Status & Action -->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Status & Aksi</h3>
              </div>
              <div class="card-body">
                <div class="mb-3">
                  <label class="form-check">
                    <input type="checkbox" class="form-check-input" name="is_active" value="1" 
                           {{ old('is_active', $kantor->is_active) ? 'checked' : '' }}>
                    <span class="form-check-label">
                      <strong>Aktifkan kantor ini</strong>
                    </span>
                  </label>
                  <div class="form-hint">Jika diaktifkan, kantor ini akan menjadi lokasi utama untuk presensi</div>
                </div>

                <div class="d-grid gap-2">
                  <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                      <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2"></path>
                      <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                      <path d="M14 4l0 4l-6 0l0 -4"></path>
                    </svg>
                    Update Kantor
                  </button>
                  
                  <a href="{{ route('admin.kantor.index') }}" class="btn btn-outline-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                      <path d="M18 6l-12 12"></path>
                      <path d="M6 6l12 12"></path>
                    </svg>
                    Batal
                  </a>
                </div>
              </div>
            </div>

            <!-- Info Kantor -->
            <div class="card mt-3">
              <div class="card-header">
                <h3 class="card-title">Info Kantor</h3>
              </div>
              <div class="card-body">
                <div class="small">
                  <div class="row">
                    <div class="col-sm-6">
                      <div class="text-muted">Dibuat</div>
                      <div>{{ $kantor->created_at->format('d M Y H:i') }}</div>
                    </div>
                    <div class="col-sm-6">
                      <div class="text-muted">Diupdate</div>
                      <div>{{ $kantor->updated_at->format('d M Y H:i') }}</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Info Koordinat -->
            <div class="card mt-3">
              <div class="card-header">
                <h3 class="card-title">Info Koordinat</h3>
              </div>
              <div class="card-body">
                <div class="small text-muted">
                  <div class="mb-2">
                    <strong>Latitude:</strong> Koordinat utara-selatan<br>
                    <em>Range: -90 sampai 90</em>
                  </div>
                  <div class="mb-2">
                    <strong>Longitude:</strong> Koordinat timur-barat<br>
                    <em>Range: -180 sampai 180</em>
                  </div>
                  <div>
                    <strong>Radius:</strong> Area presensi dalam meter<br>
                    <em>Karyawan harus berada dalam radius ini</em>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>

@endsection

@push('myScript')
<!-- Include Leaflet JavaScript -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<!-- SweetAlert2 JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  let map;
  let marker;
  let radiusCircle;

  // Initialize map
  function initMap() {
    const defaultLat = {{ old('latitude', $kantor->latitude) }};
    const defaultLng = {{ old('longitude', $kantor->longitude) }};
    const defaultRadius = {{ old('radius_meter', $kantor->radius_meter) }};

    map = L.map('map').setView([defaultLat, defaultLng], 16);

    // Add tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Custom icon
    const officeIcon = L.divIcon({
      html: '<div style="background-color: #059669; color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; font-size: 14px; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3);">🏢</div>',
      iconSize: [30, 30],
      className: 'custom-office-marker'
    });

    // Add initial marker
    marker = L.marker([defaultLat, defaultLng], {icon: officeIcon, draggable: true}).addTo(map);
    
    // Add initial radius circle
    radiusCircle = L.circle([defaultLat, defaultLng], {
      color: '#ef4444',
      fillColor: '#ef4444',
      fillOpacity: 0.2,
      radius: defaultRadius,
      weight: 2
    }).addTo(map);

    // Update coordinates when marker is dragged
    marker.on('dragend', function(e) {
      const position = e.target.getLatLng();
      updateCoordinates(position.lat, position.lng);
      updateRadiusCircle();
    });

    // Update coordinates when map is clicked
    map.on('click', function(e) {
      const lat = e.latlng.lat;
      const lng = e.latlng.lng;
      
      marker.setLatLng([lat, lng]);
      updateCoordinates(lat, lng);
      updateRadiusCircle();
    });
  }

  // Update coordinate inputs
  function updateCoordinates(lat, lng) {
    document.getElementById('latitude').value = lat;
    document.getElementById('longitude').value = lng;
  }

  // Update radius circle
  function updateRadiusCircle() {
    const lat = parseFloat(document.getElementById('latitude').value);
    const lng = parseFloat(document.getElementById('longitude').value);
    const radius = parseInt(document.getElementById('radius_meter').value) || 20;

    if (radiusCircle) {
      map.removeLayer(radiusCircle);
    }

    radiusCircle = L.circle([lat, lng], {
      color: '#ef4444',
      fillColor: '#ef4444',
      fillOpacity: 0.2,
      radius: radius,
      weight: 2
    }).addTo(map);
  }

  // Handle radius input change
  document.getElementById('radius_meter').addEventListener('input', function() {
    updateRadiusCircle();
  });

  // Initialize map when page loads
  document.addEventListener('DOMContentLoaded', function() {
    initMap();
  });

  // Auto uppercase kode kantor
  document.querySelector('input[name="kode_kantor"]').addEventListener('input', function() {
    this.value = this.value.toUpperCase();
  });

  // SweetAlert for validation errors
  @if($errors->any())
    Swal.fire({
      icon: 'error',
      title: 'Terdapat Kesalahan!',
      html: `
        <ul style="text-align: left; margin: 0; padding-left: 20px;">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      `,
      confirmButtonText: 'OK'
    });
  @endif
</script>
@endpush 