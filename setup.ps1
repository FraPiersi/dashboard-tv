Write-Host "=====================================" -ForegroundColor Cyan
Write-Host "  Setup completo Dashboard TV" -ForegroundColor Cyan
Write-Host "=====================================" -ForegroundColor Cyan

# Verifica winget
if (-Not (Get-Command winget -ErrorAction SilentlyContinue)) {
    Write-Host "ERRORE: winget non trovato. Aggiorna Windows dal Microsoft Store." -ForegroundColor Red
    exit
}

# 1. Installa XAMPP
Write-Host "`n[1/3] Installo XAMPP..." -ForegroundColor Yellow
winget install -e --id ApacheFriends.Xampp.8.2 --accept-package-agreements --accept-source-agreements
Write-Host "OK — XAMPP installato" -ForegroundColor Green

# 2. Installa Composer
Write-Host "`n[2/3] Installo Composer..." -ForegroundColor Yellow
winget install -e --id Composer.Composer --accept-package-agreements --accept-source-agreements
Write-Host "OK — Composer installato" -ForegroundColor Green

# 3. Installa Git
Write-Host "`n[3/3] Installo Git..." -ForegroundColor Yellow
winget install -e --id Git.Git --accept-package-agreements --accept-source-agreements
Write-Host "OK — Git installato" -ForegroundColor Green

# Aggiunge PHP di XAMPP al PATH
Write-Host "`nAggiungo PHP al PATH..." -ForegroundColor Yellow
$phpPath = "C:\xampp\php"
$currentPath = [System.Environment]::GetEnvironmentVariable("Path", "Machine")
if ($currentPath -notlike "*$phpPath*") {
    [System.Environment]::SetEnvironmentVariable("Path", $currentPath + ";$phpPath", "Machine")
    Write-Host "OK — PHP aggiunto al PATH" -ForegroundColor Green
} else {
    Write-Host "OK — PHP era gia nel PATH" -ForegroundColor Green
}

Write-Host "`n=====================================" -ForegroundColor Cyan
Write-Host "  Setup completato!" -ForegroundColor Green
Write-Host "=====================================" -ForegroundColor Cyan
Write-Host "`nORA:" -ForegroundColor Yellow
Write-Host "1. CHIUDI e RIAPRI PowerShell" -ForegroundColor White
Write-Host "2. Poi esegui: .\installa.ps1" -ForegroundColor White
Write-Host ""