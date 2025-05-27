# Monitoring Presensi - Struktur Komponen

## Overview
File monitoring presensi telah di-refactor dari 535 baris menjadi lebih modular dan mudah di-maintain.

## Struktur File

### File Utama
- `index.blade.php` (203 baris) - File utama yang menggunakan komponen

### Komponen Terpisah
- `components/statistics-cards.blade.php` (73 baris) - Card statistik keterlambatan
- `components/filter-form.blade.php` (47 baris) - Form filter pencarian
- `components/modal-lokasi.blade.php` (76 baris) - Modal lokasi absensi

### JavaScript Eksternal
- `public/assets/js/monitoring-presensi.js` (141 baris) - Logika JavaScript untuk peta dan modal

## Keuntungan Refactor

1. **Maintainability**: Setiap komponen memiliki fungsi yang spesifik
2. **Reusability**: Komponen dapat digunakan ulang di halaman lain
3. **Readability**: File utama menjadi lebih mudah dibaca
4. **Performance**: JavaScript terpisah dapat di-cache browser
5. **Team Development**: Developer dapat bekerja pada komponen berbeda tanpa konflik

## Penggunaan

### Menggunakan Komponen
```blade
@include('admin.dashboard.monitoringPresensi.components.statistics-cards')
@include('admin.dashboard.monitoringPresensi.components.filter-form')
@include('admin.dashboard.monitoringPresensi.components.modal-lokasi')
```

### Menggunakan JavaScript
```blade
@push('myScript')
<script src="{{ asset('assets/js/monitoring-presensi.js') }}"></script>
@endpush
```

## Fitur yang Diimplementasi

1. **Perhitungan Keterlambatan**: Otomatis menghitung jam keterlambatan karyawan
2. **Statistik Dashboard**: Total jam keterlambatan, jumlah karyawan terlambat, rata-rata
3. **Export Laporan**: Export CSV laporan keterlambatan
4. **Modal Lokasi Interaktif**: Peta Leaflet.js dengan marker lokasi absensi
5. **Filter & Search**: Berdasarkan tanggal, nama, dan departemen

## Dependencies

- Laravel Framework
- Leaflet.js (untuk peta)
- Bootstrap 5 (untuk modal dan styling)
- Flatpickr (untuk date picker)
- jQuery (untuk event handling) 