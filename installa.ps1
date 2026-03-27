Write-Host "=====================================" -ForegroundColor Cyan
Write-Host "  Installazione Dashboard TV" -ForegroundColor Cyan
Write-Host "=====================================" -ForegroundColor Cyan

# 1. Verifica PHP
Write-Host "`n[1/6] Verifico PHP..." -ForegroundColor Yellow
try {
    $phpVersion = php -v 2>&1 | Select-String "PHP"
    Write-Host "OK — $phpVersion" -ForegroundColor Green
} catch {
    Write-Host "ERRORE: PHP non trovato. Installa XAMPP da https://www.apachefriends.org" -ForegroundColor Red
    exit
}

# 2. Verifica Composer
Write-Host "`n[2/6] Verifico Composer..." -ForegroundColor Yellow
try {
    $compVersion = composer -V 2>&1 | Select-String "Composer"
    Write-Host "OK — $compVersion" -ForegroundColor Green
} catch {
    Write-Host "ERRORE: Composer non trovato. Scaricalo da https://getcomposer.org" -ForegroundColor Red
    exit
}

# 3. Installa dipendenze Laravel
Write-Host "`n[3/6] Installo dipendenze PHP (composer install)..." -ForegroundColor Yellow
composer install --no-interaction
Write-Host "OK" -ForegroundColor Green

# 4. Configura .env
Write-Host "`n[4/6] Configuro il file .env..." -ForegroundColor Yellow
if (-Not (Test-Path ".env")) {
    Copy-Item ".env.example" ".env"
    Write-Host "OK — .env creato" -ForegroundColor Green
} else {
    Write-Host "OK — .env gia esistente, lo lascio com'e" -ForegroundColor Green
}

# 5. Genera la chiave app
Write-Host "`n[5/6] Genero la chiave applicazione..." -ForegroundColor Yellow
php artisan key:generate
Write-Host "OK" -ForegroundColor Green

# 6. Database e storage
Write-Host "`n[6/6] Configuro database e storage..." -ForegroundColor Yellow

if (-Not (Test-Path "database\database.sqlite")) {
    New-Item -Path "database\database.sqlite" -ItemType File | Out-Null
    Write-Host "OK — database.sqlite creato" -ForegroundColor Green
} else {
    Write-Host "OK — database.sqlite gia esistente" -ForegroundColor Green
}

php artisan migrate --force
php artisan storage:link

# Crea cartella video se non esiste
if (-Not (Test-Path "public\videos")) {
    New-Item -Path "public\videos" -ItemType Directory | Out-Null
}

Write-Host "`n=====================================" -ForegroundColor Cyan
Write-Host "  Installazione completata!" -ForegroundColor Green
Write-Host "=====================================" -ForegroundColor Cyan
Write-Host "`nORE SUCCESSIVE:" -ForegroundColor Yellow
Write-Host "1. Copia i tuoi video in: public\videos\" -ForegroundColor White
Write-Host "2. Avvia il server con: php artisan serve" -ForegroundColor White
Write-Host "3. Apri il browser su: http://127.0.0.1:8000" -ForegroundColor White
Write-Host ""