@echo off
echo ========================================
echo    Fixing Gradle Daemon Issues
echo ========================================
echo.

echo [1/4] Stopping Gradle daemons...
call gradlew.bat --stop
echo ✅ Gradle daemons stopped

echo.
echo [2/4] Clearing Gradle cache...
if exist "%USERPROFILE%\.gradle\daemon" (
    echo Removing Gradle daemon cache...
    rmdir /s /q "%USERPROFILE%\.gradle\daemon"
    echo ✅ Daemon cache cleared
) else (
    echo ✅ No daemon cache found
)

echo.
echo [3/4] Clearing project build cache...
if exist ".gradle" (
    echo Removing project Gradle cache...
    rmdir /s /q ".gradle"
    echo ✅ Project cache cleared
) else (
    echo ✅ No project cache found
)

if exist "build" (
    echo Removing build directory...
    rmdir /s /q "build"
    echo ✅ Build directory cleared
)

if exist "app\build" (
    echo Removing app build directory...
    rmdir /s /q "app\build"
    echo ✅ App build directory cleared
)

echo.
echo [4/4] Testing Gradle...
echo Running Gradle clean to test...
call gradlew.bat clean
if %errorlevel% equ 0 (
    echo ✅ Gradle is working correctly!
    echo.
    echo Now you can build your APK:
    echo .\gradlew.bat assembleDebug
) else (
    echo ❌ Gradle still has issues
    echo.
    echo Try these additional steps:
    echo 1. Restart your computer
    echo 2. Check if any antivirus is blocking Gradle
    echo 3. Try running as administrator
)

echo.
echo ========================================
echo           Fix Complete!
echo ========================================
pause 