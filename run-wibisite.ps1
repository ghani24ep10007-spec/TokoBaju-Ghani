# PowerShell script: run-wibisite.ps1
# ------------------------------------------------------------
# Full Continuous Deployment (CD) script for Toko Baju Ghani (Laravel + Docker).
# Run this script from the VS Code integrated PowerShell terminal.
# ------------------------------------------------------------

# 1. Helper functions ------------------------------------------------
function Write-Info($msg) {
    Write-Host "$msg" -ForegroundColor Cyan
}
function Write-Warn($msg) {
    Write-Host "$msg" -ForegroundColor Yellow
}
function Write-ErrorMsg($msg) {
    Write-Host "$msg" -ForegroundColor Red
}

# 2. Ensure Docker Desktop is running --------------------------------
$dockerCmd = Get-Command docker -ErrorAction SilentlyContinue
if (-not $dockerCmd) {
    Write-Warn "Docker command not found. Trying to start Docker Desktop..."
    $dockerPath = "C:\Program Files\Docker\Docker\Docker Desktop.exe"
    if (Test-Path $dockerPath) {
        Start-Process $dockerPath
        Write-Info "Docker Desktop launched. Waiting for daemon to become ready..."
    } else {
        Write-ErrorMsg "Docker Desktop executable not found at $dockerPath. Please install Docker Desktop first."
        exit 1
    }
}

# 3. Wait for Docker daemon ------------------------------------------------
$maxAttempts = 30
$attempt = 0
while ($attempt -lt $maxAttempts) {
    try {
        docker info > $null 2>&1
        if ($LASTEXITCODE -eq 0) { break }
    } catch {}
    $attempt++
    Write-Info "Waiting for Docker engine... ($attempt/$maxAttempts)"
    Start-Sleep -Seconds 2
}
if ($attempt -eq $maxAttempts) {
    Write-ErrorMsg "Docker engine did not become ready in time. Abort."
    exit 1
}
Write-Info "Docker engine is ready."

# 4. Build and start the stack ------------------------------------------
Write-Info "Building Docker images and starting containers..."
Set-Location -Path (Split-Path -Parent $MyInvocation.MyCommand.Path)

docker compose up -d --build
if ($LASTEXITCODE -ne 0) {
    Write-ErrorMsg "docker compose failed. Check the output above."
    exit 1
}
Write-Info "Containers started."

# 5. Wait for PostgreSQL to be ready --------------------------------------
Write-Info "Waiting for PostgreSQL to accept connections..."
$pgReady = $false
for ($i=0; $i -lt 30; $i++) {
    $status = docker exec postgres_db pg_isready -U laravel_user -d laravel_db
    if ($status -match "accepting connections") { $pgReady = $true; break }
    Start-Sleep -Seconds 2
}
if (-not $pgReady) {
    Write-Warn "PostgreSQL did not become ready in time. Proceeding anyway – later migrations may fail."
}

# 6. Run Laravel migrations & seeders ------------------------------------
Write-Info "Running Laravel migrations..."
docker exec laravel_app php artisan migrate --force
if ($LASTEXITCODE -ne 0) { Write-Warn "Migrations returned a non-zero code. Check logs." }

# 7. Open the application in the default browser ---------------------------
$appUrl = "http://localhost:8000"
Write-Info "Opening $appUrl in default browser..."
Start-Process $appUrl

# 8. Done ---------------------------------------------------------------
Write-Info "✅ CD process completed. Your webapp should be reachable at $appUrl"
