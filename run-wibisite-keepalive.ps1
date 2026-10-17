# PowerShell script: run-wibisite-keepalive.ps1
# ------------------------------------------------------------
# Continuous Deployment + Keep-Alive script for Toko Baju Ghani (Laravel + Docker).
# This script ensures the Docker stack stays running continuously.
# Run it from the VS Code integrated PowerShell terminal.
# ------------------------------------------------------------

function Write-Info     { param($msg) Write-Host $msg -ForegroundColor Cyan }
function Write-Warn     { param($msg) Write-Host $msg -ForegroundColor Yellow }
function Write-ErrorMsg { param($msg) Write-Host $msg -ForegroundColor Red }

# 1. Ensure Docker Desktop is installed and running
$dockerCmd = Get-Command docker -ErrorAction SilentlyContinue
if (-not $dockerCmd) {
    Write-Warn "Docker CLI not found. Trying to start Docker Desktop..."
    $dockerPath = "C:\Program Files\Docker\Docker\Docker Desktop.exe"
    if (Test-Path $dockerPath) {
        Start-Process $dockerPath
        Write-Info "Docker Desktop launched. Waiting for daemon to become ready..."
    } else {
        Write-ErrorMsg "Docker Desktop executable not found. Install Docker Desktop first."
        exit 1
    }
}

# 2. Wait for Docker daemon to be ready (max 30 attempts)
$maxAttempts = 30; $i = 0
while ($i -lt $maxAttempts) {
    try { docker info > $null 2>&1; if ($LASTEXITCODE -eq 0) { break } } catch {}
    $i++; Write-Info "Waiting for Docker engine... ($i/$maxAttempts)"; Start-Sleep -Seconds 2
}
if ($i -eq $maxAttempts) { Write-ErrorMsg "Docker engine did not become ready. Abort."; exit 1 }
Write-Info "Docker engine ready."

# 3. Change to project root (where docker-compose.yml lives)
$projectRoot = Split-Path -Parent $MyInvocation.MyCommand.Path
Set-Location $projectRoot

# 4. Build and start containers
Write-Info "Starting Docker containers..."
docker compose up -d --build
if ($LASTEXITCODE -ne 0) { Write-ErrorMsg "docker compose failed. Aborting."; exit 1 }

# 5. Wait for PostgreSQL to accept connections (max 30 attempts)
Write-Info "Waiting for PostgreSQL readiness..."
$pgReady = $false
for ($j=0; $j -lt 30; $j++) {
    $status = docker exec postgres_db pg_isready -U laravel_user -d laravel_db 2>$null
    if ($status -match "accepting connections") { $pgReady = $true; break }
    Start-Sleep -Seconds 2
}
if (-not $pgReady) { Write-Warn "PostgreSQL did not become ready in time. Migrations may fail." }

# 6. Run Laravel migrations (forced)
Write-Info "Running Laravel migrations..."
docker exec laravel_app php artisan migrate --force
if ($LASTEXITCODE -ne 0) { Write-Warn "Migrations returned non-zero code. Check logs." }

# 7. Open the web app in default browser
$appUrl = "http://localhost:8000"
Write-Info "Opening $appUrl in default browser..."
Start-Process $appUrl

# 8. Keep-alive monitor loop (runs until you stop the script)
Write-Info "Starting keep-alive monitor for Toko Baju Ghani. Press Ctrl+C to exit."
while ($true) {
    $down = docker ps -a --filter "status=exited" --filter "name=laravel_app|postgres_db" --format "{{.Names}}"
    if ($down) {
        foreach ($c in $down) {
            Write-Warn "Container $c is stopped. Restarting..."
            docker start $c | Out-Null
        }
    }
    Start-Sleep -Seconds 30
}
