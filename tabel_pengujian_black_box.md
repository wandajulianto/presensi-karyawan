# TABEL PENGUJIAN BLACK BOX SISTEM PRESENSI

## 4.8.1 Pengujian Autentikasi

### 1. Login Karyawan

| Data Masukan | Hasil yang Diharapkan | Pengamatan | Kesimpulan |
|--------------|----------------------|------------|------------|
| NIK dan password valid | Redirect ke dashboard karyawan | Redirect ke dashboard karyawan | Akses diterima |
| NIK atau password salah | Pesan "NIK atau Password salah" | Pesan "NIK atau Password salah" | Akses ditolak |
| NIK kosong | Pesan validasi "NIK wajib diisi" | Pesan validasi "NIK wajib diisi" | Akses ditolak |
| Password kosong | Pesan validasi "Password wajib diisi" | Pesan validasi "Password wajib diisi" | Akses ditolak |
| NIK bukan angka | Pesan "NIK harus berupa angka" | Pesan "NIK harus berupa angka" | Akses ditolak |

*Tabel 4.1 Pengujian Login Karyawan*

### 2. Login Admin

| Data Masukan | Hasil yang Diharapkan | Pengamatan | Kesimpulan |
|--------------|----------------------|------------|------------|
| Email dan password admin valid | Redirect ke dashboard admin | Redirect ke dashboard admin | Akses diterima |
| Email atau password salah | Pesan "Email atau Password salah" | Pesan "Email atau Password salah" | Akses ditolak |
| Email kosong | Pesan validasi "Email wajib diisi" | Pesan validasi "Email wajib diisi" | Akses ditolak |
| Format email tidak valid | Pesan "Format email tidak valid" | Pesan "Format email tidak valid" | Akses ditolak |

*Tabel 4.2 Pengujian Login Admin*

### 3. Logout

| Data Masukan | Hasil yang Diharapkan | Pengamatan | Kesimpulan |
|--------------|----------------------|------------|------------|
| Klik tombol logout | Redirect ke halaman login dan session terhapus | Redirect ke halaman login dan session terhapus | Logout berhasil |

*Tabel 4.3 Pengujian Logout*

## 4.8.2 Pengujian Presensi Karyawan

### 1. Presensi Masuk

| Data Masukan | Hasil yang Diharapkan | Pengamatan | Kesimpulan |
|--------------|----------------------|------------|------------|
| Lokasi dalam radius, foto valid | Pesan "Terima kasih, selamat bekerja" | Pesan "Terima kasih, selamat bekerja" | Presensi berhasil |
| Lokasi luar radius | Pesan "Anda berada di luar radius kantor" | Pesan "Anda berada di luar radius kantor" | Presensi ditolak |
| Tanpa foto selfie | Pesan "Format gambar tidak valid" | Pesan "Format gambar tidak valid" | Presensi ditolak |
| GPS tidak valid | Pesan "Lokasi tidak valid" | Pesan "Lokasi tidak valid" | Presensi ditolak |

*Tabel 4.4 Pengujian Presensi Masuk*

### 2. Presensi Keluar

| Data Masukan | Hasil yang Diharapkan | Pengamatan | Kesimpulan |
|--------------|----------------------|------------|------------|
| Lokasi dalam radius, sudah presensi masuk | Pesan "Terima kasih, hati-hati di jalan" | Pesan "Terima kasih, hati-hati di jalan" | Presensi berhasil |
| Lokasi luar radius | Pesan "Anda berada di luar radius kantor" | Pesan "Anda berada di luar radius kantor" | Presensi ditolak |
| Belum presensi masuk | Sistem deteksi sebagai presensi masuk | Sistem deteksi sebagai presensi masuk | Presensi berhasil |

*Tabel 4.5 Pengujian Presensi Keluar*

## 4.8.3 Pengujian Riwayat Presensi

### 1. Tampilan dan Pencarian Riwayat

| Data Masukan | Hasil yang Diharapkan | Pengamatan | Kesimpulan |
|--------------|----------------------|------------|------------|
| Pilih bulan dan tahun valid | Tampil data presensi sesuai periode | Tampil data presensi sesuai periode | Pencarian berhasil |
| Periode tanpa data | Pesan "Tidak ada data presensi" | Pesan "Tidak ada data presensi" | Pencarian berhasil |
| Bulan tidak dipilih | Pesan "Bulan wajib dipilih" | Pesan "Bulan wajib dipilih" | Pencarian ditolak |
| Tahun tidak dipilih | Pesan "Tahun wajib dipilih" | Pesan "Tahun wajib dipilih" | Pencarian ditolak |

