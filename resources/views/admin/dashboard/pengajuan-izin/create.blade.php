@extends('admin.layouts.tabler')

@section('content')
  <!-- SweetAlert2 CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <!-- Flatpickr CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  
  <style>
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

    .form-check-custom {
      padding: 1rem;
      border: 2px solid #e2e8f0;
      border-radius: 8px;
      transition: all 0.2s;
      cursor: pointer;
    }

    .form-check-custom:hover {
      border-color: #3b82f6;
      background-color: #f1f5f9;
    }

    .form-check-custom.selected {
      border-color: #3b82f6;
      background-color: #eff6ff;
    }

    .type-icon {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 1rem;
    }

    .type-izin-icon {
      background: linear-gradient(45deg, #3b82f6, #1d4ed8);
      color: white;
    }

    .type-sakit-icon {
      background: linear-gradient(45deg, #ec4899, #be185d);
      color: white;
    }
  </style>

  <div class="page-header d-print-none">
    <div class="container-xl">
      <div class="row g-2 align-items-center">
        <div class="col">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ route('admin.pengajuan-izin.index') }}">Data Izin / Sakit</a></li>
              <li class="breadcrumb-item active" aria-current="page">Tambah Pengajuan</li>
            </ol>
          </nav>
          <h2 class="page-title">Tambah Pengajuan Izin / Sakit</h2>
        </div>
        <div class="col-auto ms-auto d-print-none">
          <div class="btn-list">
            <a href="{{ route('admin.pengajuan-izin.index') }}" class="btn btn-outline-secondary">
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
      <form action="{{ route('admin.pengajuan-izin.store') }}" method="POST">
        @csrf
        
        <div class="row">
          <div class="col-md-8">
            <!-- Informasi Karyawan -->
            <div class="form-section">
              <h3 class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                  <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"></path>
                  <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                </svg>
                Informasi Karyawan
              </h3>
              
              <div class="mb-3">
                <label class="form-label required">Pilih Karyawan</label>
                <select class="form-select @error('nik') is-invalid @enderror" name="nik" id="select-karyawan">
                  <option value="">-- Pilih Karyawan --</option>
                  @foreach($karyawans as $karyawan)
                    <option value="{{ $karyawan->nik }}" {{ old('nik') == $karyawan->nik ? 'selected' : '' }}>
                      {{ $karyawan->nik }} - {{ $karyawan->nama_lengkap }}
                      @if($karyawan->departemen)
                        ({{ $karyawan->departemen->nama_departemen }})
                      @endif
                    </option>
                  @endforeach
                </select>
                @error('nik')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <!-- Tipe Izin -->
            <div class="form-section">
              <h3 class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                  <path d="M9 11h6l-6 -6v6a6 6 0 0 0 6 6"></path>
                </svg>
                Tipe Pengajuan
              </h3>
              
              <div class="row">
                <div class="col-md-6">
                  <div class="form-check-custom {{ old('status') === 'i' ? 'selected' : '' }}" data-value="i">
                    <div class="d-flex align-items-center">
                      <div class="type-icon type-izin-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                          <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                          <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path>
                          <path d="M12 8l0 4l3 3"></path>
                        </svg>
                      </div>
                      <div>
                        <div class="fw-bold">Izin</div>
                        <div class="text-muted small">Untuk keperluan pribadi, keluarga, atau kepentingan lainnya</div>
                      </div>
                    </div>
                    <input type="radio" class="form-check-input d-none" name="status" value="i" {{ old('status') === 'i' ? 'checked' : '' }}>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-check-custom {{ old('status') === 's' ? 'selected' : '' }}" data-value="s">
                    <div class="d-flex align-items-center">
                      <div class="type-icon type-sakit-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                          <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                          <path d="M19.5 12.572l-7.5 7.428l-7.5 -7.428m0 0a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572"></path>
                        </svg>
                      </div>
                      <div>
                        <div class="fw-bold">Sakit</div>
                        <div class="text-muted small">Untuk kondisi kesehatan yang mengharuskan tidak masuk kerja</div>
                      </div>
                    </div>
                    <input type="radio" class="form-check-input d-none" name="status" value="s" {{ old('status') === 's' ? 'checked' : '' }}>
                  </div>
                </div>
              </div>
              @error('status')
                <div class="text-danger mt-2">{{ $message }}</div>
              @enderror
            </div>

            <!-- Detail Izin -->
            <div class="form-section">
              <h3 class="section-title">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                  <path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z"></path>
                  <path d="M16 3l0 4"></path>
                  <path d="M8 3l0 4"></path>
                  <path d="M4 11l16 0"></path>
                  <path d="M8 15h2v2h-2z"></path>
                </svg>
                Detail Pengajuan
              </h3>
              
              <div class="mb-3">
                <label class="form-label required">Tanggal Izin</label>
                <input type="text" class="form-control @error('tanggal_izin') is-invalid @enderror" 
                       name="tanggal_izin" id="tanggal_izin" value="{{ old('tanggal_izin') }}" 
                       placeholder="Pilih tanggal izin">
                @error('tanggal_izin')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-hint">Tanggal mulai berlakunya izin/sakit</div>
              </div>

              <div class="mb-3">
                <label class="form-label required">Keterangan</label>
                <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                          name="keterangan" rows="4" placeholder="Masukkan alasan atau keterangan izin/sakit...">{{ old('keterangan') }}</textarea>
                @error('keterangan')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-hint">Jelaskan alasan mengajukan izin/sakit (maksimal 500 karakter)</div>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <!-- Informasi Pengajuan -->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Informasi Pengajuan</h3>
              </div>
              <div class="card-body">
                <div class="mb-3">
                  <div class="text-muted">Status Awal</div>
                  <div class="fw-bold text-warning">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                      <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path>
                      <path d="M12 8l0 4l3 3"></path>
                    </svg>
                    Pending (Menunggu Approval)
                  </div>
                </div>

                <div class="mb-3">
                  <div class="text-muted">Tanggal Pengajuan</div>
                  <div class="fw-bold">{{ now()->format('d M Y H:i') }}</div>
                </div>

                <div class="d-grid gap-2">
                  <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                      <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2"></path>
                      <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                      <path d="M14 4l0 4l-6 0l0 -4"></path>
                    </svg>
                    Simpan Pengajuan
                  </button>
                  
                  <a href="{{ route('admin.pengajuan-izin.index') }}" class="btn btn-outline-secondary">
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

            <!-- Tips -->
            <div class="card mt-3">
              <div class="card-header">
                <h3 class="card-title">Tips Pengajuan</h3>
              </div>
              <div class="card-body">
                <div class="small text-muted">
                  <div class="mb-2">
                    <strong>📝 Keterangan:</strong><br>
                    Berikan alasan yang jelas dan detail untuk memudahkan proses approval
                  </div>
                  <div class="mb-2">
                    <strong>📅 Tanggal:</strong><br>
                    Pilih tanggal yang tepat sesuai kebutuhan izin/sakit
                  </div>
                  <div>
                    <strong>⏱️ Approval:</strong><br>
                    Pengajuan akan diproses oleh admin dan statusnya dapat dilihat di halaman utama
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
<!-- SweetAlert2 JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Flatpickr JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>

<script>
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

  // Initialize Flatpickr for date picker
  flatpickr("#tanggal_izin", {
    locale: "id",
    dateFormat: "Y-m-d",
    minDate: "today",
    allowInput: false,
    clickOpens: true
  });

  // Handle custom form check selection
  document.addEventListener('DOMContentLoaded', function() {
    const formChecks = document.querySelectorAll('.form-check-custom');
    
    formChecks.forEach(function(check) {
      check.addEventListener('click', function() {
        // Remove selected class from all
        formChecks.forEach(function(item) {
          item.classList.remove('selected');
          item.querySelector('input[type="radio"]').checked = false;
        });
        
        // Add selected class to clicked item
        this.classList.add('selected');
        this.querySelector('input[type="radio"]').checked = true;
      });
    });
  });

  // Enhance select with search (if you want to add select2 later)
  // You can uncomment this if you add select2 library
  /*
  $(document).ready(function() {
    $('#select-karyawan').select2({
      placeholder: "-- Pilih Karyawan --",
      allowClear: true
    });
  });
  */
</script>
@endpush 