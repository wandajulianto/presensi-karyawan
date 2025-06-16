# KAMUS DATA SISTEM PRESENSI KARYAWAN

## DAFTAR TABEL

| No | Nama Tabel | Deskripsi | Jumlah Field |
|----|------------|-----------|--------------|
| 1  | users | Data admin/pengguna sistem | 7 |
| 2  | karyawan | Data master karyawan | 7 |
| 3  | departemen | Data master departemen | 4 |
| 4  | presensi | Data transaksi presensi harian | 9 |
| 5  | pengajuan_izin | Data pengajuan izin/sakit karyawan | 7 |
| 6  | kantor | Data master lokasi kantor | 12 |
| 7  | sessions | Data sesi login pengguna | 6 |

---

## 1. TABEL USERS

**Deskripsi**: Menyimpan data admin/pengguna yang dapat mengakses dashboard admin

| No | Field Name | Type | Length | Constraint | Default | Deskripsi |
|----|------------|------|--------|------------|---------|-----------|
| 1 | id | BIGINT | 20 | PK, AUTO_INCREMENT, NOT NULL | - | Primary key, ID unik user |
| 2 | name | VARCHAR | 255 | NOT NULL | - | Nama lengkap admin |
| 3 | email | VARCHAR | 255 | NOT NULL, UNIQUE | - | Email admin untuk login |
| 4 | email_verified_at | TIMESTAMP | - | NULL | NULL | Waktu verifikasi email |
| 5 | password | VARCHAR | 255 | NOT NULL | - | Password terenkripsi |
| 6 | remember_token | VARCHAR | 100 | NULL | NULL | Token untuk remember me |
| 7 | created_at | TIMESTAMP | - | NULL | CURRENT_TIMESTAMP | Waktu pembuatan record |
| 8 | updated_at | TIMESTAMP | - | NULL | CURRENT_TIMESTAMP | Waktu update terakhir |

**Index:**
- PRIMARY KEY: id
- UNIQUE KEY: email

**Contoh Data:**
```sql
INSERT INTO users VALUES 
(1, 'Admin HRD', 'admin@company.com', NOW(), '$2y$10$...', NULL, NOW(), NOW());
```

---

## 2. TABEL KARYAWAN

**Deskripsi**: Menyimpan data master karyawan yang dapat melakukan presensi

| No | Field Name | Type | Length | Constraint | Default | Deskripsi |
|----|------------|------|--------|------------|---------|-----------|
| 1 | nik | BIGINT | 20 | PK, NOT NULL | - | Nomor Induk Karyawan |
| 2 | nama_lengkap | VARCHAR | 100 | NOT NULL | - | Nama lengkap karyawan |
| 3 | jabatan | VARCHAR | 20 | NOT NULL | - | Posisi/jabatan karyawan |
| 4 | no_hp | VARCHAR | 13 | NOT NULL | - | Nomor HP/WhatsApp |
| 5 | password | VARCHAR | 255 | NOT NULL | - | Password terenkripsi |
| 6 | foto | VARCHAR | 255 | NULL | NULL | Path file foto profil |
| 7 | kode_departemen | VARCHAR | 10 | FK, NULL | NULL | Referensi ke tabel departemen |
| 8 | remember_token | VARCHAR | 100 | NULL | NULL | Token untuk remember me |

**Index:**
- PRIMARY KEY: nik
- FOREIGN KEY: kode_departemen REFERENCES departemen(kode_departemen)
- INDEX: kode_departemen

**Constraint:**
- Foreign Key: kode_departemen → departemen.kode_departemen (ON DELETE RESTRICT, ON UPDATE CASCADE)

**Contoh Data:**
```sql
INSERT INTO karyawan VALUES 
(12345, 'John Doe', 'Staff IT', '081234567890', '$2y$10$...', 'foto1.jpg', 'IT', NULL);
```

---

## 3. TABEL DEPARTEMEN

**Deskripsi**: Menyimpan data master departemen/divisi perusahaan

| No | Field Name | Type | Length | Constraint | Default | Deskripsi |
|----|------------|------|--------|------------|---------|-----------|
| 1 | kode_departemen | VARCHAR | 10 | PK, NOT NULL | - | Kode unik departemen |
| 2 | nama_departemen | VARCHAR | 100 | NOT NULL | - | Nama lengkap departemen |
| 3 | created_at | TIMESTAMP | - | NULL | CURRENT_TIMESTAMP | Waktu pembuatan record |
| 4 | updated_at | TIMESTAMP | - | NULL | CURRENT_TIMESTAMP | Waktu update terakhir |

