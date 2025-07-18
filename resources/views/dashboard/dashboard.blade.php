@extends('layouts.presensi')

@section('content')

{{-- CSS untuk button rekap --}}
<style>
    .card-rekap-button {
        transition: all 0.3s ease;
        cursor: pointer;
        border: 1px solid #e0e0e0;
    }
    
    .card-rekap-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        border-color: #007bff;
    }
    
    .card-rekap-button:active {
        transform: translateY(0);
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    
    a.text-decoration-none:hover {
        text-decoration: none !important;
    }
    
    .card-rekap-button .card-body:hover ion-icon {
        transform: scale(1.1);
        transition: transform 0.2s ease;
    }
    
    .card-rekap-button .card-body:hover span {
        font-weight: 600;
    }
    
    @media (max-width: 480px) {
        .card-rekap-button {
            margin-bottom: 8px;
        }
        
        .card-rekap-button:hover {
            transform: translateY(-1px);
        }
    }
</style>

<!-- Bagian User -->
<div class="section" id="user-section">
    <div id="user-detail">
        <div class="avatar">
            @php
                $defaultFoto = asset('assets/img/sample/avatar/avatar1.jpg');
                $fotoProfile = $foto ? Storage::url('uploads/foto_karyawan/' . $foto) : $defaultFoto;
            @endphp       
            <img src="{{ $fotoProfile }}" alt="avatar" class="imaged w64" style="height: 60px;">
        </div>       
        <div id="user-info">
            <h2 id="user-name">{{ $fullName }}</h2>
            <span id="user-role">{{ $role }}</span>
        </div>        
    </div>
</div>

<!-- Bagian Menu -->
<div class="section" id="menu-section">
    <div class="card">
        <div class="card-body text-center">
            <div class="list-menu">

                @php
                    // Daftar menu utama
                    $menus = [
                        ['icon' => 'person-sharp', 'label' => 'Profil', 'class' => 'green', 'href' => route('profile')],
                        ['icon' => 'calendar-number', 'label' => 'Izin', 'class' => 'danger', 'href' => route('presensi.izin')],
                        ['icon' => 'document-text', 'label' => 'Riwayat', 'class' => 'warning', 'href' => route('presensi.history')],
                        ['icon' => 'location', 'label' => 'Lokasi', 'class' => 'orange', 'href' => route('presensi.create')],
                    ];
                @endphp

                @foreach ($menus as $menu)
                    <div class="item-menu text-center">
                        <div class="menu-icon">
                            <a href="{{ $menu['href'] }}" class="{{ $menu['class'] }}" style="font-size: 40px;">
                                <ion-icon name="{{ $menu['icon'] }}"></ion-icon>
                            </a>
                        </div>
                        <div class="menu-name">
                            <span class="text-center">{{ $menu['label'] }}</span>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
</div>

<!-- Bagian Presensi Hari Ini -->
<div class="section mt-2" id="presence-section">
    <div class="todaypresence">
        <div class="row">
            <!-- Kartu Masuk -->
            <div class="col-6">
                <div class="card gradasigreen">
                    <div class="card-body">
                        <div class="presencecontent">
                            <div class="iconpresence">
                                @if ($todayPresention)
                                    @php
                                        $fotoMasuk = Storage::url('uploads/absention/' . $todayPresention->foto_masuk);
                                    @endphp
                                    <img src="{{ url($fotoMasuk) }}" alt="Foto Masuk" class="imaged w48">
                                @else
                                    <ion-icon name="camera"></ion-icon>
                                @endif
                            </div>
                            <div class="presencedetail">
                                <h4 class="presencetitle">Masuk</h4>
                                <span>{{ $todayPresention?->jam_masuk ?? 'Belum Absen' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kartu Pulang -->
            <div class="col-6">
                <div class="card gradasired">
                    <div class="card-body">
                        <div class="presencecontent">
                            <div class="iconpresence">
                                @if ($todayPresention && $todayPresention->jam_keluar)
                                    @php
                                        $fotoKeluar = Storage::url('uploads/absention/' . $todayPresention->foto_keluar);
                                    @endphp
                                    <img src="{{ url($fotoKeluar) }}" alt="Foto Pulang" class="imaged w48">
                                @else
                                    <ion-icon name="camera"></ion-icon>
                                @endif
                            </div>
                            <div class="presencedetail">
                                <h4 class="presencetitle">Pulang</h4>
                                <span>{{ $todayPresention?->jam_keluar ?? 'Belum Absen' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rekap Presensi Bulanan -->
    <div id="rekappresence">
        <h3>Rekap Presensi Bulan {{ $monthName }} Tahun {{ $currentYear }}</h3>
        <div class="row">
            @php
                $currentMonth = date('m');
                $currentYear = date('Y');
                
                // Data rekap dengan link yang sesuai
                $rekapData = [
                    [
                        'count' => $recapPresention->totalPresence, 
                        'icon' => 'accessibility-outline', 
                        'label' => 'Hadir', 
                        'color' => 'text-primary',
                        'url' => route('presensi.history') . '?month=' . $currentMonth . '&year=' . $currentYear . '&kehadiran=hadir'
                    ],
                    [
                        'count' => $recapIzin->totalIzin, 
                        'icon' => 'newspaper-outline', 
                        'label' => 'Izin', 
                        'color' => 'text-success',
                        'url' => route('presensi.izin') . '?status=i&startDate=' . date('01-m-Y') . '&endDate=' . date('t-m-Y')
                    ],
                    [
                        'count' => $recapIzin->totalSakit, 
                        'icon' => 'medkit-outline', 
                        'label' => 'Sakit', 
                        'color' => 'text-warning',
                        'url' => route('presensi.izin') . '?status=s&startDate=' . date('01-m-Y') . '&endDate=' . date('t-m-Y')
                    ],
                    [
                        'count' => $recapPresention->totalLate, 
                        'icon' => 'alarm-outline', 
                        'label' => 'Telat', 
                        'color' => 'text-danger',
                        'url' => route('presensi.history') . '?month=' . $currentMonth . '&year=' . $currentYear . '&kehadiran=hadir&status=terlambat'
                    ],
                ];
            @endphp

            @foreach ($rekapData as $data)
                <div class="col-3">
                    <a href="{{ $data['url'] }}" class="text-decoration-none">
                        <div class="card card-rekap-button">
                            <div class="card-body text-center" style="padding: 12px 12px !important; line-height: 0.8rem">
                                <span class="badge bg-danger" style="position: absolute; top: 3px; right: 10px; font-size: 0.6rem; z-index: 999">
                                    {{ $data['count'] }}
                                </span>
                                <ion-icon name="{{ $data['icon'] }}" style="font-size: 1.6rem" class="{{ $data['color'] }} mb-1"></ion-icon>
                                <br>
                                <span style="font-size: 0.8rem; font-weight:500">{{ $data['label'] }}</span>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Tab Presensi Bulan Ini -->
    <div class="presencetab mt-2">
        <div class="tab-pane fade show active" id="pilled" role="tabpanel">
            <ul class="nav nav-tabs style1" role="tablist">
                <li class="nav-item">
                    <span class="nav-link active">{{ $monthName }}</span>
                </li>
            </ul>
        </div>

        <!-- Daftar histori presensi -->
        <div class="tab-content mt-2" style="margin-bottom:100px;">
            <div class="tab-pane fade show active" id="home" role="tabpanel">
                <ul class="listview image-listview">
                    @foreach ($monthlyHistory as $item)
                        @php
                            $fotoMasuk = Storage::url('uploads/absention/' . $item->foto_masuk);
                        @endphp
                        <li>
                            <div class="item">
                                <div class="icon-box bg-primary">
                                    <ion-icon name="finger-print-outline"></ion-icon>
                                </div>
                                <div class="in">
                                    <div>{{ date('d-m-Y', strtotime($item->tanggal_presensi)) }}</div>
                                    <span class="badge badge-success">{{ $item->jam_masuk }}</span>
                                    <span class="badge badge-danger">{{ $item->jam_keluar ?? 'Belum Absen' }}</span>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection
