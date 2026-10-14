# Product Requirements Document (PRD) & Software Requirements Specification (SRS)
## Platform Manajemen Jasa Pembuatan Website (Web Agency & Client Lifecycle Management)

**Kode Dokumen:** PRD-SRS-WEBDEV-2026-V1  
**Versi:** 1.0.0  
**Tanggal Rilis:** 14 Oktober 2026  
**Status:** Baseline Draft & Technical Architecture Specification  
**Mata Kuliah:** Pemrograman Web (Semester 5) - Proyek Antigravity & Docker Deployment  
**Format Acuan SRS:** Standar Internasional ISO/IEC/IEEE 29148:2018  

---

# BAGIAN I: PRODUCT REQUIREMENTS DOCUMENT (PRD)

---

## 1. Ringkasan Eksekutif & Informasi Dokumen

| Properti | Detail Spesifikasi |
| :--- | :--- |
| **Nama Produk** | **WebCraft Agency Platform** (Sistem Manajemen Layanan, Pemesanan, Pelacakan Proyek, & Finansial Jasa Website) |
| **Versi Dokumen** | v1.0.0 |
| **Status Dokumen** | Ready for Review, Development, & Technical Implementation |
| **Target Pengguna** | Klien Bisnis/UMKM, Project Manager (PM), UI/UX Designer, Web Developer, Bagian Keuangan (Finance), Super Admin Agency |
| **Platform Implementasi** | Responsive Web Application (Docker Containerized, Laravel 10/11, PostgreSQL 15, TailwindCSS / Blade) |
| **Lingkungan Runtime** | Docker Engine & Docker Compose (`laravel_app` [Port 8000], `postgres_db` [Port 5432]) |

---

## 2. Latar Belakang & Permasalahan

Dalam industri jasa pembuatan website (web development agency / software house), sering dijumpai berbagai hambatan operasional yang menyebabkan keterlambatan proyek, ketidakpuasan klien, dan kerugian finansial agency:

1. **Komunikasi Tercecer & Kehilangan Jejak Brief (Unstructured Brief Intake):**
   * Pengumpulan materi teks, logo, aset foto, dan referensi desain sering kali dilakukan melalui percakapan instan (WhatsApp/Telegram) tanpa struktur. Hal ini menyebabkan desainer dan programmer menerima informasi yang tidak lengkap dan format aset yang tidak memadai (misal: logo resolusi rendah).
2. **Pelebaran Lingkup Proyek Tanpa Kontrol (*Scope Creep*):**
   * Tidak adanya batasan kuota revisi dan kriteria penerimaan (*acceptance criteria*) yang jelas di setiap fase (desain wireframe, slicing frontend, integrasi CMS/backend). Klien sering meminta penambahan fitur di luar kesepakatan awal tanpa penyesuaian biaya (*change request*).
3. **Keterlambatan Penagihan Termin (*Delayed Cash Flow & Unpaid Invoices*):**
   * Mekanisme pembayaran jasa website umumnya menggunakan skema termin (misal: DP 50% di awal dan pelunasan 50% sebelum *handover* kredensial). Tanpa sistem *invoicing* otomatis dengan *payment gateway*, bendahara agency harus menagih secara manual, mencocokkan mutasi bank secara manual, dan rentan terhadap penundaan serah terima akun.
4. **Ketiadaan Transparansi Status Pengerjaan (*Lack of Real-time Visibility*):**
   * Klien merasa cemas karena tidak mengetahui fase pengerjaan website yang sedang berjalan. Akibatnya timbul pertanyaan repetitif harian ke tim teknis yang mengganggu fokus *coding*.
5. **Penyimpanan Kredensial Hosting/Server yang Tidak Aman (*Insecure Handover*):**
   * Penyerahan akun cPanel, domain registrar, panel database, dan akun CMS dilakukan secara terbuka lewat chat tanpa enkripsi data sensitif, menimbulkan risiko kebocoran keamanan.

---

## 3. Tujuan Produk & Sasaran (Objectives & Metrics)

* **Otomatisasi Onboarding & Brief Intake:** Mengurangi waktu tunggu pengumpulan aset dan kuesioner brief dari rata-rata 5–7 hari menjadi kurang dari 24 jam melalui formulir terpandu (*guided onboarding form*).
* **Pengendalian Revisi & Pencegahan Scope Creep:** Mengurangi konflik revisi hingga 45% dengan menerapkan *Revision Ticketing System* berbasis kuota paket yang disepakati.
* **Akselerasi Arus Kas (Payment Velocity):** Meningkatkan kepatuhan pembayaran termin DP dan Pelunasan tepat waktu hingga 90% melalui *invoice gateway* (Virtual Account & QRIS) serta penguncian penyerahan kredensial otomatis sebelum status *invoice* lunas.
* **Transparansi Proyek Terukur:** Memberikan dashboard pelacakan progres berbasis *Milestone Kanban* yang dapat diakses mandiri oleh klien 24/7.
* **Standardisasi Deployment & Kemudahan Pemeliharaan:** Sistem dikemas penuh menggunakan kontainer Docker (`Dockerfile` dan `docker-compose.yml`) untuk konsistensi di lokal mahasiswa, server staging, hingga server produksi.

---

## 4. Matriks Peran Pengguna (User Roles & Permissions)

| Peran (*Role*) | Tanggung Jawab Utama | Akses Fitur Utama |
| :--- | :--- | :--- |
| **Super Admin** | Konfigurasi sistem global, manajemen pengguna, audit log, integrasi API gateway. | Akses penuh (*Full Access*), manajemen hak akses, backup database, system setting. |
| **Project Manager (PM)** | Mengelola siklus proyek, validasi brief, alokasi desainer/developer, verifikasi revisi. | Board proyek, penentuan deadline milestone, review tiket revisi klien, persetujuan handover. |
| **Developer / Designer** | Mengerjakan task teknis, memperbarui status milestone, menyematkan URL live staging demo. | Akses task assigned, submit progress, update link demo preview, merespons tiket revisi. |
| **Finance / Kasir** | Penagihan termin, verifikasi faktur, pengelolaan beban pengeluaran (pembelian domain/hosting vendor). | Modul invoice, verifikasi manual transfer, laporan arus kas, pencatatan beban pihak ketiga. |
| **Klien (Client)** | Pemesanan paket website, pengisian brief, pembayaran termin, persetujuan milestone, tiket revisi. | Portal Klien, form submit brief/aset, pembayaran via Payment Gateway, unduh invoice/kuitansi, ambil kredensial serah terima. |

---

## 5. Ruang Lingkup & Kebutuhan Fungsional (MoSCoW)

### Modul 1: Katalog Paket Jasa & Kalkulator Biaya (Must-Have)
* **Katalog Paket:** Landing page publik yang menampilkan paket pembuatan website standar (misal: *Landing Page UMKM*, *Company Profile Pro*, *E-Commerce Store*, *Custom Web Application*).
* **Kalkulator Biaya Interaktif (*Dynamic Quotation Calculator*):** Klien dapat memilih paket dasar, menambah fitur add-on (misal: *Domain .com/.id, Hosting NVMe 5GB, Payment Gateway Integration, Multilingual, SEO Setup, Pembuatan Konten Teks*), dan memperoleh kalkulasi estimasi total biaya secara transparan secara instan.

### Modul 2: Pemesanan, Pengajuan Brief, & Kontrak Digital (Must-Have)
* **Order Placement:** Klien melakukan checkout paket dan add-on yang dipilih.
* **Formulir Pengumpulan Brief Terstruktur:** Formulir pengisian data kebutuhan bisnis, target audiens, pilihan skema warna (color palette), referensi website favorit, dan unggah berkas aset (logo format PNG/SVG, foto pendukung, dokumen profil perusahaan).
* **E-Agreement / Syarat Layanan Digital:** Klien menyetujui ketentuan kuota revisi, jangka waktu pengerjaan, dan skema pembayaran sebelum pesanan diproses.

### Modul 3: Billing, Invoicing Termin, & Payment Gateway (Must-Have)
* **Split Billing Termin:** Pembagian tagihan otomatis menjadi Termin 1 (DP 50% saat kontrak disetujui) dan Termin 2 (Pelunasan 50% setelah website selesai diuji di server staging).
* **Multi-Kanal Pembayaran Digital:** Integrasi Payment Gateway (Virtual Account Bank Mandiri, BCA, BRI, BNI dan QRIS Dinamis) dengan callback webhook real-time.
* **Kuitansi & Faktur Resmi PDF:** Penerbitan invoice dan kuitansi digital otomatis ber-QR code verifikasi keabsahan.

