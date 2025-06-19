{{-- Informasi jam kerja kantor --}}
@if ($kantor)
  <div class="alert alert-info mb-3" style="border-radius: 12px;">
    <div class="text-center">
      <div class="d-flex justify-content-center align-items-center mb-2">
        <ion-icon name="time-outline" class="me-2" style="font-size: 18px;"></ion-icon>
        <strong style="font-size: 15px;">Jam Kerja Kantor</strong>
      </div>
      <div class="badge bg-primary text-white" style="font-size: 14px; padding: 8px 16px;">
        {{ date('H:i', strtotime($kantor->jam_masuk)) }} - {{ date('H:i', strtotime($kantor->jam_keluar)) }}
      </div>
      <br>
      <small class="text-muted mt-2 d-block" style="font-size: 12px;">
        <ion-icon name="checkmark-circle-outline" class="me-1"></ion-icon>
        Tepat waktu: masuk sebelum {{ date('H:i', strtotime($kantor->jam_masuk)) }}
      </small>
    </div>
  </div>
@endif

{{-- Custom CSS untuk mobile responsiveness --}}
<style>
  /* Reset margin dan padding untuk konsistensi */
  .calendar-container {
    padding-bottom: 100px; /* Tambah ruang di bawah untuk scroll */
  }
  
  /* Grid kalender yang rapi */
  .calendar-grid {
    margin: 0 -2px;
  }
  
  .calendar-grid .row {
    margin: 0;
    gap: 0;
  }
  
  .calendar-grid .col {
    padding: 2px;
    flex: 1;
    max-width: calc(100% / 7);
  }
  
  /* Kalender day styling */
  .calendar-day {
    width: 100%;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    border-radius: 8px;
    border: none;
    margin: 0;
    padding: 4px;
    font-size: 14px;
    font-weight: bold;
    transition: all 0.2s ease;
  }
  
  .calendar-day .day-number {
    font-size: 16px;
    font-weight: bold;
    line-height: 1;
    margin-bottom: 2px;
  }
  
  .calendar-day .day-status {
    font-size: 9px;
    line-height: 1;
    white-space: nowrap;
    overflow: hidden;
  }
  
  /* Responsive untuk mobile */
  @media (max-width: 480px) {
    .card {
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      margin-bottom: 16px;
    }
    
    .card-body {
      padding: 16px;
    }
    
    .listview .item {
      padding: 16px;
      border-bottom: 1px solid #f0f0f0;
    }
    
    .avatar.avatar-60 {
      width: 50px;
      height: 50px;
    }
    
    .badge {
      border-radius: 8px;
      padding: 6px 12px;
    }
    
    .card-header h6 {
      font-size: 16px;
    }
    
    /* Kalender mobile optimizations */
    .calendar-day {
      height: 45px;
      min-height: 45px;
    }
    
    .calendar-day .day-number {
      font-size: 14px;
    }
    
    .calendar-day .day-status {
      font-size: 8px;
    }
    
    /* Legend responsive */
    .legend-item {
      margin-bottom: 8px;
    }
    
    .legend-icon {
      width: 30px;
      height: 30px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 6px;
    }
    
    /* Detail list optimizations */
    .detail-list {
      margin-bottom: 20px;
    }
    
    /* Perbaiki scroll issue */
    body {
      padding-bottom: 60px;
    }
    
    .row {
      margin-left: 0;
      margin-right: 0;
    }
  }
  
  /* Header hari dalam seminggu */
  .calendar-header .col {
    padding: 8px 2px;
    text-align: center;
    font-weight: bold;
    font-size: 12px;
  }
  
  /* Status colors */
  .status-hadir {
    background-color: #28a745;
    color: white;
  }
  
  .status-alpha {
    background-color: #ffc107;
    color: #212529;
  }
  
  .status-libur-sabtu {
    background-color: #17a2b8;
    color: white;
  }
  
  .status-libur-minggu {
    background-color: #dc3545;
    color: white;
  }
</style>

