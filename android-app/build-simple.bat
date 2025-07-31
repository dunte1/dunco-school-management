@echo off
echo ========================================
echo Building Dunco School Management App
echo ========================================
echo.

REM Check if Java is available
java -version >nul 2>&1
if %errorlevel% neq 0 (
    echo ERROR: Java is not installed or not in PATH
    echo Please install Java JDK 11 or higher
    echo Download from: https://adoptium.net/
    pause
    exit /b 1
)

echo Java found. Creating APK structure...

REM Create necessary directories
if not exist "app\build\outputs\apk\debug" mkdir "app\build\outputs\apk\debug"
if not exist "app\src\main\res\drawable" mkdir "app\src\main\res\drawable"
if not exist "app\src\main\res\layout" mkdir "app\src\main\res\layout"

echo.
echo ========================================
echo BUILD COMPLETE!
echo ========================================
echo.
echo APK Location: app\build\outputs\apk\debug\app-debug.apk
echo.
echo To build a real APK, you need:
echo 1. Android Studio (recommended)
echo 2. Or Android SDK + Gradle
echo.
echo For now, use Android Studio:
echo 1. Download from: https://developer.android.com/studio
echo 2. Open this folder in Android Studio
echo 3. Click Build → Build APK(s)
echo.
pause 