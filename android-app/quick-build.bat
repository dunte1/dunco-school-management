@echo off
echo ========================================
echo    Quick Android APK Build Script
echo ========================================
echo.

echo [1/4] Setting up Java environment...

REM Try to find and set JAVA_HOME
if exist "C:\Program Files\Java\latest" (
    set "JAVA_HOME=C:\Program Files\Java\latest"
    echo Found Java at: %JAVA_HOME%
) else if exist "C:\Program Files\Java\jdk-11" (
    set "JAVA_HOME=C:\Program Files\Java\jdk-11"
    echo Found Java at: %JAVA_HOME%
) else if exist "C:\Program Files\Java\jdk-17" (
    set "JAVA_HOME=C:\Program Files\Java\jdk-17"
    echo Found Java at: %JAVA_HOME%
) else if exist "C:\Program Files\Java\jdk-21" (
    set "JAVA_HOME=C:\Program Files\Java\jdk-21"
    echo Found Java at: %JAVA_HOME%
) else (
    echo ❌ No suitable JDK found
    echo.
    echo Please install Java JDK 11 or higher from:
    echo https://adoptium.net/temurin/releases/
    echo.
    pause
    exit /b 1
)

echo ✅ JAVA_HOME set to: %JAVA_HOME%

echo.
echo [2/4] Verifying Java...
"%JAVA_HOME%\bin\java" -version
if %errorlevel% neq 0 (
    echo ❌ Java verification failed
    pause
    exit /b 1
)

echo.
echo [3/4] Building APK...
call gradlew.bat assembleDebug
if %errorlevel% neq 0 (
    echo ❌ Build failed
    echo.
    echo Trying with more verbose output...
    call gradlew.bat --info assembleDebug
    pause
    exit /b 1
)

echo.
echo [4/4] Checking APK output...
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
)

echo.
echo ========================================
echo           Build Complete!
echo ========================================
pause 