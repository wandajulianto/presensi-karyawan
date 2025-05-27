@extends('admin.layouts.tabler')

@section('content')
  <style>
    .detail-card {
      border: 1px solid #e2e8f0;
      border-radius: 0.5rem;
      background: #f8fafc;
    }
    
    .status-pending {
      background: #f59e0b;
      color: white;
      padding: 0.5rem 1rem;
      border-radius: 0.375rem;
      font-size: 0.875rem;
      font-weight: 600;
      display: inline-flex;
      align-items: center;
    }
    
    .status-approved {
      background: #22c55e;
      color: white;
      padding: 0.5rem 1rem;
      border-radius: 0.375rem;
      font-size: 0.875rem;
      font-weight: 600;
      display: inline-flex;
      align-items: center;
    }

    .status-rejected {
      background: #ef4444;
      color: white;
      padding: 0.5rem 1rem;
      border-radius: 0.375rem;
      font-size: 0.875rem;
      font-weight: 600;
      display: inline-flex;
      align-items: center;
    }

    .type-izin {
      background: #3b82f6;
      color: white;
      padding: 0.5rem 1rem;
      border-radius: 0.375rem;
      font-size: 0.875rem;
      font-weight: 600;
    }

    .type-sakit {
      background: #ec4899;
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
          <div class="page-pretitle">Detail</div>
          <h2 class="page-title">Pengajuan {{ $pengajuanIzin->status_text }}</h2>
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
            <a href="{{ route('admin.pengajuan-izin.edit', $pengajuanIzin) }}" class="btn btn-primary">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"></path>
                <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"></path>
                <path d="M16 5l3 3"></path>
              </svg>
              Edit
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="page-body">
    <div class="container-xl">
      <div class="row">
        <div class="col-lg-8">
          <!-- Detail Pengajuan -->
          <div class="card mb-4">
            <div class="card-header">
              <h3 class="card-title">Detail Pengajuan</h3>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label fw-bold">NIK Karyawan</label>
                    <p class="text-muted">{{ $pengajuanIzin->nik }}</p>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label fw-bold">Nama Karyawan</label>
                    <p class="text-muted">{{ $pengajuanIzin->karyawan->nama_lengkap }}</p>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label fw-bold">Departemen</label>
                    <p class="text-muted">
                      @if($pengajuanIzin->karyawan->departemen)
                        {{ $pengajuanIzin->karyawan->departemen->nama_departemen }}
                      @else
                        <span class="text-danger">Tidak ada departemen</span>
                      @endif
                    </p>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label fw-bold">Tanggal Izin</label>
                    <p class="text-muted">
                      {{ \Carbon\Carbon::parse($pengajuanIzin->tanggal_izin)->format('d F Y') }}
                      <small class="text-muted">({{ \Carbon\Carbon::parse($pengajuanIzin->tanggal_izin)->format('l') }})</small>
                    </p>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label fw-bold">Tipe Pengajuan</label>
                    <div>
                      @if($pengajuanIzin->status === 'i')
                        <span class="type-izin">
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path>
                            <path d="M12 8l0 4l3 3"></path>
                          </svg>
                          Izin
                        </span>
                      @else
                        <span class="type-sakit">
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M19.5 12.572l-7.5 7.428l-7.5 -7.428m0 0a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572"></path>
                          </svg>
                          Sakit
                        </span>
                      @endif
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label fw-bold">Status Approval</label>
                    <div>
                      @if($pengajuanIzin->status_approved === 0)
                        <span class="status-pending">
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path>
                            <path d="M12 8l0 4l3 3"></path>
                          </svg>
                          Pending
                        </span>
                      @elseif($pengajuanIzin->status_approved === 1)
                        <span class="status-approved">
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M5 12l5 5l10 -10"></path>
                          </svg>
                          Disetujui
                        </span>
                      @else
                        <span class="status-rejected">
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M18 6l-12 12"></path>
                            <path d="M6 6l12 12"></path>
                          </svg>
                          Ditolak
                        </span>
                      @endif
                    </div>
                  </div>
                </div>
                <div class="col-12">
                  <div class="mb-3">
                    <label class="form-label fw-bold">Keterangan</label>
                    <div class="detail-card p-3">
                      <p class="mb-0">{{ $pengajuanIzin->keterangan ?: 'Tidak ada keterangan' }}</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-4">
          <!-- Informasi Waktu -->
          <div class="card mb-4">
            <div class="card-header">
              <h3 class="card-title">Informasi Waktu</h3>
            </div>
            <div class="card-body">
              <div class="mb-3">
                <label class="form-label fw-bold">Tanggal Pengajuan</label>
                <p class="text-muted">{{ $pengajuanIzin->created_at->format('d F Y H:i') }}</p>
              </div>
              <div class="mb-3">
                <label class="form-label fw-bold">Terakhir Diupdate</label>
                <p class="text-muted">{{ $pengajuanIzin->updated_at->format('d F Y H:i') }}</p>
              </div>
              @if($pengajuanIzin->status_approved !== 0)
                <div class="mb-3">
                  <label class="form-label fw-bold">Waktu Approval</label>
                  <p class="text-muted">{{ $pengajuanIzin->updated_at->format('d F Y H:i') }}</p>
                </div>
              @endif
            </div>
          </div>

          <!-- Actions -->
          @if($pengajuanIzin->status_approved === 0)
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Aksi Approval</h3>
              </div>
              <div class="card-body">
                <div class="d-grid gap-2">
                  <button type="button" class="btn btn-success" onclick="confirmApprove('{{ $pengajuanIzin->id }}', '{{ $pengajuanIzin->karyawan->nama_lengkap }}', '{{ $pengajuanIzin->status_text }}')">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                      <path d="M5 12l5 5l10 -10"></path>
                    </svg>
                    Setujui Pengajuan
                  </button>
                  <button type="button" class="btn btn-danger" onclick="confirmReject('{{ $pengajuanIzin->id }}', '{{ $pengajuanIzin->karyawan->nama_lengkap }}', '{{ $pengajuanIzin->status_text }}')">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                      <path d="M18 6l-12 12"></path>
                      <path d="M6 6l12 12"></path>
                    </svg>
                    Tolak Pengajuan
                  </button>
                </div>
              </div>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>

  <!-- Hidden Forms for SweetAlert Actions -->
  <form id="form-approve" method="POST" style="display: none;">
    @csrf
    @method('PATCH')
  </form>

  <form id="form-reject" method="POST" style="display: none;">
    @csrf
    @method('PATCH')
  </form>

@endsection

@push('myScript')
<!-- SweetAlert2 CSS & JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
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

  // Confirm Approve
  function confirmApprove(izinId, namaKaryawan, tipe) {
    Swal.fire({
      title: 'Setujui Pengajuan?',
      text: `Yakin ingin menyetujui pengajuan ${tipe.toLowerCase()} dari "${namaKaryawan}"?`,
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#22c55e',
      cancelButtonColor: '#6b7280',
      confirmButtonText: 'Ya, Setujui!',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        const form = document.getElementById('form-approve');
        form.action = `/admin/pengajuan-izin/${izinId}/approve`;
        form.submit();
      }
    });
  }

  // Confirm Reject
  function confirmReject(izinId, namaKaryawan, tipe) {
    Swal.fire({
      title: 'Tolak Pengajuan?',
      text: `Yakin ingin menolak pengajuan ${tipe.toLowerCase()} dari "${namaKaryawan}"?`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#ef4444',
      cancelButtonColor: '#6b7280',
      confirmButtonText: 'Ya, Tolak!',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        const form = document.getElementById('form-reject');
        form.action = `/admin/pengajuan-izin/${izinId}/reject`;
        form.submit();
      }
    });
  }
</script>
@endpush 