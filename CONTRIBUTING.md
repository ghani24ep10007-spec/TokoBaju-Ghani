# Panduan Kolaborator - Toko Baju Ghani (Laravel & Docker)

Selamat datang di proyek **Toko Baju Ghani**! Dokumen ini memandu setiap kolaborator untuk berkontribusi secara rapi dan profesional.

---

## 🛠️ Persyaratan Lingkungan
Kolaborator hanya membutuhkan:
1. **Git**
2. **Docker Desktop** (atau Docker Engine + Compose)

---

## 🚀 Alur Kerja Kolaborasi (Git Workflow)

### 1. Clone & Setup Repository
```bash
# Clone repository
git clone https://github.com/ghani24ep10007-spec/TokoBaju-Ghani.git
cd TokoBaju-Ghani

# Buat branch baru untuk fitur Anda
git checkout -b fitur/nama-fitur
```

### 2. Jalankan Kontainer Docker
```bash
# Nyalakan server aplikasi dan PostgreSQL
docker compose up -d --build

# Jalankan migrasi dan seeder
docker compose exec app php artisan migrate
docker compose exec app php artisan db:seed
```
Buka browser di `http://localhost:8000`.

### 3. Commit & Push Perubahan
```bash
git add .
git commit -m "feat: deskripsi fitur yang ditambahkan"
git push origin fitur/nama-fitur
```

### 4. Buat Pull Request (PR)
Buka GitHub dan ajukan **Pull Request (PR)** ke branch `main`. CI/CD GitHub Actions akan secara otomatis menjalankan pengujian kualitas kode dan Docker container!

---

## 👥 Menambahkan Kolaborator di GitHub
Pemilik repositori dapat menambahkan kolaborator dengan cara:
1. Buka halaman repositori di GitHub.
2. Klik tab **Settings** (Pengaturan).
3. Pilih menu **Collaborators** di sidebar kiri.
4. Klik tombol **Add people**.
5. Masukkan username GitHub (misal: `ghaniadinata` atau `ghani24ep10007-spec`).
6. Kirim undangan (*invite*). Kolaborator tinggal menerima undangan via notifikasi email atau link GitHub.
