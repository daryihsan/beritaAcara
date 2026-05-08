# SIMBAP — Sistem Informasi Manajemen Berita Acara Pemeriksaan

> Aplikasi web berbasis Laravel untuk pengelolaan **Berita Acara Pemeriksaan (BAP)** di lingkungan Balai Besar POM di Semarang. Mendukung pembuatan, pengeditan, penandatanganan digital, ekspor PDF/Excel, serta audit log aktivitas.

---

## Daftar Isi

- [Tentang Proyek](#tentang-proyek)
- [Fitur Utama](#fitur-utama)
- [Teknologi yang Digunakan](#teknologi-yang-digunakan)
- [Arsitektur Aplikasi](#arsitektur-aplikasi)
- [Struktur Direktori](#struktur-direktori)
- [Skema Database](#skema-database)
- [Hak Akses & Peran Pengguna](#hak-akses--peran-pengguna)
- [Prasyarat](#prasyarat)
- [Instalasi (Tanpa Docker)](#instalasi-tanpa-docker)
- [Instalasi (Dengan Docker)](#instalasi-dengan-docker)
- [Konfigurasi Environment](#konfigurasi-environment)
- [Panduan Penggunaan](#panduan-penggunaan)
- [Rute Aplikasi](#rute-aplikasi)
- [Komponen Frontend](#komponen-frontend)
- [Ekspor Data](#ekspor-data)
- [Keamanan](#keamanan)

---

## Tentang Proyek

SIMBAP adalah sistem manajemen dokumen internal yang dirancang khusus untuk **Balai Besar Pengawas Obat dan Makanan (BPOM) di Semarang**. Sistem ini mendigitalisasi proses pencatatan hasil pemeriksaan lapangan, mulai dari pengisian data BAP, penandatanganan digital oleh petugas, hingga pengesahan dokumen oleh kepala balai.

---

## Fitur Utama

### Manajemen Berita Acara Pemeriksaan (BAP)
- **Buat BAP baru** dengan formulir bertab (multi-step): data kepala surat, data objek, data petugas, data hasil, dan kelengkapan dokumen.
- **Edit BAP** yang sudah ada (hanya oleh petugas yang terdaftar atau admin).
- **Hapus BAP** (hanya admin) dengan soft delete untuk keamanan data.
- **Nomor surat tugas** digunakan sebagai referensi utama dokumen.

### Penandatanganan Digital
- Setiap petugas yang tercantum di BAP dapat memberikan **tanda tangan digital** melalui kanvas interaktif (menggunakan library `signature_pad`).
- Tanda tangan disimpan secara **aman di storage privat** (tidak dapat diakses langsung via URL publik).
- Tanda tangan dirender langsung di dalam dokumen PDF.

### Pengesahan Dokumen
- Admin atau petugas dapat **mengunggah file pengesahan** (gambar/PDF) sebagai bukti dokumen telah disahkan oleh kepala balai.
- File pengesahan disimpan di `public/uploads/pengesahan/` dengan nama file unik berbasis ULID.

### Ekspor Dokumen
- **Cetak PDF** per BAP dengan layout resmi berkop surat dan footer instansi.
- **Rekap Excel** seluruh BAP dengan pemformatan kolom otomatis.
- **Rekap PDF** daftar BAP dalam format tabel.
- Semua ekspor dibatasi **rate limit 5 request/menit** untuk mencegah penyalahgunaan.

### Dashboard & Statistik
- Grafik statistik jumlah BAP per tahun (personal dan global).
- Filter tampilan data berdasarkan tahun.
- Admin dapat memfilter data berdasarkan NIP petugas tertentu.

### Manajemen Pengguna
- Sistem berbasis **dua peran**: `admin` dan `petugas`.
- Login menggunakan **NIP** sebagai identitas unik pengguna.
- Throttle login: **maksimal 5 percobaan per menit**.

### Activity Log (Audit Trail)
- Setiap operasi create, update, dan delete pada BAP **dicatat secara otomatis** menggunakan `spatie/laravel-activitylog`.
- Log mencatat: siapa yang melakukan perubahan, field apa yang berubah, dan nilai lama/baru.
- Halaman khusus activity log hanya dapat diakses oleh admin, dengan tampilan DataTable server-side.

---

## Teknologi yang Digunakan

### Backend
| Komponen | Versi | Keterangan |
|---|---|---|
| PHP | ^8.2 | Bahasa pemrograman utama |
| Laravel | ^12.0 | Framework PHP |
| MySQL | 8.0 | Database utama (via Docker) |
| barryvdh/laravel-dompdf | ^3.1 | Generate PDF dari Blade template |
| maatwebsite/excel | ^3.1 | Export Excel |
| yajra/laravel-datatables | ^12.0 | Server-side DataTables |
| spatie/laravel-activitylog | ^4.10 | Audit trail / activity log |
| intervention/image | ^2 | Pemrosesan gambar (resize tanda tangan) |

### Frontend
| Komponen | Versi | Keterangan |
|---|---|---|
| Tailwind CSS | ^4.0 | CSS utility-first framework |
| Vite | ^7.0 | Build tool frontend |
| jQuery | ^4.0 | Manipulasi DOM |
| DataTables (Bootstrap 5) | ^2.3 | Tabel interaktif server-side |
| Chart.js | ^4.5 | Grafik statistik dashboard |
| signature_pad | ^5.1 | Kanvas tanda tangan digital |
| SweetAlert2 | — | Dialog konfirmasi dan notifikasi |

### DevOps
| Komponen | Keterangan |
|---|---|
| Docker | Containerisasi aplikasi |
| Docker Compose | Orkestrasi multi-container |
| Apache 2 | Web server di dalam container |
| phpMyAdmin | Antarmuka manajemen database (port 8081) |

---

## Arsitektur Aplikasi

Aplikasi ini mengikuti pola **MVC (Model-View-Controller)** dengan tambahan lapisan **Service**, **DTO (Data Transfer Object)**, dan **Policy** untuk pemisahan tanggung jawab yang lebih bersih.

```
Request (HTTP)
    │
    ▼
┌─────────────────────────────┐
│  Middleware                 │  auth, role:admin, throttle
└─────────────┬───────────────┘
              │
    ▼
┌─────────────────────────────┐
│  Controller                 │  BeritaAcaraController
│  (Thin Controller)          │  ActivityLogController
└─────────────┬───────────────┘
              │
    ▼
┌─────────────────────────────┐
│  Form Request               │  StoreBeritaAcaraRequest (validasi)
└─────────────┬───────────────┘
              │
    ▼
┌─────────────────────────────┐
│  DTO                        │  BapDto, PetugasDto
│  (Data Transfer Object)     │  (memetakan input request ke objek)
└─────────────┬───────────────┘
              │
    ▼
┌─────────────────────────────┐
│  Service Layer              │  BeritaAcaraService
│                             │  ImageService, PdfService
└─────────────┬───────────────┘
              │
    ▼
┌─────────────────────────────┐
│  Model (Eloquent ORM)       │  BeritaAcara, User
│  + Policy                   │  BeritaAcaraPolicy
└─────────────┬───────────────┘
              │
    ▼
┌─────────────────────────────┐
│  Database (MySQL)           │  berita_acara, users, berita_acara_user,
│                             │  activity_log
└─────────────────────────────┘
```

### Multi-Stage Docker Build

Dockerfile menggunakan tiga stage untuk efisiensi:

```
Stage 1 (vendor)  →  Composer install (tanpa dev dependencies)
Stage 2 (assets)  →  npm ci + Vite build
Stage 3 (final)   →  PHP 8.2 + Apache (copy hasil Stage 1 & 2)
```

---

## Struktur Direktori

```
beritaAcara-verif/
│
├── app/
│   ├── DataTransferObjects/
│   │   ├── BapDto.php              # DTO untuk data BAP (mapping request → object)
│   │   └── PetugasDto.php          # DTO untuk data petugas
│   │
│   ├── Exceptions/
│   │   └── BapException.php        # Custom exception untuk operasi BAP
│   │
│   ├── Exports/
│   │   └── BeritaAcaraExport.php   # Kelas export Excel (Maatwebsite)
│   │
│   ├── Helpers/
│   │   ├── DateHelper.php          # Format tanggal Indonesia
│   │   └── TerbilangHelper.php     # Konversi angka ke teks (terbilang)
│   │
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   └── LoginController.php         # Login & logout
│   │   │   ├── Export/
│   │   │   │   ├── ExcelController.php          # Export rekap Excel
│   │   │   │   └── PdfController.php            # Export rekap PDF list
│   │   │   ├── ActivityLogController.php        # Halaman activity log (admin)
│   │   │   ├── BeritaAcaraController.php        # Controller utama BAP (CRUD, DataTable, PDF)
│   │   │   └── PrivateFileController.php        # Serve file tanda tangan privat
│   │   │
│   │   ├── Middleware/
│   │   │   └── RoleMiddleware.php   # Middleware pengecekan role (admin/petugas)
│   │   │
│   │   └── Requests/
│   │       └── StoreBeritaAcaraRequest.php  # Validasi form BAP
│   │
│   ├── Models/
│   │   ├── BeritaAcara.php          # Model BAP (SoftDeletes, ActivityLog, HasUlids)
│   │   └── User.php                 # Model User (HasUlids)
│   │
│   ├── Policies/
│   │   └── BeritaAcaraPolicy.php    # Otorisasi akses BAP per user
│   │
│   ├── Providers/
│   │   └── AppServiceProvider.php
│   │
│   └── Services/
│       ├── BeritaAcaraService.php   # Logika bisnis utama: CRUD, sync petugas, activity log
│       ├── ImageService.php         # Simpan tanda tangan, proses gambar untuk PDF
│       └── PdfService.php          # Generate PDF BAP via DomPDF
│
├── config/
│   ├── bap.php                      # Konfigurasi khusus: daftar pejabat penandatangan, format tanggal
│   └── dompdf.php                   # Konfigurasi DomPDF
│
├── database/
│   ├── migrations/
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 0001_01_01_000001_create_cache_table.php
│   │   ├── 2026_01_07_014446_create_berita_acara_table.php
│   │   ├── 2026_01_07_014918_create_berita_acara_user_table.php
│   │   └── 2026_01_21_043457_create_activity_log_table.php
│   │
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── BeritaAcaraSeeder.php    # Data contoh BAP
│       └── UserSeeder.php           # Data user petugas dan admin
│
├── public/
│   ├── assets/img/
│   │   ├── SIMBAP.png              # Logo aplikasi
│   │   ├── bpom.png                # Logo BPOM
│   │   ├── headerpdf.png           # Header kop surat PDF
│   │   └── footerpdf.png           # Footer PDF
│   └── uploads/pengesahan/         # File pengesahan BAP yang diunggah
│
├── resources/
│   ├── css/
│   │   ├── app.css
│   │   └── components/             # CSS per komponen (auth, datatables, sweetalert, dsb)
│   │
│   ├── js/
│   │   ├── app.js
│   │   └── components/
│   │       ├── auth.js             # Logika form login
│   │       ├── bapUpload.js        # Upload file pengesahan
│   │       ├── dashboard.js        # Grafik Chart.js dashboard
│   │       ├── datatable.js        # Inisialisasi DataTable server-side
│   │       ├── loader.js           # Indikator loading
│   │       ├── signature.js        # Kanvas tanda tangan digital
│   │       ├── ui.js               # Helper UI umum
│   │       ├── unsavedHandler.js   # Cegah navigasi saat ada perubahan belum disimpan
│   │       └── validation.js       # Validasi form sisi klien
│   │
│   └── views/
│       ├── admin/activity_log/     # Tampilan log aktivitas (admin)
│       ├── auth/login.blade.php    # Halaman login
│       ├── bap/
│       │   ├── index.blade.php     # Halaman daftar BAP
│       │   ├── form.blade.php      # Form create/edit BAP (multi-tab)
│       │   ├── pdf.blade.php       # Template PDF BAP
│       │   └── partials/           # Partial view per tab form
│       ├── dashboard/menu.blade.php  # Dashboard utama
│       ├── exports/                # Template Excel & PDF rekap
│       └── layouts/                # Layout utama, navbar, sidebar
│
├── routes/
│   └── web.php                     # Definisi seluruh rute web
│
├── storage/app/private/signatures/ # Penyimpanan file tanda tangan (privat)
│
├── .env.example                    # Template environment variable
├── Dockerfile                      # Multi-stage Docker build
├── docker-compose.yml              # Konfigurasi Docker Compose
├── composer.json                   # Dependency PHP
├── package.json                    # Dependency Node.js
└── vite.config.js                  # Konfigurasi Vite
```

---

## Skema Database

### Tabel `users`
| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | ULID (PK) | ID unik user (ULID) |
| `nip` | string (unique) | Nomor Induk Pegawai, digunakan sebagai username login |
| `name` | string | Nama lengkap |
| `pangkat` | string | Pangkat/golongan pegawai |
| `jabatan` | string | Jabatan pegawai |
| `role` | string | Peran: `admin` atau `petugas` |
| `password` | string | Password terenkripsi (bcrypt) |

### Tabel `berita_acara`
| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | ULID (PK) | ID unik BAP (ULID) |
| `no_surat_tugas` | string | Nomor surat tugas |
| `tgl_surat_tugas` | date | Tanggal surat tugas |
| `tanggal_pemeriksaan` | date | Tanggal pelaksanaan pemeriksaan |
| `hari` | string | Nama hari pemeriksaan (Senin, dll.) |
| `kepala_balai_text` | text | Teks nama/jabatan kepala balai penandatangan |
| `objek_nama` | string | Nama objek yang diperiksa |
| `objek_alamat` | text | Alamat lengkap objek |
| `objek_kota` | string | Kota/kabupaten lokasi objek |
| `dalam_rangka` | text | Tujuan/konteks pemeriksaan |
| `hasil_pemeriksaan` | text | Uraian hasil pemeriksaan |
| `yang_diperiksa` | string | Item/aspek yang diperiksa |
| `file_pengesahan` | string (nullable) | Path file pengesahan yang diunggah |
| `created_by` | ULID (FK → users) | User yang membuat BAP |
| `deleted_at` | timestamp | Soft delete |

> **Index:** `(tanggal_pemeriksaan, created_at)` dan `(no_surat_tugas, objek_nama, tanggal_pemeriksaan)` untuk performa query.

### Tabel `berita_acara_user` (Pivot)
| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint (PK) | ID auto-increment |
| `berita_acara_id` | ULID (FK → berita_acara) | Referensi ke BAP |
| `user_nip` | string (FK → users.nip) | NIP petugas yang ditugaskan |
| `pangkat` | string (nullable) | Pangkat saat BAP dibuat (snapshot) |
| `jabatan` | string (nullable) | Jabatan saat BAP dibuat (snapshot) |
| `ttd` | longText (nullable) | Path file tanda tangan digital |

### Tabel `activity_log`
Dikelola otomatis oleh `spatie/laravel-activitylog`. Menyimpan event `created`, `updated`, `deleted` beserta properties (perubahan field).

---

## Hak Akses & Peran Pengguna

| Aksi | Petugas | Admin |
|---|---|---|
| Login | ✅ | ✅ |
| Lihat daftar BAP (miliknya) | ✅ | ✅ |
| Lihat daftar BAP (semua) | ❌ | ✅ |
| Buat BAP baru | ✅ | ✅ |
| Edit BAP (yang ditugaskan) | ✅ | ✅ |
| Upload tanda tangan | ✅ | ✅ |
| Upload file pengesahan | ✅ | ✅ |
| Cetak PDF per BAP | ✅ (jika terdaftar) | ✅ |
| Ekspor Excel/PDF rekap | ✅ | ✅ |
| Hapus BAP | ❌ | ✅ |
| Assign petugas ke BAP | ❌ | ✅ |
| Lihat activity log | ❌ | ✅ |
| Filter BAP berdasarkan petugas | ❌ | ✅ |

---

## Prasyarat

Sebelum instalasi, pastikan sistem memiliki:

- **PHP** >= 8.2 dengan ekstensi: `pdo_mysql`, `mbstring`, `exif`, `pcntl`, `bcmath`, `gd`, `zip`
- **Composer** >= 2.x
- **Node.js** >= 20.x dan **npm**
- **MySQL** 8.0 atau SQLite (untuk development)
- **Git**

Atau, jika menggunakan Docker:
- **Docker** >= 24.x
- **Docker Compose** >= 2.x

---

## Instalasi (Tanpa Docker)

### 1. Clone atau Ekstrak Proyek
```bash
# Jika dari zip
unzip beritaAcara-verif.zip
cd beritaAcara-verif
```

### 2. Install Dependency PHP
```bash
composer install
```

### 3. Konfigurasi Environment
```bash
cp .env.example .env
php artisan key:generate
```

Edit file `.env` sesuai kebutuhan. Untuk development dengan SQLite (default):
```dotenv
DB_CONNECTION=sqlite
```

Untuk MySQL:
```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_bap
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 4. Migrasi & Seeder Database
```bash
php artisan migrate

# Opsional: isi data contoh (user + BAP sample)
php artisan db:seed
```

### 5. Install Dependency Frontend & Build Assets
```bash
npm install
npm run build
```

### 6. Jalankan Aplikasi
```bash
# Mode development (semua proses sekaligus)
composer run dev

# Atau manual
php artisan serve
npm run dev
```

Akses aplikasi di: **http://localhost:8000**

---

## Instalasi (Dengan Docker)

Metode ini adalah cara yang direkomendasikan untuk deployment atau environment yang konsisten.

### 1. Konfigurasi Environment
```bash
cp .env.example .env
```

Edit `.env` untuk menggunakan konfigurasi MySQL Docker:
```dotenv
APP_KEY=           # Akan diisi manual atau via artisan

DB_CONNECTION=mysql
DB_HOST=bap-db     # Nama service Docker
DB_PORT=3306
DB_DATABASE=db_bap
DB_USERNAME=root
DB_PASSWORD=rootpassword
```

### 2. Build & Jalankan Container
```bash
docker compose up -d --build
```

Container yang akan berjalan:
| Container | Service | Port |
|---|---|---|
| `bap-laravel` | Aplikasi PHP + Apache | `8080` |
| `bap-mysql` | Database MySQL 8.0 | `4406` (host) |
| `bap-phpmyadmin` | phpMyAdmin | `8081` |

### 3. Setup Aplikasi di Dalam Container
```bash
# Masuk ke container aplikasi
docker exec -it bap-laravel bash

# Generate app key
php artisan key:generate

# Jalankan migrasi
php artisan migrate

# Opsional: seeder
php artisan db:seed

# Keluar
exit
```

### 4. Akses Aplikasi
- **Aplikasi:** http://localhost:8080
- **phpMyAdmin:** http://localhost:8081

### Menghentikan Container
```bash
docker compose down
# Tambahkan -v untuk menghapus volume database juga
docker compose down -v
```

---

## Konfigurasi Environment

Penjelasan variabel penting di file `.env`:

```dotenv
# Nama aplikasi yang tampil di UI
APP_NAME=SIMBAP

# Mode: local | production
APP_ENV=local

# Wajib diisi (generate dengan: php artisan key:generate)
APP_KEY=

# URL publik aplikasi
APP_URL=http://localhost

# Koneksi database: sqlite | mysql
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_bap
DB_USERNAME=root
DB_PASSWORD=

# Driver session (database direkomendasikan)
SESSION_DRIVER=database
SESSION_LIFETIME=120   # Dalam menit

# Queue untuk proses background (database untuk simplisitas)
QUEUE_CONNECTION=database

# Cache
CACHE_STORE=database

# Log level: debug | info | warning | error
LOG_LEVEL=debug
```

---

## Panduan Penggunaan

### Login
1. Buka aplikasi di browser.
2. Masukkan **NIP** dan **Password**.
3. Sistem membatasi 5 percobaan login per menit.

### Membuat BAP Baru
1. Klik tombol **+ Buat BAP** di dashboard atau halaman daftar.
2. Isi formulir yang terdiri dari beberapa tab:
   - **Tab Kepala:** Nomor & tanggal surat tugas, nama kepala balai penandatangan.
   - **Tab Objek:** Nama, alamat, kota objek yang diperiksa, konteks, dan item yang diperiksa.
   - **Tab Petugas:** Tambahkan satu atau lebih petugas dengan NIP, pangkat, jabatan, dan tanda tangan digital.
   - **Tab Hasil:** Tanggal pemeriksaan dan uraian hasil pemeriksaan.
   - **Tab Kelengkapan:** Upload file pengesahan (opsional).
3. Klik **Simpan**.

### Menandatangani BAP
1. Buka formulir edit BAP.
2. Pada tab Petugas, klik area kanvas tanda tangan di bawah nama petugas.
3. Buat tanda tangan menggunakan mouse/touchscreen.
4. Simpan formulir.

### Mencetak PDF
1. Dari daftar BAP, klik tombol **PDF** pada baris yang diinginkan.
2. Browser akan membuka/mengunduh file PDF dengan format resmi berkop surat instansi.

### Ekspor Rekap
1. Di halaman daftar BAP, gunakan tombol **Export Excel** atau **Export PDF List**.
2. File akan diunduh otomatis sesuai filter tahun yang sedang aktif.

### Activity Log (Admin)
1. Buka menu **Log Aktivitas** di sidebar (hanya muncul untuk admin).
2. Lihat seluruh riwayat perubahan data BAP secara kronologis.

---

## Rute Aplikasi

| Method | URL | Nama Route | Akses | Keterangan |
|---|---|---|---|---|
| GET | `/` | — | Publik | Redirect ke login |
| GET | `/login` | `login` | Guest | Form login |
| POST | `/login` | — | Guest | Proses login (throttle 5/menit) |
| POST | `/logout` | `logout` | Auth | Logout |
| GET | `/dashboard` | `dashboard` | Auth | Dashboard utama + statistik |
| GET | `/berita-acara/create` | `berita-acara.create` | Auth | Form buat BAP baru |
| POST | `/berita-acara` | `berita-acara.store` | Auth | Simpan BAP baru |
| GET | `/berita-acara/{id}/edit` | `berita-acara.edit` | Auth | Form edit BAP |
| PUT | `/berita-acara/{id}` | `berita-acara.update` | Auth | Update BAP |
| DELETE | `/berita-acara/{id}` | `berita-acara.destroy` | Admin | Hapus BAP |
| GET | `/berita-acara/{id}/pdf` | `berita-acara.pdf` | Auth | Cetak PDF (throttle 5/menit) |
| GET | `/berita-acara/datatable` | — | Auth | Data server-side DataTable |
| POST | `/berita-acara/{id}/upload` | `berita-acara.upload` | Auth | Upload file pengesahan |
| POST | `/berita-acara/assign` | `berita-acara.assign` | Admin | Assign petugas ke BAP |
| GET | `/berita-acara/export/excel` | `berita-acara.export.excel` | Auth | Export rekap Excel |
| GET | `/berita-acara/export/pdf-list` | `berita-acara.export.pdflist` | Auth | Export rekap PDF list |
| GET | `/admin/berita-acara` | `admin.bap.index` | Admin | Daftar BAP semua petugas |
| GET | `/admin/activity-log` | `admin.activity-log` | Admin | Halaman activity log |
| GET | `/admin/activity-log/datatable` | — | Admin | Data server-side log |
| GET | `/private/signature/{filename}` | `private.signature` | Auth | Serve file tanda tangan privat |

---

## Komponen Frontend

### JavaScript (`resources/js/components/`)

| File | Fungsi |
|---|---|
| `auth.js` | Validasi dan UX form login |
| `bapUpload.js` | Preview dan upload file pengesahan via AJAX |
| `dashboard.js` | Inisialisasi dan render grafik Chart.js per tahun |
| `datatable.js` | Setup DataTable server-side dengan filter tahun dan petugas |
| `loader.js` | Tampilkan/sembunyikan overlay loading saat request AJAX |
| `signature.js` | Inisialisasi kanvas tanda tangan, clear, dan serialize ke base64 |
| `ui.js` | Helper umum: toggle, helper tab, interaksi UI |
| `unsavedHandler.js` | Intercept navigasi keluar saat form belum disimpan |
| `validation.js` | Validasi form sisi klien sebelum submit |

### Konfigurasi Pejabat Penandatangan (`config/bap.php`)

Daftar pilihan nama/jabatan pejabat yang berwenang menandatangani BAP dapat dikonfigurasi di sini tanpa mengubah kode aplikasi:

```php
'pejabat_penandatangan' => [
    'Balai Besar POM di Semarang' => 'Kepala Balai',
    'Plh. Kepala Balai'           => 'Plh. Kepala Balai',
    'Plt. Kepala Balai'           => 'Plt. Kepala Balai',
],
```

---

## Ekspor Data

### PDF per BAP
- Template: `resources/views/bap/pdf.blade.php`
- Library: DomPDF (`barryvdh/laravel-dompdf`)
- Ukuran kertas: **A4 Portrait**
- Menyertakan: kop surat instansi, data BAP lengkap, tanda tangan digital petugas, footer.
- Memory limit dinaikkan ke **512MB** saat generate PDF untuk menangani gambar tanda tangan.

### Rekap Excel
- Template: `resources/views/exports/bap_rekap_excel.blade.php`
- Library: Maatwebsite Excel (`maatwebsite/excel`)
- Kolom: No, No. Surat Tugas, Petugas, Tgl Pemeriksaan, Tgl BAP, Objek, Alamat, Kota/Kab.
- Lebar kolom otomatis dan header diberi styling (bold, background abu-abu).

### Rekap PDF List
- Template: `resources/views/exports/bap_rekap_pdf.blade.php`
- Library: DomPDF
- Format tabel ringkas semua BAP sesuai filter aktif.

---

## Keamanan

- **Autentikasi:** Session-based dengan Laravel Auth. Password di-hash menggunakan bcrypt (12 rounds).
- **Otorisasi:** Laravel Policy (`BeritaAcaraPolicy`) memastikan petugas hanya dapat mengakses BAP yang mencantumkan NIP mereka. Admin dapat mengakses semua.
- **Role Middleware:** `RoleMiddleware` memblokir akses route admin untuk non-admin dengan HTTP 403.
- **Rate Limiting:** Login dibatasi 5 percobaan/menit. Endpoint ekspor dan PDF dibatasi 5 request/menit.
- **File Tanda Tangan Privat:** Disimpan di `storage/app/private/` (tidak dapat diakses langsung). Diakses melalui route `/private/signature/{filename}` yang dilindungi autentikasi.
- **Validasi Input:** Semua input divalidasi melalui `StoreBeritaAcaraRequest`. Data yang dirender di PDF disanitasi dengan `htmlspecialchars` dan `strip_tags`.
- **Soft Delete:** Data BAP tidak dihapus permanen, hanya di-soft delete untuk keamanan audit.
- **ULID:** Semua primary key menggunakan ULID (bukan auto-increment integer) untuk mencegah enumerasi ID.
- **Audit Trail:** Seluruh perubahan data BAP dicatat secara otomatis di tabel activity log.
