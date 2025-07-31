@echo off
echo ========================================
echo Fixing and Pushing Android App
echo ========================================
echo.

REM Navigate to android-app directory
cd "C:\Users\dunth\dunco school management system\duncoschool\android-app"

REM Remove existing git repository
rmdir /s /q .git

REM Initialize new Git repository
git init

REM Add all files
git add .

REM Commit the changes
git commit -m "Initial commit: Dunco School Management Android App"

REM Rename branch to main
git branch -M main

REM Add remote origin
git remote add origin https://github.com/dunte1/school-management-app.git

REM Push to GitHub
git push -u origin main

echo.
echo ========================================
echo ANDROID APP PUSHED SUCCESSFULLY!
echo ========================================
echo.
echo Your Android app is now at:
echo https://github.com/dunte1/school-management-app
echo.
echo Check the Actions tab for your APK build!
echo.
pause 