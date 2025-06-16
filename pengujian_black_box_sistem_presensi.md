# PENGUJIAN BLACK BOX SISTEM PRESENSI

## 4.8 Hasil Pengujian Black Box

Pengujian black box dilakukan untuk menguji fungsionalitas sistem presensi dari sisi input dan output tanpa memperhatikan struktur internal program. Pengujian ini mencakup seluruh modul utama dalam sistem presensi.

### 4.8.1 Pengujian Halaman Login

#### 1. Login Sebagai Karyawan

| Data Masukan | Hasil yang Diharapkan | Pengamatan | Kesimpulan |
|--------------|----------------------|------------|------------|
| NIK dan password yang terdaftar | Menampilkan halaman dashboard karyawan | Menampilkan halaman dashboard karyawan | Akses diterima |
| NIK dan password yang salah | Menampilkan pesan "NIK atau Password salah" | Menampilkan pesan "NIK atau Password salah" | Akses ditolak |
| NIK kosong dan password diisi | Menampilkan pesan validasi "NIK wajib diisi" | Menampilkan pesan validasi "NIK wajib diisi" | Akses ditolak |
| NIK diisi dan password kosong | Menampilkan pesan validasi "Password wajib diisi" | Menampilkan pesan validasi "Password wajib diisi" | Akses ditolak |
| NIK dengan format bukan angka | Menampilkan pesan validasi "NIK harus berupa angka" | Menampilkan pesan validasi "NIK harus berupa angka" | Akses ditolak |

*Tabel 4.1 Pengujian Login Sebagai Karyawan*

#### 2. Login Sebagai Admin

| Data Masukan | Hasil yang Diharapkan | Pengamatan | Kesimpulan |
|--------------|----------------------|------------|------------|
| Email dan password admin yang benar | Menampilkan halaman dashboard admin | Menampilkan halaman dashboard admin | Akses diterima |
| Email dan password yang salah | Menampilkan pesan "Email atau Password salah" | Menampilkan pesan "Email atau Password salah" | Akses ditolak |
| Email kosong dan password diisi | Menampilkan pesan validasi "Email wajib diisi" | Menampilkan pesan validasi "Email wajib diisi" | Akses ditolak |
| Email diisi dan password kosong | Menampilkan pesan validasi "Password wajib diisi" | Menampilkan pesan validasi "Password wajib diisi" | Akses ditolak |
| Email dengan format tidak valid | Menampilkan pesan validasi "Format email tidak valid" | Menampilkan pesan validasi "Format email tidak valid" | Akses ditolak |

*Tabel 4.2 Pengujian Login Sebagai Admin*

### 4.8.2 Pengujian Fitur Presensi

#### 1. Presensi Masuk/Keluar

| Data Masukan | Hasil yang Diharapkan | Pengamatan | Kesimpulan |
|--------------|----------------------|------------|------------|
| Lokasi dalam radius kantor, foto selfie valid | Presensi berhasil dengan pesan sukses | Presensi berhasil dengan pesan sukses | Presensi berhasil |
| Lokasi di luar radius kantor | Menampilkan pesan "Anda berada di luar radius kantor" | Menampilkan pesan "Anda berada di luar radius kantor" | Presensi ditolak |
| Lokasi valid tanpa foto selfie | Menampilkan pesan "Format gambar tidak valid" | Menampilkan pesan "Format gambar tidak valid" | Presensi ditolak |
| GPS tidak tersedia atau error | Menampilkan pesan "Lokasi tidak valid" | Menampilkan pesan "Lokasi tidak valid" | Presensi ditolak |
| Foto dengan format tidak didukung | Menampilkan pesan error format gambar | Menampilkan pesan error format gambar | Presensi ditolak |

*Tabel 4.3 Pengujian Presensi Masuk/Keluar*

### 4.8.3 Pengujian Riwayat Presensi

#### 1. Pencarian Riwayat Presensi

| Data Masukan | Hasil yang Diharapkan | Pengamatan | Kesimpulan |
|--------------|----------------------|------------|------------|
| Pilih bulan dan tahun yang memiliki data | Menampilkan riwayat presensi sesuai periode | Menampilkan riwayat presensi sesuai periode | Pencarian berhasil |
| Pilih periode tanpa data presensi | Menampilkan pesan "Tidak ada data presensi" | Menampilkan pesan "Tidak ada data presensi" | Pencarian berhasil |
| Bulan tidak dipilih | Menampilkan pesan validasi "Bulan wajib dipilih" | Menampilkan pesan validasi "Bulan wajib dipilih" | Pencarian ditolak |
| Tahun tidak dipilih | Menampilkan pesan validasi "Tahun wajib dipilih" | Menampilkan pesan validasi "Tahun wajib dipilih" | Pencarian ditolak |
| Data ditampilkan sesuai urutan tanggal | Riwayat terurut dari terbaru ke terlama | Riwayat terurut dari terbaru ke terlama | Sorting berhasil |

