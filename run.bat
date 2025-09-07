
@echo off
echo ========================================
echo    MINI COMMERCE - RUN APPLICATION
echo ========================================
echo.

echo [CHECKING DEPENDENCIES...]
echo.

REM Check if frontend dependencies are installed
if not exist frontend-nextjs\node_modules (
    echo [ERROR] Frontend dependencies not installed!
    echo Please run install.bat first to install dependencies.
    pause
    exit /b 1
) else (
    echo [OK] Frontend dependencies found
)

REM Check if PHP is available
C:\xampp\php\php.exe --version >nul 2>&1
if errorlevel 1 (
    echo [ERROR] PHP/XAMPP is not found at C:\xampp\php\php.exe
    echo Please install XAMPP from: https://www.apachefriends.org/
    pause
    exit /b 1
) else (
    echo [OK] PHP is available
)

echo.
echo ========================================
echo    STARTING APPLICATION
echo ========================================
echo.

echo [1/3] Starting PHP Backend...
start "PHP Backend" cmd /k "cd backend-php && C:\xampp\php\php.exe -S localhost:8000 -t public"

echo Waiting for backend to start...
timeout /t 3 /nobreak > nul

echo.
echo [2/3] Starting Next.js Frontend...
start "Next.js Frontend" cmd /k "cd frontend-nextjs && npx next dev"

echo.
echo [3/3] Opening browser...
timeout /t 5 /nobreak > nul
start http://localhost:3000

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