*Tabel 4.6 Pengujian Riwayat Presensi*

## 4.8.4 Pengujian Pengajuan Izin

### 1. Pengajuan Izin/Sakit

| Data Masukan | Hasil yang Diharapkan | Pengamatan | Kesimpulan |
|--------------|----------------------|------------|------------|
| Data lengkap dan valid | Pesan "Data Berhasil Disimpan" | Pesan "Data Berhasil Disimpan" | Pengajuan berhasil |
| Tanggal izin kosong | Pesan "Tanggal izin wajib diisi" | Pesan "Tanggal izin wajib diisi" | Pengajuan ditolak |
| Status tidak dipilih | Pesan "Status izin wajib dipilih" | Pesan "Status izin wajib dipilih" | Pengajuan ditolak |
| Keterangan kosong | Pesan "Keterangan wajib diisi" | Pesan "Keterangan wajib diisi" | Pengajuan ditolak |
| Tanggal di masa lalu | Pesan "Tanggal tidak boleh di masa lalu" | Pesan "Tanggal tidak boleh di masa lalu" | Pengajuan ditolak |

*Tabel 4.7 Pengujian Pengajuan Izin*

### 2. Status Pengajuan Izin

| Data Masukan | Hasil yang Diharapkan | Pengamatan | Kesimpulan |
|--------------|----------------------|------------|------------|
| Filter berdasarkan status | Tampil data sesuai status filter | Tampil data sesuai status filter | Filter berhasil |
| Filter berdasarkan tanggal | Tampil data sesuai rentang tanggal | Tampil data sesuai rentang tanggal | Filter berhasil |

*Tabel 4.8 Pengujian Status Pengajuan Izin*

## 4.8.5 Pengujian Profile Karyawan

### 1. Update Profile

| Data Masukan | Hasil yang Diharapkan | Pengamatan | Kesimpulan |
|--------------|----------------------|------------|------------|
| Data profile valid | Pesan "Profile berhasil diupdate" | Pesan "Profile berhasil diupdate" | Update berhasil |
| Nama lengkap kosong | Pesan "Nama lengkap wajib diisi" | Pesan "Nama lengkap wajib diisi" | Update ditolak |
| Password < 6 karakter | Pesan "Password minimal 6 karakter" | Pesan "Password minimal 6 karakter" | Update ditolak |
| Konfirmasi password tidak cocok | Pesan "Konfirmasi password tidak cocok" | Pesan "Konfirmasi password tidak cocok" | Update ditolak |
| Foto > 2MB | Pesan "Ukuran foto maksimal 2MB" | Pesan "Ukuran foto maksimal 2MB" | Update ditolak |

*Tabel 4.9 Pengujian Update Profile*

## 4.8.6 Pengujian Data Master Karyawan (Admin)

### 1. Tambah Karyawan

| Data Masukan | Hasil yang Diharapkan | Pengamatan | Kesimpulan |
|--------------|----------------------|------------|------------|
| Data karyawan lengkap dan valid | Pesan "Data karyawan berhasil ditambahkan" | Pesan "Data karyawan berhasil ditambahkan" | Data tersimpan |
| NIK kosong | Pesan "NIK wajib diisi" | Pesan "NIK wajib diisi" | Data ditolak |
| NIK duplikat | Pesan "NIK sudah terdaftar" | Pesan "NIK sudah terdaftar" | Data ditolak |
| Nama lengkap kosong | Pesan "Nama lengkap wajib diisi" | Pesan "Nama lengkap wajib diisi" | Data ditolak |
| Foto format tidak didukung | Pesan "Format foto tidak didukung" | Pesan "Format foto tidak didukung" | Data ditolak |

*Tabel 4.10 Pengujian Tambah Karyawan*

### 2. Edit Karyawan

| Data Masukan | Hasil yang Diharapkan | Pengamatan | Kesimpulan |
|--------------|----------------------|------------|------------|
| Update data valid | Pesan "Data karyawan berhasil diupdate" | Pesan "Data karyawan berhasil diupdate" | Update berhasil |
| Data wajib dikosongkan | Pesan validasi sesuai field kosong | Pesan validasi sesuai field kosong | Update ditolak |