*Tabel 4.4 Pengujian Riwayat Presensi*

### 4.8.4 Pengujian Pengajuan Izin

#### 1. Pengajuan Izin/Sakit

| Data Masukan | Hasil yang Diharapkan | Pengamatan | Kesimpulan |
|--------------|----------------------|------------|------------|
| Data lengkap (tanggal, status, keterangan) | Menampilkan pesan "Data Berhasil Disimpan" | Menampilkan pesan "Data Berhasil Disimpan" | Pengajuan berhasil |
| Tanggal izin kosong | Menampilkan pesan validasi "Tanggal izin wajib diisi" | Menampilkan pesan validasi "Tanggal izin wajib diisi" | Pengajuan ditolak |
| Status izin tidak dipilih | Menampilkan pesan validasi "Status wajib dipilih" | Menampilkan pesan validasi "Status wajib dipilih" | Pengajuan ditolak |
| Keterangan kosong | Menampilkan pesan validasi "Keterangan wajib diisi" | Menampilkan pesan validasi "Keterangan wajib diisi" | Pengajuan ditolak |
| Tanggal izin di masa lalu | Menampilkan pesan error tanggal tidak valid | Menampilkan pesan error tanggal tidak valid | Pengajuan ditolak |

*Tabel 4.5 Pengujian Pengajuan Izin/Sakit*

### 4.8.5 Pengujian Update Profile

#### 1. Update Data Profile

| Data Masukan | Hasil yang Diharapkan | Pengamatan | Kesimpulan |
|--------------|----------------------|------------|------------|
| Data valid (nama, no HP) | Menampilkan pesan "Profile berhasil diupdate" | Menampilkan pesan "Profile berhasil diupdate" | Update berhasil |
| Nama lengkap kosong | Menampilkan pesan validasi "Nama wajib diisi" | Menampilkan pesan validasi "Nama wajib diisi" | Update ditolak |
| Password kurang dari 6 karakter | Menampilkan pesan "Password minimal 6 karakter" | Menampilkan pesan "Password minimal 6 karakter" | Update ditolak |
| Konfirmasi password tidak cocok | Menampilkan pesan "Konfirmasi password tidak cocok" | Menampilkan pesan "Konfirmasi password tidak cocok" | Update ditolak |
| Upload foto melebihi ukuran maksimal | Menampilkan pesan error ukuran file | Menampilkan pesan error ukuran file | Update ditolak |

*Tabel 4.6 Pengujian Update Profile*

### 4.8.6 Pengujian Data Master Karyawan (Admin)

#### 1. Manajemen Karyawan

| Data Masukan | Hasil yang Diharapkan | Pengamatan | Kesimpulan |
|--------------|----------------------|------------|------------|
| Tambah karyawan dengan data lengkap | Menampilkan pesan "Data berhasil ditambahkan" | Menampilkan pesan "Data berhasil ditambahkan" | Tambah berhasil |
| NIK kosong atau duplikat | Menampilkan pesan validasi NIK | Menampilkan pesan validasi NIK | Tambah ditolak |
| Edit data karyawan | Data berhasil diupdate | Data berhasil diupdate | Edit berhasil |
| Hapus data karyawan | Data berhasil dihapus | Data berhasil dihapus | Hapus berhasil |
| Pencarian berdasarkan nama | Hasil pencarian sesuai kata kunci | Hasil pencarian sesuai kata kunci | Pencarian berhasil |

*Tabel 4.7 Pengujian Manajemen Karyawan*

### 4.8.7 Pengujian Konfigurasi Kantor (Admin)

#### 1. Pengaturan Kantor

| Data Masukan | Hasil yang Diharapkan | Pengamatan | Kesimpulan |
|--------------|----------------------|------------|------------|
| Data kantor lengkap dengan koordinat valid | Konfigurasi berhasil disimpan | Konfigurasi berhasil disimpan | Konfigurasi berhasil |
| Nama kantor kosong | Menampilkan pesan validasi | Menampilkan pesan validasi | Konfigurasi ditolak |
| Koordinat GPS di luar rentang valid | Menampilkan pesan error koordinat | Menampilkan pesan error koordinat | Konfigurasi ditolak |
| Radius kurang dari 1 atau lebih dari 1000 meter | Menampilkan pesan error radius | Menampilkan pesan error radius | Konfigurasi ditolak |

*Tabel 4.8 Pengujian Konfigurasi Kantor*

### 4.8.8 Pengujian Monitoring Presensi (Admin)

