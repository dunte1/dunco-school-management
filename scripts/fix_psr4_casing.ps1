Param()

Set-StrictMode -Version Latest
$ErrorActionPreference = 'Stop'

# Run from repo root (script is in scripts/)
Set-Location -Path (Join-Path $PSScriptRoot '..')

function Rename-WithTempExactCase {
	param(
		[string]$Path,
		[string]$TargetName
	)
	if (-not (Test-Path -LiteralPath $Path)) { return }
	$parent = Split-Path -Parent $Path
	$tmp = Join-Path $parent ("tmp_case_" + [Guid]::NewGuid().ToString('N'))
	Rename-Item -LiteralPath $Path -NewName (Split-Path -Leaf $tmp)
	Rename-Item -LiteralPath $tmp -NewName $TargetName
}

Write-Host 'Fixing exact directory casing for Modules/*/Database...'
$modules = Get-ChildItem -Directory 'Modules' -ErrorAction SilentlyContinue
foreach ($m in $modules) {
	# database -> Database
	$dbLower = Join-Path $m.FullName 'database'
	if (Test-Path -LiteralPath $dbLower) {
		Rename-WithTempExactCase -Path $dbLower -TargetName 'Database'
	}

	# Inside Database, fix subfolders
	$db = Join-Path $m.FullName 'Database'
	if (Test-Path -LiteralPath $db) {
		foreach ($pair in @(@('seeders','Seeders'), @('migrations','Migrations'), @('factories','Factories'))) {
			$lowerName = $pair[0]
			$properName = $pair[1]
			$lowerPath = Join-Path $db $lowerName
			$properPath = Join-Path $db $properName
			if (Test-Path -LiteralPath $lowerPath) {
				Rename-WithTempExactCase -Path $lowerPath -TargetName $properName
			}
		}
	}
}

Write-Host 'Done fixing casing.'