*Tabel 4.11 Pengujian Edit Karyawan*

### 3. Hapus Karyawan

| Data Masukan | Hasil yang Diharapkan | Pengamatan | Kesimpulan |
|--------------|----------------------|------------|------------|
| Hapus karyawan yang ada | Pesan "Data karyawan berhasil dihapus" | Pesan "Data karyawan berhasil dihapus" | Hapus berhasil |
| Konfirmasi hapus dibatalkan | Data tidak terhapus, tetap di list | Data tidak terhapus, tetap di list | Batal berhasil |

*Tabel 4.12 Pengujian Hapus Karyawan*

## 4.8.7 Pengujian Data Master Departemen (Admin)

### 1. Manajemen Departemen

| Data Masukan | Hasil yang Diharapkan | Pengamatan | Kesimpulan |
|--------------|----------------------|------------|------------|
| Tambah departemen valid | Pesan "Departemen berhasil ditambahkan" | Pesan "Departemen berhasil ditambahkan" | Tambah berhasil |
| Kode departemen duplikat | Pesan "Kode departemen sudah ada" | Pesan "Kode departemen sudah ada" | Tambah ditolak |
| Edit departemen | Pesan "Departemen berhasil diupdate" | Pesan "Departemen berhasil diupdate" | Edit berhasil |
| Hapus departemen | Pesan "Departemen berhasil dihapus" | Pesan "Departemen berhasil dihapus" | Hapus berhasil |

*Tabel 4.13 Pengujian Data Master Departemen*

## 4.8.8 Pengujian Konfigurasi Kantor (Admin)

### 1. Tambah/Edit Kantor

| Data Masukan | Hasil yang Diharapkan | Pengamatan | Kesimpulan |
|--------------|----------------------|------------|------------|
| Data kantor lengkap dan valid | Pesan "Data kantor berhasil disimpan" | Pesan "Data kantor berhasil disimpan" | Data tersimpan |
| Nama kantor kosong | Pesan "Nama kantor wajib diisi" | Pesan "Nama kantor wajib diisi" | Data ditolak |
| Latitude tidak valid (-90 to 90) | Pesan "Latitude harus antara -90 sampai 90" | Pesan "Latitude harus antara -90 sampai 90" | Data ditolak |
| Longitude tidak valid (-180 to 180) | Pesan "Longitude harus antara -180 sampai 180" | Pesan "Longitude harus antara -180 sampai 180" | Data ditolak |
| Radius < 1 meter | Pesan "Radius minimal 1 meter" | Pesan "Radius minimal 1 meter" | Data ditolak |
| Radius > 1000 meter | Pesan "Radius maksimal 1000 meter" | Pesan "Radius maksimal 1000 meter" | Data ditolak |

*Tabel 4.14 Pengujian Konfigurasi Kantor*

## 4.8.9 Pengujian Monitoring Presensi (Admin)

### 1. Monitoring Real-time

| Data Masukan | Hasil yang Diharapkan | Pengamatan | Kesimpulan |
|--------------|----------------------|------------|------------|
| Filter berdasarkan tanggal | Tampil data presensi sesuai tanggal | Tampil data presensi sesuai tanggal | Filter berhasil |
| Filter berdasarkan nama karyawan | Tampil data sesuai karyawan | Tampil data sesuai karyawan | Filter berhasil |
| Filter berdasarkan departemen | Tampil data sesuai departemen | Tampil data sesuai departemen | Filter berhasil |
| Export data keterlambatan | File export berhasil didownload | File export berhasil didownload | Export berhasil |

*Tabel 4.15 Pengujian Monitoring Presensi*

## 4.8.10 Pengujian Laporan Presensi (Admin)

### 1. Generate Laporan

| Data Masukan | Hasil yang Diharapkan | Pengamatan | Kesimpulan |
|--------------|----------------------|------------|------------|
| Filter periode valid | Tampil laporan sesuai periode | Tampil laporan sesuai periode | Laporan berhasil |
| Bulan tidak dipilih | Pesan "Bulan wajib dipilih" | Pesan "Bulan wajib dipilih" | Laporan gagal |
| Export ke PDF | File PDF berhasil didownload | File PDF berhasil didownload | Export berhasil |
| Export ke Excel | File Excel berhasil didownload | File Excel berhasil didownload | Export berhasil |
| Cetak per karyawan | PDF laporan individual berhasil | PDF laporan individual berhasil | Cetak berhasil |

