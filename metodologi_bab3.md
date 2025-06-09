# BAB III METODOLOGI

## 3.1 Metodologi

### 3.1.1 Identifikasi Masalah

Dalam pelaksanaan kerja praktik di [Nama Perusahaan], dilakukan identifikasi masalah yang berkaitan dengan sistem presensi karyawan yang sedang berjalan. Proses identifikasi masalah dilakukan melalui:

#### **Observasi Langsung**
- Mengamati proses presensi manual yang masih menggunakan sistem absensi konvensional
- Mencatat kendala-kendala yang dihadapi dalam proses presensi harian
- Mengidentifikasi ineffisiensi dalam sistem pencatatan kehadiran karyawan

#### **Wawancara dengan Stakeholder**
- **HRD Manager**: Mengidentifikasi kebutuhan laporan dan monitoring kehadiran
- **Karyawan**: Mengetahui kesulitan dalam proses presensi sehari-hari
- **IT Administrator**: Memahami infrastruktur teknologi yang tersedia

#### **Analisis Dokumentasi**
- Review Standard Operating Procedure (SOP) presensi yang berlaku
- Analisis laporan kehadiran bulanan untuk mengidentifikasi pola keterlambatan
- Evaluasi sistem yang sedang berjalan dan keterbatasannya

**Masalah yang Teridentifikasi:**
1. Proses presensi manual yang memakan waktu dan rentan kesalahan
2. Kesulitan monitoring real-time kehadiran karyawan
3. Keterbatasan dalam pembuatan laporan kehadiran yang akurat
4. Tidak adanya validasi lokasi presensi (memungkinkan titip absen)
5. Proses administrasi yang manual dan membutuhkan waktu lama

### 3.1.2 Penentuan Topik Kerja Praktik

Berdasarkan hasil identifikasi masalah, ditentukan topik kerja praktik sebagai berikut:

**Topik**: "Pengembangan Sistem Presensi Karyawan Berbasis Web dengan Fitur GPS Location"

#### **Ruang Lingkup Topik:**
- **Sistem Presensi Digital**: Menggantikan sistem manual dengan sistem berbasis web
- **Integrasi GPS**: Memastikan validitas lokasi presensi sesuai area kantor
- **Multi-Role Access**: Sistem dengan akses berbeda untuk admin dan karyawan
- **Reporting System**: Fitur laporan komprehensif untuk monitoring kehadiran
- **Real-time Monitoring**: Dashboard untuk monitoring kehadiran secara real-time

#### **Batasan Topik:**
- Sistem dikembangkan khusus untuk presensi karyawan [Nama Perusahaan]
- Radius GPS dibatasi maksimal 50 meter dari titik koordinat kantor
- Sistem hanya mencakup presensi masuk dan keluar, tidak termasuk izin/cuti
- Platform berbasis web yang mobile-friendly
- Database lokal (tidak cloud-based)

### 3.1.3 Pengumpulan Data

#### **3.1.3.1 Data Primer**

**A. Observasi Partisipatif**
- **Lokasi**: Kantor [Nama Perusahaan]
- **Waktu**: 2 minggu pertama kerja praktik
- **Objek Observasi**: 
  - Proses presensi harian karyawan
  - Workflow administrasi HRD
  - Infrastruktur IT yang tersedia
  - Kebiasaan dan pola kerja karyawan

**B. Wawancara Terstruktur**
- **HRD Manager** (1 orang): Kebutuhan sistem, kebijakan presensi, format laporan
- **Supervisor/Team Leader** (3 orang): Monitoring tim, kendala manajemen kehadiran
- **Karyawan** (15 orang): User experience, preferensi sistem, kendala presensi
- **IT Administrator** (1 orang): Infrastruktur teknologi, keamanan sistem

