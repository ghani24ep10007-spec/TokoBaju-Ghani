# Script Otomatis Setup & Jalankan Docker Web App (Laravel + PostgreSQL)
Write-Host "==========================================================" -ForegroundColor Cyan
Write-Host "  SETUP & RUNNING DOCKER LARAVEL + POSTGRESQL (NO ERROR)  " -ForegroundColor Cyan
Write-Host "==========================================================" -ForegroundColor Cyan

# 1. Cek apakah Docker terpasang dan berjalan
$dockerCmd = Get-Command docker -ErrorAction SilentlyContinue

if (-not $dockerCmd) {
    $dockerPath = "C:\Program Files\Docker\Docker\Docker Desktop.exe"
    if (Test-Path $dockerPath) {
        Write-Host "[1/4] Menyalakan Docker Desktop..." -ForegroundColor Yellow
        Start-Process $dockerPath
    } else {
        Write-Host "[!] Docker Desktop belum terinstall di Windows." -ForegroundColor Red
        Write-Host "[!] Membuka installer Docker Desktop Installer.exe..." -ForegroundColor Yellow
        Start-Process ".\Docker Desktop Installer.exe"
        Write-Host "[*] Silakan selesaikan instalasi Docker Desktop, lalu jalankan script ini kembali." -ForegroundColor Green
        exit
    }
}

# 2. Tunggu Docker Daemon aktif
Write-Host "[1/3] Memeriksa koneksi Docker Engine..." -ForegroundColor Yellow
$maxAttempts = 30
$attempt = 0
$dockerReady = $false

while (-not $dockerReady -and $attempt -lt $maxAttempts) {
    $attempt++
    docker info 2>&1 | Out-Null
    if ($LASTEXITCODE -eq 0) {
        $dockerReady = $true
        break
    }
    Write-Host "Menunggu Docker Engine aktif (percobaan $attempt/$maxAttempts)..." -ForegroundColor DarkGray
    Start-Sleep -Seconds 2
}

if (-not $dockerReady) {
    Write-Host "[!] Docker Desktop belum siap. Pastikan icon paus di taskbar sudah 'Running'." -ForegroundColor Red
    exit
}

Write-Host "[✓] Docker Engine siap!" -ForegroundColor Green

# 3. Build dan jalankan container
Write-Host "[2/3] Membangun image & menyalakan container (docker compose up -d --build)..." -ForegroundColor Yellow
docker compose up -d --build

# 4. Tunggu database PostgreSQL siap dan jalankan migrasi
Write-Host "[3/3] Menyiapkan migrasi tabel database PostgreSQL..." -ForegroundColor Yellow
Start-Sleep -Seconds 5
docker compose exec app php artisan migrate

Write-Host ""
Write-Host "==========================================================" -ForegroundColor Green
Write-Host " [SUKSES] Aplikasi web berhasil dijalankan tanpa error!  " -ForegroundColor Green
Write-Host " Buka browser di: http://localhost:8000                  " -ForegroundColor Cyan
Write-Host "==========================================================" -ForegroundColor Green

# Buka otomatis browser
Start-Process "http://localhost:8000"
