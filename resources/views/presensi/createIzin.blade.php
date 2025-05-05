@extends('layouts.presensi')

@section('header')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <!-- App Header -->
  <div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="arrow-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Formulir Permohonan Izin</div>
    <div class="right"></div>
  </div>
  <!-- * App Header -->
@endsection

@section('content')
  <div class="row" style="margin-top: 70px">
    <div class="col">
      <form action="{{ route('presensi.store.izin') }}" method="POST" id="formIzin">
        @csrf

        <div class="row">
          <div class="col">
            <div class="form-group">
              <label for="tanggalIzin">Tanggal</label>
              <input type="text" id="tanggalIzin" name="tanggalIzin" class="form-control" style="font-size: 16px;" />
            </div>
          </div>
        </div>
        
        <div class="row">
          <div class="col">
            <div class="form-group">
              <label for="status">Status (Izin / Sakit)</label>
              <select name="status" id="status" class="form-control custom-select">
                <option value="i">Izin</option>
                <option value="s">Sakit</option>
              </select>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col">
            <div class="form-group">
              <label for="keterangan">Keterangan</label>
              <textarea name="keterangan" id="keterangan" cols="30" rows="5" class="form-control" placeholder="Input Keterangan"></textarea>
            </div>
            <div class="form-group">
              <button class="btn btn-primary w-100">Kirim</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
@endsection

@push('myScript')
  <script>

  flatpickr("#tanggalIzin", {
    dateFormat: "Y/m/d",
    minDate: new Date().fp_incr(-5), // 5 hari ke belakang
    defaultDate: "today"
  });

  // Validasi sebelum submit
  document.getElementById('formIzin').addEventListener('submit', function(e) {
    const tanggal = document.getElementById('tanggalIzin').value.trim();
    const status = document.getElementById('status').value;
    const keterangan = document.getElementById('keterangan').value.trim();

    if (!tanggal || !status || !keterangan) {
      e.preventDefault(); // cegah form dikirim
      Swal.fire({
        icon: 'warning',
        title: 'Oops...',
        text: 'Semua formulir harus diisi!',
        timer: 3000,
        timerProgressBar: true,
      });
    }
  });

  </script>
@endpush