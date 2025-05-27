# 📋 Sistem Presensi Karyawan

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-10.x-red?style=for-the-badge&logo=laravel" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.1+-blue?style=for-the-badge&logo=php" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-8.0+-orange?style=for-the-badge&logo=mysql" alt="MySQL">
  <img src="https://img.shields.io/badge/License-MIT-green?style=for-the-badge" alt="License">
</p>

Sistem Presensi Karyawan adalah aplikasi web berbasis Laravel yang dirancang untuk mengelola dan memonitor kehadiran karyawan secara digital. Sistem ini dilengkapi dengan fitur-fitur modern seperti presensi berbasis lokasi GPS, monitoring real-time, laporan komprehensif, dan manajemen data karyawan.

## 🌟 Fitur Utama

### 👥 **Manajemen Karyawan**
- ✅ CRUD karyawan lengkap (Create, Read, Update, Delete)
- ✅ Upload foto profil karyawan
- ✅ Manajemen departemen dan jabatan
- ✅ Validasi NIK unik
- ✅ Filter dan pencarian karyawan

### 📍 **Sistem Presensi GPS**
- ✅ Presensi masuk/keluar berbasis lokasi GPS
- ✅ Radius kantor yang dapat dikonfigurasi (default: 20 meter)
- ✅ Capture foto selfie saat presensi
- ✅ Validasi lokasi real-time
- ✅ Penyimpanan koordinat lokasi absensi

### ⏰ **Monitoring & Keterlambatan**
- ✅ Perhitungan otomatis jam keterlambatan
- ✅ Monitoring presensi real-time
- ✅ Statistik kehadiran dan keterlambatan
- ✅ Alert untuk karyawan yang terlambat
- ✅ Modal lokasi absensi dengan peta interaktif

### 📊 **Laporan & Analitik**
- ✅ Laporan presensi harian, bulanan, tahunan
- ✅ Rekap presensi per karyawan
- ✅ Export laporan ke CSV/Excel
- ✅ Cetak laporan PDF dengan kop surat
- ✅ Dashboard statistik dengan visualisasi

### 🗺️ **Peta Interaktif**
- ✅ Integrasi Leaflet.js untuk peta
- ✅ Marker lokasi kantor, masuk, dan keluar
- ✅ Auto-fit bounds untuk multiple marker
- ✅ Popup informatif dengan koordinat

### 🔐 **Multi-Role Authentication**
- ✅ Role admin dan karyawan
- ✅ Guard terpisah untuk setiap role
- ✅ Middleware untuk proteksi route
- ✅ Session management yang aman

## 🚀 Teknologi yang Digunakan

### **Backend**
- **Laravel 10.x** - PHP Framework
- **PHP 8.1+** - Programming Language
- **MySQL 8.0+** - Database Management System
- **Carbon** - Date/Time Manipulation
- **Laravel Auth** - Authentication System

### **Frontend**
- **Tabler UI** - Admin Dashboard Template
- **Bootstrap 5** - CSS Framework
- **jQuery** - JavaScript Library
- **Leaflet.js** - Interactive Maps
- **Flatpickr** - Date Picker
- **Font Awesome** - Icon Library

### **Additional Libraries**
- **DomPDF** - PDF Generation
- **Intervention Image** - Image Processing
- **Laravel Storage** - File Management

## 📋 Persyaratan Sistem

### **Server Requirements**
- PHP >= 8.1
- MySQL >= 8.0 atau MariaDB >= 10.3
- Apache/Nginx Web Server
- Composer (PHP Dependency Manager)
- Node.js & NPM (untuk asset compilation)

### **PHP Extensions**
```
- ext-gd (untuk manipulasi gambar)
- ext-mbstring (untuk string multibyte)
- ext-pdo_mysql (untuk koneksi MySQL)
- ext-openssl (untuk enkripsi)
- ext-json (untuk JSON processing)
- ext-curl (untuk HTTP requests)
```

## 🛠️ Instalasi

### **1. Clone Repository**
```bash
git clone https://github.com/username/sistem-presensi.git
cd sistem-presensi
```

### **2. Install Dependencies**
```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install && npm run build
```

