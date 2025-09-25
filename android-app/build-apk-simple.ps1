# Simple APK Build Script for Dunco School Management System
Write-Host "========================================" -ForegroundColor Green
Write-Host "   Dunco School Management APK Builder" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""

# Check if Java is installed
Write-Host "Checking Java installation..." -ForegroundColor Yellow
try {
    $javaVersion = java -version 2>&1
    Write-Host "✅ Java found:" -ForegroundColor Green
    Write-Host $javaVersion -ForegroundColor White
} catch {
    Write-Host "❌ Java not found in PATH" -ForegroundColor Red
    Write-Host ""
    Write-Host "Please install Java JDK 17 from:" -ForegroundColor Yellow
    Write-Host "https://adoptium.net/temurin/releases/" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "After installation, run this script again." -ForegroundColor Yellow
    Read-Host "Press Enter to continue"
    exit 1
}

# Check JAVA_HOME
Write-Host ""
Write-Host "Checking JAVA_HOME..." -ForegroundColor Yellow
if ($env:JAVA_HOME) {
    Write-Host "✅ JAVA_HOME is set to: $env:JAVA_HOME" -ForegroundColor Green
} else {
    Write-Host "❌ JAVA_HOME is not set" -ForegroundColor Red
    Write-Host ""
    Write-Host "Setting JAVA_HOME automatically..." -ForegroundColor Yellow
    
    # Try to find Java installation
    $javaLocations = @(
        "C:\Program Files\Java",
        "C:\Program Files (x86)\Java"
    )
    
    $foundJava = $null
    foreach ($location in $javaLocations) {
        if (Test-Path $location) {
            $javaDirs = Get-ChildItem $location -Directory | Where-Object { $_.Name -match "jdk|java" }
            if ($javaDirs) {
                $latestJava = $javaDirs | Sort-Object Name -Descending | Select-Object -First 1
                $foundJava = $latestJava.FullName
                break
            }
        }
    }
    
    if ($foundJava) {
        Write-Host "Setting JAVA_HOME to: $foundJava" -ForegroundColor Green
        [Environment]::SetEnvironmentVariable("JAVA_HOME", $foundJava, "User")
        $env:JAVA_HOME = $foundJava
    } else {
        Write-Host "❌ Could not find Java installation" -ForegroundColor Red
        Write-Host "Please install Java JDK 17 manually" -ForegroundColor Yellow
        Read-Host "Press Enter to continue"
        exit 1
    }
}

# Build the APK
Write-Host ""
Write-Host "Building Android APK..." -ForegroundColor Yellow
Write-Host "This may take a few minutes..." -ForegroundColor White

# Clean previous build
if (Test-Path "gradlew.bat") {
    Write-Host "Cleaning previous build..." -ForegroundColor White
    & .\gradlew.bat clean | Out-Null
}

# Build debug APK
Write-Host "Building debug APK..." -ForegroundColor White
$buildResult = & .\gradlew.bat assembleDebug

if ($LASTEXITCODE -eq 0) {
    Write-Host ""
    Write-Host "✅ APK built successfully!" -ForegroundColor Green
    
    # Check if APK exists
    $apkPath = "app\build\outputs\apk\debug\app-debug.apk"
    if (Test-Path $apkPath) {
        $apkSize = (Get-Item $apkPath).Length
        $apkSizeMB = [math]::Round($apkSize / 1MB, 2)
        
        Write-Host ""
        Write-Host "📱 APK Details:" -ForegroundColor Cyan
        Write-Host "   Location: $apkPath" -ForegroundColor White
        Write-Host "   Size: $apkSizeMB MB" -ForegroundColor White
        Write-Host ""
        Write-Host "📋 To install on your phone:" -ForegroundColor Yellow
        Write-Host "   1. Copy the APK to your phone" -ForegroundColor White
        Write-Host "   2. Enable 'Install from Unknown Sources' in settings" -ForegroundColor White
        Write-Host "   3. Open the APK file and install" -ForegroundColor White
        Write-Host ""
        Write-Host "🎉 Your Dunco School Management app is ready!" -ForegroundColor Green
    } else {
        Write-Host "❌ APK file not found" -ForegroundColor Red
    }
} else {
    Write-Host ""
    Write-Host "❌ Build failed!" -ForegroundColor Red
    Write-Host ""
    Write-Host "Common solutions:" -ForegroundColor Yellow
    Write-Host "1. Make sure you have Android SDK installed" -ForegroundColor White
    Write-Host "2. Check that ANDROID_HOME is set correctly" -ForegroundColor White
    Write-Host "3. Try running: .\gradlew.bat --info assembleDebug" -ForegroundColor White
    Write-Host "4. Install Android Studio for full development environment" -ForegroundColor White
}

Write-Host ""
Write-Host "========================================" -ForegroundColor Green
Write-Host "           Build Complete!" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""
Read-Host "Press Enter to continue" 