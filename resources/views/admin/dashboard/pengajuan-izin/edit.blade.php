@extends('admin.layouts.tabler')

@section('content')
  <!-- SweetAlert2 CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <!-- Flatpickr CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

  <style>
    .section-header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      border-radius: 0.5rem 0.5rem 0 0;
      padding: 1rem;
      margin: -1rem -1rem 1rem -1rem;
    }

    .form-section {
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      border-radius: 0.5rem;
      padding: 1.5rem;
      margin-bottom: 1.5rem;
    }

    .radio-custom {
      position: relative;
      margin-bottom: 1rem;
    }

    .radio-custom input[type="radio"] {
      position: absolute;
      opacity: 0;
      pointer-events: none;
    }

    .radio-custom label {
      display: flex;
      align-items: center;
      padding: 1rem;
      border: 2px solid #e2e8f0;
      border-radius: 0.5rem;
      cursor: pointer;
      transition: all 0.2s;
      background: white;
    }

    .radio-custom label:hover {
      border-color: #94a3b8;
      background: #f1f5f9;
    }

    .radio-custom input[type="radio"]:checked + label {
      border-color: #3b82f6;
      background: #eff6ff;
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .radio-custom .radio-icon {
      width: 48px;
      height: 48px;
      border-radius: 0.5rem;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 1rem;
      color: white;
      font-size: 1.25rem;
    }

    .radio-custom.izin .radio-icon {
      background: linear-gradient(135deg, #3b82f6, #1e40af);
    }

    .radio-custom.sakit .radio-icon {
      background: linear-gradient(135deg, #ec4899, #be185d);
    }

    .radio-content h6 {
      margin: 0 0 0.25rem 0;
      font-weight: 600;
      color: #1f2937;
    }

    .radio-content p {
      margin: 0;
      font-size: 0.875rem;
      color: #6b7280;
    }

    .tips-card {
      background: linear-gradient(135deg, #fef3c7, #fbbf24);
      border: none;
      border-radius: 0.5rem;
    }

    .btn-primary {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border: none;
    }

    .btn-primary:hover {
      background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
      transform: translateY(-1px);
    }

    .form-control:focus {
      border-color: #667eea;
      box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .alert-validation {
      background: #fef2f2;
      border: 1px solid #fecaca;
      color: #991b1b;
      border-radius: 0.5rem;
      padding: 1rem;
      margin-bottom: 1rem;
    }

    .badge-edit {
      background: #f59e0b;
      color: white;
      padding: 0.5rem 1rem;
      border-radius: 0.375rem;
      font-size: 0.875rem;
      font-weight: 600;
    }
  </style>

  <div class="page-header d-print-none">
    <div class="container-xl">
      <div class="row g-2 align-items-center">
        <div class="col">
          <div class="page-pretitle">Edit</div>
          <h2 class="page-title">
            Pengajuan {{ $pengajuanIzin->status_text }}
            <span class="badge-edit ms-2">{{ $pengajuanIzin->status_text }}</span>
          </h2>
        </div>
        <div class="col-auto ms-auto d-print-none">
          <div class="btn-list">
            <a href="{{ route('admin.pengajuan-izin.index') }}" class="btn btn-outline-secondary">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <path d="M5 12l14 0"></path>
                <path d="M5 12l6 6"></path>
                <path d="M5 12l6 -6"></path>
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
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <form action="{{ route('admin.pengajuan-izin.update', $pengajuanIzin) }}" method="POST" id="form-pengajuan">
            @csrf
            @method('PUT')

            <!-- Data Karyawan Section -->
            <div class="card mb-4">
              <div class="card-body">
                <div class="section-header">
                  <h3 class="mb-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                      <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path>
                      <path d="M12 10m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0"></path>
                      <path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855"></path>
                    </svg>
                    Informasi Karyawan
                  </h3>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="mb-3">
                      <label class="form-label fw-bold">NIK Karyawan</label>
                      <input type="text" class="form-control bg-light" value="{{ $pengajuanIzin->nik }}" readonly>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="mb-3">
                      <label class="form-label fw-bold">Nama Lengkap</label>
                      <input type="text" class="form-control bg-light" value="{{ $pengajuanIzin->karyawan->nama_lengkap }}" readonly>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="mb-3">
                      <label class="form-label fw-bold">Departemen</label>
                      <input type="text" class="form-control bg-light" 
                             value="{{ $pengajuanIzin->karyawan->departemen ? $pengajuanIzin->karyawan->departemen->nama_departemen : 'Tidak ada departemen' }}" readonly>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="mb-3">
                      <label class="form-label fw-bold">Jabatan</label>
                      <input type="text" class="form-control bg-light" value="{{ $pengajuanIzin->karyawan->jabatan ?: 'Tidak ada jabatan' }}" readonly>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Detail Pengajuan Section -->
            <div class="card mb-4">
              <div class="card-body">
                <div class="section-header">
                  <h3 class="mb-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                      <rect x="4" y="5" width="16" height="16" rx="2"></rect>
                      <line x1="16" y1="3" x2="16" y2="7"></line>
                      <line x1="8" y1="3" x2="8" y2="7"></line>
                      <rect x="8" y="11" width="2" height="2"></rect>
                      <rect x="10" y="13" width="2" height="2"></rect>
                      <rect x="12" y="11" width="2" height="2"></rect>
                    </svg>
                    Detail Pengajuan
                  </h3>
                </div>

                <!-- Validation Errors -->
                @if ($errors->any())
                  <div class="alert-validation">
                    <div class="d-flex align-items-center mb-2">
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <circle cx="12" cy="12" r="9"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                      </svg>
                      <strong>Terdapat kesalahan input:</strong>
                    </div>
                    <ul class="mb-0">
                      @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                      @endforeach
                    </ul>
                  </div>
                @endif

                <!-- Tipe Izin -->
                <div class="form-section">
                  <label class="form-label fw-bold mb-3">Jenis Pengajuan</label>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="radio-custom izin">
                        <input type="radio" id="status_izin" name="status" value="i" {{ $pengajuanIzin->status === 'i' ? 'checked' : '' }}>
                        <label for="status_izin">
                          <div class="radio-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                              <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                              <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path>
                              <path d="M12 8l0 4l3 3"></path>
                            </svg>
                          </div>
                          <div class="radio-content">
                            <h6>Izin</h6>
                            <p>Tidak masuk kerja karena ada keperluan pribadi</p>
                          </div>
                        </label>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="radio-custom sakit">
                        <input type="radio" id="status_sakit" name="status" value="s" {{ $pengajuanIzin->status === 's' ? 'checked' : '' }}>
                        <label for="status_sakit">
                          <div class="radio-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                              <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                              <path d="M19.5 12.572l-7.5 7.428l-7.5 -7.428m0 0a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572"></path>
                            </svg>
                          </div>
                          <div class="radio-content">
                            <h6>Sakit</h6>
                            <p>Tidak masuk kerja karena kondisi kesehatan</p>
                          </div>
                        </label>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Tanggal Izin -->
                <div class="mb-4">
                  <label class="form-label fw-bold">Tanggal Izin <span class="text-danger">*</span></label>
                  <input type="text" class="form-control @error('tanggal_izin') is-invalid @enderror" 
                         name="tanggal_izin" id="tanggal_izin" 
                         value="{{ old('tanggal_izin', \Carbon\Carbon::parse($pengajuanIzin->tanggal_izin)->format('Y-m-d')) }}" 
                         placeholder="Pilih tanggal izin">
                  @error('tanggal_izin')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <!-- Keterangan -->
                <div class="mb-4">
                  <label class="form-label fw-bold">Keterangan <span class="text-danger">*</span></label>
                  <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                            name="keterangan" rows="4" 
                            placeholder="Jelaskan alasan izin/sakit Anda...">{{ old('keterangan', $pengajuanIzin->keterangan) }}</textarea>
                  @error('keterangan')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                  <div class="form-hint">Minimal 10 karakter, maksimal 500 karakter</div>
                </div>

                <!-- Submit Button -->
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                  <a href="{{ route('admin.pengajuan-izin.index') }}" class="btn btn-outline-secondary me-md-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                      <path d="M18 6l-12 12"></path>
                      <path d="M6 6l12 12"></path>
                    </svg>
                    Batal
                  </a>
                  <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                      <path d="M5 12l5 5l10 -10"></path>
                    </svg>
                    Update Pengajuan
                  </button>
                </div>
              </div>
            </div>
          </form>
        </div>

        <!-- Tips Sidebar -->
        <div class="col-lg-4">
          <div class="card tips-card">
            <div class="card-body">
              <h4 class="card-title text-dark">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                  <path d="M12 9v2m0 4v.01"></path>
                  <path d="M5 19h14a2 2 0 0 0 1.414 -3.414l-7 -7a2 2 0 0 0 -2.828 0l-7 7a2 2 0 0 0 1.414 3.414z"></path>
                </svg>
                Tips Edit Pengajuan
              </h4>
              <div class="text-dark">
                <p><strong>Pastikan data yang Anda edit:</strong></p>
                <ul class="mb-3">
                  <li>Tanggal izin tidak boleh tanggal yang sudah lewat</li>
                  <li>Keterangan harus jelas dan lengkap</li>
                  <li>Pilih jenis yang sesuai (Izin/Sakit)</li>
                  <li>Data yang sudah disetujui tidak bisa diubah</li>
                </ul>
                
                <div class="alert alert-warning">
                  <strong>Perhatian:</strong><br>
                  Pastikan data yang Anda ubah sudah benar karena perubahan akan mempengaruhi status approval.
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
<!-- SweetAlert2 JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Flatpickr JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

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

  // Flatpickr Date Picker
  flatpickr("#tanggal_izin", {
    dateFormat: "Y-m-d",
    minDate: "today",
    locale: {
      firstDayOfWeek: 1,
      weekdays: {
        shorthand: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
        longhand: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']
      },
      months: {
        shorthand: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
        longhand: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']
      }
    }
  });

  // Form Validation
  document.getElementById('form-pengajuan').addEventListener('submit', function(e) {
    const tanggalIzin = document.getElementById('tanggal_izin').value;
    const keterangan = document.querySelector('textarea[name="keterangan"]').value;
    const status = document.querySelector('input[name="status"]:checked');

    if (!tanggalIzin) {
      e.preventDefault();
      Swal.fire({
        icon: 'error',
        title: 'Validasi Gagal!',
        text: 'Tanggal izin harus diisi.',
        showConfirmButton: true
      });
      return;
    }

    if (!keterangan || keterangan.length < 10) {
      e.preventDefault();
      Swal.fire({
        icon: 'error',
        title: 'Validasi Gagal!',
        text: 'Keterangan harus diisi minimal 10 karakter.',
        showConfirmButton: true
      });
      return;
    }

    if (!status) {
      e.preventDefault();
      Swal.fire({
        icon: 'error',
        title: 'Validasi Gagal!',
        text: 'Jenis pengajuan (Izin/Sakit) harus dipilih.',
        showConfirmButton: true
      });
      return;
    }

    // Confirm submission
    e.preventDefault();
    Swal.fire({
      title: 'Konfirmasi Update',
      text: 'Yakin ingin mengupdate pengajuan ini?',
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#667eea',
      cancelButtonColor: '#6b7280',
      confirmButtonText: 'Ya, Update!',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        this.submit();
      }
    });
  });
</script>
@endpush 