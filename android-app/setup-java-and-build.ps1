# PowerShell script for Java setup and Android build
Write-Host "========================================" -ForegroundColor Green
Write-Host "   Java Setup and Android Build Script" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""

Write-Host "[1/8] Checking current Java installation..." -ForegroundColor Yellow
try {
    $javaVersion = java -version 2>&1
    Write-Host "✅ Java is installed" -ForegroundColor Green
    Write-Host $javaVersion
} catch {
    Write-Host "❌ Java is not installed or not in PATH" -ForegroundColor Red
}

Write-Host ""
Write-Host "[2/8] Checking JAVA_HOME..." -ForegroundColor Yellow
if ($env:JAVA_HOME) {
    Write-Host "✅ JAVA_HOME is set to: $env:JAVA_HOME" -ForegroundColor Green
} else {
    Write-Host "❌ JAVA_HOME is not set" -ForegroundColor Red
}

Write-Host ""
Write-Host "[3/8] Looking for existing Java installations..." -ForegroundColor Yellow
$foundJava = $null

# Check common Java installation locations
$javaLocations = @(
    "C:\Program Files\Java",
    "C:\Program Files (x86)\Java",
    "$env:LOCALAPPDATA\Android\Sdk\jbr",
    "$env:LOCALAPPDATA\Android\Sdk\jre"
)

foreach ($location in $javaLocations) {
    if (Test-Path $location) {
        Write-Host "Found Java installations in $location" -ForegroundColor Green
        Get-ChildItem $location -Directory | ForEach-Object { Write-Host "  - $($_.Name)" }
        if (-not $foundJava) {
            $foundJava = $location
        }
    }
}

Write-Host ""
Write-Host "[4/8] Setting up Java environment..." -ForegroundColor Yellow
if ($foundJava) {
    Write-Host "Found Java at: $foundJava" -ForegroundColor Green
    
    # Find the latest Java version
    $javaDirs = Get-ChildItem $foundJava -Directory | Where-Object { $_.Name -match "jdk|java" }
    if ($javaDirs) {
        $latestJava = $javaDirs | Sort-Object Name -Descending | Select-Object -First 1
        $javaPath = $latestJava.FullName
        
        Write-Host "Setting JAVA_HOME to: $javaPath" -ForegroundColor Green
        [Environment]::SetEnvironmentVariable("JAVA_HOME", $javaPath, "User")
        $env:JAVA_HOME = $javaPath
        Write-Host "✅ JAVA_HOME set successfully" -ForegroundColor Green
    } else {
        Write-Host "❌ Could not find valid Java installation" -ForegroundColor Red
    }
} else {
    Write-Host "❌ No Java installation found" -ForegroundColor Red
    Write-Host ""
    Write-Host "Please install Java JDK 11 or higher from:" -ForegroundColor Yellow
    Write-Host "https://adoptium.net/temurin/releases/" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "After installation, run this script again." -ForegroundColor Yellow
    Read-Host "Press Enter to continue"
    exit 1
}

Write-Host ""
Write-Host "[5/8] Verifying Java version..." -ForegroundColor Yellow
if ($env:JAVA_HOME) {
    $javaExe = Join-Path $env:JAVA_HOME "bin\java.exe"
    if (Test-Path $javaExe) {
        Write-Host "✅ Java is working correctly" -ForegroundColor Green
        & $javaExe -version
    } else {
        Write-Host "❌ Java is not working correctly" -ForegroundColor Red
    }
}

Write-Host ""
Write-Host "[6/8] Cleaning previous build..." -ForegroundColor Yellow
if (Test-Path "gradlew.bat") {
    & .\gradlew.bat clean
    Write-Host "✅ Clean completed" -ForegroundColor Green
} else {
    Write-Host "❌ Gradle wrapper not found" -ForegroundColor Red
}

Write-Host ""
Write-Host "[7/8] Building debug APK..." -ForegroundColor Yellow
if (Test-Path "gradlew.bat") {
    $buildResult = & .\gradlew.bat assembleDebug
    if ($LASTEXITCODE -eq 0) {
        Write-Host "✅ Build completed successfully!" -ForegroundColor Green
    } else {
        Write-Host "❌ Build failed" -ForegroundColor Red
        Write-Host ""
        Write-Host "Common solutions:" -ForegroundColor Yellow
        Write-Host "1. Make sure you have Android SDK installed" -ForegroundColor White
        Write-Host "2. Check that ANDROID_HOME is set correctly" -ForegroundColor White
        Write-Host "3. Try running: .\gradlew.bat --info assembleDebug" -ForegroundColor White
    }
} else {
    Write-Host "❌ Gradle wrapper not found" -ForegroundColor Red
}

Write-Host ""
Write-Host "[8/8] Checking APK output..." -ForegroundColor Yellow
$apkPath = "app\build\outputs\apk\debug\app-debug.apk"
if (Test-Path $apkPath) {
    Write-Host "✅ APK created successfully!" -ForegroundColor Green
    Write-Host ""
    Write-Host "APK Location: $apkPath" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "To install on your phone:" -ForegroundColor Yellow
    Write-Host "1. Copy the APK to your phone" -ForegroundColor White
    Write-Host "2. Enable 'Install from Unknown Sources' in settings" -ForegroundColor White
    Write-Host "3. Open the APK file and install" -ForegroundColor White
    Write-Host ""
    $apkSize = (Get-Item $apkPath).Length
    Write-Host "APK Size: $apkSize bytes" -ForegroundColor Cyan
} else {
    Write-Host "❌ APK not found" -ForegroundColor Red
    Write-Host ""
    Write-Host "Check the build output above for errors." -ForegroundColor Yellow
}

Write-Host ""
Write-Host "========================================" -ForegroundColor Green
Write-Host "           Setup Complete!" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""
Write-Host "If you encounter issues:" -ForegroundColor Yellow
Write-Host "1. Restart your terminal/command prompt" -ForegroundColor White
Write-Host "2. Make sure JAVA_HOME is set correctly" -ForegroundColor White
Write-Host "3. Install Android Studio for full development environment" -ForegroundColor White
Write-Host ""
Read-Host "Press Enter to continue" 