### **3. Environment Configuration**
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### **4. Database Setup**
Edit file `.env` dengan konfigurasi database Anda:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=presensi_db
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### **5. Database Migration & Seeding**
```bash
# Jalankan migrasi database
php artisan migrate

# (Opsional) Seed data dummy
php artisan db:seed
```

### **6. Storage Link**
```bash
# Buat symbolic link untuk storage
php artisan storage:link
```

### **7. Install DomPDF (untuk fitur PDF)**
```bash
composer require barryvdh/laravel-dompdf
```

### **8. Jalankan Server**
```bash
php artisan serve
```

Aplikasi akan berjalan di `http://localhost:8000`

## 📁 Struktur Direktori

```
sistem-presensi/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── AdminDashboardController.php
│   │   │   │   ├── MonitorPresensiController.php
│   │   │   │   ├── DataMaster/
│   │   │   │   │   ├── KaryawanController.php
│   │   │   │   │   └── DepartemenController.php
│   │   │   │   └── Laporan/
│   │   │   │       └── LaporanPresensiController.php
│   │   │   ├── AuthController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── PresensiController.php
│   │   │   └── ProfileController.php
│   │   └── Middleware/
│   ├── Models/
│   │   ├── User.php
│   │   ├── Karyawan.php
│   │   ├── Departemen.php
│   │   ├── Presensi.php
│   │   └── PengajuanIzin.php
│   └── ...
├── database/
│   ├── migrations/
│   │   ├── create_karyawan_table.php
│   │   ├── create_departemens_table.php
│   │   ├── create_presensi_table.php
│   │   └── create_pengajuan_izin_table.php
│   └── seeders/
├── public/
│   ├── assets/
│   │   ├── css/
│   │   ├── js/
│   │   │   └── monitoring-presensi.js
│   │   └── images/
│   └── storage/ (symbolic link)
├── resources/
│   ├── views/
│   │   ├── admin/
│   │   │   ├── layouts/
│   │   │   └── dashboard/
│   │   │       ├── monitoringPresensi/
│   │   │       │   ├── index.blade.php
│   │   │       │   └── components/
│   │   │       │       ├── statistics-cards.blade.php
│   │   │       │       ├── filter-form.blade.php
│   │   │       │       └── modal-lokasi.blade.php
│   │   │       └── laporan/
│   │   │           ├── presensi/
│   │   │           │   ├── index.blade.php
│   │   │           │   └── pdf.blade.php
│   │   │           └── rekap/
│   │   │               └── index.blade.php
│   │   ├── auth/
│   │   ├── dashboard/
│   │   ├── presensi/
│   │   └── layouts/
│   └── ...
├── routes/
│   └── web.php
└── storage/
    └── app/
        └── public/
            └── uploads/
                └── absention/
```

## 🔑 Akun Default

### **Admin**
- **Email:** admin@admin.com
- **Password:** password

### **Karyawan**
- **NIK:** 12345
- **Password:** password

## 📖 Panduan Penggunaan

### **Untuk Admin**

#### **1. Dashboard Admin**
- Akses statistik kehadiran real-time
- Monitor karyawan yang hadir hari ini
- Lihat total keterlambatan dan izin

#### **2. Manajemen Data Master**
```
Admin → Data Master → Karyawan
- Tambah karyawan baru
- Edit data karyawan
- Upload foto profil
- Hapus karyawan
```

#### **3. Monitoring Presensi**
```
Admin → Monitoring Presensi
- Filter berdasarkan tanggal, nama, departemen
- Lihat statistik keterlambatan
- View lokasi absensi di peta
- Export laporan keterlambatan
```

#### **4. Laporan Presensi**
```
Admin → Laporan → Presensi
- Generate laporan periode tertentu
- Export ke CSV/Excel
- Cetak PDF per karyawan
- Filter berdasarkan multiple criteria
```

#### **5. Rekap Presensi**
```
Admin → Laporan → Rekap Presensi
- Lihat rekap per karyawan
- Statistik kehadiran dan keterlambatan
- Persentase kehadiran dengan color coding
- Export rekap ke CSV
```

### **Untuk Karyawan**

#### **1. Dashboard Karyawan**
- Lihat status presensi hari ini
- History presensi bulan ini
- Rekap kehadiran dan keterlambatan