### Modul 4: Portal Klien & Milestone Tracking (Must-Have)
* **Visualisasi Progres Proyek:** Tampilan timeline 5 tahapan utama:
  1. *Brief & Requirement Gathering*
  2. *UI/UX Wireframing & Design Approval*
  3. *Frontend & Backend Development*
  4. *Testing, UAT, & Content Upload on Staging Server*
  5. *Deployment, Final Handover, & Go-Live*
* **Staging Demo Viewer:** Klien dapat langsung menguji website pada subdomain pementasan (*preview link* staging).
* **Milestone Sign-Off:** Klien memberikan persetujuan formal digital untuk setiap fase sebelum melangkah ke fase berikutnya.

### Modul 5: Manajemen Revisi Berkuota (Should-Have)
* **Sistem Tiket Revisi (*Revision Ticketing System*):** Klien mengajukan revisi dengan melampirkan screenshot, URL halaman, dan deskripsi detail perubahan.
* **Quota Enforcement:** Sistem secara otomatis memotong kuota revisi yang tersedia (misal: 2x revisi minor pada paket Company Profile). Apabila kuota habis, sistem menawarkan add-on tiket revisi berbayar.

### Modul 6: Notifikasi Otomatis (Should-Have)
* **Integrasi WhatsApp Business / Gateway API & Email:** Pengiriman notifikasi otomatis untuk:
  * Pengingat pembayaran invoice DP dan Pelunasan.
  * Pemberitahuan bahwa desain UI/UX atau versi staging siap di-review.
  * Pengingat batas waktu approval milestone klien (SLA 3 hari).

### Modul 7: Vault Kredensial & Serah Terima Proyek (Must-Have)
* **Secure Credential Vault:** Modul penyimpanan akun penting (login cPanel/DirectAdmin, akun CMS WordPress/Laravel Admin, kredensial domain) yang dienkripsi secara asimetris/AES-256.
* **Gated Release:** Kredensial hanya dapat di-dekripsi dan dilihat oleh klien jika **Termin 2 (Pelunasan) bernilai LUNAS / PAID**.
* **Penerbitan Berita Acara Serah Terima (BAST):** Dokumen digital serah terima website lengkap dengan tanggal mulai garansi pemeliharaan (*warranty period* 30–90 hari).

### Modul 8: Laporan Operasional & Keuangan Agency (Could-Have)
* **Laporan Profitabilitas Proyek:** Rekapitulasi nilai kontrak proyek dikurangi pengeluaran modal langsung (*Cost of Goods Sold* - COGS) seperti pembelian domain, sewa lisensi tema/plugin, dan alokasi freelance programmer.
* **Ekspor Data:** Rekapitulasi laporan dalam format Microsoft Excel (.xlsx) dan PDF untuk keperluan audit keuangan.

---

## 6. Alur Pengguna Utama (Core User Flows)

### Alur A: Klien Melakukan Order, Pembayaran DP, & Pengisian Brief
```text
[Klien Mengunjungi Landing Page]
               │
               ▼
[Pilih Paket Website & Add-on di Kalkulator Biaya]
               │
               ▼
[Isi Data Profil Klien & Buat Akun (Register)]
               │
               ▼
[Sistem Terbitkan Invoice Termin 1: DP 50%]
               │
               ▼
[Klien Melakukan Pembayaran via Virtual Account / QRIS]
               │
               ▼
[Payment Gateway Callback Webhook Diterima Server]
               ├──> [Status Invoice Berubah Menjadi "PAID"]
               ├──> [Proyek Aktif & Notifikasi Dikirim ke PM]
               └──> [Klien Diarahkan ke Formulir Brief Proyek]
               │
               ▼
[Klien Mengisi Kuesioner Brief & Unggah Aset Logo/Foto]
               │
               ▼
[PM Menerima Brief, Meninjau Kelengkapan, & Menugaskan Developer]
```

### Alur B: Penanganan Milestone, Review Staging, & Revisi
```text
[Developer Menyelesaikan Pengerjaan di Staging Server]
               │
               ▼
[PM Memverifikasi Kualitas & Mengubah Status Milestone "Ready for Client Review"]
               │
               ▼
[Klien Menerima Notifikasi WhatsApp/Email & Membuka Preview Link]
               │
        ┌──────┴────────────────────────┐
        ▼                               ▼
[Klien Setuju / Approve]       [Klien Minta Revisi]
        │                               │
        │                               ▼
        │                      [Sistem Cek Sisa Kuota Revisi]
        │                               ├──> Sisa > 0: Tiket Revisi Diterbitkan ke Dev
        │                               └──> Sisa = 0: Muncul Invoice Add-on Revisi
        │                               │
        │                               ▼
        │                      [Developer Memperbaiki & Submit Ulang]
        │                               │
        └───────────────────────────────┘
                        │
                        ▼
         [Milestone Ditandai "APPROVED"]
```

### Alur C: Pelunasan Termin 2 & Serah Terima Kredensial (Handover)
```text
[Seluruh Milestone Teknis Selesai & Ditandatangani Klien]
                        │
                        ▼
[Sistem Otomatis Menerbitkan Invoice Termin 2: Pelunasan 50%]
                        │
                        ▼
[Klien Membayar via VA / QRIS / Transfer Bank]
                        │
                        ▼
[Status Invoice Menjadi "PAID"]
                        │
                        ▼
[Sistem Membuka Akses "Secure Credential Vault" di Portal Klien]
                        ├──> [Klien Melihat Detail Login cPanel/Hosting, Domain, & Admin Web]
                        ├──> [Sistem Menerbitkan Berita Acara Serah Terima (BAST) PDF]
                        └──> [Masa Garansi Bug-Fix 60 Hari Otomatis Aktif]
```

---

## 7. Kebutuhan Non-Fungsional (Non-Functional Requirements)

| Kategori | Spesifikasi & Standar Teknis |
| :--- | :--- |
| **Keamanan (Security)** | Enkripsi HTTPS (TLS 1.3), Password Hashing menggunakan algoritma Bcrypt/Argon2id, Session Protection (HttpOnly, Secure, SameSite=Lax/Strict), Proteksi CSRF pada seluruh *state-changing request*, Sanitasi input XSS, Parameterized SQL Queries via Laravel Eloquent ORM. |
| **Keamanan Vault** | Kredensial akun hosting klien dienkripsi di basis data menggunakan enkripsi simetris AES-256-CBC dengan *application key* yang tersimpan di environment variables `.env`. |
| **Penyimpanan Berkas** | Validasi tipe MIME dan ekstensi berkas secara ketat saat pengunggahan aset (hanya menerima JPG, PNG, SVG, PDF, ZIP dengan limit maksimal 25 MB per berkas). |
| **Performa & Latensi** | Waktu respon rata-rata halaman (*Time to First Byte* / TTFB) < 600 ms; respon API webhook < 300 ms; efisiensi query basis data dengan indexing pada foreign key. |
| **Ketersediaan (Availability)** | Layanan dirancang untuk uptime 99.5% pada container Docker terisolasi dengan auto-restart policy (`restart: always`). |
| **Kompatibilitas Tampilan** | Desain responsif (*mobile-friendly*) untuk layar smartphone (360px–430px) hingga layar monitor desktop (1920x1080). |

---

## 8. Arsitektur Data Entitas Kunci (Entity Overview)

