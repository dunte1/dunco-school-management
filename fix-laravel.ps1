function Show-Menu {
    Clear-Host
    Write-Host "===================================" -ForegroundColor Cyan
    Write-Host "   Laravel Tools - PowerShell Menu " -ForegroundColor Cyan
    Write-Host "===================================" -ForegroundColor Cyan
    Write-Host "1. Clear Laravel cache"
    Write-Host "2. Reinstall vendors (fix cache error)"
    Write-Host "3. Run Laravel server"
    Write-Host "4. Run migrations"
    Write-Host "5. Rollback last migration"
    Write-Host "6. Fresh migrate (drop all tables & re-run)"
    Write-Host "7. Seed database"
    Write-Host "8. Migrate + Seed (setup database)"
    Write-Host "9. Exit"
    Write-Host ""
}

function Clear-LaravelCache {
    Write-Host "Clearing Laravel caches..." -ForegroundColor Yellow
    php artisan config:clear
    php artisan cache:clear
    php artisan route:clear
    php artisan view:clear
    php artisan optimize:clear
    Write-Host "✅ Cache cleared!" -ForegroundColor Green
    Pause
}

function Reinstall-Vendors {
    Write-Host "Fixing Laravel vendor issues..." -ForegroundColor Yellow
    if (Test-Path vendor) { Remove-Item vendor -Recurse -Force }
    if (Test-Path composer.lock) { Remove-Item composer.lock -Force }
    composer install
    composer require illuminate/cache
    Write-Host "✅ Vendors reinstalled!" -ForegroundColor Green
    Pause
}

function Run-LaravelServer {
    Write-Host "Starting Laravel server on http://127.0.0.1:8000 ..." -ForegroundColor Green
    php artisan serve --host=127.0.0.1 --port=8000
}

# ==== Database functions ====
function Run-Migrations {
    Write-Host "🔄 Running migrations..." -ForegroundColor Yellow
    php artisan migrate
    Pause
}

function Rollback-Migration {
    Write-Host "⏪ Rolling back last migration..." -ForegroundColor Yellow
    php artisan migrate:rollback
    Pause
}

function Fresh-Migrate {
    Write-Host "⚠ Dropping all tables and re-running migrations..." -ForegroundColor Red
    php artisan migrate:fresh
    Pause
}

function Seed-Database {
    Write-Host "🌱 Seeding database..." -ForegroundColor Yellow
    php artisan db:seed
    Pause
}

function Migrate-Seed {
    Write-Host "🔄 Running migrations & seeding..." -ForegroundColor Yellow
    php artisan migrate --seed
    Pause
}

do {
    Show-Menu
    $choice = Read-Host "Enter your choice (1-9)"

    switch ($choice) {
        "1" { Clear-LaravelCache }
        "2" { Reinstall-Vendors }
        "3" { Run-LaravelServer }
        "4" { Run-Migrations }
        "5" { Rollback-Migration }
        "6" { Fresh-Migrate }
        "7" { Seed-Database }
        "8" { Migrate-Seed }
        "9" { Write-Host "Exiting... Bye!" -ForegroundColor Cyan }
        default { Write-Host "Invalid choice, try again!" -ForegroundColor Red; Pause }
    }
} until ($choice -eq "9")
