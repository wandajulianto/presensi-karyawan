<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Laporan' }}</title>
    <style>
        @page {
            margin: 2cm 1.5cm 2cm 1.5cm;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        /* Header / Kop Surat */
        .header {
            border-bottom: 3px solid #0066cc;
            padding-bottom: 15px;
            margin-bottom: 20px;
            position: relative;
        }
        .header-content {
            display: table;
            width: 100%;
        }
        .header-logo {
            display: table-cell;
            width: 80px;
            vertical-align: middle;
            text-align: center;
        }
        .header-logo img {
            width: 70px;
            height: 70px;
            object-fit: contain;
        }
        .header-text {
            display: table-cell;
            vertical-align: middle;
            padding-left: 15px;
        }
        .header-title {
            font-size: 18px;
            font-weight: bold;
            color: #0066cc;
            text-transform: uppercase;
            margin: 0 0 5px 0;
        }
        .header-subtitle {
            font-size: 12px;
            color: #666;
            margin: 2px 0;
        }

        /* Judul Laporan */
        .report-title {
            text-align: center;
            margin: 20px 0;
            padding: 10px 0;
            border-bottom: 2px solid #eee;
        }
        .report-title h1 {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
            color: #333;
        }
        .report-title .period {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }

        /* Tabel */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        table.info-table {
            border: none;
            margin-bottom: 20px;
        }
        table.info-table td {
            border: none;
            padding: 4px 8px;
            font-size: 11px;
        }
        table.info-table .label {
            font-weight: bold;
            width: 25%;
        }

        table.data-table th {
            background-color: #0066cc;
            color: white;
            font-weight: bold;
            padding: 8px 6px;
            border: 1px solid #0055aa;
            font-size: 10px;
            text-align: center;
        }
        table.data-table td {
            border: 1px solid #ddd;
            padding: 6px;
            font-size: 10px;
            text-align: center;
        }
        table.data-table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        table.data-table tbody tr:hover {
            background-color: #e3f2fd;
        }

        /* Summary */
        .summary {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
        }
        .summary h3 {
            margin: 0 0 10px 0;
            font-size: 12px;
            color: #0066cc;
        }
        .summary .summary-grid {
            display: table;
            width: 100%;
        }
        .summary .summary-item {
            display: table-cell;
            width: 25%;
            padding: 5px;
            text-align: center;
        }
        .summary .summary-value {
            font-size: 16px;
            font-weight: bold;
            color: #0066cc;
        }
        .summary .summary-label {
            font-size: 10px;
            color: #666;
        }

        /* Footer / Tanda Tangan */
        .footer {
            margin-top: 40px;
            display: table;
            width: 100%;
        }
        .footer-left {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        .footer-right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            text-align: center;
        }
        .signature-box {
            border: 1px solid #ddd;
            padding: 15px;
            text-align: center;
            border-radius: 5px;
            background-color: #fafafa;
        }
        .signature-location {
            margin-bottom: 15px;
            font-size: 11px;
        }
        .signature-title {
            font-weight: bold;
            margin-bottom: 50px;
            font-size: 11px;
        }
        .signature-name {
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 5px;
            font-size: 11px;
        }
        .signature-nip {
            font-size: 10px;
            color: #666;
        }

        /* Utilities */
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .mb-10 { margin-bottom: 10px; }
        .mb-20 { margin-bottom: 20px; }

        /* Print styles */
        @media print {
            .no-print { display: none; }
            body { -webkit-print-color-adjust: exact; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Header / Kop Surat -->
    <div class="header">
        <div class="header-content">
            <div class="header-logo">
                @if(isset($kopSurat) && $kopSurat->logo_path)
                    <img src="{{ $kopSurat->logo_path }}" alt="Logo">
                @else
                    <img src="{{ public_path('assets/img/logo.png') }}" alt="Logo">
                @endif
            </div>
            <div class="header-text">
                <div class="header-title">
                    {{ $kopSurat->nama_instansi ?? config('app.name', 'Nama Instansi') }}
                </div>
                <div class="header-subtitle">
                    {{ $kopSurat->alamat_instansi ?? 'Alamat Instansi' }}
                </div>
                @if(isset($kopSurat))
                    @if($kopSurat->telepon_instansi)
                        <div class="header-subtitle">Telp: {{ $kopSurat->telepon_instansi }}</div>
                    @endif
                    @if($kopSurat->email_instansi)
                        <div class="header-subtitle">Email: {{ $kopSurat->email_instansi }}</div>
                    @endif
                    @if($kopSurat->website_instansi)
                        <div class="header-subtitle">Website: {{ $kopSurat->website_instansi }}</div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <!-- Judul Laporan -->
    <div class="report-title">
        <h1>{{ $title ?? 'Laporan' }}</h1>
        @if(isset($period))
            <div class="period">{{ $period }}</div>
        @endif
    </div>

    <!-- Konten Laporan -->
    <div class="content">
        @yield('content')
    </div>

    <!-- Footer / Tanda Tangan -->
    <div class="footer">
        <div class="footer-left">
            @yield('footer-left')
        </div>
        <div class="footer-right">
            <div class="signature-box">
                <div class="signature-location">
                    @php
                        $city = 'Kota';
                        if ($kopSurat && $kopSurat->alamat_instansi) {
                            $parts = array_map('trim', explode(',', $kopSurat->alamat_instansi));
                            foreach ($parts as $part) {
                                if (stripos($part, 'Kabupaten') !== false) {
                                    $city = str_ireplace('Kabupaten ', '', $part);
                                    break;
                                }
                                if (stripos($part, 'Kota') !== false) {
                                    $city = str_ireplace('Kota ', '', $part);
                                    break;
                                }
                            }
                            if ($city == 'Kota' && count($parts) >= 3) {
                                $city = $parts[2];
                            }
                        }
                    @endphp
                    {{ $city }}, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
                </div>
                <div class="signature-title">
                    {{ $kopSurat->jabatan_pimpinan ?? 'Pimpinan' }}
                </div>
                <div class="signature-name">
                    {{ $kopSurat->nama_pimpinan ?? 'Nama Pimpinan' }}
                </div>
                @if(isset($kopSurat) && $kopSurat->nip_pimpinan)
                    <div class="signature-nip">
                        NIP: {{ $kopSurat->nip_pimpinan }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html> 