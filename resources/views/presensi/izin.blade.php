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
    <div class="pageTitle">Data Izin / Sakit</div>
    <div class="right"></div>
  </div>
  <!-- * App Header -->
@endsection

@section('content')

  <div class="row" style="margin-top: 70px">
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
          </ul>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
      </div>
      @endif
    </div>
  </div>

  <div class="row">
    <div class="col">
      <form method="GET" action="{{ route('presensi.izin') }}">

        <!-- Dropdown -->
        <div class="row mb-3">
          <div class="col">
            <div class="dropdown">
              <button class="btn btn-primary dropdown-toggle" type="button" id="filterDropdown" data-toggle="dropdown" aria-expanded="false">
                Filter Berdasarkan
              </button>
              <div class="dropdown-menu" aria-labelledby="dropdownFilterButton">
                <a href="#" class="dropdown-item" data-filter="tanggal">Tanggal</a>
                <a href="#" class="dropdown-item" data-filter="status">Status</a>
                <a href="#" class="dropdown-item" data-filter="persetujuan">Persetujuan</a>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Filter: Tanggal -->
        <div class="filter-form" id="formFilterTanggal" style="display: none;">
          <div class="row">
            <div class="col">
              <div class="form-group">
                <label for="startDate">Dari:</label>
                <input type="text" name="startDate" class="form-control datepicker" placeholder="Dari Tanggal" value="{{ request('startDate') }}">
              </div>
            </div>
          </div>   
          <div class="row">
            <div class="col">
              <div class="form-group">
                <label for="endDate">Sampai:</label>
                <input type="text" name="endDate" class="form-control datepicker" placeholder="Sampai Tanggal" value="{{ request('endDate') }}">
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-primary">Filter</button>
              </div>
            </div>
          </div>
        </div>

        <!-- Filter: Status -->
        <div class="filter-form" id="formFilterStatus" style="display: none;">
          <div class="row">
            <div class="col">
              <div class="form-group" >
                <label>Status:</label>
                <select class="form-control" name="status">
                  <option value="">Pilih Status</option>
                  <option value="i" {{ request('status') == 'i' ? 'selected' : '' }}>Izin</option>
                  <option value="s" {{ request('status') == 's' ? 'selected' : '' }}>Sakit</option>
                </select>
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-primary">Filter</button>
              </div>
            </div>
          </div>
        </div>

        <!-- Filter: Approval -->
        <div class="filter-form" id="formFilterApproval" style="display: none;">
          <div class="row">
            <div class="col">
              <div class="form-group">
                <label>Status Persetujuan:</label>
                <select class="form-control" name="approval">
                  <option value="">Pilih Status</option>
                  <option value="1" {{ request('approval') == '1' ? 'selected' : '' }}>Disetujui</option>
                  <option value="2" {{ request('approval') == '2' ? 'selected' : '' }}>Ditolak</option>
                  <option value="0" {{ request('approval') == '0' ? 'selected' : '' }}>Menunggu</option>
                </select>
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-primary">Filter</button>
              </div>
            </div>
          </div>
        </div>

      </form>  
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-bordered table-hover" >
      <thead>
        <tr>
          <th class="text-center">Tanggal</th>
          <th class="text-center">Status</th>
          <th class="text-center">Keterangan</th>
          <th class="text-center">Status Approval</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($dataIzin as $izin)
          <tr>
            <td class="text-center">{{ \Carbon\Carbon::parse($izin->tanggal_izin)->format('d-m-Y') }}</td>
            <td class="text-center">{{ $izin->status == 'i' ? 'Izin' : 'Sakit' }}</td>
            <td class="text-center">{{ $izin->keterangan }}</td>
            <td class="text-center">
              @if ($izin->status_approved == 1)
                <span class="badge badge-success">Disetujui</span>
              @elseif ($izin->status_approved == 2)
                <span class="badge badge-danger">Ditolak</span>
              @else
                <span class="badge badge-warning">Menunggu</span>
              @endif
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="4" class="text-center" class="text-center">Belum ada data izin atau sakit</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>  

  <div class="fab-button bottom-right" style="margin-bottom: 70px">
    <a href="{{ route('presensi.create.izin') }}" class="fab">
      <ion-icon name="add-outline"></ion-icon>
    </a>
  </div>
@endsection

@push('myScript')
<script>
  
  $(document).ready(function () {
    $('.dropdown-item').on('click', function (e) {
      e.preventDefault();
      const filter = $(this).data('filter');

      // Sembunyikan semua form filter dan reset nilai filter sebelumnya
      $('.filter-form').hide();
      $('select[name="status"], select[name="approval"], input[name="startDate"], input[name="endDate"]').val(''); // reset semua input filter

      // Tampilkan form berdasarkan filter yang dipilih
      if (filter === 'tanggal') {
        $('#formFilterTanggal').show();
      } else if (filter === 'status') {
        $('#formFilterStatus').show();
      } else if (filter === 'persetujuan') {
        $('#formFilterApproval').show();
      }

      $('#filterDropdown').text('Filter Berdasarkan: ' + $(this).text());
    });
  });

  flatpickr(".datepicker", {
    dateFormat: "d-m-Y",
  });
</script>
@endpush