* **`users`**: `id`, `name`, `email`, `password_hash`, `role` (admin, pm, dev, finance, client), `phone_number`, `created_at`.
* **`clients`**: `id`, `user_id`, `company_name`, `business_sector`, `address`, `whatsapp_number`.
* **`service_packages`**: `id`, `name`, `slug`, `base_price`, `features_list` (JSON), `revision_quota`, `estimated_days`.
* **`orders`**: `id`, `order_number`, `client_id`, `package_id`, `total_amount`, `status` (*pending, in_progress, review, completed, cancelled*), `start_date`, `target_completion_date`.
* **`order_addons`**: `id`, `order_id`, `addon_name`, `price`.
* **`brief_submissions`**: `id`, `order_id`, `brand_story`, `color_palette`, `competitor_references`, `feature_notes`, `drive_assets_url`, `status` (*submitted, reviewed, approved*).
* **`project_milestones`**: `id`, `order_id`, `title`, `stage_order` (1–5), `preview_url`, `status` (*pending, active, review, approved*), `approved_at`.
* **`revision_tickets`**: `id`, `order_id`, `milestone_id`, `issue_description`, `attachment_url`, `status` (*open, in_progress, resolved*).
* **`invoices`**: `id`, `invoice_number`, `order_id`, `term_type` (*term_1_dp, term_2_settlement, additional_revision*), `amount`, `due_date`, `status` (*unpaid, paid, expired*).
* **`payments`**: `id`, `invoice_id`, `channel` (*VA_BCA, VA_MANDIRI, QRIS, MANUAL_TRANSFER*), `transaction_ref`, `amount_paid`, `paid_at`, `status` (*success, pending, failed*).
* **`credential_vaults`**: `id`, `order_id`, `service_type` (*cpanel, domain, cms_admin, database*), `login_url`, `username`, `encrypted_password`, `is_unlocked`.

---

## 9. Rencana Implementasi & Roadmap Pengembangan

```text
Fase 1 (Minggu 1 - 4)  : Fondasi Sistem, Autentikasi, Katalog, & Order Intake
├── Setup Docker Compose (PHP 8.2 + PostgreSQL 15 + Nginx)
├── Skema Database Migrations & RBAC Multi-Role
└── Katalog Layanan, Kalkulator Biaya, & Registrasi Order

Fase 2 (Minggu 5 - 8)  : Invoicing, Payment Gateway, & Form Pengisian Brief
├── Sistem Invoicing Termin 1 & 2
├── Integrasi Webhook Payment Gateway (Sandbox VA & QRIS)
└── Formulir Onboarding Brief Proyek & File Asset Uploader

Fase 3 (Minggu 9 - 12) : Project Tracking, Review Staging, & Modul Revisi
├── Board Milestone Proyek & Preview URL Integration
├── Sistem Tiket Revisi Berkuota
└── Client Portal Interaktif

Fase 4 (Minggu 13 - 15): Vault Kredensial, Serah Terima (BAST), & Laporan Finansial
├── Enkripsi Kredensial Hosting & Autolock Mekanisme
├── Generator Dokumen BAST & Kuitansi PDF
└── Laporan Finansial & Profitabilitas Proyek

Fase 5 (Minggu 16)     : QA, Security Hardening, & Deployment Production
├── Pengujian Transaksi Webhook Idempotency
├── Security Vulnerability Scan (SQLi, XSS, CSRF)
└── Docker Staging to Production Handover
```

---
---

# BAGIAN II: SOFTWARE REQUIREMENTS SPECIFICATION (SRS)

**Standar Format Acuan:** ISO/IEC/IEEE 29148:2018  
**Sistem:** WebCraft Agency Management System  
**Arsitektur Target:** Docker Containerized Web App (PHP 8.2-cli Laravel + PostgreSQL 15 Alpine)  

---

## 1. Pendahuluan

### 1.1 Tujuan Dokumen
Dokumen *Software Requirements Specification* (SRS) ini mendefinisikan seluruh spesifikasi kebutuhan fungsional, kebutuhan non-fungsional, karakteristik pengguna, antarmuka eksternal, dan arsitektur basis data relasional untuk sistem **WebCraft Agency Management System**. Dokumen ini dirancang sebagai acuan tunggal pengembang (*developer*), perancang sistem (*system architect*), penguji (*QA engineer*), serta dosen pengampu mata kuliah Pemrograman Web dalam mengukur ketercapaian dan validitas fungsional perangkat lunak.

### 1.2 Cakupan Produk
Sistem WebCraft adalah aplikasi web terintegrasi yang mencakup siklus hidup lengkap (*end-to-end lifecycle*) bisnis jasa pembuatan website:
* Pemilihan paket dan kalkulator estimasi kebutuhan pembuatan website.
* Manajemen pesanan, penerbitan kontrak, dan pengumpulan materi brief awal.
* Manajemen termin pembayaran berbasis *split invoice* (DP dan Pelunasan) dengan integrasi gerbang pembayaran digital.
* Manajemen alur kerja proyek internal berbasis *milestone*, penugasan staf teknis, dan verifikasi tautan staging.
* Tata kelola revisi berbasis batasan kuota untuk mencegah *scope creep*.
* Brankas data kredensial hosting/server terenkripsi yang hanya dapat dibuka setelah pelunasan terverifikasi penuh.
* Akuntansi sederhana untuk merekap laba kotor proyek dan pengeluaran vendor infrastruktur.

### 1.3 Definisi, Akronim, dan Singkatan

| Istilah / Akronim | Definisi Teknis |
| :--- | :--- |
| **BAST** | Berita Acara Serah Terima (Dokumen bukti legalitas penyelesaian pekerjaan website). |
| **Brief** | Dokumen formulir kebutuhan teknis dan estetika website yang diisi oleh klien. |
| **COGS** | *Cost of Goods Sold* (Biaya modal langsung proyek: sewa domain, hosting cPanel, plugin berbayar). |
| **DP** | *Down Payment* (Uang muka pembayaran awal, standar 50% dari total nilai kontrak). |
| **Idempotency** | Karakteristik operasi API di mana request webhook yang dikirim berulang kali menghasilkan status yang konsisten tanpa menduplikasi pencatatan pembayaran. |
| **MIME** | *Multipurpose Internet Mail Extensions* (Standar verifikasi format tipe file berkas biner). |
| **POS** | *Point of Sale* / Modul pencatatan pembayaran tunai atau manual perbankan. |
| **RBAC** | *Role-Based Access Control* (Metode pembatasan otorisasi sistem berdasarkan peran pengguna). |
| **Scope Creep** | Penambahan fitur atau lingkup kerja di luar kesepakatan awal tanpa penambahan biaya dan waktu. |
| **Staging Server** | Server replika yang digunakan untuk mendemonstrasikan website kepada klien sebelum dipindahkan ke server live produksi. |
| **VA / QRIS** | Virtual Account (Nomor rekening virtual unik per invoice) / Quick Response Code Indonesian Standard. |

### 1.4 Referensi
1. ISO/IEC/IEEE 29148:2018 - *Systems and software engineering — Life cycle processes — Requirements engineering*.
2. Dokumentasi Resmi Docker & Docker Compose File Reference v3.8.
3. Dokumentasi Framework Laravel 10.x / 11.x (Database Eloquent, Authentication, Middleware, Queue Jobs).
4. OWASP Top 10 Web Application Security Risks (2021).
5. Standar Pembayaran Digital Bank Indonesia & Petunjuk Integrasi Payment Gateway API (Midtrans/Xendit).

---

## 2. Deskripsi Keseluruhan Sistem

### 2.1 Arsitektur Sistem Terdistribusi (Docker Architecture)

Aplikasi dibangun di atas lingkungan kontainer Docker terisolasi yang terdiri dari lapisan *Presentation*, lapisan *Application Logic*, lapisan *Data Persistence*, serta integrasi layanan pihak ketiga.