**Index:**
- PRIMARY KEY: kode_departemen

**Contoh Data:**
```sql
INSERT INTO departemen VALUES 
('IT', 'Information Technology', NOW(), NOW()),
('HRD', 'Human Resources Development', NOW(), NOW()),
('FIN', 'Finance', NOW(), NOW());
```

---

## 4. TABEL PRESENSI

**Deskripsi**: Menyimpan data transaksi presensi harian karyawan

| No | Field Name | Type | Length | Constraint | Default | Deskripsi |
|----|------------|------|--------|------------|---------|-----------|
| 1 | id | BIGINT | 20 | PK, AUTO_INCREMENT, NOT NULL | - | Primary key unik |
| 2 | nik | BIGINT | 20 | FK, NOT NULL | - | Referensi ke NIK karyawan |
| 3 | tanggal_presensi | DATE | - | NOT NULL | - | Tanggal presensi |
| 4 | jam_masuk | TIME | - | NOT NULL | - | Waktu presensi masuk |
| 5 | jam_keluar | TIME | - | NULL | NULL | Waktu presensi keluar |
| 6 | foto_masuk | VARCHAR | 255 | NOT NULL | - | Path foto saat masuk |
| 7 | foto_keluar | VARCHAR | 255 | NULL | NULL | Path foto saat keluar |
| 8 | lokasi_masuk | TEXT | - | NOT NULL | - | GPS coordinates masuk |
| 9 | lokasi_keluar | TEXT | - | NULL | NULL | GPS coordinates keluar |

**Index:**
- PRIMARY KEY: id
- INDEX: nik
- INDEX: tanggal_presensi
- COMPOSITE INDEX: nik, tanggal_presensi

**Constraint:**
- Foreign Key: nik → karyawan.nik (ON DELETE CASCADE)

**Format Data:**
- lokasi_masuk/keluar: "latitude,longitude" (contoh: "-6.2088,106.8456")
- foto_masuk/keluar: "timestamp_filename.jpg" (contoh: "1640995200_foto_masuk.jpg")

**Contoh Data:**
```sql
INSERT INTO presensi VALUES 
(1, 12345, '2024-01-15', '08:00:00', '17:00:00', 
 '1640995200_masuk.jpg', '1641024000_keluar.jpg',
 '-6.2088,106.8456', '-6.2088,106.8456');
```

---

## 5. TABEL PENGAJUAN_IZIN

**Deskripsi**: Menyimpan data pengajuan izin/sakit karyawan

| No | Field Name | Type | Length | Constraint | Default | Deskripsi |
|----|------------|------|--------|------------|---------|-----------|
| 1 | id | BIGINT | 20 | PK, AUTO_INCREMENT, NOT NULL | - | Primary key unik |
| 2 | nik | BIGINT | 20 | FK, NOT NULL | - | Referensi ke NIK karyawan |
| 3 | tanggal_izin | DATE | - | NOT NULL | - | Tanggal izin yang diajukan |
| 4 | status | ENUM | - | NOT NULL | - | Jenis izin: 'i'=izin, 's'=sakit |
| 5 | keterangan | TEXT | - | NOT NULL | - | Alasan/keterangan izin |
| 6 | status_approved | TINYINT | 1 | NOT NULL | 0 | Status approval: 0=pending, 1=approved, 2=rejected |
| 7 | created_at | TIMESTAMP | - | NULL | CURRENT_TIMESTAMP | Waktu pengajuan |
| 8 | updated_at | TIMESTAMP | - | NULL | CURRENT_TIMESTAMP | Waktu update terakhir |

**Index:**
- PRIMARY KEY: id
- FOREIGN KEY: nik
- INDEX: status_approved
- INDEX: tanggal_izin

**Constraint:**
- Foreign Key: nik → karyawan.nik (ON DELETE CASCADE)
- ENUM values: status IN ('i', 's')
- CHECK: status_approved IN (0, 1, 2)

