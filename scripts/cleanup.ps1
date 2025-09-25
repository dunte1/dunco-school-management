Param()

Set-StrictMode -Version Latest
$ErrorActionPreference = 'Stop'

# Ensure we run from repo root (script is in scripts/)
Set-Location -Path (Join-Path $PSScriptRoot '..')

Write-Host 'Deleting all .zip files...'
$zips = Get-ChildItem -Recurse -File -Filter *.zip -ErrorAction SilentlyContinue
if ($zips) {
	$zips | ForEach-Object { Write-Host (' - ' + $_.FullName) }
	$zips | Remove-Item -Force
} else {
	Write-Host ' - No zip files found.'
}

Write-Host 'Fixing PSR-4 directory casing for module database folders...'
$modules = Get-ChildItem -Directory 'Modules' -ErrorAction SilentlyContinue
foreach ($m in $modules) {
	$dbLower = Join-Path $m.FullName 'database'
	if (Test-Path $dbLower) {
		$dbUpper = Join-Path $m.FullName 'Database'
		if (-not (Test-Path $dbUpper)) { New-Item -ItemType Directory -Path $dbUpper | Out-Null }
		foreach ($name in 'seeders','migrations','factories') {
			$src = Join-Path $dbLower $name
			if (Test-Path $src) {
				$proper = $name.Substring(0,1).ToUpper() + $name.Substring(1)
				$dst = Join-Path $dbUpper $proper
				if (-not (Test-Path $dst)) { New-Item -ItemType Directory -Path $dst | Out-Null }
				Get-ChildItem -Force $src | ForEach-Object { Move-Item -LiteralPath $_.FullName -Destination $dst -Force }
			}
		}
	}
}

Write-Host 'Cleanup complete.'




