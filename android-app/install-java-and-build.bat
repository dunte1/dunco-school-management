@echo off
echo ========================================
echo    Java Installation and APK Build
echo ========================================
echo.

echo [1/6] Checking current Java installation...
java -version >nul 2>&1
if %errorlevel% equ 0 (
    echo ✅ Java is installed
    java -version
) else (
    echo ❌ Java is not installed or not in PATH
)

echo.
echo [2/6] Checking for JDK 11+...
set "FOUND_JDK="

REM Check for existing JDK installations
if exist "C:\Program Files\Java\latest" (
    set "JAVA_HOME=C:\Program Files\Java\latest"
    echo Found Java at: %JAVA_HOME%
    "%JAVA_HOME%\bin\java" -version >nul 2>&1
    if %errorlevel% equ 0 (
        set "FOUND_JDK=1"
        echo ✅ Valid JDK found
    )
)

if not defined FOUND_JDK (
    echo ❌ No suitable JDK found (need JDK 11+)
    echo.
    echo [3/6] Installing Java JDK 17...
    echo Downloading OpenJDK 17...
    
    REM Download OpenJDK 17
    powershell -Command "& {[Net.ServicePointManager]::SecurityProtocol = [Net.SecurityProtocolType]::Tls12; Invoke-WebRequest -Uri 'https://github.com/adoptium/temurin17-binaries/releases/download/jdk-17.0.9%%2B9/OpenJDK17U-jdk_x64_windows_hotspot_17.0.9_9.zip' -OutFile 'jdk17.zip'}"
    
    if exist "jdk17.zip" (
        echo Extracting JDK...
        powershell -Command "Expand-Archive -Path 'jdk17.zip' -DestinationPath 'C:\Program Files\Java' -Force"
        
        REM Find the extracted JDK folder
        for /f "delims=" %%i in ('dir "C:\Program Files\Java" /b /ad ^| findstr "jdk-17"') do (
            set "JAVA_HOME=C:\Program Files\Java\%%i"
        )
        
        if defined JAVA_HOME (
            echo ✅ JDK installed at: %JAVA_HOME%
            setx JAVA_HOME "%JAVA_HOME%"
        ) else (
            echo ❌ Failed to install JDK
            pause
            exit /b 1
        )
        
        REM Clean up
        del "jdk17.zip"
    ) else (
        echo ❌ Failed to download JDK
        echo.
        echo Please manually install Java JDK 17 from:
        echo https://adoptium.net/temurin/releases/
        echo.
        pause
        exit /b 1
    )
) else (
    echo [3/6] Using existing JDK...
)

echo.
echo [4/6] Verifying Java setup...
if defined JAVA_HOME (
    "%JAVA_HOME%\bin\java" -version
    if %errorlevel% neq 0 (
        echo ❌ Java verification failed
        pause
        exit /b 1
    )
    echo ✅ Java setup verified
) else (
    echo ❌ JAVA_HOME not set
    pause
    exit /b 1
)

echo.
echo [5/6] Building Android APK...
call gradlew.bat clean
call gradlew.bat assembleDebug

if %errorlevel% neq 0 (
    echo ❌ Build failed
    echo.
    echo Trying with verbose output...
    call gradlew.bat --info assembleDebug
    pause
    exit /b 1
)

echo.
echo [6/6] Checking APK output...
if exist "app\build\outputs\apk\debug\app-debug.apk" (
    echo ✅ APK created successfully!
    echo.
    echo APK Location: app\build\outputs\apk\debug\app-debug.apk
    echo.
    echo To install on your phone:
    echo 1. Copy the APK to your phone
    echo 2. Enable "Install from Unknown Sources" in settings
    echo 3. Open the APK file and install
    echo.
    for %%A in ("app\build\outputs\apk\debug\app-debug.apk") do echo APK Size: %%~zA bytes
) else (
    echo ❌ APK not found
    echo.
    echo Check the build output above for errors.
)

echo.
echo ========================================
echo           Process Complete!
echo ========================================
echo.
echo If you encounter issues:
echo 1. Restart your terminal/command prompt
echo 2. Make sure JAVA_HOME is set correctly
echo 3. Install Android Studio for full development environment
echo.
pause 