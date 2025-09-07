@echo off
echo ========================================
echo    MINI COMMERCE - E-COMMERCE APP
echo ========================================
echo.

echo [CHECKING PREREQUISITES...]
echo.

REM Check if Node.js is installed
echo Checking Node.js...
node --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ❌ Node.js is not installed!
    echo Please download and install Node.js from: https://nodejs.org/
    echo After installation, restart this script.
    pause
    exit /b 1
) else (
    echo ✅ Node.js is installed
)

REM Check if npm is available
echo Checking npm...
npm --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ❌ npm is not available!
    echo Please reinstall Node.js to get npm.
    pause
    exit /b 1
) else (
    echo ✅ npm is available
)

REM Check if PHP is available
echo Checking PHP...
C:\xampp\php\php.exe --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ❌ PHP/XAMPP is not found at C:\xampp\php\php.exe
    echo Please install XAMPP from: https://www.apachefriends.org/
    echo Or update the PHP path in this script.
    pause
    exit /b 1
) else (
    echo ✅ PHP is available
)

echo.
echo [1/4] Installing Frontend Dependencies...
cd frontend-nextjs
if not exist node_modules (
    echo Installing npm dependencies... This may take a few minutes.
    npm install
    if %errorlevel% neq 0 (
        echo ❌ Failed to install frontend dependencies!
        echo Please check your internet connection and try again.
        pause
        exit /b 1
    )
    echo ✅ Frontend dependencies installed successfully
) else (
    echo ✅ Frontend dependencies already installed
)
cd ..

echo.
echo [2/4] Starting PHP Backend...
start "PHP Backend" cmd /k "cd backend-php && C:\xampp\php\php.exe -S localhost:8000 -t public"

echo Waiting for backend to start...
timeout /t 3 /nobreak > nul

echo.
echo [3/4] Starting Next.js Frontend...
start "Next.js Frontend" cmd /k "cd frontend-nextjs && npm run dev"

echo.
echo [4/4] Opening browser and setup tools...
timeout /t 5 /nobreak > nul
start http://localhost:3000

REM Ask if user wants to open XAMPP and phpMyAdmin
echo.
echo Do you want to open XAMPP Control Panel and phpMyAdmin for database setup? (Y/N)
set /p choice="Enter your choice: "
if /i "%choice%"=="Y" (
    echo Opening XAMPP Control Panel...
    start "" "C:\xampp\xampp-control.exe"
    timeout /t 2 /nobreak > nul
    echo Opening phpMyAdmin...
    start http://localhost/phpmyadmin
)

echo.
echo ========================================
echo    APPLICATION READY! 🚀
echo ========================================
echo.
echo ✅ Frontend: http://localhost:3000
echo ✅ Backend:  http://localhost:8000
echo.
echo 📋 IMPORTANT SETUP NOTES:
echo.
echo 🗄️  DATABASE SETUP REQUIRED:
echo    1. Open XAMPP Control Panel
echo    2. Start Apache and MySQL services
echo    3. Open phpMyAdmin (http://localhost/phpmyadmin)
echo    4. Create database: mini_commerce
echo    5. Import file: database\schema.sql
echo.
echo 🔐 LOGIN CREDENTIALS:
echo    • Admin: admin@example.com / password
echo    • Customer: afifj27@gmail.com / (your password)
echo.
echo 🎯 FEATURES:
echo    • 28+ Products with real data
echo    • 🛒 Cart Icon with Counter
echo    • 🌙 Dark Mode Toggle
echo    • 👁️ Password Visibility
echo    • 🔐 User Authentication
echo    • 🛍️ Shopping Cart
echo    • 👨‍💼 Admin Panel
echo    • 📱 Responsive Design
echo.
echo 🆘 TROUBLESHOOTING:
echo    • If frontend doesn't load: Check if Node.js is installed
echo    • If backend fails: Check if XAMPP is running
echo    • If database errors: Import schema.sql in phpMyAdmin
echo    • Port conflicts: Make sure ports 3000 and 8000 are free
echo.
echo ========================================
echo.
echo Press any key to close this window...
pause > nul