#### **2. Presensi**
```
Karyawan → Presensi
- Check-in/Check-out dengan GPS
- Capture foto selfie
- Validasi lokasi otomatis
- Lihat jarak dari kantor
```

#### **3. History Presensi**
```
Karyawan → History
- Filter berdasarkan bulan/tahun
- Lihat detail jam masuk/keluar
- Status keterlambatan
```

#### **4. Pengajuan Izin**
```
Karyawan → Izin
- Ajukan izin sakit/cuti
- Upload dokument pendukung
- Track status approval
```

## ⚙️ Konfigurasi

### **1. Lokasi Kantor**
Edit di `app/Http/Controllers/PresensiController.php`:
```php
private const OFFICE_LAT = -7.33351589751558;
private const OFFICE_LONG = 108.22279680492574;
private const MAX_DISTANCE_METERS = 20; // radius dalam meter
```

### **2. Jam Kerja**
Edit di controller yang relevan:
```php
$jamKerjaStandar = '07:00:00'; // jam masuk standar
```

### **3. Logo Instansi**
Letakkan file logo di `public/logo.png` untuk tampil di PDF

### **4. Nama Instansi**
Edit di `config/app.php`:
```php
'name' => env('APP_NAME', 'Nama Instansi Anda'),
```

## 🔧 Troubleshooting

### **1. Error Storage Link**
```bash
# Hapus link lama dan buat ulang
rm public/storage
php artisan storage:link
```

### **2. Error Permission**
```bash
# Set permission untuk storage dan cache
chmod -R 775 storage bootstrap/cache
```

### **3. Error DomPDF**
```bash
# Install ulang DomPDF
composer require barryvdh/laravel-dompdf
php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
```

### **4. Error GPS/Location**
- Pastikan browser mengizinkan akses lokasi
- Gunakan HTTPS untuk production
- Check koordinat kantor sudah benar

## 📱 Fitur Mobile-Friendly

- ✅ Responsive design untuk semua ukuran layar
- ✅ Touch-friendly interface
- ✅ GPS location support
- ✅ Camera integration untuk selfie
- ✅ Offline capability (limited)

## 🔐 Security Features

- ✅ CSRF Protection
- ✅ SQL Injection Prevention
- ✅ XSS Protection
- ✅ Password Hashing
- ✅ Route Protection dengan Middleware
- ✅ File Upload Validation
- ✅ Session Security

## 🧪 Testing

```bash
# Jalankan test
php artisan test

# Test dengan coverage
php artisan test --coverage
```

## 🚀 Deployment

### **Production Checklist**
- [ ] Set `APP_ENV=production` di `.env`
- [ ] Set `APP_DEBUG=false` di `.env`
- [ ] Configure database production
- [ ] Set up SSL certificate
- [ ] Configure backup system
- [ ] Set up monitoring
- [ ] Optimize dengan `php artisan optimize`

### **Performance Optimization**
```bash
# Cache konfigurasi
php artisan config:cache

# Cache route
php artisan route:cache

# Cache view
php artisan view:cache

# Optimize autoloader
composer install --optimize-autoloader --no-dev
```

## 🤝 Contributing

1. Fork repository
2. Buat feature branch (`git checkout -b feature/amazing-feature`)
3. Commit changes (`git commit -m 'Add amazing feature'`)
4. Push ke branch (`git push origin feature/amazing-feature`)
5. Buat Pull Request

## 📄 License

Proyek ini menggunakan lisensi MIT. Lihat file [LICENSE](LICENSE) untuk detail.

## 👥 Developer

- **Nama**: [Nama Anda]
- **Email**: [email@example.com]
- **GitHub**: [username]

## 🙏 Acknowledgments

- [Laravel Framework](https://laravel.com)
- [Tabler UI](https://tabler.io)
- [Leaflet.js](https://leafletjs.com)
- [DomPDF](https://github.com/dompdf/dompdf)

## 📞 Support

Jika Anda mengalami masalah atau membutuhkan bantuan:

1. Buka [GitHub Issues](https://github.com/username/sistem-presensi/issues)
2. Kirim email ke [support@example.com]
3. Baca dokumentasi di [Wiki](https://github.com/username/sistem-presensi/wiki)

---

<p align="center">
  <strong>Dibuat dengan ❤️ menggunakan Laravel</strong>
</p>
