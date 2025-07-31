@echo off
echo ========================================
echo Pushing Android App to GitHub
echo ========================================
echo.

REM Check if Git is installed
git --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ERROR: Git is not installed
    echo Please install Git from: https://git-scm.com/
    pause
    exit /b 1
)

echo Git found. Setting up repository...

REM Initialize Git repository
git init

REM Add all files
git add .

REM Commit the changes
git commit -m "Initial commit: Dunco School Management Android App"

echo.
echo ========================================
echo REPOSITORY READY!
echo ========================================
echo.
echo Now you need to:
echo.
echo 1. Create a GitHub repository:
echo    - Go to: https://github.com/new
echo    - Name it: dunco-school-app
echo    - Make it Public
echo    - Don't initialize with README
echo.
echo 2. Connect and push:
echo    git remote add origin https://github.com/YOUR_USERNAME/dunco-school-app.git
echo    git push -u origin main
echo.
echo 3. Get your APK:
echo    - Go to your GitHub repository
echo    - Click "Actions" tab
echo    - Download APK from "Artifacts"
echo.
echo Replace YOUR_USERNAME with your actual GitHub username!
echo.
pause 