**Keterangan Status:**
- status: 'i' = Izin, 's' = Sakit
- status_approved: 0 = Pending, 1 = Disetujui, 2 = Ditolak

**Contoh Data:**
```sql
INSERT INTO pengajuan_izin VALUES 
(1, 12345, '2024-01-20', 'i', 'Acara keluarga', 1, NOW(), NOW());
```

---

## 6. TABEL KANTOR

**Deskripsi**: Menyimpan data master lokasi kantor untuk validasi GPS

| No | Field Name | Type | Length | Constraint | Default | Deskripsi |
|----|------------|------|--------|------------|---------|-----------|
| 1 | id | BIGINT | 20 | PK, AUTO_INCREMENT, NOT NULL | - | Primary key unik |
| 2 | nama_kantor | VARCHAR | 255 | NOT NULL | - | Nama lokasi kantor |
| 3 | alamat | TEXT | - | NOT NULL | - | Alamat lengkap kantor |
| 4 | latitude | DECIMAL | 10,8 | NOT NULL | - | Koordinat latitude |
| 5 | longitude | DECIMAL | 11,8 | NOT NULL | - | Koordinat longitude |
| 6 | radius_meter | INT | 11 | NOT NULL | 20 | Radius validasi dalam meter |
| 7 | kode_kantor | VARCHAR | 50 | NOT NULL, UNIQUE | - | Kode unik kantor |
| 8 | deskripsi | TEXT | - | NULL | NULL | Deskripsi tambahan |
| 9 | is_active | BOOLEAN | 1 | NOT NULL | TRUE | Status aktif kantor |
| 10 | timezone | VARCHAR | 50 | NOT NULL | 'Asia/Jakarta' | Timezone kantor |
| 11 | jam_masuk | TIME | - | NOT NULL | '07:00:00' | Jam mulai kerja |
| 12 | jam_keluar | TIME | - | NOT NULL | '17:00:00' | Jam selesai kerja |
| 13 | created_at | TIMESTAMP | - | NULL | CURRENT_TIMESTAMP | Waktu pembuatan |
| 14 | updated_at | TIMESTAMP | - | NULL | CURRENT_TIMESTAMP | W   |

**Index:**
- PRIMARY KEY: id
- UNIQUE KEY: kode_kantor
- INDEX: is_active

**Constraint:**
- latitude: BETWEEN -90 AND 90
- longitude: BETWEEN -180 AND 180
- radius_meter: > 0

**Contoh Data:**
```sql
INSERT INTO kantor VALUES 
(1, 'Kantor Pusat', 'Jl. Sudirman No. 123 Jakarta', 
 -6.20876400, 106.84560100, 50, 'KP001', 
 'Gedung utama perusahaan', TRUE, 'Asia/Jakarta', 
 '07:00:00', '17:00:00', NOW(), NOW());
```

---

## 7. TABEL SESSIONS

**Deskripsi**: Menyimpan data sesi login pengguna untuk keamanan

| No | Field Name | Type | Length | Constraint | Default | Deskripsi |
|----|------------|------|--------|------------|---------|-----------|
| 1 | id | VARCHAR | 255 | PK, NOT NULL | - | Session ID unik |
| 2 | user_id | BIGINT | 20 | FK, NULL | NULL | ID user yang login |
| 3 | ip_address | VARCHAR | 45 | NULL | NULL | IP address pengguna |
| 4 | user_agent | TEXT | - | NULL | NULL | Browser user agent |
| 5 | payload | TEXT | - | NOT NULL | - | Data session terenkripsi |
| 6 | last_activity | INT | 11 | NOT NULL | - | Timestamp aktivitas terakhir |

**Index:**
- PRIMARY KEY: id
- INDEX: user_id
- INDEX: last_activity

**Contoh Data:**
```sql
INSERT INTO sessions VALUES 
('abc123def456', 1, '192.168.1.100', 
 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', 
 'encrypted_payload_data', 1640995200);
```

---

## RELASI ANTAR TABEL

### Diagram Relasi:
```
DEPARTEMEN (1) ←→ (N) KARYAWAN (1) ←→ (N) PRESENSI
                      ↓ (1)
                      ↓
                   (N) PENGAJUAN_IZIN

USERS (1) ←→ (N) SESSIONS

KANTOR (Independent table untuk validasi GPS)
```

### Detail Relasi:

