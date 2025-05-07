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
      <div class="row row-deck row-cards">
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-vcenter table-striped">
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
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $karyawan->nik }}</td>
                        <td>{{ $karyawan->nama_lengkap }}</td>
                        <td>{{ $karyawan->jabatan }}</td>
                        <td>{{ $karyawan->no_hp }}</td>
                        <td><img src="{{ $foto }}" alt="Foto Karyawan" class="avatar w-10 h-10"></td>
                        <td>{{ $karyawan->nama_departemen }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