```text
┌────────────────────────────────────────────────────────────────────────┐
│                        PRESENTATION LAYER                              │
│  [Web Publik & Katalog]    [Portal Dashboard Klien]   [Dashboard Admin/PM]│
└───────────────────────────────────┬────────────────────────────────────┘
                                    │ HTTPS (TLS 1.3) / Port 8000:8000
                                    ▼
┌────────────────────────────────────────────────────────────────────────┐
│               DOCKER ENVIRONMENT (laravel-net: Bridge)                 │
│                                                                        │
│  ┌──────────────────────────────────────────────────────────────────┐  │
│  │ Container: laravel_app (PHP 8.2-cli / Laravel Artisan Engine)     │  │
│  │                                                                  │  │
│  │  ┌──────────────────┐  ┌──────────────────┐  ┌────────────────┐  │  │
│  │  │ Routing & Guard  │  │ Quotation Engine │  │ Order & Brief  │  │  │
│  │  │ (RBAC Middleware)│  │ (Pricing Calc)   │  │ Management     │  │  │
│  │  └──────────────────┘  └──────────────────┘  └────────────────┘  │  │
│  │  ┌──────────────────┐  ┌──────────────────┐  ┌────────────────┐  │  │
│  │  │ Milestone Engine │  │ Revision Tracker │  │ Credential     │  │  │
│  │  │ & Staging URL    │  │ (Quota Governor) │  │ Vault (AES-256)│  │  │
│  │  └──────────────────┘  └──────────────────┘  └────────────────┘  │  │
│  │  ┌──────────────────┐  ┌──────────────────┐  ┌────────────────┐  │  │
│  │  │ Invoicing Termin │  │ Webhook Listener │  │ PDF Generator  │  │  │
│  │  │ & Accounting     │  │ (Payment Handler)│  │ (Invoice/BAST) │  │  │
│  │  └──────────────────┘  └──────────────────┘  └────────────────┘  │  │
│  └──────────────────────────────────┬───────────────────────────────┘  │
│                                     │ UNIX Socket / TCP Port 5432       │
│                                     ▼                                  │
│  ┌──────────────────────────────────────────────────────────────────┐  │
│  │ Container: postgres_db (Image: postgres:15-alpine)               │  │
│  │ - Volume Mount: pgdata:/var/lib/postgresql/data                  │  │
│  │ - Database ACID Transaction Support & Strict Referential Integrity│ │
│  └──────────────────────────────────────────────────────────────────┘  │
└─────────────────────────────────────┬──────────────────────────────────┘
                                      │ REST API (JSON / HTTPS)
                                      ▼
┌────────────────────────────────────────────────────────────────────────┐
│                      EXTERNAL 3RD PARTY SERVICES                       │
│  ┌────────────────────────┐  ┌───────────────────┐  ┌───────────────┐  │
│  │ Payment Gateway        │  │ WhatsApp Gateway  │  │ Cloud Storage │  │
│  │ (VA & QRIS Engine)     │  │ (Fonnte/Twilio API│  │ (AWS S3/Local)│  │
│  └────────────────────────┘  └───────────────────┘  └───────────────┘  │
└────────────────────────────────────────────────────────────────────────┘
```

### 2.2 Karakteristik & Hak Akses Pengguna

| Aktor | Kemampuan Teknis | Frekuensi Penggunaan | Hak Akses Utama |
| :--- | :--- | :--- | :--- |
| **Super Admin** | Tingkat Mahir | Harian | Konfigurasi sistem, manajemen seluruh akun tim dan klien, pemulihan database, melihat audit log. |
| **Project Manager** | Tingkat Menengah | Harian (Intensif) | Menyetujui form brief, menetapkan staf pelaksana, mengontrol jadwal milestone, memutuskan validitas revisi. |
| **Developer/Designer** | Tingkat Mahir | Harian (Intensif) | Mengunggah progres pengerjaan, memasukkan link live staging, membalas tiket revisi yang dialokasikan. |
| **Finance Officer** | Tingkat Menengah | Harian | Mengontrol status penerbitan invoice termin 1 dan termin 2, menginput bukti transfer manual, mencatat biaya domain/hosting. |
| **Client** | Tingkat Dasar | Mingguan / Insidental | Mengonfigurasi paket, mengunggah brief materi, membayar invoice via payment gateway, mengajukan revisi, mengunduh kredensial dan BAST. |

### 2.3 Batasan Desain & Implementasi
1. **Lingkungan Terisolasi Kontainer:** Aplikasi harus dapat dijalankan melalui berkas `docker-compose.yml` dengan satu perintah `docker compose up -d` tanpa memerlukan instalasi PHP atau PostgreSQL lokal pada mesin host pengguna.
2. **Kepatuhan Pembayaran Termin:** Kredensial server dan domain (*vault*) tidak boleh dapat diakses oleh klien sebelum sistem mencatat transaksi pelunasan (*Term 2*) bernilai status `PAID`.
3. **Kerahasiaan Data Akses Klien:** Seluruh password cPanel, kata sandi email bisnis, dan kredensial hosting wajib disimpan terenkripsi dengan algoritma `AES-256-CBC` menggunakan *key* terisolasi.
4. **Idempotensi Webhook:** Webhook listener pembayaran dari payment gateway wajib menerapkan mekanisme validasi *signature header* dan pemeriksaan status pesanan untuk mencegah proses duplikasi entri saat terjadi pengiriman ulang (*network retry*).

---

## 3. Kebutuhan Antarmuka Eksternal

### 3.1 Antarmuka Pengguna (UI)
* **Kerapian & Responsivitas:** Halaman antarmuka wajib mengadopsi standar CSS modern (TailwindCSS) dengan adaptasi fleksibel untuk resolusi smartphone (360px ke atas) hingga layar monitor 1080p/2K.
* **Komponen Interaktif:** 
  * Formulir kalkulator estimasi biaya dengan kalkulasi langsung di sisi peramban (*instant reactive subtotal*).
  * Indikator progress bar tahapan pengerjaan website yang memiliki 5 tahapan visual status (*Not Started, In Progress, Reviewing, Completed*).
  * Area drag-and-drop untuk pengunggahan aset brief (logo, dokumen) dengan indikator progress pengunggahan.

### 3.2 Antarmuka Perangkat Lunak & API Eksternal
* **Payment Gateway Service:**
  * Protokol: RESTful JSON over HTTPS.
  * Fitur: Pembuatan dynamic invoice (*Snap/Checkout API*), penerbitan nomor Virtual Account (BCA, BNI, BRI, Mandiri), QRIS dinamis, dan listener endpoint Webhook Callback untuk *payment settlement*.
* **WhatsApp Notification Gateway API:**
  * Protokol: HTTP POST request.
  * Fitur: Mengirimkan template pesan teks otomatis kepada klien saat invoice terbit, saat milestone membutuhkan review, dan saat website telah berstatus online (*live*).
* **Storage Interface:**
  * Penyimpanan aset berkas lokal terisolasi pada volume `/var/www/storage/app/public` atau integrasi *S3-compatible Object Storage* untuk berkas berkapasitas besar.

### 3.3 Antarmuka Komunikasi
* Semua pertukaran data antara klien (browser) dan server backend wajib melewati enkripsi protokol **HTTPS (TLS 1.3)**.
* Pengiriman respons API internal berformat data `application/json` dengan status kode HTTP standar (200 OK, 201 Created, 400 Bad Request, 401 Unauthorized, 403 Forbidden, 422 Unprocessable Content, 500 Server Error).

---

## 4. Spesifikasi Kebutuhan Fungsional Rinci

### Pengelompokan Modul:
* **FR-AUTH** : Modul Autentikasi & Manajemen Pengguna
* **FR-CAT**  : Modul Katalog, Add-on & Kalkulator Harga
* **FR-ORD**  : Modul Pemesanan & Pengumpulan Brief Proyek
* **FR-PAY**  : Modul Invoicing Termin & Gerbang Pembayaran
* **FR-TRK**  : Modul Pelacakan Milestone & Live Staging
* **FR-REV**  : Modul Manajemen Tiket Revisi Berkuota
* **FR-VLT**  : Modul Brankas Kredensial Hosting & Handover BAST
* **FR-RPT**  : Modul Akuntansi Sederhana & Laporan Keuangan

---

### 4.1 Modul Autentikasi & Manajemen Pengguna (FR-AUTH)

#### FR-AUTH-01: Autentikasi Multi-Peran & Session Guard
* **Deskripsi:** Sistem memvalidasi kredensial pengguna (email dan password) dan memberikan hak akses serta pengalihan halaman (*redirect*) berdasarkan peran yang dimiliki.
* **Aktor:** Seluruh Aktor.
* **Pra-kondisi:** Pengguna telah terdaftar di database dengan akun aktif.
* **Alur Utama:**
  1. Pengguna memasukkan alamat email dan password pada halaman `/login`.
  2. Sistem mencari record pada tabel `users` berdasarkan email.
  3. Sistem memverifikasi kecocokan kata sandi menggunakan fungsi `Hash::check()` dengan algoritma Bcrypt/Argon2id.
  4. Jika cocok, sistem meregenerasi ID sesi peramban untuk mencegah *session fixation*.
  5. Pengguna diarahkan ke dashboard sesuai perannya:
     * `client` ➔ `/client/dashboard`
     * `pm` ➔ `/pm/dashboard`
     * `developer` ➔ `/dev/tasks`
     * `finance` ➔ `/finance/invoices`
     * `admin` ➔ `/admin/overview`
* **Alur Alternatif:**
  * Jika password tidak cocok, sistem menambah hitungan *failed attempts*. Jika gagal 5 kali berturut-turut, login dikunci sementara selama 10 menit (*rate limiting / brute force protection*).
