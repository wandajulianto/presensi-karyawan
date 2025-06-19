@extends('admin.layouts.tabler')

@section('content')
<div class="page-header d-print-none">
  <div class="container-xl">
    <div class="row g-2 align-items-center">
      <div class="col">
        <h2 class="page-title">
          Profil Admin
        </h2>
        <div class="text-muted mt-1">Kelola informasi profil Anda</div>
      </div>
    </div>
  </div>
</div>

<div class="page-body">
  <div class="container-xl">
    <div class="row row-deck row-cards">
      <div class="col-12">
        <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="card">
          @csrf
          @method('PUT')
          
          <div class="card-header">
            <h3 class="card-title">Informasi Profil</h3>
          </div>
          
          <div class="card-body">
            {{-- Notifikasi --}}
            @if (session('success'))
              <div class="alert alert-success alert-dismissible" role="alert">
                <div class="d-flex">
                  <div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                      <path d="M5 12l5 5l10 -10" />
                    </svg>
                  </div>
                  <div>{{ session('success') }}</div>
                </div>
                <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
              </div>
            @endif

            @if (session('error'))
              <div class="alert alert-danger alert-dismissible" role="alert">
                <div class="d-flex">
                  <div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                      <path d="M10.29 3.86l1.71 .71l1.71 -.71a1 1 0 0 1 1.39 .928l-.1 1.98l1.73 .98a1 1 0 0 1 .39 1.31l-1.09 1.77l1.09 1.77a1 1 0 0 1 -.39 1.31l-1.73 .98l.1 1.98a1 1 0 0 1 -1.39 .928l-1.71 -.71l-1.71 .71a1 1 0 0 1 -1.39 -.928l.1 -1.98l-1.73 -.98a1 1 0 0 1 -.39 -1.31l1.09 -1.77l-1.09 -1.77a1 1 0 0 1 .39 -1.31l1.73 -.98l-.1 -1.98a1 1 0 0 1 1.39 -.928z" />
                      <path d="M9 12l2 2l4 -4" />
                    </svg>
                  </div>
                  <div>{{ session('error') }}</div>
                </div>
                <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
              </div>
            @endif

            <div class="row">
              <div class="col-md-4">
                {{-- Foto Profil --}}
                <div class="mb-3">
                  <label class="form-label">Foto Profil</label>
                  <div class="text-center">
                    <div class="avatar avatar-xl mb-3" id="preview-container">
                      <img src="{{ $user->foto_profile }}" alt="Foto Profil" class="avatar-img" id="foto-preview">
                    </div>
                    <input type="file" class="form-control @error('foto') is-invalid @enderror" name="foto" id="foto" accept=".jpg,.jpeg,.png">
                    @error('foto')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-hint">Format: JPG, JPEG, PNG. Maksimal 2MB.</small>
                  </div>
                </div>
              </div>

              <div class="col-md-8">
                <div class="row">
                  {{-- Nama --}}
                  <div class="col-12">
                    <div class="mb-3">
                      <label class="form-label required">Nama Lengkap</label>
                      <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" 
                             value="{{ old('name', $user->name) }}" placeholder="Masukkan nama lengkap">
                      @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>

                  {{-- Email --}}
                  <div class="col-12">
                    <div class="mb-3">
                      <label class="form-label required">Email</label>
                      <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" 
                             value="{{ old('email', $user->email) }}" placeholder="Masukkan email">
                      @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>

                  {{-- Password --}}
                  <div class="col-md-6">
                    <div class="mb-3">
                      <label class="form-label">Password Baru</label>
                      <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" 
                             placeholder="Kosongkan jika tidak ingin mengubah" id="password-input" autocomplete="new-password">
                      @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                      <small class="form-hint">Kosongkan atau biarkan jika tidak ingin mengubah password</small>
                    </div>
                  </div>

                  {{-- Konfirmasi Password --}}
                  <div class="col-md-6" id="password-confirm-group" style="display: none;">
                    <div class="mb-3">
                      <label class="form-label">Konfirmasi Password</label>
                      <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                             name="password_confirmation" placeholder="Ulangi password baru" id="password-confirm-input" autocomplete="new-password">
                      @error('password_confirmation')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                      <small class="form-hint">Wajib diisi jika mengubah password</small>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="card-footer text-end">
            <a href="{{ route('dashboard.admin') }}" class="btn btn-outline-secondary me-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <path d="M11 7h-5a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-5" />
                <path d="M10 14l4 -4" />
                <path d="M15 6l4 4l-6 6h-4v-4z" />
              </svg>
              Batal
            </a>
            <button type="submit" class="btn btn-primary">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <path d="M5 12l5 5l10 -10" />
              </svg>
              Simpan Perubahan
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Preview foto
    const fotoInput = document.getElementById('foto');
    const fotoPreview = document.getElementById('foto-preview');
    
    fotoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                fotoPreview.src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });

    // Toggle konfirmasi password
    const passwordInput = document.getElementById('password-input');
    const passwordConfirmGroup = document.getElementById('password-confirm-group');
    const passwordConfirmInput = document.getElementById('password-confirm-input');
    
    passwordInput.addEventListener('input', function() {
        if (this.value.trim() !== '') {
            passwordConfirmGroup.style.display = 'block';
            passwordConfirmInput.required = true;
        } else {
            passwordConfirmGroup.style.display = 'none';
            passwordConfirmInput.required = false;
            passwordConfirmInput.value = '';
        }
    });

    // Cek jika ada error pada password confirmation, tampilkan field
    @if ($errors->has('password_confirmation'))
        passwordConfirmGroup.style.display = 'block';
        passwordConfirmInput.required = true;
    @endif
});
</script>
@endsection 