**C. Kuesioner Online**
- **Platform**: Google Forms
- **Target**: 50 karyawan dari berbagai departemen
- **Durasi**: 1 minggu pengumpulan data
- **Materi**: 
  - Kepuasan terhadap sistem presensi saat ini
  - Fitur yang diharapkan pada sistem baru
  - Preferensi teknologi dan interface
  - Tingkat literasi digital

#### **3.1.3.2 Data Sekunder**

**A. Dokumentasi Perusahaan**
- Standard Operating Procedure (SOP) presensi karyawan
- Data kehadiran karyawan 6 bulan terakhir
- Struktur organisasi dan job description
- Kebijakan jam kerja dan aturan kehadiran
- Infrastructure IT yang tersedia

**B. Studi Literatur**
- Jurnal penelitian tentang sistem presensi berbasis GPS
- Best practices pengembangan sistem informasi
- Framework Laravel dan teknologi web development
- Standar keamanan aplikasi web
- Regulasi perlindungan data pribadi

### 3.1.4 Perancangan Model Sistem

#### **3.1.4.1 Metodologi Pengembangan**
Sistem dikembangkan menggunakan **Software Development Life Cycle (SDLC)** dengan model **Waterfall**:

**1. Requirements Analysis**
- Analisis kebutuhan fungsional dan non-fungsional
- Dokumentasi Software Requirements Specification (SRS)
- Validasi kebutuhan dengan stakeholder

**2. System Design**
- Perancangan arsitektur sistem MVC (Model-View-Controller)
- Design database dengan Entity Relationship Diagram (ERD)
- Perancangan User Interface dan User Experience
- Design security dan authentication system

**3. Implementation**
- Coding menggunakan framework Laravel 12.x
- Implementasi database MySQL
- Integrasi API GPS dan mapping
- Development responsive web interface

**4. Testing**
- Unit testing untuk setiap modul
- Integration testing antar komponen
- User Acceptance Testing (UAT)
- Performance dan security testing

**5. Deployment**
- Setup production environment
- Database migration
- User training dan documentation
- Go-live sistem

#### **3.1.4.2 Arsitektur Sistem**

**A. Arsitektur Aplikasi**
```
Presentation Layer (View)
    ↓
Business Logic Layer (Controller)
    ↓
Data Access Layer (Model)
    ↓
Database Layer (MySQL)
```

**B. Technology Stack**
- **Backend**: PHP 8.2+, Laravel 12.x
- **Frontend**: HTML5, CSS3, JavaScript, Bootstrap 5
- **Database**: MySQL 8.0+
- **Maps Integration**: Leaflet.js + OpenStreetMap
- **Additional**: jQuery, Carbon, DomPDF

### 3.1.5 Implementasi Sistem

#### **3.1.5.1 Development Environment Setup**
- **IDE**: Visual Studio Code dengan extensions PHP dan Laravel
- **Local Server**: XAMPP (Apache, MySQL, PHP)
- **Version Control**: Git dengan repository GitHub
- **Package Manager**: Composer untuk PHP dependencies
- **Testing Tools**: PHPUnit, Postman untuk API testing

#### **3.1.5.2 Tahapan Implementasi**

**Minggu 1-2: Project Setup & Basic Structure**
- Installation Laravel framework
- Database configuration dan migration
- Basic authentication system
- Project repository setup

**Minggu 3-4: Core Features Development**
- Model development (Karyawan, Presensi, Departemen)
- Controller implementation
- Database relationships
- Basic CRUD operations

**Minggu 5-6: GPS Integration & Advanced Features**
- HTML5 Geolocation API integration
- GPS validation system
- Photo capture functionality
- Location mapping dengan Leaflet.js

**Minggu 7-8: UI/UX Development**
- Responsive web design
- Dashboard admin dan karyawan
- Report generation system
- Mobile-friendly interface