#### 1. Monitoring Real-time

| Data Masukan | Hasil yang Diharapkan | Pengamatan | Kesimpulan |
|--------------|----------------------|------------|------------|
| Filter berdasarkan tanggal | Data presensi sesuai tanggal | Data presensi sesuai tanggal | Filter berhasil |
| Filter berdasarkan nama karyawan | Data presensi karyawan tertentu | Data presensi karyawan tertentu | Filter berhasil |
| Filter berdasarkan departemen | Data presensi departemen tertentu | Data presensi departemen tertentu | Filter berhasil |
| Export data keterlambatan | File berhasil didownload | File berhasil didownload | Export berhasil |

*Tabel 4.9 Pengujian Monitoring Presensi*

### 4.8.9 Pengujian Laporan Presensi (Admin)

#### 1. Generate Laporan

| Data Masukan | Hasil yang Diharapkan | Pengamatan | Kesimpulan |
|--------------|----------------------|------------|------------|
| Filter periode dan karyawan valid | Laporan berhasil di-generate | Laporan berhasil di-generate | Laporan berhasil |
| Export laporan ke PDF | File PDF berhasil didownload | File PDF berhasil didownload | Export berhasil |
| Export laporan ke Excel | File Excel berhasil didownload | File Excel berhasil didownload | Export berhasil |
| Filter tanpa data | Menampilkan pesan "Tidak ada data" | Menampilkan pesan "Tidak ada data" | Filter berhasil |

*Tabel 4.10 Pengujian Laporan Presensi*

### 4.8.10 Pengujian Manajemen Pengajuan Izin (Admin)

#### 1. Kelola Pengajuan Izin

| Data Masukan | Hasil yang Diharapkan | Pengamatan | Kesimpulan |
|--------------|----------------------|------------|------------|
| Approve pengajuan izin | Status berubah menjadi approved | Status berubah menjadi approved | Approve berhasil |
| Reject pengajuan izin | Status berubah menjadi rejected | Status berubah menjadi rejected | Reject berhasil |
| Edit pengajuan izin | Data berhasil diupdate | Data berhasil diupdate | Edit berhasil |
| Hapus pengajuan izin | Data berhasil dihapus | Data berhasil dihapus | Hapus berhasil |

*Tabel 4.11 Pengujian Manajemen Pengajuan Izin*

### 4.8.11 Pengujian Keamanan dan Akses

#### 1. Authorization dan Session

| Data Masukan | Hasil yang Diharapkan | Pengamatan | Kesimpulan |
|--------------|----------------------|------------|------------|
| Akses halaman admin tanpa login | Redirect ke halaman login | Redirect ke halaman login | Security berhasil |
| Session timeout | Auto logout dan redirect login | Auto logout dan redirect login | Security berhasil |
| Akses URL admin sebagai karyawan | Access denied atau redirect | Access denied atau redirect | Authorization berhasil |
| Logout dari sistem | Session terhapus dan redirect login | Session terhapus dan redirect login | Logout berhasil |

*Tabel 4.12 Pengujian Keamanan dan Akses*

### 4.8.12 Pengujian Responsivitas

#### 1. Kompatibilitas Device

| Data Masukan | Hasil yang Diharapkan | Pengamatan | Kesimpulan |
|--------------|----------------------|------------|------------|
| Akses dari smartphone | Interface responsive dan fungsional | Interface responsive dan fungsional | Mobile compatible |
| Akses dari tablet | Interface responsive dan fungsional | Interface responsive dan fungsional | Tablet compatible |
| Fitur kamera untuk presensi | Kamera berfungsi dan foto tersimpan | Kamera berfungsi dan foto tersimpan | Camera berhasil |
| Loading time halaman | Load time kurang dari 3 detik | Load time kurang dari 3 detik | Performance baik |

*Tabel 4.13 Pengujian Responsivitas*

## 4.9 Kesimpulan Pengujian Black Box

Berdasarkan hasil pengujian black box yang telah dilakukan, sistem presensi menunjukkan:

1. **Fungsionalitas Login**: Berfungsi dengan baik untuk karyawan dan admin dengan validasi yang tepat
2. **Fitur Presensi**: GPS dan foto selfie berfungsi sesuai requirement dengan validasi radius kantor
3. **Manajemen Data**: CRUD operations untuk semua entitas berfungsi dengan baik
4. **Laporan dan Monitoring**: Fitur reporting dan export berfungsi sesuai kebutuhan
5. **Keamanan**: Authorization dan session management berfungsi dengan baik
6. **User Experience**: Interface responsive dan user-friendly di berbagai device

Sistem telah memenuhi semua requirement fungsional dan siap untuk diimplementasikan. 