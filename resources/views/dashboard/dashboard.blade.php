@extends('layouts.presensi')

@section('content')

<!-- User Section -->
<div class="section" id="user-section">
    <div id="user-detail">
        <div class="avatar">
            <img src="assets/img/sample/avatar/avatar1.jpg" alt="avatar" class="imaged w64 rounded">
        </div>
        <div id="user-info">
            <h2 id="user-name">Adam Abdi Al A'la</h2>
            <span id="user-role">Head of IT</span>
        </div>
    </div>
</div>

<!-- Menu Section -->
<div class="section" id="menu-section">
    <div class="card">
        <div class="card-body text-center">
            <div class="list-menu">

                @php
                    $menus = [
                        ['icon' => 'person-sharp', 'label' => 'Profil', 'class' => 'green'],
                        ['icon' => 'calendar-number', 'label' => 'Cuti', 'class' => 'danger'],
                        ['icon' => 'document-text', 'label' => 'Histori', 'class' => 'warning'],
                        ['icon' => 'location', 'label' => 'Lokasi', 'class' => 'orange'],
                    ];
                @endphp

                @foreach ($menus as $menu)
                    <div class="item-menu text-center">
                        <div class="menu-icon">
                            <a href="#" class="{{ $menu['class'] }}" style="font-size: 40px;">
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

<!-- Presence Section -->
<div class="section mt-2" id="presence-section">
    <div class="todaypresence">
        <div class="row">
            <!-- Masuk Card -->
            <div class="col-6">
                <div class="card gradasigreen">
                    <div class="card-body">
                        <div class="presencecontent">
                            <div class="iconpresence">
                                @if ($todayPresention)
                                    @php
                                        $fotoMasuk = Storage::url('uploads/absention/' . $todayPresention->foto_masuk);
                                    @endphp
                                    <img src="{{ url($fotoMasuk) }}" alt="Foto Masuk" class="imaged w64">
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

            <!-- Pulang Card -->
            <div class="col-6">
                <div class="card gradasired">
                    <div class="card-body">
                        <div class="presencecontent">
                            <div class="iconpresence">
                                @if ($todayPresention && $todayPresention->jam_keluar)
                                    @php
                                        $fotoKeluar = Storage::url('uploads/absention/' . $todayPresention->foto_keluar);
                                    @endphp
                                    <img src="{{ url($fotoKeluar) }}" alt="Foto Pulang" class="imaged w64">
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

    <!-- Tabs: Bulan Ini & Leaderboard -->
    <div class="presencetab mt-2">
        <div class="tab-pane fade show active" id="pilled" role="tabpanel">
            <ul class="nav nav-tabs style1" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#home" role="tab">Bulan Ini</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#profile" role="tab">Leaderboard</a>
                </li>
            </ul>
        </div>

        <div class="tab-content mt-2" style="margin-bottom:100px;">
            <!-- Tab: Bulan Ini -->
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
                                    <span class="badge badge-danger">
                                        {{ $item->jam_keluar ?? 'Belum Absen' }}
                                    </span>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Tab: Leaderboard -->
            <div class="tab-pane fade" id="profile" role="tabpanel">
                <ul class="listview image-listview">
                    @php
                        $leaders = [
                            ['name' => 'Edward Lindgren', 'role' => 'Designer'],
                            ['name' => 'Emelda Scandroot', 'badge' => '3'],
                            ['name' => 'Henry Bove'],
                            ['name' => 'Henry Bove'],
                            ['name' => 'Henry Bove'],
                        ];
                    @endphp

                    @foreach ($leaders as $leader)
                        <li>
                            <div class="item">
                                <img src="assets/img/sample/avatar/avatar1.jpg" alt="image" class="image">
                                <div class="in">
                                    <div>{{ $leader['name'] }}</div>
                                    @if (isset($leader['role']))
                                        <span class="text-muted">{{ $leader['role'] }}</span>
                                    @elseif (isset($leader['badge']))
                                        <span class="badge badge-primary">{{ $leader['badge'] }}</span>
                                    @endif
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