{{-- Kalender Presensi --}}
@if (isset($calendarData) && count($calendarData) > 0)
  <div class="calendar-container">
    <div class="card mb-3">
      <div class="card-header bg-primary text-white">
        <h6 class="mb-0 text-center">
          <ion-icon name="calendar-outline" class="me-2"></ion-icon>
          {{ $months[$month] ?? 'Bulan' }} {{ $year }}
        </h6>
      </div>
      <div class="card-body p-3">
        {{-- Header hari --}}
        <div class="calendar-header">
          <div class="row">
            <div class="col text-danger">Min</div>
            <div class="col">Sen</div>
            <div class="col">Sel</div>
            <div class="col">Rab</div>
            <div class="col">Kam</div>
            <div class="col">Jum</div>
            <div class="col text-primary">Sab</div>
          </div>
        </div>

              {{-- Generate calendar grid --}}
        @php
          $startOfMonth = date('w', strtotime("$year-$month-01")); // Hari pertama bulan (0=Minggu)
          $calendar = collect($calendarData);
          $weeks = [];
          $currentWeek = [];
          
          // Tambah empty cells untuk hari sebelum tanggal 1
          for ($i = 0; $i < $startOfMonth; $i++) {
              $currentWeek[] = null;
          }
          
          // Tambah semua tanggal
          foreach ($calendar as $dayData) {
              $currentWeek[] = $dayData;
              
              // Jika sudah 7 hari (Minggu-Sabtu), mulai week baru
              if (count($currentWeek) == 7) {
                  $weeks[] = $currentWeek;
                  $currentWeek = [];
              }
          }
          
          // Tambah empty cells untuk sisa hari
          while (count($currentWeek) < 7 && count($currentWeek) > 0) {
              $currentWeek[] = null;
          }
          if (count($currentWeek) > 0) {
              $weeks[] = $currentWeek;
          }
        @endphp

        {{-- Tampilkan calendar grid --}}
        <div class="calendar-grid">
          @foreach ($weeks as $week)
            <div class="row">
              @foreach ($week as $dayData)
                <div class="col">
                  @if ($dayData)
                    {{-- Tentukan class berdasarkan status --}}
                    @php
                      $statusClass = '';
                      $statusText = '';
                      
                      switch($dayData['status']) {
                          case 'hadir':
                              $statusClass = 'status-hadir';
                              $jamMasuk = substr($dayData['presensi']->jam_masuk, 0, 5); // HH:MM
                              $statusText = $jamMasuk;
                              break;
                          case 'libur_minggu':
                              $statusClass = 'status-libur-minggu';
                              $statusText = '';
                              break;
                          case 'libur_sabtu':
                              $statusClass = 'status-libur-sabtu';
                              $statusText = '';
                              break;
                          case 'tidak_hadir':
                              $statusClass = 'status-alpha';
                              $statusText = '';
                              break;
                      }
                    @endphp
                    
                    <div class="calendar-day {{ $statusClass }}">
                      <div class="day-number">{{ $dayData['day'] }}</div>
                      @if($statusText)
                        <div class="day-status">{{ $statusText }}</div>
                      @endif
                    </div>
                  @else
                    {{-- Empty cell --}}
                    <div class="calendar-day" style="visibility: hidden;"></div>
                  @endif
                </div>
              @endforeach
            </div>
          @endforeach
        </div>
      </div>
    </div>

    {{-- Legend --}}
    <div class="card mb-3">
      <div class="card-header bg-light">
        <h6 class="mb-0 text-center">
          <ion-icon name="information-circle-outline" class="me-2"></ion-icon>
          Keterangan
        </h6>
      </div>
      <div class="card-body p-3">
        <div class="row">
          <div class="col-6 legend-item">
            <div class="d-flex align-items-center">
              <div class="legend-icon status-hadir me-3">
                <ion-icon name="checkmark-outline"></ion-icon>
              </div>
              <span>Hadir</span>
            </div>
          </div>
          <div class="col-6 legend-item">
            <div class="d-flex align-items-center">
              <div class="legend-icon status-alpha me-3">
                <ion-icon name="close-outline"></ion-icon>
              </div>
              <span>Alpha</span>
            </div>
          </div>
          <div class="col-6 legend-item">
            <div class="d-flex align-items-center">
              <div class="legend-icon status-libur-sabtu me-3">
                <ion-icon name="home-outline"></ion-icon>
              </div>
              <span>Sabtu</span>
            </div>
          </div>
          <div class="col-6 legend-item">
            <div class="d-flex align-items-center">
              <div class="legend-icon status-libur-minggu me-3">
                <ion-icon name="home-outline"></ion-icon>
              </div>
              <span>Minggu</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endif

{{-- Tampilkan pesan jika data riwayat kosong --}}
@if ($history->isEmpty())
  <div class="alert alert-warning">
    <p>Data Tidak Tersedia</p>
  </div>
@endif

{{-- Daftar riwayat presensi detail --}}
@if (!$history->isEmpty())
  <div class="detail-list">
    <div class="card">
      <div class="card-header bg-secondary text-white">
        <h6 class="mb-0 text-center">
          <ion-icon name="list-outline" class="me-2"></ion-icon>
          Detail Presensi
        </h6>
      </div>
      <div class="card-body p-0">
        <ul class="listview image-listview">
        @foreach ($history as $item)
    <li>
      <div class="item">

        {{-- Ambil URL gambar foto masuk --}}
        @php
          $fotoMasuk = Storage::url('uploads/absention/' . $item->foto_masuk);
          
          // Ambil jam masuk kantor untuk perbandingan
          if ($kantor && $kantor->jam_masuk) {
              $jamMasukKantor = is_string($kantor->jam_masuk) ? 
                  $kantor->jam_masuk : 
                  $kantor->jam_masuk->format('H:i:s');
          } else {
              $jamMasukKantor = '07:00:00';
          }
          
          // Pastikan perbandingan menggunakan format waktu yang benar
          $jamMasukKaryawan = substr($item->jam_masuk, 0, 8); // Ambil H:i:s saja
          $jamMasukKantorStr = substr($jamMasukKantor, 0, 8); // Ambil H:i:s saja
          
          $isTepatWaktu = $jamMasukKaryawan < $jamMasukKantorStr;
          
          // Hitung selisih waktu jika terlambat
          $selisihWaktu = '';
          if (!$isTepatWaktu) {
              $timeKaryawan = strtotime($jamMasukKaryawan);
              $timeKantor = strtotime($jamMasukKantorStr);
              $selisihDetik = $timeKaryawan - $timeKantor;
              
              $jam = floor($selisihDetik / 3600);
              $menit = floor(($selisihDetik % 3600) / 60);
              
              if ($jam > 0) {
                  $selisihWaktu = $jam . ' jam ' . $menit . ' menit';
              } else {
                  $selisihWaktu = $menit . ' menit';
              }
          }
        @endphp

        {{-- Gambar foto masuk --}}
        <img src="{{ url($fotoMasuk) }}" alt="Foto Masuk" class="image">

        {{-- Informasi presensi (tanggal, jam masuk, jam keluar) --}}
        <div class="in d-flex justify-content-between align-items-center w-100">

          {{-- Kolom kiri: Tanggal --}}
          <div class="text-start" style="flex: 1;">
            <b>{{ date('d-m-Y', strtotime($item->tanggal_presensi)) }}</b>
          </div>

          {{-- Kolom tengah: Jam Masuk dengan status kedisiplinan --}}
          <div class="text-center" style="flex: 1;">
            <span class="badge {{ $isTepatWaktu ? 'bg-success' : 'bg-danger' }}">
              {{ $item->jam_masuk }}
            </span>
            <br>
            <small class="text-muted">
              @if($isTepatWaktu)
                Tepat Waktu
              @else
                Terlambat<br>{{ $selisihWaktu }}
              @endif
            </small>
          </div>

          {{-- Kolom kanan: Jam Keluar (tampilkan '-' jika null) --}}
          <div class="text-end" style="flex: 1;">
            <span class="badge bg-primary">
              {{ $item->jam_keluar ?? '-' }}
            </span>
          </div>

        </div>
        </div>
      </li>
    @endforeach
          </ul>
      </div>
    </div>
  </div>
@endif
