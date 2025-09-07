@echo off
echo ========================================
echo    MINI COMMERCE - E-COMMERCE APP
echo ========================================
echo.

echo [1/3] Starting PHP Backend...
start "PHP Backend" cmd /k "cd backend-php && C:\xampp\php\php.exe -S localhost:8000 -t public"

echo Waiting for backend to start...
timeout /t 3 /nobreak > nul

echo.
echo [2/3] Starting Next.js Frontend...
start "Next.js Frontend" cmd /k "cd frontend-nextjs && npm run dev"

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
echo FEATURES:
echo • 28+ Products with real data
echo • 🛒 Cart Icon with Counter
echo • 🌙 Dark Mode Toggle
echo • 👁️ Password Visibility
echo • 🔐 User Authentication
echo • 🛍️ Shopping Cart
echo • 👨‍💼 Admin Panel
echo • 📱 Responsive Design
echo.
echo LOGIN CREDENTIALS:
echo • Admin: admin@example.com / password
echo • Customer: afifj27@gmail.com / (your password)
echo ========================================
echo.
echo Press any key to close this window...
pause > nul
