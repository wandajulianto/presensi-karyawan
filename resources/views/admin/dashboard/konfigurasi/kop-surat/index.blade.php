@extends('admin.layouts.tabler')

@section('content')
<div class="page-header d-print-none">
  <div class="container-xl">
    <div class="row g-2 align-items-center">
      <div class="col">
        <div class="page-pretitle">Konfigurasi</div>
        <h2 class="page-title">Kop Surat & Tanda Tangan</h2>
      </div>
      <div class="col-auto ms-auto d-print-none">
        <div class="btn-list">
          <button type="button" class="btn btn-outline-info" id="btn-preview">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
              <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path>
              <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"></path>
            </svg>
            Preview
          </button>
        </div>
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
            <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
              <path d="M5 12l5 5l10 -10"></path>
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
            <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
              <path d="M12 9v4"></path>
              <path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z"></path>
              <path d="M12 16h.01"></path>
            </svg>
          </div>
          <div>{{ session('error') }}</div>
        </div>
        <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
      </div>
    @endif

    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Konfigurasi Kop Surat</h3>
            <div class="card-actions">
              <span class="badge bg-blue-lt">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                  <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"></path>
                  <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                  <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                  <path d="M21 21v-2a4 4 0 0 0 -3 -3.85"></path>
                </svg>
                Berlaku untuk semua laporan
              </span>
            </div>
          </div>
          
          <form action="{{ route('admin.konfigurasi.kop-surat.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="card-body">
              <div class="row">
                <!-- Informasi Instansi -->
                <div class="col-md-6">
                  <h4 class="card-title mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                      <path d="M3 21l18 0"></path>
                      <path d="M5 21v-16l3 -3l10 10l3 -3"></path>
                      <path d="M9 9l1 0"></path>
                      <path d="M9 12l1 0"></path>
                      <path d="M9 15l1 0"></path>
                      <path d="M13 9l1 0"></path>
                      <path d="M13 12l1 0"></path>
                      <path d="M13 15l1 0"></path>
                    </svg>
                    Informasi Instansi
                  </h4>
                  
                  <div class="mb-3">
                    <label class="form-label required">Nama Instansi</label>
                    <input type="text" name="nama_instansi" class="form-control @error('nama_instansi') is-invalid @enderror" 
                           value="{{ old('nama_instansi', $kopSurat->nama_instansi) }}" placeholder="Nama lengkap instansi">
                    @error('nama_instansi')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>

                  <div class="mb-3">
                    <label class="form-label required">Alamat Instansi</label>
                    <textarea name="alamat_instansi" rows="3" class="form-control @error('alamat_instansi') is-invalid @enderror" 
                              placeholder="Alamat lengkap instansi">{{ old('alamat_instansi', $kopSurat->alamat_instansi) }}</textarea>
                    @error('alamat_instansi')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Telepon</label>
                    <input type="text" name="telepon_instansi" class="form-control @error('telepon_instansi') is-invalid @enderror" 
                           value="{{ old('telepon_instansi', $kopSurat->telepon_instansi) }}" placeholder="(021) 123456">
                    @error('telepon_instansi')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email_instansi" class="form-control @error('email_instansi') is-invalid @enderror" 
                           value="{{ old('email_instansi', $kopSurat->email_instansi) }}" placeholder="info@instansi.com">
                    @error('email_instansi')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Website</label>
                    <input type="text" name="website_instansi" class="form-control @error('website_instansi') is-invalid @enderror" 
                           value="{{ old('website_instansi', $kopSurat->website_instansi) }}" placeholder="www.instansi.com">
                    @error('website_instansi')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Logo Instansi</label>
                    <input type="file" name="logo_instansi" class="form-control @error('logo_instansi') is-invalid @enderror" 
                           accept="image/*">
                    @error('logo_instansi')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-hint">Format: JPEG, PNG, JPG, GIF. Maksimal 2MB.</div>
                    
                    @if($kopSurat->logo_instansi)
                      <div class="mt-2">
                        <img src="{{ $kopSurat->logo_url }}" alt="Logo Instansi" style="max-height: 100px;" class="border rounded">
                        <div class="text-muted mt-1">Logo saat ini</div>
                      </div>
                    @endif
                  </div>
                </div>

                <!-- Informasi Pimpinan -->
                <div class="col-md-6">
                  <h4 class="card-title mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                      <path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"></path>
                      <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path>
                      <path d="M15 15l3.5 3.5"></path>
                      <path d="M9 9l-3.5 -3.5"></path>
                    </svg>
                    Informasi Pimpinan / Penandatangan
                  </h4>

                  <div class="mb-3">
                    <label class="form-label required">Nama Pimpinan</label>
                    <input type="text" name="nama_pimpinan" class="form-control @error('nama_pimpinan') is-invalid @enderror" 
                           value="{{ old('nama_pimpinan', $kopSurat->nama_pimpinan) }}" placeholder="Nama lengkap dengan gelar">
                    @error('nama_pimpinan')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>

                  <div class="mb-3">
                    <label class="form-label required">Jabatan</label>
                    <input type="text" name="jabatan_pimpinan" class="form-control @error('jabatan_pimpinan') is-invalid @enderror" 
                           value="{{ old('jabatan_pimpinan', $kopSurat->jabatan_pimpinan) }}" placeholder="Direktur Utama">
                    @error('jabatan_pimpinan')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>

                  <div class="mb-3">
                    <label class="form-label">NIP</label>
                    <input type="text" name="nip_pimpinan" class="form-control @error('nip_pimpinan') is-invalid @enderror" 
                           value="{{ old('nip_pimpinan', $kopSurat->nip_pimpinan) }}" placeholder="198501012010011001">
                    @error('nip_pimpinan')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-hint">Opsional. Nomor Induk Pegawai jika ada.</div>
                  </div>

                  <!-- Info Card -->
                  <div class="card card-sm bg-azure-lt border-azure">
                    <div class="card-body">
                      <div class="d-flex align-items-center">
                        <div class="subheader me-2">Info:</div>
                        <div class="ms-auto lh-1">
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon text-azure" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M12 9h.01"></path>
                            <path d="M11 12h1v4h1"></path>
                            <path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z"></path>
                          </svg>
                        </div>
                      </div>
                      <div class="h4 m-0">Konfigurasi ini akan digunakan untuk semua laporan PDF dan CSV yang di-export dari sistem.</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="card-footer">
              <div class="d-flex">
                <button type="submit" class="btn btn-primary">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2"></path>
                    <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                    <path d="M14 4l0 4l-6 0l0 -4"></path>
                  </svg>
                  Simpan Konfigurasi
                </button>
                <button type="button" class="btn btn-outline-secondary ms-auto" id="btn-reset">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4"></path>
                    <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4"></path>
                  </svg>
                  Reset Form
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Preview -->
<div class="modal modal-blur fade" id="modal-preview" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Preview Kop Surat</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="preview-content">
        <div class="text-center">
          <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
          <div class="mt-2">Memuat preview...</div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('myScript')
