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
            Tambah Data Departemen
          </h2>
        </div>
      </div>
    </div>
  </div>
  <div class="page-body">
    <div class="container-xl">
      <div class="row row-deck row-cards">
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <form action="{{ route('data-master.departemen.store') }}" method="POST" id="formDepartemen">
                @csrf
                <div class="mb-3">
                  <label for="kode_departemen" class="form-label">Kode Departemen <span class="text-danger">*</span></label>
                  <div class="input-icon mb-3">
                    <span class="input-icon-addon">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-barcode"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7v-1a2 2 0 0 1 2 -2h2" /><path d="M4 17v1a2 2 0 0 0 2 2h2" /><path d="M16 4h2a2 2 0 0 1 2 2v1" /><path d="M16 20h2a2 2 0 0 0 2 -2v-1" /><path d="M5 11h1v2h-1z" /><path d="M10 11l0 2" /><path d="M14 11h1v2h-1z" /><path d="M19 11l0 2" /></svg>
                    </span>
                    <input type="text" class="form-control" id="kode_departemen" name="kode_departemen" placeholder="Masukkan Kode Departemen" required>
                  </div>
                </div>
                <div class="mb-3">
                  <label for="nama_departemen" class="form-label">Nama Departemen <span class="text-danger">*</span></label>
                  <div class="input-icon mb-3">
                    <span class="input-icon-addon">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-building"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 21l18 0" /><path d="M9 8l1 0" /><path d="M9 12l1 0" /><path d="M9 16l1 0" /><path d="M14 8l1 0" /><path d="M14 12l1 0" /><path d="M14 16l1 0" /><path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16" /></svg>
                    </span>
                    <input type="text" class="form-control" id="nama_departemen" name="nama_departemen" placeholder="Masukkan Nama Departemen" required>
                  </div>
                </div>
                <div class="form-footer">
                  <a href="{{ route('data-master.departemen') }}" class="btn btn-link">Kembali</a>
                  <button type="button" class="btn btn-primary" id="btnSimpan">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-device-floppy"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M14 4l0 4l-6 0l0 -4" /></svg>
                    Simpan Data
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('myScript')
<script>
  $(document).ready(function() {
    $('#btnSimpan').click(function() {
      // Validasi form
      let kode_departemen = $('#kode_departemen').val();
      let nama_departemen = $('#nama_departemen').val();
      
      if (kode_departemen === '') {
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Kode Departemen tidak boleh kosong!',
        });
        $('#kode_departemen').focus();
        return false;
      }
      
      if (nama_departemen === '') {
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Nama Departemen tidak boleh kosong!',
        });
        $('#nama_departemen').focus();
        return false;
      }
      
      // Jika semua validasi berhasil, submit form
      $('#formDepartemen').submit();
    });
  });
</script>
@endpush 