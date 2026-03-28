Write-Host "=====================================" -ForegroundColor Cyan
Write-Host "  Setup completo Dashboard TV" -ForegroundColor Cyan
Write-Host "=====================================" -ForegroundColor Cyan

# 1. Verifica winget
if (-Not (Get-Command winget -ErrorAction SilentlyContinue)) {
    Write-Host "ERRORE: winget non trovato. Aggiorna Windows." -ForegroundColor Red
    exit
}

# 2. Installa XAMPP
Write-Host "`n[1/3] Installo XAMPP..." -ForegroundColor Yellow
winget install -e --id ApacheFriends.Xampp.8.2 --accept-package-agreements --accept-source-agreements

# 3. Installa Composer
Write-Host "`n[2/3] Installo Composer..." -ForegroundColor Yellow
winget install -e --id Composer.Composer --accept-package-agreements --accept-source-agreements

# 4. Installa Git
Write-Host "`n[3/3] Installo Git..." -ForegroundColor Yellow
winget install -e --id Git.Git --accept-package-agreements --accept-source-agreements

# 5. Aggiunta PHP al PATH (Metodo semplificato)
Write-Host "`nAggiungo PHP al PATH di sistema..." -ForegroundColor Yellow
$phpDir = "C:\xampp\php"
$oldPath = [System.Environment]::GetEnvironmentVariable("Path", "Machine")
if ($oldPath -notlike "*$phpDir*") {
    $newPath = "$oldPath;$phpDir"
    [System.Environment]::SetEnvironmentVariable("Path", $newPath, "Machine")
    Write-Host "OK - PHP aggiunto correttamente." -ForegroundColor Green
} else {
    Write-Host "OK - PHP era gia presente." -ForegroundColor Green
}

Write-Host "`n=====================================" -ForegroundColor Cyan
Write-Host "  Setup completato con successo!" -ForegroundColor Green
Write-Host "=====================================" -ForegroundColor Cyan
Write-Host "1. CHIUDI questa finestra di PowerShell."
Write-Host "2. RIAPRILA e vai nella cartella del progetto."
Write-Host "3. Esegui: .\installa.ps1"
Write-Host "====================================="