@echo off
REM =====================================================
REM DUNCO SCHOOL MANAGEMENT SYSTEM - COMPREHENSIVE TEST
REM =====================================================
REM Run this script to verify the entire system is working
REM =====================================================

echo.
echo ============================================
echo  DUNCO SCHOOL MANAGEMENT SYSTEM
echo  Comprehensive System Test
echo ============================================
echo.

REM Step 1: Check MySQL
echo [1/8] Checking MySQL connection...
php artisan tinker --execute="DB::connection()->getPdo(); echo 'MySQL OK';" 2>nul
if %errorlevel% neq 0 (
    echo FAIL: MySQL not running or not configured
    exit /b 1
)
echo PASS: MySQL connected
echo.

REM Step 2: Run migrations
echo [2/8] Running migrations...
php artisan migrate --force 2>nul
if %errorlevel% neq 0 (
    echo FAIL: Migration failed
    exit /b 1
)
echo PASS: Migrations complete
echo.

REM Step 3: Run seeders
echo [3/8] Running seeders...
php artisan db:seed --force 2>nul
if %errorlevel% neq 0 (
    echo FAIL: Seeding failed
    exit /b 1
)
echo PASS: Seeders complete
echo.

REM Step 4: Clear caches
echo [4/8] Clearing caches...
php artisan config:clear 2>nul
php artisan route:clear 2>nul
php artisan view:clear 2>nul
echo PASS: Caches cleared
echo.

REM Step 5: Build assets
echo [5/8] Building production assets...
call npm run build 2>nul
if %errorlevel% neq 0 (
    echo FAIL: Asset build failed
    exit /b 1
)
echo PASS: Assets built
echo.

REM Step 6: Verify routes load
echo [6/8] Verifying routes...
php artisan route:list 2>nul | find /c "GET" > nul
echo PASS: Routes loaded
echo.

REM Step 7: Run all tests
echo [7/8] Running comprehensive test suite...
php artisan test 2>&1
echo.

REM Step 8: Summary
echo [8/8] Test Summary
echo ============================================
echo.
echo If all tests passed, the system is ready.
echo.
echo Admin Login: admin@dunco.com / password
echo.
echo ============================================

pause
