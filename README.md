<p align="center">
    <a href="https://laravel.com" target="_blank">
        <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
    </a>
</p>

<p align="center">
    <a href="https://github.com/daryihsan/beritaAcara/actions"><img src="https://github.com/daryihsan/beritaAcara/workflows/tests/badge.svg" alt="Build Status"></a>
    <a href="https://github.com/daryihsan/beritaAcara"><img src="https://img.shields.io/github/license/daryihsan/beritaAcara" alt="License"></a>
</p>

# Berita Acara

Aplikasi manajemen Berita Acara (Official Minutes/Report) berbasis web yang dibangun dengan Laravel. Sistem ini dirancang untuk memudahkan pembuatan, pengelolaan, dan dokumentasi berita acara secara digital.

## Tentang Aplikasi

Berita Acara adalah aplikasi berbasis web yang memungkinkan pengguna untuk:

- 📝 Membuat dan mengelola berita acara dengan mudah
- 📊 Menyimpan dan mengorganisir dokumen berita acara
- 🔍 Mencari dan mengakses arsip berita acara
- 👥 Manajemen pengguna dan hak akses
- 📄 Ekspor berita acara ke berbagai format
- 🔐 Keamanan data yang terjamin

## Teknologi yang Digunakan

Aplikasi ini dibangun menggunakan teknologi modern:

- **Framework**: Laravel 11.x
- **Frontend**: Blade Templates, Vite
- **Database**: MySQL/PostgreSQL
- **CSS Framework**: Tailwind CSS / Bootstrap
- **PHP Version**: 8.2+

## Persyaratan Sistem

Sebelum menginstal aplikasi, pastikan sistem Anda memenuhi persyaratan berikut:

- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL >= 5.7 atau PostgreSQL >= 10
- Web Server (Apache/Nginx)

## Instalasi

Ikuti langkah-langkah berikut untuk menginstal aplikasi:

### 1. Clone Repository

```bash
git clone https://github.com/daryihsan/beritaAcara.git
cd beritaAcara
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install
```

### 3. Konfigurasi Environment

```bash
# Copy file environment
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Konfigurasi Database

Edit file `.env` dan sesuaikan konfigurasi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=berita_acara
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Migrasi Database

```bash
# Jalankan migrasi
php artisan migrate

# (Optional) Jalankan seeder untuk data dummy
php artisan db:seed
```

### 6. Build Assets

```bash
# Development
npm run dev

# Production
npm run build
```

### 7. Jalankan Aplikasi

```bash
php artisan serve
```

Aplikasi akan berjalan di `http://localhost:8000`

## Fitur Utama

### 1. Manajemen Berita Acara
- Pembuatan berita acara baru dengan template
- Edit dan update berita acara
- Hapus berita acara
- Preview sebelum finalisasi

### 2. Sistem Pengguna
- Autentikasi pengguna
- Role dan permission management
- Profile management

### 3. Pencarian dan Filter
- Pencarian berdasarkan judul, tanggal, atau kategori
- Filter data dengan berbagai parameter
- Sorting data

### 4. Ekspor Dokumen
- Ekspor ke format PDF
- Ekspor ke format Word/Excel
- Print-friendly view

## Struktur Direktori

```
beritaAcara/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   └── Middleware/
│   ├── Models/
│   └── Services/
├── config/
├── database/
│   ├── migrations/
│   └── seeders/
├── public/
├── resources/
│   ├── views/
│   ├── css/
│   └── js/
├── routes/
│   ├── web.php
│   └── api.php
├── storage/
└── tests/
```

## Penggunaan

### Login ke Aplikasi

1. Akses aplikasi melalui browser
2. Masukkan kredensial login
3. Dashboard akan muncul setelah login berhasil

### Membuat Berita Acara Baru

1. Klik menu "Buat Berita Acara"
2. Isi form dengan informasi yang diperlukan
3. Tambahkan lampiran jika diperlukan
4. Klik "Simpan" untuk menyimpan draft atau "Submit" untuk finalisasi

### Mengelola Berita Acara

1. Akses menu "Daftar Berita Acara"
2. Gunakan filter dan pencarian untuk menemukan dokumen
3. Klik aksi yang diinginkan (View/Edit/Delete/Export)

## Testing

Jalankan automated tests dengan perintah:

```bash
# Semua test
php artisan test

# Test spesifik
php artisan test --filter=NamaTest
```

## Deployment

### Persiapan Production

```bash
# Optimize application
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Build production assets
npm run build
```

### Server Requirements

Pastikan server production memiliki:
- PHP 8.2+ dengan ekstensi yang diperlukan
- Composer
- Database (MySQL/PostgreSQL)
- Web server yang dikonfigurasi dengan benar

## Kontribusi

Kontribusi sangat diterima! Jika Anda ingin berkontribusi:

1. Fork repository ini
2. Buat branch baru (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan Anda (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

## Changelog

Lihat file [CHANGELOG.md](CHANGELOG.md) untuk riwayat perubahan versi.

## Lisensi

Aplikasi ini menggunakan lisensi [MIT License](LICENSE).

## Kontak

Dary Ihsan - [@daryihsan](https://github.com/daryihsan)

Project Link: [https://github.com/daryihsan/beritaAcara](https://github.com/daryihsan/beritaAcara)

## Acknowledgments

- [Laravel](https://laravel.com) - The PHP Framework
- [Tailwind CSS](https://tailwindcss.com) - CSS Framework
- [Blade](https://laravel.com/docs/blade) - Template Engine

---

<p align="center">Made with ❤️ using Laravel</p>