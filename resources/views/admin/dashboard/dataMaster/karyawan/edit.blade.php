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
            Edit Data Karyawan
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
              <form action="{{ route('data-master.karyawan.update', $karyawan->nik) }}" method="POST" enctype="multipart/form-data" id="formEditKaryawan">
                @csrf
                @method('PUT')
                <div class="mb-3">
                  <label for="nik" class="form-label">NIK</label>
                  <div class="input-icon mb-3">
                    <span class="input-icon-addon">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-barcode"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7v-1a2 2 0 0 1 2 -2h2" /><path d="M4 17v1a2 2 0 0 0 2 2h2" /><path d="M16 4h2a2 2 0 0 1 2 2v1" /><path d="M16 20h2a2 2 0 0 0 2 -2v-1" /><path d="M5 11h1v2h-1z" /><path d="M10 11l0 2" /><path d="M14 11h1v2h-1z" /><path d="M19 11l0 2" /></svg>
                    </span>
                    <input type="number" class="form-control" id="nik" value="{{ $karyawan->nik }}" disabled>
                  </div>
                </div>
                <div class="mb-3">
                  <label for="nama_lengkap" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                  <div class="input-icon mb-3">
                    <span class="input-icon-addon">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                    </span>
                    <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="{{ $karyawan->nama_lengkap }}" placeholder="Masukkan Nama Lengkap" required>
                  </div>
                </div>
                <div class="mb-3">
                  <label for="jabatan" class="form-label">Jabatan <span class="text-danger">*</span></label>
                  <div class="input-icon mb-3">
                    <span class="input-icon-addon">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-info-circle"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" /><path d="M12 9h.01" /><path d="M11 12h1v4h1" /></svg>
                    </span>
                    <input type="text" class="form-control" id="jabatan" name="jabatan" value="{{ $karyawan->jabatan }}" placeholder="Masukkan Jabatan" required>
                  </div>
                </div>
                <div class="mb-3">
                  <label for="no_hp" class="form-label">No. HP <span class="text-danger">*</span></label>
                  <div class="input-icon mb-3">
                    <span class="input-icon-addon">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-phone"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2" /></svg>
                    </span>
                    <input type="text" class="form-control" id="no_hp" name="no_hp" value="{{ $karyawan->no_hp }}" placeholder="Masukkan No. HP" required>
                  </div>
                </div>
                <div class="mb-3">
                  <label class="form-label">Departemen</label>
                  <select class="form-select" name="kode_departemen">
                    <option value="">Pilih Departemen</option>
                    @foreach ($departemens as $dept)
                      <option value="{{ $dept->kode_departemen }}" {{ $karyawan->kode_departemen == $dept->kode_departemen ? 'selected' : '' }}>
                        {{ $dept->nama_departemen }}
                      </option>
                    @endforeach
                  </select>
                </div>
                <div class="mb-3">
                  <label class="form-label">Foto</label>
                  <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                  @if($karyawan->foto)
                    <div class="mt-2">
                      <img src="{{ Storage::url('uploads/foto_karyawan/'.$karyawan->foto) }}" alt="Foto Karyawan" class="avatar w-10 h-10">
                    </div>
                  @endif
                </div>
                <div class="form-footer">
                  <a href="{{ route('data-master.karyawan') }}" class="btn btn-link">Kembali</a>
                  <button type="submit" class="btn btn-primary" id="btnUpdate">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-device-floppy"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M14 4l0 4l-6 0l0 -4" /></svg>
                    Update Data
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
    $('#btnUpdate').click(function() {
      // Validasi form
      let nama_lengkap = $('#nama_lengkap').val();
      let jabatan = $('#jabatan').val();
      let no_hp = $('#no_hp').val();
      
      if (nama_lengkap === '') {
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Nama Lengkap tidak boleh kosong!',
        });
        $('#nama_lengkap').focus();
        return false;
      }
      
      if (jabatan === '') {
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Jabatan tidak boleh kosong!',
        });
        $('#jabatan').focus();
        return false;
      }
      
      if (no_hp === '') {
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'No. HP tidak boleh kosong!',
        });
        $('#no_hp').focus();
        return false;
      }
      
      // Jika semua validasi berhasil, submit form
      $('#formEditKaryawan').submit();
    });
  });
</script>
@endpush 