# 📱 Building the Dunco School Management Android App

## 🚀 **Quick Build Options**

### **Option 1: Android Studio (Recommended)**

1. **Download Android Studio**
   - Go to: https://developer.android.com/studio
   - Download and install Android Studio

2. **Open the Project**
   - Open Android Studio
   - Click "Open an existing Android Studio project"
   - Navigate to the `android-app` folder and select it

3. **Build the APK**
   - Wait for Gradle sync to complete
   - Go to Build → Build Bundle(s) / APK(s) → Build APK(s)
   - The APK will be created in `app/build/outputs/apk/debug/`

### **Option 2: Command Line with Gradle**

1. **Install Java JDK 11+**
   ```bash
   # Download from: https://adoptium.net/
   ```

2. **Install Android SDK**
   ```bash
   # Download from: https://developer.android.com/studio#command-tools
   ```

3. **Set Environment Variables**
   ```bash
   export ANDROID_HOME=/path/to/android/sdk
   export JAVA_HOME=/path/to/java
   ```

4. **Build the APK**
   ```bash
   cd android-app
   ./gradlew assembleDebug
   ```

### **Option 3: Online Build Service**

1. **GitHub Actions (Free)**
   - Push the code to GitHub
   - Set up GitHub Actions workflow
   - Get APK from Actions artifacts

2. **Bitrise (Free tier)**
   - Connect your GitHub repository
   - Configure Android build
   - Download APK from dashboard

## 🔧 **Prerequisites**

### **Required Software**
- **Java JDK 11 or higher**
- **Android SDK** (API 24-34)
- **Gradle 8.0+**

### **System Requirements**
- **Windows**: Windows 10/11
- **macOS**: macOS 10.14+
- **Linux**: Ubuntu 18.04+

## 📱 **Testing the App**

### **1. Install on Phone**
1. Enable "Install from Unknown Sources" in phone settings
2. Copy APK to phone
3. Open APK file and install

### **2. Configure API**
Edit `app/build.gradle`:
```gradle
debug {
    buildConfigField "String", "BASE_URL", "\"http://YOUR_IP:8000/api/mobile/v1/\""
}
```

### **3. Test Credentials**
- **Email**: `student@test.com`
- **Password**: `password123`

## 🛠️ **Troubleshooting**

### **Common Issues**

**1. "Gradle sync failed"**
- Check internet connection
- Update Gradle version
- Clear Gradle cache

**2. "SDK not found"**
- Install Android SDK
- Set ANDROID_HOME environment variable
- Install required SDK platforms

**3. "Java not found"**
- Install Java JDK 11+
- Set JAVA_HOME environment variable
- Add Java to PATH

**4. "Build failed"**
- Check all dependencies are included
- Verify minimum SDK version
- Check for syntax errors

## 📦 **APK Location**

After successful build, the APK will be located at:
```
android-app/app/build/outputs/apk/debug/app-debug.apk
```

## 🔄 **Real-time Updates**

The app will automatically sync with your Laravel system:
- Any changes in your main system
- Will appear in the mobile app
- Via the API endpoints we created

## 📞 **Support**

If you encounter issues:
1. Check the error logs in Android Studio
2. Verify all prerequisites are installed
3. Ensure your Laravel server is running
4. Test API endpoints manually

---

**Ready to build!** 🚀 