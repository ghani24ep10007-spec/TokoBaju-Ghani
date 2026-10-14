# 🛍️ Toko Baju Ghani - Fullstack Web Application

![CI/CD Pipeline](https://github.com/ghani24ep10007-spec/TokoBaju-Ghani/actions/workflows/ci-cd.yml/badge.svg)
![Laravel](https://img.shields.io/badge/Laravel-10.x-FF2D20?style=flat&logo=laravel&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-Containerized-2496ED?style=flat&logo=docker&logoColor=white)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-15-4169E1?style=flat&logo=postgresql&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green.svg)

Aplikasi web modern **Toko Baju Ghani** dan Sistem Manajemen Praktikum Pemrograman Web yang dibangun menggunakan arsitektur **Laravel 10**, dikontainerisasi penuh menggunakan **Docker & Docker Compose**, didukung basis data relasional **PostgreSQL 15**, serta dilengkapi **CI/CD Pipeline (GitHub Actions)** otomatis yang siap untuk kolaborasi tim (*collaborator-ready*).

---

## 📌 Repositori Resmi & Akun Kolaborasi
Proyek ini dikembangkan dan disinkronkan pada dua akun GitHub:
* **Akun Utama (Spesifikasi Akademik):** [ghani24ep10007-spec / TokoBaju-Ghani](https://github.com/ghani24ep10007-spec/TokoBaju-Ghani)
* **Akun Personal / Portofolio:** [ghaniadinata / TokoBaju-Ghani](https://github.com/ghaniadinata)

---

## ✨ Fitur Utama
1. **Katalog Busana Distro & Fashion Store:**
   - Filter busana per kategori (Kaos & T-Shirt, Kemeja Flanel, Outerwear/Hoodie, Celana Chino, Batik Modern).
   - Tampilan responsif modern dengan Tailwind CSS & font Plus Jakarta Sans.
   - Manajemen produk (Tambah produk baru ke PostgreSQL dan Hapus produk).
2. **Interactive Shopping Cart & Instant Checkout:**
   - Keranjang belanja interaktif berbasis slider drawer.
   - Perhitungan total otomatis dan integrasi langsung ke pemesanan WhatsApp.
3. **Modul Manajemen Data Mahasiswa (Praktikum SI):**
   - CRUD lengkap data mahasiswa (NIM, Nama, Program Studi, Status) tersimpan di PostgreSQL.
4. **CI/CD Pipeline Terintegrasi (GitHub Actions):**
   - Linting sintaks PHP 8.2 otomatis pada setiap *push* dan *pull request*.
   - Validasi struktur file dan template `.env.example`.
   - Otomatisasi pengujian *build* container Docker dan migrasi database.
5. **Multi-Container Architecture:**
   - Layanan web aplikasi: PHP 8.2 CLI / Laravel (`laravel_app` pada port 8000).
   - Layanan database: PostgreSQL 15 Alpine (`postgres_db` pada port 5432).

---

## 🏗️ Struktur Direktori Proyek
```
├── .github/
│   ├── workflows/
│   │   └── ci-cd.yml          # GitHub Actions CI/CD Pipeline Otomatis
│   └── pull_request_template.md # Template standar PR kolaborator
├── app/
│   ├── Http/Controllers/      # Controller Product & Mahasiswa
│   └── Models/                # Eloquent Model (Product & Mahasiswa)
├── bootstrap/                 # Inisialisasi Kernel Laravel
├── config/                    # Konfigurasi Database PostgreSQL & Aplikasi
├── database/
│   ├── migrations/            # File migrasi tabel mahasiswa & products
│   ├── seeders/               # File seeder katalog Toko Baju
│   └── migrate.php            # Standalone migration script
├── public/
│   ├── index.php              # Entrypoint web server aplikasi
│   └── index.html             # Landing page dokumentasi WebCraft Agency
├── resources/
│   └── views/                 # Template Blade & UI
├── routes/
│   ├── web.php                # Rute web utama
│   └── api.php                # Rute RESTful API
├── .dockerignore
├── .env.example               # Template environment kredensial
├── .gitignore                 # Filter file build, cache, dan installer
├── artisan                    # CLI Artisan Laravel
├── composer.json              # Spesifikasi dependensi Laravel 10 & PHP 8.2
├── Dockerfile                 # Definisi image PHP 8.2 & ekstensi PostgreSQL
├── docker-compose.yml         # Konfigurasi container app + postgres
├── setup-and-run.ps1          # Script otomatisasi 1-klik di Windows PowerShell
└── CONTRIBUTING.md            # Panduan alur kolaborasi GitHub
```

---

## 🚀 Panduan Memulai Cepat (Quick Start)

### Cara 1: Menggunakan Script Otomatis (Windows PowerShell)
Buka terminal PowerShell di folder proyek, lalu jalankan:
```powershell
.\setup-and-run.ps1
```
Script akan memeriksa status Docker, membangun container, menjalankan migrasi database, dan membuka browser di `http://localhost:8000`.

---

### Cara 2: Menggunakan Docker Compose Manual
```bash
# 1. Salin konfigurasi environment
cp .env.example .env

# 2. Bangun dan jalankan kontainer
docker compose up -d --build

# 3. Jalankan migrasi tabel database PostgreSQL
docker compose exec app php artisan migrate

# 4. (Opsional) Isi data katalog produk
docker compose exec app php artisan db:seed
```
Buka aplikasi di web browser: **[http://localhost:8000](http://localhost:8000)**.

---

## 🔄 Sinkronisasi Git & Kolaborasi Dual Account

### 1. Menambahkan Remote untuk Akun `ghaniadinata`
Agar Anda dapat melakukan push ke kedua akun GitHub (`ghani24ep10007-spec` dan `ghaniadinata`):

```bash
# Tambahkan remote baru untuk ghaniadinata
git remote add adinata https://github.com/ghaniadinata/TokoBaju-Ghani.git

# Mengatur agar git push origin main langsung push ke kedua akun sekaligus:
git remote set-url --add --push origin https://github.com/ghani24ep10007-spec/TokoBaju-Ghani.git
git remote set-url --add --push origin https://github.com/ghaniadinata/TokoBaju-Ghani.git
```

### 2. Cara Menambahkan Akun sebagai Kolaborator di GitHub
Jika Anda ingin akun `ghaniadinata` langsung memiliki akses tulis penuh pada repositori `ghani24ep10007-spec/TokoBaju-Ghani` (atau sebaliknya):
1. Buka [https://github.com/ghani24ep10007-spec/TokoBaju-Ghani/settings/access](https://github.com/ghani24ep10007-spec/TokoBaju-Ghani/settings/access).
2. Klik tombol hijau **Add people**.
3. Ketik **`ghaniadinata`**.
4. Klik **Add ghaniadinata to this repository**.
5. Buka akun `ghaniadinata` (atau email yang terdaftar) dan klik **Accept Invitation**.
6. Selesai! Kedua akun kini menjadi kolaborator resmi dengan hak akses penuh.

---

## 🛡️ Lisensi & Hak Cipta
Dikembangkan untuk kebutuhan Praktikum Pemrograman Web & Portofolio Rekayasa Perangkat Lunak. Lisensi di bawah [MIT License](LICENSE).
