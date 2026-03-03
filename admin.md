# 📘 DOKUMEN ARSITEKTUR SISTEM INFORMASI SEKOLAH

## (SIAKAD + PPDB)

# 1️⃣ ARSITEKTUR ROLE & HAK AKSES

## 1.1 Role Utama (5 Role)

Sistem menggunakan:

* Laravel Authentication
* Laravel Policy
* Middleware berbasis role
* Permission berbasis database (opsional spatie/laravel-permission)

---

## 🔐 1. Admin

Akses penuh tanpa batas.

### Hak Akses:

* Master Data
* Akademik
* PPDB
* Informasi Publik
* Manajemen Sistem
* Tahun Ajaran Aktif
* Impersonate User
* Log Aktivitas penuh

Admin adalah root controller sistem.

---

## 🏫 2. Kepala Sekolah

Akses hampir semua modul operasional.

### Dapat:

* Kelola Data Siswa
* Kelola Data Guru
* Akses seluruh Akademik
* Akses seluruh PPDB
* Akses Informasi Publik
* Lihat Log Aktivitas

### Tidak Dapat:

* Manajemen Sistem inti
* Role & Permission
* Impersonate
* Pengaturan global sistem

---

## 👨‍🏫 3. Guru

Akses berbasis kelompok mengajar.

### Dapat:

* Lihat siswa kelompok sendiri
* Input absensi kelompok sendiri
* Kelola Materi KBM kelompok sendiri
* Lihat Kalender Akademik
* Edit profil sendiri

### Tidak Dapat:

* Akses PPDB
* Manajemen Sistem
* Kelompok lain
* Ubah Tahun Ajaran aktif

Semua pembatasan dikunci melalui:

* Policy
* Query Scope
* Middleware Role

---

## 🧾 4. Operator

Fokus pada operasional sekolah.

### Dapat:

* Kelola Data Siswa
* Akademik
* PPDB
* Galeri
* Berita
* Buku Tamu

### Tidak Dapat:

* Manajemen Sistem
* Role & Permission
* Impersonate
* Pengaturan global

---

## 👨‍👩‍👧 5. Orang Tua / Calon Siswa

Login khusus PPDB.

### Dapat:

* Isi formulir PPDB
* Upload dokumen
* Lihat status
* Lihat pengumuman

---

# 2️⃣ STRUKTUR NAVIGASI DASHBOARD

Tanpa Filament. Menggunakan:

* Layout Dashboard Blade
* Sidebar dinamis berdasarkan role

---

## 📂 A. Master Data

* Data Siswa
* Data Guru
* Tahun Ajaran

## 📚 B. Akademik

* Absensi Siswa
* Absensi Guru
* Materi KBM
* Kalender Akademik

## 🏫 C. PPDB

* Pendaftaran
* Verifikasi Dokumen
* Pengumuman
* Settings PPDB
* Riwayat PPDB
* Export Data

## 🌐 D. Informasi Publik

* Galeri
* Kegiatan Sekolah
* Buku Tamu

## ⚙ E. Manajemen Sistem (Admin Only)

* Kelola User
* Role & Permission
* Log Aktivitas

---

# 3️⃣ DETAIL MODUL SISTEM

---

# A. DATA SISWA

## A.1 Struktur Tabel: `siswas`

### Identitas Anak

* nis
* nisn
* nik_anak
* nama_lengkap_anak
* nama_panggilan_anak
* tempat_lahir
* tanggal_lahir
* jenis_kelamin
* agama

### Alamat

* alamat
* provinsi
* kota_kabupaten
* kecamatan
* kelurahan
* nama_jalan

### Data Tambahan

* anak_ke
* tinggal_bersama
* berat_badan
* tinggi_badan
* golongan_darah
* penyakit_pernah_diderita
* imunisasi_pernah_diterima

### Data Ayah

* nama_ayah
* nik_ayah
* tempat_lahir_ayah
* tanggal_lahir_ayah
* pendidikan_ayah
* pekerjaan_ayah
* bidang_pekerjaan_ayah
* penghasilan_per_bulan_ayah
* no_hp_ayah
* email_ayah

### Data Ibu

* nama_ibuan
* nik_ibu
* tempat_lahir_ibu
* tanggal_lahir_ibu
* pendidikan_ibu
* pekerjaan_ibu
* bidang_pekerjaan_ibu
* penghasilan_per_bulan_ibu
* no_hp_ibu
* email_ibu

### Data Wali

* punya_wali (boolean)
* nama_wali
* hubungan_wali
* nik_wali
* pekerjaan_wali
* nomor_telepon_wali

