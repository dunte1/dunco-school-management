@echo off
echo ========================================
echo    Android Studio Setup Checker
echo ========================================
echo.

echo [1/5] Checking Java installation...
java -version >nul 2>&1
if %errorlevel% neq 0 (
    echo ❌ Java is not installed or not in PATH
    echo.
    echo Please install JDK 11 or 17 from:
    echo https://adoptium.net/
    echo.
    echo After installation, set JAVA_HOME:
    echo setx JAVA_HOME "C:\Program Files\Java\jdk-11.0.x" /M
    echo setx PATH "%%PATH%%;%%JAVA_HOME%%\bin" /M
    echo.
    pause
    exit /b 1
) else (
    echo ✅ Java is installed
    java -version
)

echo.
echo [2/5] Checking Android SDK...
if exist "%LOCALAPPDATA%\Android\Sdk" (
    echo ✅ Android SDK found at: %LOCALAPPDATA%\Android\Sdk
) else (
    echo ❌ Android SDK not found
    echo Please install Android Studio and let it download the SDK
)

echo.
echo [3/5] Checking project structure...
if exist "app\build.gradle" (
    echo ✅ Project structure is correct
) else (
    echo ❌ Project structure issue
    echo Make sure you're in the android-app directory
)

echo.
echo [4/5] Checking Gradle wrapper...
if exist "gradlew.bat" (
    echo ✅ Gradle wrapper found
) else (
    echo ❌ Gradle wrapper missing
    echo This should be automatically generated
)

echo.
echo [5/5] Checking local.properties...
if exist "local.properties" (
    echo ✅ local.properties found
    echo SDK path: 
    findstr "sdk.dir" local.properties
) else (
    echo ❌ local.properties missing
    echo This file should be created by Android Studio
)

echo.
echo ========================================
echo           Setup Complete!
echo ========================================
echo.
echo Next steps:
echo 1. Install Android Studio if not already installed
echo 2. Open Android Studio
echo 3. Open project: %CD%
echo 4. Wait for Gradle sync to complete
echo 5. Build → Build APK(s)
echo.
echo For detailed instructions, see: ANDROID_STUDIO_SETUP.md
echo.
pause 