#### **3.1.5.3 Fitur yang Diimplementasikan**
1. **Authentication System**: Multi-role login (Admin/Karyawan)
2. **GPS-based Attendance**: Presensi dengan validasi lokasi
3. **Photo Verification**: Capture foto saat presensi
4. **Real-time Monitoring**: Dashboard monitoring kehadiran
5. **Comprehensive Reports**: Laporan harian, bulanan, tahunan
6. **Employee Management**: CRUD data karyawan
7. **Department Management**: Manajemen departemen dan jabatan

### 3.1.6 Pengujian Sistem

#### **3.1.6.1 Unit Testing**
- **Tools**: PHPUnit (built-in Laravel)
- **Coverage**: 
  - Model methods testing
  - Controller functionality testing
  - Helper functions validation
  - Database operations testing

#### **3.1.6.2 Integration Testing**
- **Database Integration**: Testing koneksi dan operasi database
- **API Integration**: Testing GPS API dan external services
- **Cross-browser Testing**: Compatibility testing pada berbagai browser
- **Mobile Responsiveness**: Testing pada berbagai device mobile

#### **3.1.6.3 User Acceptance Testing (UAT)**
- **Participants**: 10 representative users (5 admin, 5 karyawan)
- **Scenarios**: Real-world usage scenarios
- **Duration**: 1 minggu testing period
- **Feedback Collection**: Questionnaire dan interview

#### **3.1.6.4 Performance Testing**
- **Load Testing**: Testing dengan multiple concurrent users
- **Response Time**: Measuring system response time
- **Resource Usage**: CPU, memory, dan storage utilization
- **GPS Accuracy**: Testing akurasi deteksi lokasi

#### **3.1.6.5 Security Testing**
- **Authentication Testing**: Login security validation
- **Authorization Testing**: Role-based access control
- **Data Encryption**: Sensitive data protection testing
- **SQL Injection Prevention**: Database security testing

## 3.2 Analisis Kebutuhan

### 3.2.1 Data Masukan

#### **3.2.1.1 Data Karyawan**
- **NIK (Nomor Induk Karyawan)**: Primary key, unique identifier
- **Nama Lengkap**: Nama karyawan sesuai data resmi
- **Email**: Email corporate untuk login dan notifikasi
- **Departemen**: Unit kerja karyawan
- **Jabatan**: Posisi/role karyawan dalam organisasi
- **Tanggal Bergabung**: Tanggal mulai bekerja
- **Status Karyawan**: Aktif/Non-aktif/Cuti
- **Foto Profil**: Upload foto untuk identifikasi

#### **3.2.1.2 Data Presensi**
- **NIK**: Foreign key referensi ke data karyawan
- **Tanggal Presensi**: Tanggal presensi (YYYY-MM-DD)
- **Jam Masuk**: Timestamp presensi masuk
- **Jam Keluar**: Timestamp presensi keluar
- **Foto Masuk**: Capture foto saat presensi masuk
- **Foto Keluar**: Capture foto saat presensi keluar
- **Lokasi Masuk**: Koordinat GPS (latitude, longitude) presensi masuk
- **Lokasi Keluar**: Koordinat GPS (latitude, longitude) presensi keluar
- **Status**: Status presensi (Tepat Waktu/Terlambat/Tidak Hadir)

#### **3.2.1.3 Data Konfigurasi Sistem**
- **Koordinat Kantor**: Latitude dan longitude lokasi kantor
- **Radius Toleransi**: Jarak maksimal presensi dari titik kantor (meter)
- **Jam Kerja**: Jam masuk dan keluar standar
- **Toleransi Keterlambatan**: Batas waktu toleransi terlambat (menit)

### 3.2.2 Data Keluaran

#### **3.2.2.1 Dashboard Admin**
- **Statistik Real-time**: 
  - Jumlah karyawan hadir hari ini
  - Jumlah karyawan terlambat
  - Jumlah karyawan belum presensi
  - Tingkat kehadiran bulanan

- **Monitoring Live**: 
  - Daftar karyawan yang sedang presensi
  - Lokasi presensi pada peta
  - Status presensi real-time