### Kontak Umum

* no_hp_ortu
* email_ortu

### Sistem Field Wajib

* id
* tahun_ajaran_id
* status (aktif / lulus / pindah / nonaktif)
* created_at
* updated_at
* deleted_at (soft delete)

---

## A.2 Aturan Sistem

* Tidak ada tabel siswa_lulus.
* Status mengontrol arsip.
* Filter berdasarkan Tahun Ajaran.
* Global Scope Tahun Ajaran aktif diterapkan pada Model.

---

## A.3 Absensi Siswa

Validasi:

* Tidak boleh double input tanggal yang sama.
* Tidak boleh input tanggal masa depan.
* Harus sesuai Tahun Ajaran aktif.
* Guru hanya akses kelompoknya.

Validasi dilakukan di:

* Form Request
* Database Unique Index
* Policy

---

# B. DATA GURU

## Struktur Tabel: `gurus`

* nip
* nama
* jabatan
* kelompok_mengajar
* tahun_ajaran_id
* created_at
* updated_at
* deleted_at

---

## Absensi Guru

Status:

* Masuk
* Sakit
* Izin

Validasi:

* Unique (guru_id + tanggal)
* Tidak boleh tanggal masa depan

Fitur:

* Export Excel (Laravel Excel)

---

# C. PPDB

## Struktur Tabel: `ppdbs`

Semua field sesuai daftar yang kamu berikan.

Tambahan sistem:

* id
* tahun_ajaran_id
* user_id (orang tua)
* status
* catatan_admin
* created_at
* updated_at
* deleted_at

---

## Status PPDB

1. Draft
2. Menunggu Verifikasi
3. Revisi Dokumen
4. Dokumen Verified
5. Lulus
6. Tidak Lulus

Status mengontrol:

* Hak upload
* Tampilan dashboard
* Akses pengumuman

---

## Validasi Khusus

* Email unique per Tahun Ajaran
* NIK unique per Tahun Ajaran
* Otomatis terikat Tahun Ajaran aktif

Gunakan composite unique index:
(nik_anak, tahun_ajaran_id)

---

# D. AKADEMIK

## Kalender Akademik

* Judul
* Deskripsi
* Tanggal
* Foto
* tahun_ajaran_id

## Materi KBM

* judul
* deskripsi
* file
* kelompok_id
* guru_id
* tahun_ajaran_id

Policy membatasi akses guru.

---

# E. INFORMASI PUBLIK

## Galeri

* foto
* video
* caption

## Kegiatan

* judul
* isi
* gambar_utama (wajib)

## Buku Tamu

* nama
* email
* pesan
* tanggal

---

# F. MANAJEMEN SISTEM

## Manajemen User

* Admin
* Kepala Sekolah
* Guru
* Operator
* Orang Tua

Fitur:

* CRUD user
* Reset password
* Upload foto
* Impersonate

---

## Log Aktivitas (WAJIB)

Tabel: `activity_logs`

Field:

* user_id
* role
* aksi
* tabel
* data_id
* deskripsi_perubahan
* ip_address
* user_agent
* created_at

Dicatat saat:

* Login / Logout
* CRUD data
* Perubahan status siswa
* Perubahan status PPDB
* Reset password
* Export data
* Impersonate
* Perubahan Tahun Ajaran aktif

Log hanya bisa dihapus oleh Admin.

---

# 4️⃣ STRUKTUR DATABASE GLOBAL

Semua tabel utama wajib memiliki:

* id
* tahun_ajaran_id
* created_at
* updated_at
* deleted_at (untuk data penting)

Gunakan:

* Foreign Key Constraint
* Soft Delete
* Global Scope Tahun Ajaran aktif

---

# 5️⃣ ATURAN LOGIKA SISTEM

1. Tahun Ajaran aktif bersifat global.
2. Data lintas tahun tidak boleh bercampur.
3. Guru hanya akses kelompoknya.
4. Status PPDB mengontrol upload.
5. Jika PPDB Closed → tombol daftar hilang otomatis.
6. Semua aksi penting masuk log.
7. Semua resource wajib memiliki Policy.

---

# 6️⃣ KESIMPULAN

Sistem ini:

✔ Terstruktur berbasis Tahun Ajaran
✔ Tidak tergantung panel generator
✔ Aman dengan audit log lengkap
✔ Role dikontrol Laravel Policy
✔ Siap dikembangkan skala besar
✔ Siap digunakan sebagai sistem operasional sekolah