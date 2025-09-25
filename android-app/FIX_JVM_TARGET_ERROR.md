# 🔧 Fix: "Unknown Kotlin JVM target: 21" Error

## 🚨 **Root Cause**
The error occurs because:
1. **Java is not installed** on your system
2. **JVM target mismatch** between your Java version and project configuration
3. **Android Studio** is trying to use a JVM target that doesn't exist

## ✅ **Solution Applied**

I've updated your `app/build.gradle` to use **Java 8** compatibility:
```gradle
compileOptions {
    sourceCompatibility JavaVersion.VERSION_1_8
    targetCompatibility JavaVersion.VERSION_1_8
}

kotlinOptions {
    jvmTarget = '1.8'
}
```

This makes your project compatible with:
- ✅ Java 8 (minimum)
- ✅ Java 11 (recommended)
- ✅ Java 17 (latest)

---

## 🚀 **Complete Fix Steps**

### **Step 1: Install Java (Required)**

**Option A: Install JDK 11 (Recommended)**
1. Download from: https://adoptium.net/temurin/releases/
2. Choose: **Windows x64 JDK 11**
3. Install with default settings

**Option B: Install JDK 8 (Minimum)**
1. Download from: https://adoptium.net/temurin/releases/
2. Choose: **Windows x64 JDK 8**
3. Install with default settings

### **Step 2: Set Environment Variables**

**Open PowerShell as Administrator and run:**
```powershell
# For JDK 11 (recommended)
setx JAVA_HOME "C:\Program Files\Eclipse Adoptium\jdk-11.0.x-hotspot" /M
setx PATH "%PATH%;%JAVA_HOME%\bin" /M

# OR for JDK 8
setx JAVA_HOME "C:\Program Files\Eclipse Adoptium\jdk-8.0.x-hotspot" /M
setx PATH "%PATH%;%JAVA_HOME%\bin" /M
```

### **Step 3: Verify Java Installation**
```powershell
# Restart your terminal/PowerShell
java -version
```

**Expected output:**
```
openjdk version "11.0.x" 2023-xx-xx
OpenJDK Runtime Environment Temurin-11.0.x+x
OpenJDK 64-Bit Server VM Temurin-11.0.x+x (build 11.0.x+x, mixed mode)
```

### **Step 4: Clean and Rebuild**

**In Android Studio:**
1. File → Invalidate Caches / Restart
2. Build → Clean Project
3. Build → Rebuild Project

**Or via command line:**
```bash
cd android-app
./gradlew clean
./gradlew assembleDebug
```

---

## 🔍 **Alternative Solutions**

### **If you still get JVM target errors:**

**Option 1: Update Gradle Properties**
Add to `gradle.properties`:
```properties
org.gradle.jvmargs=-Xmx2048m -Dfile.encoding=UTF-8 -XX:+UseParallelGC
android.useAndroidX=true
kotlin.code.style=official
android.nonTransitiveRClass=true
```

**Option 2: Check Android Studio Settings**
1. File → Settings → Build, Execution, Deployment → Compiler
2. Set "Kotlin compiler target JVM" to **1.8**

**Option 3: Update Kotlin Version**
In `build.gradle` (project level):
```gradle
plugins {
    id 'org.jetbrains.kotlin.android' version '1.8.20' apply false
}
```

---

## 🚨 **Common Issues & Solutions**

### **Issue 1: "JAVA_HOME is not set"**
**Solution:**
```powershell
# Check if JAVA_HOME is set
echo $env:JAVA_HOME

# If empty, set it manually
setx JAVA_HOME "C:\Program Files\Eclipse Adoptium\jdk-11.0.x-hotspot" /M
```

### **Issue 2: "Gradle daemon failed"**
**Solution:**
1. Delete `.gradle` folder in project
2. Restart Android Studio
3. Let it rebuild Gradle cache

### **Issue 3: "Android SDK not found"**
**Solution:**
1. Install Android Studio
2. Let it download Android SDK
3. Set SDK path in `local.properties`

---

## 📱 **Test Your Fix**

### **Quick Test Commands:**
```bash
# Navigate to project
cd "C:\Users\dunth\dunco school management system\duncoschool\android-app"

# Check Java
java -version

# Check Gradle
./gradlew --version

# Build debug APK
./gradlew assembleDebug

# Build release APK
./gradlew assembleRelease
```

### **Expected Success:**
- ✅ Java version shows correctly
- ✅ Gradle sync completes without errors
- ✅ APK builds successfully
- ✅ No JVM target errors

---

## 🎯 **What I Fixed**

1. **✅ Updated JVM target** from '11' to '1.8' for better compatibility
2. **✅ Updated Java compatibility** to VERSION_1_8
3. **✅ Created this fix guide** with step-by-step instructions
4. **✅ Provided alternative solutions** for different scenarios

---

## 📞 **Still Having Issues?**

1. **Check Java installation**: `java -version`
2. **Check JAVA_HOME**: `echo $env:JAVA_HOME`
3. **Clean project**: Build → Clean Project
4. **Invalidate caches**: File → Invalidate Caches / Restart
5. **Check Android Studio logs**: View → Tool Windows → Build

**Your app should now build successfully! 🚀** 