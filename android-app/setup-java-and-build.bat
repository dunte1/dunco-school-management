@echo off
echo ========================================
echo    Java Setup and Android Build Script
echo ========================================
echo.

echo [1/8] Checking current Java installation...
java -version >nul 2>&1
if %errorlevel% equ 0 (
    echo ✅ Java is installed
    java -version
) else (
    echo ❌ Java is not installed or not in PATH
)

echo.
echo [2/8] Checking JAVA_HOME...
if defined JAVA_HOME (
    echo ✅ JAVA_HOME is set to: %JAVA_HOME%
) else (
    echo ❌ JAVA_HOME is not set
)

echo.
echo [3/8] Looking for existing Java installations...
set "FOUND_JAVA="

REM Check common Java installation locations
if exist "C:\Program Files\Java" (
    echo Found Java installations in C:\Program Files\Java
    dir "C:\Program Files\Java" /b
    set "FOUND_JAVA=C:\Program Files\Java"
)

if exist "C:\Program Files (x86)\Java" (
    echo Found Java installations in C:\Program Files (x86)\Java
    dir "C:\Program Files (x86)\Java" /b
    if not defined FOUND_JAVA set "FOUND_JAVA=C:\Program Files (x86)\Java"
)

if exist "C:\Users\%USERNAME%\AppData\Local\Android\Sdk\jbr" (
    echo Found Android Studio JDK
    set "FOUND_JAVA=C:\Users\%USERNAME%\AppData\Local\Android\Sdk\jbr"
)

echo.
echo [4/8] Setting up Java environment...
if defined FOUND_JAVA (
    echo Found Java at: %FOUND_JAVA%
    
    REM Find the latest Java version
    for /f "delims=" %%i in ('dir "%FOUND_JAVA%" /b /ad ^| findstr /i "jdk\|java"') do (
        set "JAVA_PATH=%FOUND_JAVA%\%%i"
    )
    
    if defined JAVA_PATH (
        echo Setting JAVA_HOME to: %JAVA_PATH%
        setx JAVA_HOME "%JAVA_PATH%"
        set "JAVA_HOME=%JAVA_PATH%"
        echo ✅ JAVA_HOME set successfully
    ) else (
        echo ❌ Could not find valid Java installation
    )
) else (
    echo ❌ No Java installation found
    echo.
    echo Please install Java JDK 11 or higher from:
    echo https://adoptium.net/temurin/releases/
    echo.
    echo After installation, run this script again.
    pause
    exit /b 1
)

echo.
echo [5/8] Verifying Java version...
if defined JAVA_HOME (
    "%JAVA_HOME%\bin\java" -version >nul 2>&1
    if %errorlevel% equ 0 (
        echo ✅ Java is working correctly
        "%JAVA_HOME%\bin\java" -version
    ) else (
        echo ❌ Java is not working correctly
    )
)

echo.
echo [6/8] Cleaning previous build...
if exist "gradlew.bat" (
    call gradlew.bat clean
    echo ✅ Clean completed
) else (
    echo ❌ Gradle wrapper not found
)

echo.
echo [7/8] Building debug APK...
if exist "gradlew.bat" (
    call gradlew.bat assembleDebug
    if %errorlevel% equ 0 (
        echo ✅ Build completed successfully!
    ) else (
        echo ❌ Build failed
        echo.
        echo Common solutions:
        echo 1. Make sure you have Android SDK installed
        echo 2. Check that ANDROID_HOME is set correctly
        echo 3. Try running: gradlew.bat --info assembleDebug
    )
) else (
    echo ❌ Gradle wrapper not found
)

echo.
echo [8/8] Checking APK output...
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
    echo APK Size: 
    for %%A in ("app\build\outputs\apk\debug\app-debug.apk") do echo %%~zA bytes
) else (
    echo ❌ APK not found
    echo.
    echo Check the build output above for errors.
)

echo.
echo ========================================
echo           Setup Complete!
echo ========================================
echo.
echo If you encounter issues:
echo 1. Restart your terminal/command prompt
echo 2. Make sure JAVA_HOME is set correctly
echo 3. Install Android Studio for full development environment
echo.
pause 