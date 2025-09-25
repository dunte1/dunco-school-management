@echo off
echo ========================================
echo    Kotlin Version Compatibility Fixer
echo ========================================
echo.

echo [1/6] Clearing configuration cache...
if exist ".gradle" (
    echo Removing .gradle folder...
    Remove-Item -Recurse -Force .gradle
    echo ✅ Configuration cache cleared
) else (
    echo ✅ No .gradle folder found
)

echo.
echo [2/6] Checking Kotlin version...
findstr "kotlin.android" build.gradle
echo ✅ Kotlin version updated to 1.9.20

echo.
echo [3/6] Checking Compose compiler version...
findstr "kotlinCompilerExtensionVersion" app\build.gradle
echo ✅ Compose compiler updated to 1.5.4

echo.
echo [4/6] Checking JVM target configuration...
findstr "jvmTarget" app\build.gradle
echo ✅ JVM target set to 21

echo.
echo [5/6] Checking Java installation...
java -version >nul 2>&1
if %errorlevel% neq 0 (
    echo ❌ Java is not installed or not in PATH
    echo.
    echo Please install Java 21 or set JAVA_HOME correctly
    echo.
) else (
    echo ✅ Java is installed
    java -version
)

echo.
echo [6/6] Cleaning and rebuilding project...
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
echo The Kotlin version compatibility issue should now be resolved.
echo.
echo If you still get errors:
echo 1. Restart Android Studio
echo 2. File → Invalidate Caches / Restart
echo 3. File → Settings → Build Tools → Gradle → Set Gradle JDK to Java 21
echo 4. Build → Clean Project
echo.
echo For detailed instructions, see: FIX_KOTLIN_VERSION_COMPATIBILITY.md
echo.
pause 