* **Pasca-kondisi:** Sesi login pengguna aktif, status otentikasi tersimpan aman pada session cookie.

---

### 4.2 Modul Katalog, Add-on, & Kalkulator Biaya (FR-CAT)

#### FR-CAT-01: Kalkulator Estimasi Biaya & Pemilihan Fitur
* **Deskripsi:** Pengunjung website dapat memilih paket website dasar dan mencentang berbagai fitur *add-on* untuk mendapatkan kalkulasi estimasi total biaya dan estimasi waktu pengerjaan.
* **Aktor:** Klien (Pengunjung Publik / Registered Client).
* **Input Data:**
  * Pilihan Paket Dasar (Radio Button: *Landing Page, Company Profile, Toko Online, Custom App*).
  * Checklist Add-on (Pilihan: *Domain .com, Domain .id, Hosting Tambahan, Payment Gateway, Multi-Bahasa, Input Produk/Artikel Massal, SEO Setup*).
* **Aturan Bisnis:**
  * `Total Biaya = Harga Dasar Paket + Σ(Harga Add-on yang Dipilih)`.
  * `Estimasi Hari Kerja = Hari Dasar Paket + Σ(Hari Tambahan Add-on)`.
  * Tombol *Lanjutkan Pemesanan* mengarahkan pengguna ke alur pembuatan pesanan resmi dengan menyimpan state opsi yang dipilih.

---

### 4.3 Modul Pemesanan & Pengumpulan Brief Proyek (FR-ORD)

#### FR-ORD-01: Pembuatan Pesanan & Order Intake
* **Deskripsi:** Klien yang telah memilih konfigurasi paket mengonfirmasi pembuatan pesanan yang akan menghasilkan nomor order unik dan pesanan berstatus `WAITING_DP`.
* **Aktor:** Klien.
* **Proses:**
  1. Sistem mengunci ringkasan item paket dan add-on yang dipilih.
  2. Sistem men-generate `order_number` dengan format: `ORD-YYYYMMDD-XXXX` (contoh: `ORD-20261014-0012`).
  3. Sistem membuat entri di tabel `orders` dengan status awal `WAITING_DP`.
  4. Sistem memicu modul penagihan (FR-PAY) untuk menerbitkan invoice Termin 1 (DP 50%).

#### FR-ORD-02: Pengisian Formulir Brief Proyek Terstruktur
* **Deskripsi:** Setelah pembayaran Termin 1 lunas, klien wajib mengisi kuesioner brief dan mengunggah aset materi website.
* **Input Data:**
  * `brand_name` (Nama Usaha/Merek).
  * `tagline_description` (Slogan dan penjelasan produk/jasa).
  * `color_preferences` (Warna dominan yang diinginkan, misal: Navy & Gold).
  * `target_audience` (Sasaran konsumen, misal: B2B, Mahasiswa, Ibu Rumah Tangga).
  * `competitor_urls` (Referensi 2–3 website pesaing/contoh yang disukai).
  * `logo_file` (Upload file SVG/PNG resolusi tinggi).
  * `supporting_assets` (Upload ZIP berisi foto/konten profil atau tautan Google Drive).
* **Validasi:** File upload dibatasi format biner aman dengan ukuran maksimal 25 MB.
* **Pasca-kondisi:** Data tersimpan pada entitas `brief_submissions`. Status pesanan berubah menjadi `BRIEF_SUBMITTED`, dan PM menerima pemberitahuan review.

---

### 4.4 Modul Invoicing Termin & Gerbang Pembayaran (FR-PAY)

#### FR-PAY-01: Penerbitan Invoice Termin (DP 50% & Pelunasan 50%)
* **Deskripsi:** Sistem membagi kewajiban pembayaran proyek menjadi dua faktur penagihan termin.
* **Aturan Bisnis:**
  * **Termin 1 (DP):** `Nominal = 50% * Total Nilai Order`. Diterbitkan langsung setelah order dibuat. Batas jatuh tempo 2x24 jam.
  * **Termin 2 (Pelunasan):** `Nominal = 50% * Total Nilai Order + Biaya Tambahan Revisi (jika ada)`. Diterbitkan otomatis ketika tahapan *Development & Staging* selesai disetujui klien.
* **Keluaran:** Dokumen tagihan berformat PDF resmi yang memuat nomor faktur, rincian biaya, instruksi pembayaran, dan barcode.

#### FR-PAY-02: Pemrosesan Webhook Payment Gateway (Idempotent Handler)
* **Deskripsi:** Server menerima callback notifikasi instan dari gateway pembayaran (Midtrans/Xendit) saat klien menyelesaikan transaksi Virtual Account atau QRIS.
* **Alur Eksekusi:**
  1. Gateway mengirimkan payload HTTP POST ke URL endpoint `/api/payments/webhook`.
  2. Server membaca *Signature Key Header* dan mencocokkannya dengan rumus: `SHA512(order_id + status_code + gross_amount + ServerKey)`.
  3. Jika signature tidak cocok, server mengembalikan respon `HTTP 401 Unauthorized` dan menghentikan proses.
  4. Server memeriksa apakah invoice sudah berstatus `PAID` sebelumnya (*Idempotency Check*). Jika sudah lunas, langsung return `HTTP 200 OK` tanpa mutasi data.
  5. Jika status transaksi `settlement` / `capture`:
     * Status invoice diupdate menjadi `PAID`.
     * Mencatat waktu pelunasan pada kolom `paid_at`.
     * Jika invoice tersebut adalah Termin 1: Status order diubah menjadi `IN_PROGRESS` dan membuka form brief.
     * Jika invoice tersebut adalah Termin 2: Status order diubah menjadi `COMPLETED` dan membuka kunci brankas kredensial (FR-VLT).
  6. Server memicu pengiriman pesan kuitansi lunas ke nomor WhatsApp klien.
  7. Server mengembalikan respon `HTTP 200 OK` dalam waktu < 2 detik.

---

### 4.5 Modul Pelacakan Milestone & Live Staging (FR-TRK)

#### FR-TRK-01: Pengelolaan Milestone Pengerjaan Proyek
* **Deskripsi:** Tim Project Manager dan Developer mendokumentasikan progres website secara terstruktur dalam 5 milestone berurutan.
* **Daftar Tahapan Baku:**
  1. *Fase 1: Review Brief & Konsep Sitemap*
  2. *Fase 2: Desain UI/UX & Wireframing (Figma)*
  3. *Fase 3: Slicing Kode & Pengembangan Sistem (Frontend + Backend)*
  4. *Fase 4: Pengujian di Server Staging & Evaluasi Klien*
  5. *Fase 5: Pelunasan, Migrasi ke Server Live, & Final Handover*
* **Fitur Tautan Staging:** Pada Fase 4, Developer menginput URL demo aktif (contoh: `https://staging.webcraft.id/klien-abc`) yang langsung disematkan dalam iFrame/tombol uji coba di dashboard klien.

#### FR-TRK-02: Persetujuan Milestone Digital (*Milestone Sign-off*)
* **Deskripsi:** Klien melakukan review terhadap hasil pengerjaan di setiap fase dan memberikan persetujuan formal digital.
* **Alur:** Klien menekan tombol *"Setujui Milestone Ini"*, sistem mencatat timestamp dan alamat IP klien sebagai bukti persetujuan digital, lalu membuka fase berikutnya.

---

### 4.6 Modul Manajemen Tiket Revisi Berkuota (FR-REV)

#### FR-REV-01: Pengajuan Tiket Revisi Berbasis Batasan Kuota
* **Deskripsi:** Klien dapat meminta revisi atas hasil kerja yang ditampilkan pada link staging dengan batasan kuota sesuai paket.
* **Aturan Bisnis:**
  * Setiap paket website memiliki kuota revisi mayor/minor (contoh: Paket Basic = 2x revisi; Paket Pro = 4x revisi).
  * Pengajuan 1 tiket revisi dapat memuat maksimal 5 poin koreksi spesifik dengan lampiran screenshot pendukung.
  * Setiap kali tiket revisi berstatus `APPROVED_FOR_WORK` oleh PM, kuota revisi klien berkurang sebanyak 1.
* **Penanganan Kuota Habis:** Jika kuota habis (`sisa_kuota == 0`), formulir pengajuan revisi akan menampilkan peringatan penambahan biaya (*Additional Change Request*) sebesar tarif flat (misal: Rp 200.000,- per siklus revisi) dan otomatis menerbitkan invoice add-on.

