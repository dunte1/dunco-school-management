# 📱 Install Your Android App

## 🚀 **Option 1: Android Studio (Easiest)**

### **Step 1: Download Android Studio**
- Go to: https://developer.android.com/studio
- Download and install (free)

### **Step 2: Open Your Project**
1. Open Android Studio
2. Click "Open an existing Android Studio project"
3. Navigate to: `C:\Users\dunth\dunco school management system\duncoschool\android-app`
4. Click "OK"

### **Step 3: Build APK**
1. Wait for Gradle sync (5-10 minutes first time)
2. Click **Build** → **Build Bundle(s) / APK(s)** → **Build APK(s)**
3. Click "locate" when build finishes

### **Step 4: Install on Phone**
1. Copy APK to your phone
2. Enable "Install from Unknown Sources" in phone settings
3. Open APK file and install

---

## 🔧 **Option 2: GitHub Actions (Free Online Build)**

### **Step 1: Push to GitHub**
```bash
git init
git add .
git commit -m "Initial commit"
git remote add origin https://github.com/YOUR_USERNAME/dunco-school-app.git
git push -u origin main
```

### **Step 2: Get APK**
1. Go to your GitHub repository
2. Click "Actions" tab
3. Click on the latest workflow run
4. Download the APK from "Artifacts"

### **Step 3: Install on Phone**
1. Download APK to your phone
2. Enable "Install from Unknown Sources"
3. Open APK and install

---

## 📱 **Option 3: Bitrise (Free Online Build)**

### **Step 1: Connect Repository**
1. Go to: https://bitrise.io/
2. Sign up with GitHub
3. Connect your repository

### **Step 2: Configure Build**
1. Select "Android" template
2. Configure build settings
3. Start build

### **Step 3: Download APK**
1. Wait for build to complete
2. Download APK from dashboard
3. Install on your phone

---

## ⚙️ **Configure Your App**

### **1. Update API URL**
Edit `app/build.gradle`:
```gradle
debug {
    buildConfigField "String", "BASE_URL", "\"http://YOUR_COMPUTER_IP:8000/api/mobile/v1/\""
}
```

### **2. Start Laravel Server**
```bash
cd C:\Users\dunth\dunco school management system\duncoschool
php artisan serve --host=0.0.0.0 --port=8000
```

### **3. Test Credentials**
- **Email**: `student@test.com`
- **Password**: `password123`

---

## 🛠️ **Troubleshooting**

### **Common Issues**

**"Gradle sync failed"**
- Check internet connection
- Try File → Invalidate Caches / Restart

**"SDK not found"**
- Android Studio will prompt to download SDK
- Click "Download" when prompted

**"Build failed"**
- Check error log in Android Studio
- Make sure all files are present

**"APK won't install"**
- Enable "Install from Unknown Sources"
- Check if APK is corrupted
- Try downloading again

---

## 📱 **What You'll Get**

Once installed, your app will have:

✅ **Beautiful Login Screen** - Material Design 3
✅ **Student Dashboard** - View grades, attendance, fees
✅ **Parent Dashboard** - Monitor child's progress  
✅ **Teacher Dashboard** - Manage grades and attendance
✅ **Admin Dashboard** - System analytics
✅ **M-Pesa Integration** - Direct mobile payments
✅ **Real-time Updates** - Syncs with your Laravel system
✅ **Offline Support** - Works without internet
✅ **Push Notifications** - Instant alerts
✅ **Biometric Login** - Fingerprint/Face recognition

---

## 🔄 **Real-time Sync**

The app automatically reflects changes from your main system:
- New grades appear instantly
- Fee payments update in real-time
- Attendance records sync automatically
- Notifications push immediately

---

## 📞 **Need Help?**

If you encounter issues:
1. Check the error logs
2. Verify all prerequisites are installed
3. Ensure your Laravel server is running
4. Test API endpoints manually

**Your comprehensive school management app is ready to install!** 🚀 