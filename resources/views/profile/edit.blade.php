@extends('layouts.presensi')

@section('header')
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="arrow-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Edit Profile</div>
    <div class="rig"></div>
</div>
@endsection

@section('content')
<form action="{{ route('profile.update', $karyawan->nik) }}" method="POST" enctype="multipart/form-data" style="margin-top: 4rem">
    @csrf
    @method('PUT')

    <div class="col">
      {{-- Tampilkan pesan sukses --}}
      @if (session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session('success') }}
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
      </div>
      @endif

      {{-- Tampilkan error validasi --}}
      @if ($errors->any())
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <ul class="mb-0">
              @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
              @endforeach
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
          </ul>
      </div>
      @endif
    </div>

    <div class="col">
        <div class="form-group boxed">
            <div class="input-wrapper">
                <input type="text" class="form-control" value="{{ old('nama_lengkap', $karyawan->nama_lengkap) }}" name="nama_lengkap" placeholder="Nama Lengkap" autocomplete="off">
            </div>
        </div>

        <div class="form-group boxed">
            <div class="input-wrapper">
                <input type="text" class="form-control" value="{{ old('no_hp', $karyawan->no_hp) }}" name="no_hp" placeholder="No. HP" autocomplete="off">
            </div>
        </div>

        <div class="form-group boxed">
            <div class="input-wrapper">
                <input type="password" class="form-control" name="password" placeholder="Password (opsional)" autocomplete="off">
            </div>
        </div>

        <div class="form-group boxed">
            <div class="input-wrapper">
                <input type="password" class="form-control" name="password_confirmation" placeholder="Konfirmasi Password" autocomplete="off">
            </div>
        </div>

        <div class="custom-file-upload" id="fileUpload1">
            <input type="file" name="foto" id="fileuploadInput" accept=".png, .jpg, .jpeg">
            <label for="fileuploadInput">
                <span>
                    <strong>
                        <ion-icon name="cloud-upload-outline"></ion-icon>
                        <i>Tap to Upload Foto</i>
                    </strong>
                </span>
            </label>
        </div>

        <div class="form-group boxed">
            <div class="input-wrapper">
                <button type="submit" class="btn btn-primary btn-block">
                    <ion-icon name="refresh-outline"></ion-icon>
                    Update
                </button>
            </div>
        </div>
    </div>
</form>
@endsection