1. **DEPARTEMEN → KARYAWAN**
   - Type: One-to-Many
   - Key: departemen.kode_departemen = karyawan.kode_departemen
   - Constraint: ON DELETE RESTRICT, ON UPDATE CASCADE

2. **KARYAWAN → PRESENSI**
   - Type: One-to-Many
   - Key: karyawan.nik = presensi.nik
   - Constraint: ON DELETE CASCADE

3. **KARYAWAN → PENGAJUAN_IZIN**
   - Type: One-to-Many
   - Key: karyawan.nik = pengajuan_izin.nik
   - Constraint: ON DELETE CASCADE

4. **USERS → SESSIONS**
   - Type: One-to-Many
   - Key: users.id = sessions.user_id
   - Constraint: ON DELETE CASCADE

---

## BUSINESS RULES & CONSTRAINTS

### 1. Rules untuk Presensi:
- Satu karyawan hanya dapat presensi masuk 1x dan keluar 1x per hari
- Lokasi presensi harus dalam radius yang ditentukan di tabel kantor
- Foto presensi wajib di-upload (format: JPG/PNG, max 2MB)
- Presensi masuk: jam 06:00 - 10:00
- Presensi keluar: jam 15:00 - 20:00

### 2. Rules untuk Pengajuan Izin:
- Tanggal izin tidak boleh mundur (harus >= hari ini)
- Satu karyawan dapat mengajukan beberapa izin untuk tanggal berbeda
- Status default: pending (0)
- Hanya admin yang dapat approve/reject

### 3. Rules untuk Data Master:
- NIK karyawan harus unik
- Email admin harus unik
- Kode departemen harus unik
- Kode kantor harus unik

### 4. Rules untuk GPS Validation:
- Radius minimum: 10 meter
- Radius maksimum: 500 meter
- Koordinat harus valid (latitude: -90 to 90, longitude: -180 to 180)

---

## NAMING CONVENTION

### Table Names:
- Menggunakan lowercase
- Singular form (karyawan, bukan karyawans)
- Underscore untuk pemisah kata

### Field Names:
- Menggunakan snake_case
- Descriptive names
- Consistent naming (created_at, updated_at untuk semua tabel)

### Index Names:
- PRIMARY KEY: table_name_pkey
- FOREIGN KEY: fk_table_field
- INDEX: idx_table_field

---

## DATA TYPES EXPLANATION

### VARCHAR vs TEXT:
- **VARCHAR**: Fixed length, untuk data dengan panjang terbatas
- **TEXT**: Variable length, untuk data panjang seperti alamat, keterangan

### BIGINT vs INT:
- **BIGINT**: Untuk ID yang mungkin besar (seperti NIK)
- **INT**: Untuk nilai numerik biasa (radius_meter)

### DECIMAL untuk GPS:
- **DECIMAL(10,8)**: Latitude dengan 8 digit decimal precision
- **DECIMAL(11,8)**: Longitude dengan 8 digit decimal precision

### TIMESTAMP vs DATE vs TIME:
- **TIMESTAMP**: Full datetime dengan timezone
- **DATE**: Hanya tanggal (YYYY-MM-DD)
- **TIME**: Hanya waktu (HH:MM:SS)

---

## SECURITY CONSIDERATIONS

### 1. Password Storage:
- Menggunakan bcrypt hashing
- Salt value otomatis dari Laravel

### 2. Session Management:
- Session ID di-encrypt
- Auto-expire setelah tidak aktif
- IP address tracking

### 3. File Upload:
- Validasi ekstensi file
- Rename file dengan timestamp
- Stored outside web directory

### 4. GPS Data:
- Koordinat disimpan sebagai string
- Validasi range koordinat
- Double validation (client + server)

---

## MAINTENANCE NOTES

### 1. Backup Strategy:
- Daily backup untuk tabel transaksi (presensi, pengajuan_izin)
- Weekly backup untuk master data
- Monthly archive untuk data lama

### 2. Index Optimization:
- Monitor query performance
- Add composite index jika diperlukan
- Regular ANALYZE TABLE

### 3. Data Cleanup:
- Archive data presensi > 2 tahun
- Cleanup session data > 30 hari
- Regular log cleanup

### 4. Monitoring:
- Track table size growth
- Monitor foreign key violations
- Alert untuk data anomali 