---

### 4.7 Modul Brankas Kredensial Hosting & Handover BAST (FR-VLT)

#### FR-VLT-01: Penyimpanan Kredensial Terenkripsi (*Credential Vault*)
* **Deskripsi:** Staf teknis memasukkan informasi akses sensitif website yang dibuatkan untuk klien (cPanel, akun domain registrar, akun Administrator WordPress/Laravel, konfigurasi database).
* **Mekanisme Keamanan:**
  * Nilai password dienkripsi pada saat penyimpanan menggunakan fungsi `Crypt::encryptString()` (enkripsi simetris AES-256-CBC dengan HMAC SHA-256).
  * Kredensial tidak pernah ditampilkan dalam bentuk teks polos (*plaintext*) di database.

#### FR-VLT-02: Pembukaan Kunci Kredensial Otomatis (*Gated Credential Handover*)
* **Deskripsi:** Portal Klien secara ketat mengunci tab menu *"Akses Kredensial Website"*.
* **Kondisi Buka Kunci:**
  * Status Invoice Termin 1 = `PAID`.
  * Status Invoice Termin 2 (Pelunasan) = `PAID`.
  * Seluruh tiket revisi telah berstatus `RESOLVED`.
* **Keluaran:** Tombol *"Lihat Password"* aktif. Klien dapat meng-copy password akun hosting dan mengunduh berkas Berita Acara Serah Terima (BAST) bertanda tangan digital QR Code.

---

### 4.8 Modul Akuntansi Sederhana & Laporan Keuangan (FR-RPT)

#### FR-RPT-01: Rekapitulasi Laba Kotor Proyek & Pengeluaran Vendor
* **Deskripsi:** Bagian keuangan mencatat pengeluaran modal langsung (*COGS*) untuk setiap order proyek (misal: pendaftaran domain Rp 150.000, hosting Rp 350.000, pembelian lisensi plugin Rp 200.000).
* **Kalkulasi:**
  $$\text{Laba Kotor Proyek} = \text{Total Pembayaran Masuk} - \Sigma(\text{Pengeluaran Modal Vendor})$$
* **Fitur Ekspor:** Menghasilkan laporan laba rugi per proyek dalam format spreadsheet Microsoft Excel (.xlsx) dan dokumen ringkasan bulanan PDF.

---

## 5. Kebutuhan Non-Fungsional (NFR)

### 5.1 Kinerja (Performance)
* **NFR-PERF-01 (Waktu Respons Halaman):** Waktu respons halaman web dinamis tidak boleh melebihi 1,2 detik pada kondisi koneksi normal (persentil ke-90).
* **NFR-PERF-02 (Kecepatan Pemrosesan Webhook):** Endpoint webhook payment gateway harus memvalidasi data dan mengembalikan kode status HTTP 200 dalam waktu kurang dari 500 milidetik.
* **NFR-PERF-03 (Penanganan Unggah Berkas):** Sistem mampu menangani pengunggahan berkas aset hingga ukuran 25 MB per transaksi secara paralel tanpa menyebabkan kegagalan memori worker PHP (`memory_limit = 256M`).

### 5.2 Keamanan & Integritas Data (Security)
* **NFR-SEC-01 (Penyimpanan Kata Sandi):** Kata sandi seluruh akun pengguna wajib di-hash menggunakan algoritma Bcrypt (cost factor minimal 10) atau Argon2id.
* **NFR-SEC-02 (Perlindungan Injeksi & XSS):** Semua interaksi basis data wajib menggunakan *Prepared Statements* via Laravel Eloquent ORM. Seluruh output teks pada Blade views wajib melewati fungsi *HTML escaping* bawaan `{{ $var }}`.
* **NFR-SEC-03 (Pencegahan CSRF):** Seluruh formulir web dengan metode POST, PUT, PATCH, dan DELETE wajib menyertakan token proteksi CSRF (*Cross-Site Request Forgery*).
* **NFR-SEC-04 (Enkripsi Data At-Rest):** Data sensitif pada tabel `credential_vaults` wajib disimpan menggunakan enkripsi berkekuatan tinggi AES-256-CBC.

### 5.3 Keandalan & Ketersediaan (Reliability & Availability)
* **NFR-REL-01 (Uptime Layanan):** Sistem beroperasi pada kontainer Docker dengan target ketersediaan layanan minimal 99.5% per bulan.
* **NFR-REL-02 (Kebijakan Pencadangan Data):** Basis data PostgreSQL wajib dicadangkan (*database dump*) secara berkala setiap 24 jam dengan retensi penyimpanan selama 30 hari.
* **NFR-REL-03 (Pemulihan Kegagalan Kontainer):** Setiap container dalam Docker Compose wajib dikonfigurasi dengan opsi `restart: always` agar otomatis pulih saat mesin *reboot* atau terjadi *crash process*.

### 5.4 Kemudahan Pemeliharaan & Lingkungan (Maintainability)
* **NFR-MNT-01 (Kontainerisasi Terstandar):** Seluruh konfigurasi sistem dibungkus dalam `Dockerfile` dan `docker-compose.yml` yang kompatibel dengan Docker Desktop pada sistem operasi Windows, Linux, maupun macOS.
* **NFR-MNT-02 (Konfigurasi Terpusat):** Seluruh parameter lingkungan (koneksi database, API key gateway pembayaran, app secret) wajib diisolasi dalam berkas konfigurasi `.env` dan tidak boleh di-hardcode ke dalam *source code*.

---

## 6. Rancangan Skema Basis Data Relasional (PostgreSQL)

### 6.1 Diagram Relasi Antar Entitas (Entity-Relationship Diagram)

```text
┌─────────────────┐       ┌─────────────────┐       ┌──────────────────┐
│      users      │1     1│     clients     │1     *│      orders      │
├─────────────────┤───────├─────────────────┤───────├──────────────────┤
│ id (PK)         │       │ id (PK)         │       │ id (PK)          │
│ name            │       │ user_id (FK)    │       │ order_number (UQ)│
│ email (UQ)      │       │ company_name    │       │ client_id (FK)   │
│ password        │       │ whatsapp_no     │       │ package_id (FK)  │
│ role            │       │ address         │       │ total_amount     │
└─────────────────┘       └─────────────────┘       │ status           │
                                                    └────────┬─────────┘
                                                             │1
               ┌────────────────────────┬────────────────────┼───────────────────────┐
               │*                       │1                   │*                      │*
       ┌───────┴─────────┐      ┌───────┴─────────┐  ┌───────┴─────────┐     ┌───────┴─────────┐
       │   order_addons  │      │brief_submissions│  │project_milestones│   │    invoices     │
       ├─────────────────┤      ├─────────────────┤  ├─────────────────┤     ├─────────────────┤
       │ id (PK)         │      │ id (PK)         │  │ id (PK)         │     │ id (PK)         │
       │ order_id (FK)   │      │ order_id (FK)   │  │ order_id (FK)   │     │ order_id (FK)   │
       │ addon_name      │      │ brand_name      │  │ title           │     │ invoice_no (UQ) │
       │ price           │      │ color_palette   │  │ stage_number    │     │ term_type       │
       └─────────────────┘      │ logo_path       │  │ staging_url     │     │ amount          │
                                │ status          │  │ status          │     │ status          │
                                └─────────────────┘  └────────┬────────┘     └────────┬────────┘
                                                              │1                      │1
                                                              │*                      │*
                                                     ┌────────┴────────┐     ┌────────┴────────┐
                                                     │ revision_tickets│     │    payments     │
                                                     ├─────────────────┤     ├─────────────────┤
                                                     │ id (PK)         │     │ id (PK)         │
                                                     │ milestone_id(FK)│     │ invoice_id (FK) │
                                                     │ description     │     │ payment_channel │
                                                     │ attachment_url  │     │ reference_code  │
                                                     │ status          │     │ amount_paid     │
                                                     └─────────────────┘     │ paid_at         │
                                                                             └─────────────────┘
```

---

### 6.2 Kamus Data Entitas Kunci (Data Dictionary)

