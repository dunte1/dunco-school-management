# Java Setup and Android APK Build Guide

## Current Issue
You have Java 8 JRE installed, but Android development requires **JDK 11 or higher**.

## Solution Options

### Option 1: Manual Java Installation (Recommended)

1. **Download Java JDK 17** from: https://adoptium.net/temurin/releases/
   - Choose: **Windows x64 JDK 17**
   - Download the `.msi` installer

2. **Install Java JDK 17**
   - Run the downloaded `.msi` file
   - Follow the installation wizard
   - Make sure to check "Set JAVA_HOME variable"

3. **Verify Installation**
   - Open a new Command Prompt
   - Run: `java -version`
   - Should show Java 17.x.x

4. **Build the APK**
   - Run: `.\quick-build.bat`

### Option 2: Using Android Studio (Easiest)

1. **Download Android Studio** from: https://developer.android.com/studio
2. **Install Android Studio**
   - It comes with its own JDK
   - Follow the setup wizard
3. **Open your project** in Android Studio
4. **Build APK** using: Build → Build Bundle(s) / APK(s) → Build APK(s)

### Option 3: Automatic Installation Script

Run the provided script:
```bash
.\install-java-and-build.bat
```

## Quick Test

After installing Java JDK 17, test with:

```bash
# Check Java version
java -version

# Should show something like:
# openjdk version "17.0.9" 2023-10-17
# OpenJDK Runtime Environment Temurin-17.0.9+9 (build 17.0.9+9)
# OpenJDK 64-Bit Server VM Temurin-17.0.9+9 (build 17.0.9+9, mixed mode, sharing)

# Build APK
.\gradlew.bat assembleDebug
```

## APK Location

After successful build, your APK will be at:
```
app\build\outputs\apk\debug\app-debug.apk
```

## Install on Phone

1. Copy the APK to your phone
2. Enable "Install from Unknown Sources" in Android settings
3. Open the APK file and install

## Troubleshooting

### If you get "JAVA_HOME is not set":
1. Set JAVA_HOME manually:
   ```cmd
   setx JAVA_HOME "C:\Program Files\Java\jdk-17.0.9"
   ```
2. Restart Command Prompt
3. Try building again

### If you get "Java 8 JVM" error:
- Make sure you installed JDK 17, not JRE 8
- Check that JAVA_HOME points to the JDK folder, not JRE

### If build fails:
1. Run: `.\gradlew.bat --info assembleDebug`
2. Check the detailed error output
3. Make sure Android SDK is installed

## Project Information

- **App Name**: Dunco School Management System
- **Package**: com.dunco.schoolmanagement
- **Min SDK**: 24 (Android 7.0)
- **Target SDK**: 34 (Android 14)
- **Build Tools**: Android Gradle Plugin 8.1.0
- **Required Java**: JDK 11 or higher (JDK 17 recommended) 