<#
.SYNOPSIS
    Fresh-migrate smoke check against a temporary SQLite database.

.DESCRIPTION
    Runs `migrate:fresh` (and optionally seeders) against a throwaway SQLite
    database so it never touches developer data. Used by CI and local checks.

.PARAMETER Seed
    Also run database seeders after migrating.

.EXAMPLE
    pwsh ./scripts/fresh-migrate-check.ps1 -Seed
#>
param(
    [switch]$Seed
)

$ErrorActionPreference = 'Stop'

$tmpDb = Join-Path ([System.IO.Path]::GetTempPath()) ("dunco-fresh-" + [guid]::NewGuid().ToString('N') + ".sqlite")
New-Item -ItemType File -Path $tmpDb -Force | Out-Null

$previousConnection = $env:DB_CONNECTION
$previousDatabase   = $env:DB_DATABASE

$env:DB_CONNECTION = 'sqlite'
$env:DB_DATABASE   = $tmpDb

$failed = $false
try {
    Write-Host "==> migrate:fresh on temporary sqlite DB: $tmpDb"
    php artisan migrate:fresh --force
    if ($LASTEXITCODE -ne 0) { throw "migrate:fresh failed with exit code $LASTEXITCODE" }

    if ($Seed) {
        Write-Host "==> db:seed"
        php artisan db:seed --force
        if ($LASTEXITCODE -ne 0) { throw "db:seed failed with exit code $LASTEXITCODE" }
    }

    Write-Host "OK: fresh migration smoke check passed." -ForegroundColor Green
}
catch {
    $failed = $true
    Write-Host "FAILED: $($_.Exception.Message)" -ForegroundColor Red
}
finally {
    Remove-Item -LiteralPath $tmpDb -Force -ErrorAction SilentlyContinue

    if ($null -eq $previousConnection) { Remove-Item Env:DB_CONNECTION -ErrorAction SilentlyContinue } else { $env:DB_CONNECTION = $previousConnection }
    if ($null -eq $previousDatabase)   { Remove-Item Env:DB_DATABASE   -ErrorAction SilentlyContinue } else { $env:DB_DATABASE   = $previousDatabase }
}

if ($failed) { exit 1 }
exit 0
