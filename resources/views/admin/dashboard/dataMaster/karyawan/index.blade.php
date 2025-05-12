@extends('admin.layouts.tabler')
@section('content')
  <div class="page-header d-print-none">
    <div class="container-xl">
      <div class="row g-2 align-items-center">
        <div class="col">
          <!-- Page pre-title -->
          <div class="page-pretitle">
            Data Master
          </div>
          <h2 class="page-title">
            Data Karyawan
          </h2>
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
            <div>
              {{ session('success') }}
            </div>
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
            <div>
              {{ session('error') }}
            </div>
          </div>
          <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
        </div>
      @endif
      <div class="row row-deck row-cards">
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <div class="table-responsive">
                <div class="d-flex mb-3">
                  <a href="{{ route('data-master.karyawan.create') }}" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-plus"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                    Tambah Data Karyawan
                  </a>
                </div>
                <form action="{{ route('data-master.karyawan') }}" method="GET">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <input type="text" class="form-control" name="nama_lengkap" value="{{ request('nama_lengkap') }}" placeholder="Cari Karyawan">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <select name="departemen" id="departemen" class="form-select">
                          <option value="">- Pilih Departemen -</option>
                          <option value="null" {{ request('departemen') == 'null' ? 'selected' : '' }}>
                            (Tanpa Departemen)
                          </option>
                          @foreach ($departemens as $departemen)
                            <option value="{{ $departemen->kode_departemen }}" {{ request('departemen') == $departemen->kode_departemen ? 'selected' : '' }}>
                              {{ $departemen->nama_departemen }}
                            </option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="col-md-12 mt-3">
                      <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                          <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-search"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
                          Cari
                        </button>
                      </div>
                    </div>
                  </div>
                </form>
                <table class="table table-vcenter table-striped mt-3">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>NIK</th>
                      <th>Nama</th>
                      <th>Jabatan</th>
                      <th>No. HP</th>
                      <th>Foto</th>
                      <th>Departemen</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($karyawans as $karyawan)
                      @php
                        // Cek jika foto NULL atau string kosong
                        $foto = $karyawan->foto 
                          ? Storage::url('uploads/foto_karyawan/'.$karyawan->foto)
                          : asset('assets/img/avatar1.jpg');
                      @endphp
                      <tr>
                        <td>{{ $karyawans->firstItem() + $loop->index }}</td>
                        <td>{{ $karyawan->nik }}</td>
                        <td>{{ $karyawan->nama_lengkap }}</td>
                        <td>{{ $karyawan->jabatan }}</td>
                        <td>{{ $karyawan->no_hp }}</td>
                        <td><img src="{{ $foto }}" alt="Foto Karyawan" class="avatar w-10 h-10"></td>
                        <td>{{ $karyawan->departemen->nama_departemen ?? 'Tidak Ada Departemen' }}</td>
                        <td>
                          <a href="{{ route('data-master.karyawan.edit', $karyawan->nik) }}" class="btn btn-warning">
                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-edit"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                            Edit
                          </a>
                          <button type="button" class="btn btn-danger btn-delete text-2xl" data-nik="{{ $karyawan->nik }}" data-nama="{{ $karyawan->nama_lengkap }}">
                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-trash"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                            Hapus
                          </button>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
                <div class="d-flex justify-content-center mt-3">
                  {{ $karyawans->appends(request()->query())->links('vendor.pagination.bootstrap-5') }}
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
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Konfirmasi delete
    const deleteButtons = document.querySelectorAll('.btn-delete');
    deleteButtons.forEach(button => {
      button.addEventListener('click', function() {
        const nik = this.dataset.nik;
        const nama = this.dataset.nama;
            
        Swal.fire({
          title: 'Konfirmasi Hapus',
          text: `Apakah Anda yakin ingin menghapus data karyawan ${nama}?`,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Ya, Hapus!',
          cancelButtonText: 'Batal'
        }).then((result) => {
          if (result.isConfirmed) {
            // Buat form delete dinamis
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/data-master/karyawan/${nik}`;
                    
            // Tambahkan CSRF token
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            form.appendChild(csrf);
                    
            // Tambahkan method spoofing untuk DELETE
            const method = document.createElement('input');
            method.type = 'hidden';
            method.name = '_method';
            method.value = 'DELETE';
            form.appendChild(method);
                    
            // Submit form
            document.body.appendChild(form);
            form.submit();
          }
        });
      });
    });
  });
</script>
@endpush
