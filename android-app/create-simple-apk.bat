@echo off
echo ========================================
echo Creating Simple APK for Testing
echo ========================================
echo.

REM Create necessary directories
if not exist "app\build\outputs\apk\debug" mkdir "app\build\outputs\apk\debug"
if not exist "app\src\main\res\drawable" mkdir "app\src\main\res\drawable"
if not exist "app\src\main\res\layout" mkdir "app\src\main\res\layout"
if not exist "app\src\main\res\values" mkdir "app\src\main\res\values"

echo Creating simple APK structure...

REM Create a simple APK file (placeholder)
echo Creating placeholder APK...
echo "This is a placeholder APK file" > "app\build\outputs\apk\debug\app-debug.apk"

echo.
echo ========================================
echo SIMPLE APK CREATED!
echo ========================================
echo.
echo APK Location: app\build\outputs\apk\debug\app-debug.apk
echo.
echo For a real working APK, use one of these options:
echo.
echo 1. GITHUB ACTIONS (Recommended):
echo    - Push code to GitHub
echo    - Get APK from Actions tab
echo.
echo 2. ANDROID STUDIO (After fixing Gradle):
echo    - Run Android Studio as Administrator
echo    - Change Gradle home directory
echo    - Build → Build APK(s)
echo.
echo 3. BITRISE (Free online build):
echo    - Sign up at bitrise.io
echo    - Connect your GitHub repo
echo    - Download APK from dashboard
echo.
pause 