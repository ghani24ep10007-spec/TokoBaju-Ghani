# PowerShell script: run-cloudflare.ps1
# ------------------------------------------------------------
# Menghubungkan http://localhost:8000 ke domain sahir.my.id via Cloudflare Tunnel
# ------------------------------------------------------------

param (
    [string]$Subdomain = "tokobaju",
    [string]$Domain = "sahir.my.id",
    [switch]$QuickTunnel
)

$ErrorActionPreference = "Stop"

Write-Host "==========================================================" -ForegroundColor Cyan
Write-Host "   CLOUDFLARE TUNNEL - SAHIR.MY.ID (LOCALHOST:8000)      " -ForegroundColor Cyan
Write-Host "==========================================================" -ForegroundColor Cyan

$cloudflared = ".\cloudflared.exe"
if (-not (Test-Path $cloudflared)) {
    Write-Host "[*] Mengunduh cloudflared.exe..." -ForegroundColor Yellow
    curl.exe -L -o "cloudflared.exe" "https://github.com/cloudflare/cloudflared/releases/latest/download/cloudflared-windows-amd64.exe"
}

# 1. Mode Quick Tunnel (Instan tanpa login Cloudflare)
if ($QuickTunnel) {
    Write-Host "[*] Memulai Cloudflare Quick Tunnel (TryCloudflare)..." -ForegroundColor Green
    & $cloudflared tunnel --url http://localhost:8000
    exit
}

# 2. Mode Domain Resmi (sahir.my.id)
$certPath = "$env:USERPROFILE\.cloudflared\cert.pem"

if (-not (Test-Path $certPath)) {
    Write-Host "[!] Belum login ke Cloudflare." -ForegroundColor Yellow
    Write-Host "[*] Browser akan dibuka untuk mengotorisasi domain '$Domain'..." -ForegroundColor Cyan
    Write-Host "[*] Silakan pilih domain '$Domain' pada browser yang terbuka." -ForegroundColor Cyan
    & $cloudflared tunnel login
}

if (-not (Test-Path $certPath)) {
    Write-Host "[!] Otorisasi belum selesai. Menjalankan Quick Tunnel sementara..." -ForegroundColor Red
    & $cloudflared tunnel --url http://localhost:8000
    exit
}

$fullHostname = "$Subdomain.$Domain"
$tunnelName = "tunnel-$Subdomain"

Write-Host "[✓] Terautentikasi ke Cloudflare!" -ForegroundColor Green
Write-Host "[*] Memeriksa / Menyiapkan Tunnel '$tunnelName'..." -ForegroundColor Yellow

# Buat tunnel jika belum ada
$tunnelList = & $cloudflared tunnel list
if ($tunnelList -notmatch $tunnelName) {
    Write-Host "[*] Membuat tunnel baru: $tunnelName" -ForegroundColor Cyan
    & $cloudflared tunnel create $tunnelName
}

# Route DNS record ke domain sahir.my.id
Write-Host "[*] Mendaftarkan DNS record otomatis: $fullHostname -> $tunnelName" -ForegroundColor Yellow
& $cloudflared tunnel route dns -f $tunnelName $fullHostname

Write-Host ""
Write-Host "==========================================================" -ForegroundColor Green
Write-Host " [SUKSES] Subdomain aktif: https://$fullHostname         " -ForegroundColor Green
Write-Host " Meneruskan trafik ke: http://localhost:8000             " -ForegroundColor Cyan
Write-Host "==========================================================" -ForegroundColor Green
Write-Host ""

# Jalankan tunnel
& $cloudflared tunnel run --url http://localhost:8000 $tunnelName