<script>
// Function untuk extract city dari alamat
function extractCityFromAddress(alamat) {
    if (!alamat) return 'Kota';
    
    const parts = alamat.split(',').map(part => part.trim());
    
    // Cari bagian yang mengandung "Kabupaten" atau "Kota"
    for (let part of parts) {
        if (part.toLowerCase().includes('kabupaten')) {
            return part.replace(/kabupaten\s+/i, '');
        }
        if (part.toLowerCase().includes('kota')) {
            return part.replace(/kota\s+/i, '');
        }
    }
    
    // Jika tidak ada "Kabupaten" atau "Kota", ambil bagian ketiga (index 2)
    if (parts.length >= 3) {
        return parts[2];
    }
    
    return 'Kota';
}

document.addEventListener('DOMContentLoaded', function() {
  // Preview functionality
  document.getElementById('btn-preview').addEventListener('click', function() {
    const modal = new bootstrap.Modal(document.getElementById('modal-preview'));
    modal.show();
    
    // Load preview data
    fetch('{{ route("admin.konfigurasi.kop-surat.preview") }}', {
      method: 'GET',
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      credentials: 'same-origin'
    })
      .then(response => {
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
      })
      .then(data => {
        if (data.success) {
          const content = `
            <div class="border p-4 bg-white" style="min-height: 400px;">
              <!-- Header Kop Surat -->
              <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                <div class="me-3">
                  <img src="${data.data.logo_url}" alt="Logo" style="max-height: 80px; max-width: 80px; object-fit: contain;">
                </div>
                <div class="flex-fill">
                  <h3 class="mb-1 text-primary fw-bold">${data.data.nama_instansi}</h3>
                  <div class="text-muted">${data.data.alamat_instansi}</div>
                  ${data.data.telepon_instansi ? `<div class="text-muted">Telp: ${data.data.telepon_instansi}</div>` : ''}
                  ${data.data.email_instansi ? `<div class="text-muted">Email: ${data.data.email_instansi}</div>` : ''}
                  ${data.data.website_instansi ? `<div class="text-muted">Website: ${data.data.website_instansi}</div>` : ''}
                </div>
              </div>
              
              <!-- Contoh Judul Laporan -->
              <div class="text-center mb-4">
                <h4 class="fw-bold">LAPORAN PRESENSI KARYAWAN</h4>
                <div class="text-muted">Periode: Januari 2025</div>
              </div>
              
              <!-- Contoh Tabel -->
              <table class="table table-sm border">
                <thead class="table-primary">
                  <tr>
                    <th>No</th>
                    <th>NIK</th>
                    <th>Nama</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>1</td>
                    <td>001</td>
                    <td>John Doe</td>
                    <td>01/01/2025</td>
                    <td><span class="badge bg-success">Hadir</span></td>
                  </tr>
                  <tr>
                    <td>2</td>
                    <td>002</td>
                    <td>Jane Smith</td>
                    <td>01/01/2025</td>
                    <td><span class="badge bg-warning">Terlambat</span></td>
                  </tr>
                </tbody>
              </table>
              
              <!-- Footer Tanda Tangan -->
              <div class="d-flex justify-content-end mt-5">
                <div class="text-center" style="width: 250px;">
                                                                             <div class="mb-4">${extractCityFromAddress(data.data.alamat_instansi)}, ${new Date().toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' })}</div>
                  <div class="fw-bold mb-5">${data.data.jabatan_pimpinan}</div>
                  <div class="fw-bold border-bottom border-dark d-inline-block px-3">${data.data.nama_pimpinan}</div>
                  ${data.data.nip_pimpinan ? `<div class="text-muted mt-1">NIP: ${data.data.nip_pimpinan}</div>` : ''}
                </div>
              </div>
            </div>
          `;
          document.getElementById('preview-content').innerHTML = content;
        } else {
          document.getElementById('preview-content').innerHTML = `
            <div class="alert alert-info">
              <div class="d-flex">
                <div>
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M12 9h.01"></path>
                    <path d="M11 12h1v4h1"></path>
                    <path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z"></path>
                  </svg>
                </div>
                <div>
                  <h4>Preview Tidak Tersedia</h4>
                  <p>Konfigurasi kop surat belum tersedia atau belum disimpan.</p>
                  <p class="text-muted">Silakan isi dan simpan form konfigurasi terlebih dahulu, kemudian coba preview lagi.</p>
                </div>
              </div>
            </div>
          `;
        }
      })
      .catch(error => {
        document.getElementById('preview-content').innerHTML = `
          <div class="alert alert-danger">
            <div class="d-flex">
              <div>
                <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                  <path d="M12 9v4"></path>
                  <path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z"></path>
                  <path d="M12 16h.01"></path>
                </svg>
              </div>
              <div>
                <h4>Error Loading Preview</h4>
                <p>Terjadi kesalahan saat memuat preview: ${error.message}</p>
                <p class="text-muted">Pastikan Anda sudah login dan memiliki akses ke halaman ini.</p>
              </div>
            </div>
          </div>
        `;
      });
  });

  // Reset form functionality
  document.getElementById('btn-reset').addEventListener('click', function() {
    if (confirm('Apakah Anda yakin ingin mereset form? Semua perubahan yang belum disimpan akan hilang.')) {
      document.querySelector('form').reset();
    }
  });
});
</script>
@endpush 