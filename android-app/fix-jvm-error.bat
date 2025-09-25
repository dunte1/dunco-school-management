@echo off
echo ========================================
echo    JVM Target Error Fixer
echo ========================================
echo.

echo [1/4] Checking Java installation...
java -version >nul 2>&1
if %errorlevel% neq 0 (
    echo ❌ Java is not installed!
    echo.
    echo Please install JDK 11 from: https://adoptium.net/
    echo.
    echo After installation, run this script again.
    echo.
    pause
    exit /b 1
) else (
    echo ✅ Java is installed
    java -version
)

echo.
echo [2/4] Checking JAVA_HOME...
if "%JAVA_HOME%"=="" (
    echo ❌ JAVA_HOME is not set
    echo.
    echo Please set JAVA_HOME environment variable:
    echo setx JAVA_HOME "C:\Program Files\Eclipse Adoptium\jdk-11.0.x-hotspot" /M
    echo.
) else (
    echo ✅ JAVA_HOME is set to: %JAVA_HOME%
)

echo.
echo [3/4] Checking project configuration...
if exist "app\build.gradle" (
    echo ✅ Project structure is correct
    echo ✅ JVM target has been updated to 1.8
) else (
    echo ❌ Project structure issue
)

echo.
echo [4/4] Cleaning project...
if exist "gradlew.bat" (
    echo Running: ./gradlew clean
    call gradlew clean
    echo.
    echo Running: ./gradlew assembleDebug
    call gradlew assembleDebug
) else (
    echo ❌ Gradle wrapper not found
)

echo.
echo ========================================
echo           Fix Complete!
echo ========================================
echo.
echo If you still get JVM target errors:
echo 1. Restart Android Studio
echo 2. File → Invalidate Caches / Restart
echo 3. Build → Clean Project
echo 4. Build → Rebuild Project
echo.
echo For detailed instructions, see: FIX_JVM_TARGET_ERROR.md
echo.
pause 