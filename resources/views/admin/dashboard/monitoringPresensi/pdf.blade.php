@extends('admin.dashboard.laporan.template.pdf-layout')

@section('title', 'Laporan Monitoring Keterlambatan Karyawan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="text-primary mb-0">LAPORAN MONITORING KETERLAMBATAN KARYAWAN</h5>
    <div class="text-end">
        <div><strong>Periode:</strong> {{ $period ?? 'Semua Periode' }}</div>
        @if(isset($departemen))
        <div><strong>Departemen:</strong> {{ $departemen }}</div>
        @endif
    </div>
</div>

@if(!empty($presensis) && count($presensis) > 0)
<table class="table table-sm table-bordered">
    <thead class="bg-light">
        <tr>
            <th width="5%" class="text-center">No</th>
            <th width="10%" class="text-center">NIK</th>
            <th width="25%">Nama Karyawan</th>
            <th width="20%">Departemen</th>
            <th width="12%" class="text-center">Tanggal</th>
            <th width="10%" class="text-center">Jam Masuk</th>
            <th width="10%" class="text-center">Keterlambatan</th>
            <th width="8%" class="text-center">Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($presensis as $index => $item)
        <tr>
            <td class="text-center">{{ $index + 1 }}</td>
            <td class="text-center">{{ $item->karyawan->nik }}</td>
            <td>{{ $item->karyawan->nama_lengkap }}</td>
            <td>{{ $item->karyawan->departemen->nama_departemen ?? '-' }}</td>
            <td class="text-center">{{ \Carbon\Carbon::parse($item->tanggal_presensi)->translatedFormat('d/m/Y') }}</td>
            <td class="text-center">{{ $item->jam_masuk }}</td>
            <td class="text-center">
                @if($item->jam_masuk > '07:00:00')
                    @php
                        $jamMasuk = \Carbon\Carbon::parse($item->jam_masuk);
                        $jamNormal = \Carbon\Carbon::parse('07:00:00');
                        $terlambat = $jamMasuk->diff($jamNormal);
                    @endphp
                    {{ $terlambat->format('%H:%I') }}
                @else
                    -
                @endif
            </td>
            <td class="text-center">
                @if($item->jam_masuk > '07:00:00')
                    <span class="badge bg-danger text-white">Terlambat</span>
                @else
                    <span class="badge bg-success text-white">Tepat Waktu</span>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- Summary -->
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card border-primary">
            <div class="card-body p-3">
                <h6 class="card-title text-primary mb-2">Ringkasan Data</h6>
                <div class="row text-sm">
                    <div class="col-6">
                        <div><strong>Total Data:</strong></div>
                        <div><strong>Terlambat:</strong></div>
                        <div><strong>Tepat Waktu:</strong></div>
                    </div>
                    <div class="col-6">
                                            <div>{{ count($presensis) }} karyawan</div>
                    <div>{{ collect($presensis)->where('jam_masuk', '>', '07:00:00')->count() }} karyawan</div>
                    <div>{{ collect($presensis)->where('jam_masuk', '<=', '07:00:00')->count() }} karyawan</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-info">
            <div class="card-body p-3">
                <h6 class="card-title text-info mb-2">Persentase</h6>
                @php
                    $totalData = count($presensis);
                    $totalTerlambat = collect($presensis)->where('jam_masuk', '>', '07:00:00')->count();
                    $persentaseTerlambat = $totalData > 0 ? round(($totalTerlambat / $totalData) * 100, 1) : 0;
                    $persentaseTepatWaktu = 100 - $persentaseTerlambat;
                @endphp
                <div class="row text-sm">
                    <div class="col-6">
                        <div><strong>Keterlambatan:</strong></div>
                        <div><strong>Tepat Waktu:</strong></div>
                    </div>
                    <div class="col-6">
                        <div>{{ $persentaseTerlambat }}%</div>
                        <div>{{ $persentaseTepatWaktu }}%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@else
<div class="alert alert-warning">
    <div class="d-flex">
        <div>
            <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <path d="M12 9v4"></path>
                <path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z"></path>
                <path d="M12 16h.01"></path>
            </svg>
        </div>
        <div>
            <h4 class="alert-title">Tidak ada data!</h4>
            <div class="text-muted">Tidak ditemukan data keterlambatan untuk kriteria yang dipilih.</div>
        </div>
    </div>
</div>
@endif

<div class="mt-4 text-muted text-sm">
    <div><strong>Catatan:</strong></div>
    <ul class="mb-0">
        <li>Jam masuk normal: 07:00 WIB</li>
        <li>Karyawan yang masuk setelah jam 07:00 dianggap terlambat</li>
        <li>Data diurutkan berdasarkan tingkat keterlambatan (paling terlambat di atas)</li>
    </ul>
</div>
@endsection 