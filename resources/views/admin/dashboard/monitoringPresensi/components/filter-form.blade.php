<form action="" method="GET">
  <div class="row">
    <div class="col-md-4">
      <div class="input-icon mb-3">
        <span class="input-icon-addon">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-calendar"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z" /><path d="M16 3v4" /><path d="M8 3v4" /><path d="M4 11h16" /><path d="M11 15h1" /><path d="M12 15v3" /></svg>
        </span>
        <input type="text" class="form-control flatpickr" id="tanggal" name="tanggal" value="{{ request('tanggal') }}" placeholder="Pilih Tanggal Presensi" autocomplete="off">
      </div>
    </div>
    <div class="col-md-4">
      <div class="input-icon mb-3">
        <span class="input-icon-addon">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
        </span>
        <input type="text" class="form-control" name="nama_lengkap" value="{{ request('nama_lengkap') }}" placeholder="Cari Nama Karyawan">
      </div>
    </div>
    <div class="col-md-4">
      <div class="input-icon mb-3">
        <select name="departemen" class="form-select">
          <option value="">Semua Departemen</option>
          @foreach ($departemens as $departemen)
            <option value="{{ $departemen->kode_departemen }}" {{ request('departemen') == $departemen->kode_departemen ? 'selected' : '' }}>
              {{ $departemen->nama_departemen }}
            </option>
          @endforeach
        </select>
      </div>
    </div>
    <div class="col-md-12">
      <button type="submit" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-search"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
        Cari
      </button>
      <div class="btn-group ms-2" role="group">
        <a href="{{ route('dashboard.admin.monitoring-presensi.export-keterlambatan', request()->query()) }}" class="btn btn-success">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-download">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2"/>
            <path d="M7 11l5 5l5 -5"/>
            <path d="M12 4l0 12"/>
          </svg>
          Export CSV
        </a>
        <a href="{{ route('dashboard.admin.monitoring-presensi.cetak-keterlambatan', request()->query()) }}" class="btn btn-danger">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file-type-pdf">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M14 3v4a1 1 0 0 0 1 1h4"/>
            <path d="M5 12v-7a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2h-8.5"/>
            <path d="M2 15a1 1 0 0 1 1 -1h1a1 1 0 0 1 1 1v1a1 1 0 0 1 -1 1h-1a1 1 0 0 1 -1 -1z"/>
            <path d="M2 21v-2"/>
            <path d="M5 21v-2"/>
          </svg>
          Export PDF
        </a>
      </div>
    </div>
  </div>
</form> 