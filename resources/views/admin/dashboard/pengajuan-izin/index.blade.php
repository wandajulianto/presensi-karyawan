@extends('admin.layouts.tabler')

@section('content')
  <!-- SweetAlert2 CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  
  <style>
    .status-pending {
      background: #f59e0b;
      color: white;
      padding: 0.25rem 0.75rem;
      border-radius: 0.375rem;
      font-size: 0.75rem;
      font-weight: 600;
      display: inline-flex;
      align-items: center;
    }
    
    .status-approved {
      background: #22c55e;
      color: white;
      padding: 0.25rem 0.75rem;
      border-radius: 0.375rem;
      font-size: 0.75rem;
      font-weight: 600;
      display: inline-flex;
      align-items: center;
    }

    .status-rejected {
      background: #ef4444;
      color: white;
      padding: 0.25rem 0.75rem;
      border-radius: 0.375rem;
      font-size: 0.75rem;
      font-weight: 600;
      display: inline-flex;
      align-items: center;
    }

    .type-izin {
      background: #3b82f6;
      color: white;
      padding: 0.25rem 0.75rem;
      border-radius: 0.375rem;
      font-size: 0.75rem;
      font-weight: 600;
    }

    .type-sakit {
      background: #ec4899;
      color: white;
      padding: 0.25rem 0.75rem;
      border-radius: 0.375rem;
      font-size: 0.75rem;
      font-weight: 600;
    }

    .btn-group-action {
      white-space: nowrap;
    }

    .btn-group-action .btn {
      margin-right: 0.25rem;
    }

    .card-summary {
      border: 1px solid #e2e8f0;
      transition: all 0.2s;
    }

    .card-summary:hover {
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .text-truncate-2 {
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
      max-width: 200px;
    }
  </style>

  <div class="page-header d-print-none">
    <div class="container-xl">
      <div class="row g-2 align-items-center">
        <div class="col">
          <div class="page-pretitle">Manajemen</div>
          <h2 class="page-title">Data Izin / Sakit</h2>
        </div>
        <div class="col-auto ms-auto d-print-none">
          <div class="btn-list">
            <a href="{{ route('admin.pengajuan-izin.export', request()->query()) }}" class="btn btn-outline-success d-none d-sm-inline-block">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <path d="M14 3v4a1 1 0 0 0 1 1h4"></path>
                <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"></path>
                <path d="M9 15l2 2l4 -4"></path>
              </svg>
              Export CSV
            </a>
            <a href="{{ route('admin.pengajuan-izin.create') }}" class="btn btn-primary d-none d-sm-inline-block">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
              </svg>
              Tambah Pengajuan
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="page-body">
    <div class="container-xl">
      <!-- Summary Cards -->
      <div class="row mb-4">
        <div class="col-sm-6 col-lg-2">
          <div class="card card-summary">
            <div class="card-body">
              <div class="d-flex align-items-center">
                <div class="subheader">Total Pengajuan</div>
              </div>
              <div class="h1 mb-3">{{ $summary['total'] }}</div>
              <div class="d-flex mb-2">
                <div class="flex-fill">
                  <div class="progress progress-sm">
                    <div class="progress-bar bg-primary" style="width: 100%" role="progressbar"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-6 col-lg-2">
          <div class="card card-summary">
            <div class="card-body">
              <div class="d-flex align-items-center">
                <div class="subheader">Pending</div>
              </div>
              <div class="h1 mb-3 text-warning">{{ $summary['pending'] }}</div>
              <div class="d-flex mb-2">
                <div class="flex-fill">
                  <div class="progress progress-sm">
                    <div class="progress-bar bg-warning" style="width: {{ $summary['total'] > 0 ? ($summary['pending'] / $summary['total']) * 100 : 0 }}%" role="progressbar"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-6 col-lg-2">
          <div class="card card-summary">
            <div class="card-body">
              <div class="d-flex align-items-center">
                <div class="subheader">Disetujui</div>
              </div>
              <div class="h1 mb-3 text-success">{{ $summary['approved'] }}</div>
              <div class="d-flex mb-2">
                <div class="flex-fill">
                  <div class="progress progress-sm">
                    <div class="progress-bar bg-success" style="width: {{ $summary['total'] > 0 ? ($summary['approved'] / $summary['total']) * 100 : 0 }}%" role="progressbar"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-6 col-lg-2">
          <div class="card card-summary">
            <div class="card-body">
              <div class="d-flex align-items-center">
                <div class="subheader">Ditolak</div>
              </div>
              <div class="h1 mb-3 text-danger">{{ $summary['rejected'] }}</div>
              <div class="d-flex mb-2">
                <div class="flex-fill">
                  <div class="progress progress-sm">
                    <div class="progress-bar bg-danger" style="width: {{ $summary['total'] > 0 ? ($summary['rejected'] / $summary['total']) * 100 : 0 }}%" role="progressbar"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-6 col-lg-2">
          <div class="card card-summary">
            <div class="card-body">
              <div class="d-flex align-items-center">
                <div class="subheader">Izin</div>
              </div>
              <div class="h1 mb-3 text-blue">{{ $summary['izin'] }}</div>
              <div class="d-flex mb-2">
                <div class="flex-fill">
                  <div class="progress progress-sm">
                    <div class="progress-bar bg-blue" style="width: {{ $summary['total'] > 0 ? ($summary['izin'] / $summary['total']) * 100 : 0 }}%" role="progressbar"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-6 col-lg-2">
          <div class="card card-summary">
            <div class="card-body">
              <div class="d-flex align-items-center">
                <div class="subheader">Sakit</div>
              </div>
              <div class="h1 mb-3 text-pink">{{ $summary['sakit'] }}</div>
              <div class="d-flex mb-2">
                <div class="flex-fill">
                  <div class="progress progress-sm">
                    <div class="progress-bar bg-pink" style="width: {{ $summary['total'] > 0 ? ($summary['sakit'] / $summary['total']) * 100 : 0 }}%" role="progressbar"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Filter Form -->
      <div class="row mb-4">
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <form method="GET" action="{{ route('admin.pengajuan-izin.index') }}">
                <div class="row g-3">
                  <div class="col-md-3">
                    <label class="form-label">Cari Nama Karyawan</label>
                    <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Masukkan nama karyawan...">
                  </div>
                  <div class="col-md-2">
                    <label class="form-label">Tipe</label>
                    <select class="form-select" name="status">
                      <option value="">Semua Tipe</option>
                      <option value="i" {{ request('status') === 'i' ? 'selected' : '' }}>Izin</option>
                      <option value="s" {{ request('status') === 's' ? 'selected' : '' }}>Sakit</option>
                    </select>
                  </div>
                  <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="approval">
                      <option value="">Semua Status</option>
                      <option value="0" {{ request('approval') === '0' ? 'selected' : '' }}>Pending</option>
                      <option value="1" {{ request('approval') === '1' ? 'selected' : '' }}>Disetujui</option>
                      <option value="2" {{ request('approval') === '2' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                  </div>
                  <div class="col-md-2">
                    <label class="form-label">Bulan</label>
                    <select class="form-select" name="bulan">
                      <option value="">Semua Bulan</option>
                      @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>
                          {{ \Carbon\Carbon::create()->month($i)->format('F') }}
                        </option>
                      @endfor
                    </select>
                  </div>
                  <div class="col-md-2">
                    <label class="form-label">Tahun</label>
                    <select class="form-select" name="tahun">
                      @for ($year = date('Y'); $year >= date('Y') - 5; $year--)
                        <option value="{{ $year }}" {{ request('tahun', date('Y')) == $year ? 'selected' : '' }}>{{ $year }}</option>
                      @endfor
                    </select>
                  </div>
                  <div class="col-md-1">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-1">
                      <button type="submit" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                          <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                          <circle cx="10" cy="10" r="7"></circle>
                          <path d="M21 21l-6 -6"></path>
                        </svg>
                      </button>
                      <a href="{{ route('admin.pengajuan-izin.index') }}" class="btn btn-outline-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                          <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                          <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4"></path>
                          <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4"></path>
                        </svg>
                      </a>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

      <!-- Data Table -->
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Daftar Pengajuan Izin/Sakit</h3>
              <div class="card-actions">
                <div class="text-muted">
                  Total: {{ $pengajuanIzins->total() }} pengajuan
                </div>
              </div>
            </div>
            <div class="card-body">
              @if($pengajuanIzins->count() > 0)
                <div class="table-responsive">
                  <table class="table table-vcenter table-striped">
                    <thead>
                      <tr>
                        <th>NIK</th>
                        <th>Nama Karyawan</th>
                        <th>Tanggal Izin</th>
                        <th>Tipe</th>
                        <th>Keterangan</th>
                        <th>Status</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($pengajuanIzins as $izin)
                        <tr>
                          <td>
                            <span class="badge bg-blue-lt">{{ $izin->nik }}</span>
                          </td>
                          <td>
                            <div class="fw-bold">{{ $izin->karyawan->nama_lengkap }}</div>
                            @if($izin->karyawan->departemen)
                              <div class="text-muted small">{{ $izin->karyawan->departemen->nama_departemen }}</div>
                            @endif
                          </td>
                          <td>
                            <div class="fw-bold">{{ \Carbon\Carbon::parse($izin->tanggal_izin)->format('d M Y') }}</div>
                            <div class="text-muted small">{{ \Carbon\Carbon::parse($izin->tanggal_izin)->format('l') }}</div>
                          </td>
                          <td>
                            @if($izin->status === 'i')
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
                          </td>
                          <td>
                            <div class="text-truncate-2">{{ $izin->keterangan }}</div>
                          </td>
                          <td>
                            @if($izin->status_approved === 0)
                              <span class="status-pending">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                  <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                  <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path>
                                  <path d="M12 8l0 4l3 3"></path>
                                </svg>
                                Pending
                              </span>
                            @elseif($izin->status_approved === 1)
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
                          </td>
                          <td>
                            <div class="small">{{ $izin->created_at->format('d M Y H:i') }}</div>
                          </td>
                          <td>
                            <div class="btn-group-action">
                              @if($izin->status_approved === 0)
                                <button type="button" class="btn btn-success btn-sm" title="Setujui" 
                                        onclick="confirmApprove('{{ $izin->id }}', '{{ $izin->karyawan->nama_lengkap }}', '{{ $izin->status_text }}')">
                                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M5 12l5 5l10 -10"></path>
                                  </svg>
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" title="Tolak" 
                                        onclick="confirmReject('{{ $izin->id }}', '{{ $izin->karyawan->nama_lengkap }}', '{{ $izin->status_text }}')">
                                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M18 6l-12 12"></path>
                                    <path d="M6 6l12 12"></path>
                                  </svg>
                                </button>
                              @endif
                              
                              <a href="{{ route('admin.pengajuan-izin.show', $izin) }}" class="btn btn-info btn-sm" title="Detail">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                  <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                  <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path>
                                  <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"></path>
                                </svg>
                              </a>

                              <a href="{{ route('admin.pengajuan-izin.edit', $izin) }}" class="btn btn-primary btn-sm" title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                  <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                  <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"></path>
                                  <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"></path>
                                  <path d="M16 5l3 3"></path>
                                </svg>
                              </a>

                              <button type="button" class="btn btn-outline-danger btn-sm" title="Hapus" 
                                      onclick="confirmDelete('{{ $izin->id }}', '{{ $izin->karyawan->nama_lengkap }}', '{{ $izin->status_text }}')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                  <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                  <path d="M4 7l16 0"></path>
                                  <path d="M10 11l0 6"></path>
                                  <path d="M14 11l0 6"></path>
                                  <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path>
                                  <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path>
                                </svg>
                              </button>
                            </div>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                  <div class="text-muted">
                    Menampilkan {{ $pengajuanIzins->firstItem() ?? 0 }} sampai {{ $pengajuanIzins->lastItem() ?? 0 }} 
                    dari {{ $pengajuanIzins->total() }} pengajuan
                  </div>
                  {{ $pengajuanIzins->appends(request()->query())->links() }}
                </div>
              @else
                <div class="text-center py-5">
                  <div class="empty">
                    <div class="empty-img">
                      <img src="{{ asset('assets/img/undraw_no_data_qbuo.svg') }}" height="128" alt="">
                    </div>
                    <p class="empty-title">Tidak ada data pengajuan izin</p>
                    <p class="empty-subtitle text-muted">
                      Belum ada pengajuan izin/sakit yang sesuai dengan filter yang dipilih
                    </p>
                    <div class="empty-action">
                      <a href="{{ route('admin.pengajuan-izin.create') }}" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                          <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                          <line x1="12" y1="5" x2="12" y2="19"></line>
                          <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        Tambah Pengajuan Pertama
                      </a>
                    </div>
                  </div>
                </div>
              @endif
            </div>
          </div>
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

  <form id="form-delete" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
  </form>

@endsection

@push('myScript')
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

  // Confirm Delete
  function confirmDelete(izinId, namaKaryawan, tipe) {
    Swal.fire({
      title: 'Hapus Pengajuan?',
      text: `Yakin ingin menghapus pengajuan ${tipe.toLowerCase()} dari "${namaKaryawan}"? Data yang dihapus tidak dapat dikembalikan!`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#ef4444',
      cancelButtonColor: '#6b7280',
      confirmButtonText: 'Ya, Hapus!',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        const form = document.getElementById('form-delete');
        form.action = `/admin/pengajuan-izin/${izinId}`;
        form.submit();
      }
    });
  }
</script>
@endpush 