#### 1. Entitas `users`
| Nama Kolom | Tipe Data | Constraint | Keterangan |
| :--- | :--- | :--- | :--- |
| `id` | `BIGSERIAL` | PRIMARY KEY | Pengenal unik record pengguna. |
| `name` | `VARCHAR(150)` | NOT NULL | Nama lengkap personil/klien. |
| `email` | `VARCHAR(150)` | UNIQUE, NOT NULL | Alamat email resmi untuk proses otentikasi. |
| `password` | `VARCHAR(255)` | NOT NULL | Kata sandi terenkripsi algoritma Bcrypt. |
| `role` | `VARCHAR(20)` | NOT NULL | Nilai enum: `admin`, `pm`, `developer`, `finance`, `client`. |
| `phone_number`| `VARCHAR(20)` | NULL | Nomor kontak telepon/WhatsApp pengguna. |
| `created_at` | `TIMESTAMP` | DEFAULT NOW() | Waktu pembuatan akun. |

#### 2. Entitas `clients`
| Nama Kolom | Tipe Data | Constraint | Keterangan |
| :--- | :--- | :--- | :--- |
| `id` | `BIGSERIAL` | PRIMARY KEY | Pengenal unik profil klien. |
| `user_id` | `BIGINT` | FOREIGN KEY (`users.id`) | Relasi ke akun login pengguna. |
| `company_name`| `VARCHAR(150)` | NOT NULL | Nama entitas bisnis, CV, PT, atau toko klien. |
| `business_sector`|`VARCHAR(100)`| NOT NULL | Sektor bisnis (Kuliner, Fashion, Hukum, dll). |
| `whatsapp_no` | `VARCHAR(25)` | NOT NULL | Nomor WhatsApp resmi penerima notifikasi notulen/invoice. |
| `address` | `TEXT` | NULL | Alamat fisik kantor pemesan jasa website. |

#### 3. Entitas `service_packages`
| Nama Kolom | Tipe Data | Constraint | Keterangan |
| :--- | :--- | :--- | :--- |
| `id` | `BIGSERIAL` | PRIMARY KEY | Pengenal unik paket layanan. |
| `name` | `VARCHAR(100)` | NOT NULL | Nama paket (contoh: *Company Profile Pro*). |
| `slug` | `VARCHAR(100)` | UNIQUE, NOT NULL | Slug URL paket untuk display web. |
| `base_price` | `DECIMAL(12,2)`| NOT NULL | Harga dasar sebelum penambahan fitur add-on. |
| `features_list`|`JSONB` | NOT NULL | Daftar ringkasan fitur bawaan dalam bentuk JSON array. |
| `revision_quota`|`SMALLINT` | NOT NULL DEFAULT 2 | Jatah kuota pengajuan revisi gratis. |
| `estimated_days`|`SMALLINT` | NOT NULL | Estimasi durasi pengerjaan dalam hari kerja normal. |

#### 4. Entitas `orders`
| Nama Kolom | Tipe Data | Constraint | Keterangan |
| :--- | :--- | :--- | :--- |
| `id` | `BIGSERIAL` | PRIMARY KEY | Pengenal unik transaksi order. |
| `order_number`| `VARCHAR(50)` | UNIQUE, NOT NULL | Nomor pesanan (contoh: `ORD-20261014-001`). |
| `client_id` | `BIGINT` | FOREIGN KEY (`clients.id`)| Merujuk ke entitas klien pemesan. |
| `package_id` | `BIGINT` | FOREIGN KEY (`service_packages.id`) | Merujuk ke paket website yang dipilih. |
| `total_amount`| `DECIMAL(12,2)`| NOT NULL | Total nilai kesepakatan kontrak (paket + add-on). |
| `status` | `VARCHAR(30)` | NOT NULL | Status siklus: `WAITING_DP`, `BRIEF_PHASE`, `IN_PROGRESS`, `STAGING_REVIEW`, `COMPLETED`, `CANCELLED`. |
| `target_completion_date`|`DATE`| NULL | Tenggat waktu penyelesaian proyek yang dijanjikan. |

#### 5. Entitas `brief_submissions`
| Nama Kolom | Tipe Data | Constraint | Keterangan |
| :--- | :--- | :--- | :--- |
| `id` | `BIGSERIAL` | PRIMARY KEY | Pengenal unik data materi brief. |
| `order_id` | `BIGINT` | FOREIGN KEY (`orders.id`) | Merujuk ke pesanan yang bersangkutan. |
| `brand_name` | `VARCHAR(120)` | NOT NULL | Nama merek yang akan ditampilkan pada website. |
| `color_palette`|`VARCHAR(100)` | NULL | Preferensi warna (kode HEX atau deskripsi nama warna). |
| `competitor_references`|`TEXT` | NULL | Tautan URL website acuan/kompetitor. |
| `logo_file_path`|`VARCHAR(255)`| NULL | Jalur penyimpanan berkas logo di disk/storage. |
| `supporting_assets_url`|`TEXT` | NULL | Tautan folder Google Drive aset tambahan berkas gambar. |
| `status` | `VARCHAR(20)` | DEFAULT 'SUBMITTED'| Status: `SUBMITTED`, `NEED_REVISION`, `ACCEPTED`. |

#### 6. Entitas `project_milestones`
| Nama Kolom | Tipe Data | Constraint | Keterangan |
| :--- | :--- | :--- | :--- |
| `id` | `BIGSERIAL` | PRIMARY KEY | Pengenal unik tahapan milestone. |
| `order_id` | `BIGINT` | FOREIGN KEY (`orders.id`) | Merujuk ke order proyek. |
| `title` | `VARCHAR(150)` | NOT NULL | Judul tahapan (contoh: *Fase 2 - Desain Wireframe UI/UX*). |
| `stage_number`| `SMALLINT` | NOT NULL | Urutan kronologis tahapan kerja (1 sampai 5). |
| `staging_url` | `VARCHAR(255)` | NULL | Tautan demo website untuk preview oleh klien. |
| `status` | `VARCHAR(25)` | NOT NULL | Nilai: `LOCKED`, `ACTIVE`, `IN_REVIEW`, `APPROVED`. |
| `approved_at` | `TIMESTAMP` | NULL | Timestamp persetujuan digital oleh klien. |

#### 7. Entitas `invoices`
| Nama Kolom | Tipe Data | Constraint | Keterangan |
| :--- | :--- | :--- | :--- |
| `id` | `BIGSERIAL` | PRIMARY KEY | Pengenal unik record tagihan. |
| `invoice_number`|`VARCHAR(50)` | UNIQUE, NOT NULL | Nomor formal faktur (contoh: `INV-202610-0042`). |
| `order_id` | `BIGINT` | FOREIGN KEY (`orders.id`) | Merujuk ke pesanan proyek terkait. |
| `term_type` | `VARCHAR(30)` | NOT NULL | Tipe termin: `TERM_1_DP`, `TERM_2_SETTLEMENT`, `EXTRA_REVISION`. |
| `amount` | `DECIMAL(12,2)`| NOT NULL | Nominal tagihan wajib dibayarkan. |
| `due_date` | `DATE` | NOT NULL | Tanggal jatuh tempo pembayaran. |
| `status` | `VARCHAR(20)` | DEFAULT 'UNPAID' | Nilai status: `UNPAID`, `PAID`, `CANCELLED`, `EXPIRED`. |

#### 8. Entitas `credential_vaults`
| Nama Kolom | Tipe Data | Constraint | Keterangan |
| :--- | :--- | :--- | :--- |
| `id` | `BIGSERIAL` | PRIMARY KEY | Pengenal unik data kredensial akses. |
| `order_id` | `BIGINT` | FOREIGN KEY (`orders.id`) | Merujuk ke proyek yang bersangkutan. |
| `service_label`|`VARCHAR(80)` | NOT NULL | Label akses (cPanel, Domain Registrar, WP-Admin, Database). |
| `login_url` | `VARCHAR(255)` | NOT NULL | URL halaman login pengelolaan server/CMS. |
| `username` | `VARCHAR(100)` | NOT NULL | Username akun akses. |
| `encrypted_password`|`TEXT` | NOT NULL | Password tersimpan dengan enkripsi simetris AES-256. |
| `is_unlocked` | `BOOLEAN` | DEFAULT FALSE | Flag penanda hak akses buka password oleh klien. |

---

## 7. Matriks Keterlacakan Kebutuhan (Requirements Traceability Matrix)