#### **3.2.2.2 Dashboard Karyawan**
- **Status Presensi Hari Ini**: Sudah/belum presensi masuk dan keluar
- **Riwayat Presensi**: History presensi 30 hari terakhir
- **Statistik Personal**: Total hari kerja, keterlambatan, dll.

#### **3.2.2.3 Laporan Sistem**
- **Laporan Harian**: 
  - Daftar kehadiran per tanggal
  - Detail jam masuk/keluar setiap karyawan
  - Status keterlambatan

- **Laporan Bulanan**: 
  - Rekap kehadiran per karyawan per bulan
  - Statistik keterlambatan
  - Analisis tren kehadiran

- **Laporan Custom**: 
  - Filter berdasarkan periode tertentu
  - Filter berdasarkan departemen
  - Export ke format PDF/Excel

### 3.2.3 Perangkat Keras

#### **3.2.3.1 Server Requirements**
- **Processor**: Intel Core i5 atau equivalent (minimum)
- **RAM**: 8 GB (minimum), 16 GB (recommended)
- **Storage**: 500 GB SSD untuk performance optimal
- **Network**: Broadband internet connection (minimum 10 Mbps)
- **Operating System**: 
  - Linux (Ubuntu 20.04 LTS atau CentOS 8) - recommended
  - Windows Server 2019 atau lebih baru
  - macOS (untuk development environment)

#### **3.2.3.2 Client Requirements**
- **Desktop/Laptop**: 
  - Processor: Intel Core i3 atau equivalent
  - RAM: 4 GB minimum
  - Browser: Chrome 90+, Firefox 88+, Safari 14+, Edge 90+
  - Internet Connection: Minimum 2 Mbps

- **Mobile Device**: 
  - Android 8.0+ atau iOS 12.0+
  - GPS capability
  - Camera untuk foto verifikasi
  - Internet connection (WiFi/Mobile data)

#### **3.2.3.3 Network Infrastructure**
- **Internet Connection**: Stabil dengan uptime 99.9%
- **Backup Connection**: Secondary internet untuk redundancy
- **Local Network**: LAN dengan sufficient bandwidth
- **WiFi Coverage**: Area kantor tercakup WiFi untuk mobile access

### 3.2.4 Perangkat Lunak

#### **3.2.4.1 Development Environment**
- **Web Server**: Apache 2.4+ atau Nginx 1.18+
- **PHP**: Version 8.2 atau lebih baru
- **Database**: MySQL 8.0+ atau MariaDB 10.6+
- **Composer**: PHP dependency manager
- **Node.js**: 16.x atau 18.x untuk asset compilation
- **Git**: Version control system

#### **3.2.4.2 Framework dan Libraries**
- **Backend Framework**: Laravel 12.x
- **Frontend Framework**: Bootstrap 5.3
- **JavaScript Libraries**: 
  - jQuery 3.6+
  - Leaflet.js (untuk maps)
  - Chart.js (untuk grafik)
  - Flatpickr (date picker)

#### **3.2.4.3 Third-party Services**
- **PDF Generation**: DomPDF atau Laravel Snappy
- **Image Processing**: Intervention Image
- **Date/Time Handling**: Carbon PHP
- **Maps Provider**: OpenStreetMap (via Leaflet.js)

#### **3.2.4.4 Development Tools**
- **IDE**: Visual Studio Code, PHPStorm, atau Sublime Text
- **Database Management**: phpMyAdmin, MySQL Workbench
- **API Testing**: Postman atau Insomnia
- **Version Control**: Git dengan GitHub/GitLab
- **Terminal**: Command line interface untuk Laravel Artisan

#### **3.2.4.5 Production Environment**
- **Web Server**: Nginx dengan PHP-FPM
- **Database**: MySQL 8.0 dengan regular backup
- **SSL Certificate**: HTTPS implementation
- **Monitoring**: System monitoring tools
- **Backup Solution**: Automated daily backups 