*Tabel 4.16 Pengujian Laporan Presensi*

## 4.8.11 Pengujian Manajemen Pengajuan Izin (Admin)

### 1. Kelola Pengajuan Izin

| Data Masukan | Hasil yang Diharapkan | Pengamatan | Kesimpulan |
|--------------|----------------------|------------|------------|
| Approve pengajuan izin | Status berubah menjadi "Disetujui" | Status berubah menjadi "Disetujui" | Approve berhasil |
| Reject pengajuan izin | Status berubah menjadi "Ditolak" | Status berubah menjadi "Ditolak" | Reject berhasil |
| Edit pengajuan izin | Data berhasil diupdate | Data berhasil diupdate | Edit berhasil |
| Hapus pengajuan izin | Data berhasil dihapus | Data berhasil dihapus | Hapus berhasil |
| Export data pengajuan | File export berhasil didownload | File export berhasil didownload | Export berhasil |

*Tabel 4.17 Pengujian Manajemen Pengajuan Izin*

## 4.8.12 Pengujian Dashboard

### 1. Dashboard Karyawan

| Data Masukan | Hasil yang Diharapkan | Pengamatan | Kesimpulan |
|--------------|----------------------|------------|------------|
| Akses dashboard karyawan | Tampil info profile dan rekap presensi | Tampil info profile dan rekap presensi | Dashboard berhasil |
| Status presensi hari ini | Tampil status sudah/belum presensi | Tampil status sudah/belum presensi | Status akurat |
| Rekap bulanan | Tampil statistik kehadiran bulan ini | Tampil statistik kehadiran bulan ini | Rekap akurat |

*Tabel 4.18 Pengujian Dashboard Karyawan*

### 2. Dashboard Admin

| Data Masukan | Hasil yang Diharapkan | Pengamatan | Kesimpulan |
|--------------|----------------------|------------|------------|
| Akses dashboard admin | Tampil statistik keseluruhan sistem | Tampil statistik keseluruhan sistem | Dashboard berhasil |
| Total karyawan | Tampil jumlah karyawan terdaftar | Tampil jumlah karyawan terdaftar | Data akurat |
| Rekap presensi hari ini | Tampil summary presensi hari ini | Tampil summary presensi hari ini | Data akurat |

*Tabel 4.19 Pengujian Dashboard Admin*

## 4.8.13 Pengujian Keamanan dan Akses

### 1. Authorization

| Data Masukan | Hasil yang Diharapkan | Pengamatan | Kesimpulan |
|--------------|----------------------|------------|------------|
| Karyawan akses halaman admin | Redirect ke halaman tidak authorized | Redirect ke halaman tidak authorized | Akses ditolak |
| Admin akses halaman karyawan | Redirect ke dashboard admin | Redirect ke dashboard admin | Akses dibatasi |
| Session expired | Redirect ke halaman login | Redirect ke halaman login | Session berhasil |
| Akses tanpa login | Redirect ke halaman login | Redirect ke halaman login | Proteksi berhasil |

*Tabel 4.20 Pengujian Keamanan dan Akses*

## 4.8.14 Pengujian Responsivitas

### 1. Mobile Responsive

| Data Masukan | Hasil yang Diharapkan | Pengamatan | Kesimpulan |
|--------------|----------------------|------------|------------|
| Akses dari smartphone | Tampilan responsive dan user-friendly | Tampilan responsive dan user-friendly | Mobile berhasil |
| Fitur kamera untuk selfie | Kamera dapat diakses dan foto tersimpan | Kamera dapat diakses dan foto tersimpan | Kamera berhasil |
| Touch navigation | Menu dan tombol mudah disentuh | Menu dan tombol mudah disentuh | Navigation berhasil |
| GPS location | GPS dapat diakses dan akurat | GPS dapat diakses dan akurat | GPS berhasil |

*Tabel 4.21 Pengujian Mobile Responsive*

---

**Catatan:**
- Kolom "Pengamatan" diisi saat melakukan testing aktual
- Kolom "Kesimpulan" berisi hasil akhir: berhasil/gagal
- Testing dilakukan dengan metode Black Box (fokus input-output)
- Setiap test case harus didokumentasikan dengan screenshot jika diperlukan 