| Kebutuhan PRD | ID Kebutuhan SRS | Prioritas | Modul Terkait | Kriteria Verifikasi |
| :--- | :--- | :--- | :--- | :--- |
| **Katalog & Kalkulator Biaya** | FR-CAT-01, NFR-PERF-01 | Must-Have | Katalog & Pricing | Perhitungan total harga dan estimasi hari berubah otomatis secara real-time saat add-on diubah. |
| **Pemesanan & Order Intake** | FR-ORD-01, NFR-SEC-03 | Must-Have | Order Management | Nomor order unik berhasil terbentuk dan status order beralih menjadi `WAITING_DP`. |
| **Formulir Brief Terpandu** | FR-ORD-02, NFR-PERF-03 | Must-Have | Client Onboarding | Klien berhasil mengunggah logo resolusi tinggi dan mengisi preferensi desain tanpa error. |
| **Billing Termin Multi-Fase** | FR-PAY-01 | Must-Have | Finansial & Invoicing | Sistem menghasilkan invoice DP (50%) dan Pelunasan (50%) dengan perhitungan presisi. |
| **Integrasi Payment Gateway** | FR-PAY-02, NFR-PERF-02 | Must-Have | Integrasi Gateway | Webhook transaksi settlement mengubah status invoice menjadi `PAID` dan bersifat idempoten. |
| **Pelacakan Milestone & Demo**| FR-TRK-01, FR-TRK-02 | Must-Have | Project Tracking | Klien dapat membuka preview staging dan melakukan penandatanganan approval fase digital. |
| **Manajemen Kuota Revisi** | FR-REV-01 | Should-Have | Revision Governance| Kuota berkurang tiap kali revisi disetujui, serta menerbitkan tagihan tambahan saat kuota 0. |
| **Brankas Akses Kredensial** | FR-VLT-01, FR-VLT-02 | Must-Have | Security & Handover | Kredensial tersimpan dalam enkripsi AES-256 dan hanya dapat didekripsi setelah status pelunasan LUNAS. |
| **Rekapitulasi Laba Proyek** | FR-RPT-01 | Could-Have | Laporan Keuangan | Menghitung laba kotor proyek secara akurat (Total bayar dikurangi biaya modal domain/hosting). |
| **Kontainerisasi Sistem** | NFR-MNT-01, NFR-REL-03 | Must-Have | Infrastruktur Docker | Aplikasi berjalan normal dengan perintah `docker compose up -d` menggunakan PHP 8.2 & PostgreSQL 15. |

---

## 8. Timeline & Matriks Rencana Kerja (16 Minggu / 8 Sprint Scrum)

Pengembangan sistem dirancang menggunakan metodologi **Agile / Scrum** dengan siklus *sprint* 2 mingguan sepanjang 16 minggu (4 bulan penuh) yang relevan untuk satu semester perkuliahan:

```text
TIMELINE PENGEMBANGAN SISTEM (16 MINGGU):
[Sprint 1: Fondasi & Docker] ──> [Sprint 2: Katalog & Order] ──> [Sprint 3: Payment Gateway] ──>
[Sprint 4: Brief & Board PM] ──> [Sprint 5: Staging & Revisi] ──> [Sprint 6: Vault & BAST] ──>
[Sprint 7: Testing & Security] ──> [Sprint 8: Cutover & Go-Live]
```

### Rincian Eksekusi Sprint:

* **Sprint 1 (Minggu 1–2) - Environment Setup & Foundation:**
  * Penyiapan berkas kontainer `Dockerfile` dan `docker-compose.yml` (PHP 8.2-cli + PostgreSQL 15).
  * Inisialisasi basis data relasional, migration skema, dan konfigurasi environment `.env`.
  * Implementasi modul otentikasi (FR-AUTH-01) dengan 5 peran pengguna dan enkripsi password Bcrypt.
* **Sprint 2 (Minggu 3–4) - Katalog Layanan & Order Management:**
  * Pembuatan katalog landing page dan komponen kalkulator biaya dinamis (FR-CAT-01).
  * Alur registrasi klien dan mekanisme order checkout intake (FR-ORD-01).
* **Sprint 3 (Minggu 5–6) - Invoicing & Integrasi Payment Gateway:**
  * Implementasi mesin pembagi termin tagihan (Termin 1 DP 50% dan Termin 2 Pelunasan).
  * Integrasi API Virtual Account & QRIS Sandbox (FR-PAY-01).
  * Pembuatan endpoint Webhook Listener dengan verifikasi signature HMAC dan pencegahan duplikasi (*idempotency*).
* **Sprint 4 (Minggu 7–8) - Onboarding Brief & Task Assignment:**
  * Pembuatan antarmuka formulir pengumpulan brief terpandu dan modul upload berkas aset aman (FR-ORD-02).
  * Dashboard internal Project Manager untuk peninjauan materi brief dan alokasi desainer/developer pelaksana.
* **Sprint 5 (Minggu 9–10) - Milestone Tracking & Sistem Revisi Berkuota:**
  * Tampilan timeline milestone 5 tahapan pengerjaan pada portal klien dan internal tim (FR-TRK-01).
  * Fitur penyematan tautan live preview staging dan tombol digital approval.
  * Modul tiket revisi dengan penghitung kuota otomatis dan pencegahan *scope creep* (FR-REV-01).
* **Sprint 6 (Minggu 11–12) - Vault Kredensial, BAST, & Reporting:**
  * Implementasi brankas penyimpanan akun cPanel/hosting terenkripsi AES-256 (FR-VLT-01).
  * Mekanisme pembukaan kunci kredensial bersyarat pelunasan termin 2 (FR-VLT-02).
  * Generator otomatis dokumen Berita Acara Serah Terima (BAST) dan kuitansi pembayaran format PDF.
  * Modul laporan laba rugi proyek dan rekap beban vendor (FR-RPT-01).
* **Sprint 7 (Minggu 13–14) - Quality Assurance, Security Audit, & Testing:**
  * Eksekusi Unit Test dan Feature Test pada modul kalkulasi harga, webhook payment, dan enkripsi vault.
  * Uji penetrasi keamanan (*Penetration Testing*): Verifikasi kerentanan SQLi, Cross-Site Scripting (XSS), IDOR pada dokumen invoice, dan validasi MIME upload file.
  * Uji beban simulasi transaksi (*Stress Testing*) untuk memastikan kestabilan kontainer Docker saat menerima request simultan.
* **Sprint 8 (Minggu 15–16) - UAT, Migrasi, & Production Handover:**
  * Pelaksanaan *User Acceptance Testing* (UAT) bersama perwakilan klien dan pengguna internal.
  * Penandatanganan berita acara pengujian sistem (UAT Sign-off).
  * Finalisasi dokumentasi instalasi Docker dan presentasi proyek tugas besar semester 5.

---

## 9. Quality Gates Sebelum Rilis Produksi (Production Readiness Checklist)

Sebelum sistem diizinkan untuk di-deploy ke lingkungan operasional produksi, seluruh kriteria gerbang mutu (*Quality Gates*) berikut wajib terpenuhi:

```text
[CHECKLIST GERBANG MUTU / QUALITY GATES]
├── [1. Keamanan & Kredensial]
│   ├── Nilai password vault 100% terenkripsi AES-256 (tidak ada plaintext di database).
│   ├── 0 temuan tingkat Critical dan High pada pemindaian keamanan OWASP Top 10.
│   └── Seluruh kredensial API gateway dan rahasia aplikasi tersimpan di berkas .env terisolasi.
├── [2. Integritas Pembayaran & Idempotensi]
│   ├── Pengujian simulasi 20 callback webhook berulang dengan ID transaksi sama menghasilkan tepat 1 status PAID.
│   └── Akses kredensial server terbukti secara mutlak tetap terkunci apabila Termin 2 bernilai UNPAID.
├── [3. Manajemen Revisi & Berkas]
│   ├── Sistem berhasil memblokir pengajuan revisi baru saat kuota paket telah bernilai 0.
│   └── Sistem menolak pengunggahan file biner berbahaya (.exe, .php, .sh) pada form brief aset.
└── [4. Stabilitas Kontainer Docker]
    ├── Perintah `docker compose up --build` berjalan lancar tanpa error dependensi.
    └── Basis data PostgreSQL berhasil melakukan *auto-mount data persistence* pada volume `pgdata`.
```

---
*Dokumen ini disusun sebagai panduan teknis resmi rancang bangun perangkat lunak pada lingkungan Docker & Laravel untuk mata kuliah Pemrograman Web Semester 5.*
