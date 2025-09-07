# Mini Commerce - Install Dependencies (PowerShell Version)
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "   MINI COMMERCE - INSTALL DEPENDENCIES" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

Write-Host "[CHECKING PREREQUISITES...]" -ForegroundColor Yellow
Write-Host ""

# Check if Node.js is installed
Write-Host "Checking Node.js..." -ForegroundColor White
try {
    $nodeVersion = node --version 2>$null
    if ($LASTEXITCODE -eq 0) {
        Write-Host "[OK] Node.js is installed: $nodeVersion" -ForegroundColor Green
    } else {
        throw "Node.js not found"
    }
} catch {
    Write-Host "[ERROR] Node.js is not installed!" -ForegroundColor Red
    Write-Host "Please download and install Node.js from: https://nodejs.org/" -ForegroundColor Yellow
    Write-Host "After installation, restart this script." -ForegroundColor Yellow
    Read-Host "Press Enter to exit"
    exit 1
}

# Check if npm is available
Write-Host "Checking npm..." -ForegroundColor White
try {
    $npmVersion = npm --version 2>$null
    if ($LASTEXITCODE -eq 0) {
        Write-Host "[OK] npm is available: $npmVersion" -ForegroundColor Green
    } else {
        throw "npm not found"
    }
} catch {
    Write-Host "[ERROR] npm is not available!" -ForegroundColor Red
    Write-Host "Please reinstall Node.js to get npm." -ForegroundColor Yellow
    Read-Host "Press Enter to exit"
    exit 1
}

# Check if PHP is available
Write-Host "Checking PHP..." -ForegroundColor White
try {
    $phpVersion = & "C:\xampp\php\php.exe" --version 2>$null
    if ($LASTEXITCODE -eq 0) {
        Write-Host "[OK] PHP is available" -ForegroundColor Green
    } else {
        throw "PHP not found"
    }
} catch {
    Write-Host "[ERROR] PHP/XAMPP is not found at C:\xampp\php\php.exe" -ForegroundColor Red
    Write-Host "Please install XAMPP from: https://www.apachefriends.org/" -ForegroundColor Yellow
    Write-Host "Or update the PHP path in this script." -ForegroundColor Yellow
    Read-Host "Press Enter to exit"
    exit 1
}

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "   INSTALLING DEPENDENCIES" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Install Frontend Dependencies
Write-Host "[1/2] Installing Frontend Dependencies..." -ForegroundColor Yellow
Set-Location "frontend-nextjs"

# Clean install
if (Test-Path "node_modules") {
    Write-Host "Removing existing node_modules for clean install..." -ForegroundColor Yellow
    Remove-Item -Recurse -Force "node_modules" -ErrorAction SilentlyContinue
}
if (Test-Path "package-lock.json") {
    Write-Host "Removing package-lock.json for clean install..." -ForegroundColor Yellow
    Remove-Item "package-lock.json" -ErrorAction SilentlyContinue
}

Write-Host "Installing npm dependencies... This may take a few minutes." -ForegroundColor White
Write-Host "Please wait, do not close this window..." -ForegroundColor White

try {
    npm install
    if ($LASTEXITCODE -ne 0) {
        throw "npm install failed"
    }
    Write-Host "[OK] Frontend dependencies installed successfully" -ForegroundColor Green
} catch {
    Write-Host "[ERROR] Failed to install frontend dependencies!" -ForegroundColor Red
    Write-Host "Please check your internet connection and try again." -ForegroundColor Yellow
    Read-Host "Press Enter to exit"
    exit 1
}

# Test Next.js installation
Write-Host "Testing Next.js installation..." -ForegroundColor White
try {
    $nextVersion = npx next --version 2>$null
    if ($LASTEXITCODE -eq 0) {
        Write-Host "[OK] Next.js CLI is accessible" -ForegroundColor Green
    } else {
        throw "Next.js not accessible"
    }
} catch {
    Write-Host "[WARNING] Next.js CLI not accessible via npx" -ForegroundColor Yellow
    Write-Host "Trying alternative installation method..." -ForegroundColor Yellow
    try {
        npm install next@latest --save
        if ($LASTEXITCODE -ne 0) {
            throw "Next.js install failed"
        }
        Write-Host "[OK] Next.js installed successfully" -ForegroundColor Green
    } catch {
        Write-Host "[ERROR] Failed to install Next.js!" -ForegroundColor Red
        Write-Host "Please check your internet connection and try again." -ForegroundColor Yellow
        Read-Host "Press Enter to exit"
        exit 1
    }
}

Set-Location ".."

Write-Host ""
Write-Host "[2/2] Checking Backend Files..." -ForegroundColor Yellow
if (Test-Path "backend-php") {
    Write-Host "[OK] Backend PHP files found" -ForegroundColor Green
} else {
    Write-Host "[ERROR] Backend PHP files not found!" -ForegroundColor Red
    Read-Host "Press Enter to exit"
    exit 1
}

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "    INSTALLATION COMPLETE! ✅" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "SETUP INSTRUCTIONS FOR NEW USERS:" -ForegroundColor Yellow
Write-Host ""
Write-Host "DATABASE SETUP (REQUIRED):" -ForegroundColor Cyan
Write-Host "   1. Download and install XAMPP from: https://www.apachefriends.org/" -ForegroundColor White
Write-Host "   2. Start XAMPP Control Panel" -ForegroundColor White
Write-Host "   3. Start Apache and MySQL services" -ForegroundColor White
Write-Host "   4. Open phpMyAdmin: http://localhost/phpmyadmin" -ForegroundColor White
Write-Host "   5. Create new database: mini_commerce" -ForegroundColor White
Write-Host "   6. Import file: database\schema.sql" -ForegroundColor White
Write-Host ""
Write-Host "RUNNING THE APPLICATION:" -ForegroundColor Cyan
Write-Host "   1. Make sure XAMPP is running (Apache + MySQL)" -ForegroundColor White
Write-Host "   2. Run: run.bat" -ForegroundColor White
Write-Host "   3. Open browser: http://localhost:3000" -ForegroundColor White
Write-Host ""
Write-Host "LOGIN CREDENTIALS:" -ForegroundColor Cyan
Write-Host "   - Admin: admin@example.com / password" -ForegroundColor White
Write-Host "   - Customer: afifj27@gmail.com / (your password)" -ForegroundColor White
Write-Host ""
Write-Host "FEATURES:" -ForegroundColor Cyan
Write-Host "   - 28+ Products with real data" -ForegroundColor White
Write-Host "   - Shopping Cart with Counter" -ForegroundColor White
Write-Host "   - Dark Mode Toggle" -ForegroundColor White
Write-Host "   - Password Visibility" -ForegroundColor White
Write-Host "   - User Authentication" -ForegroundColor White
Write-Host "   - Admin Panel" -ForegroundColor White
Write-Host "   - Responsive Design" -ForegroundColor White
Write-Host ""
Write-Host "TROUBLESHOOTING:" -ForegroundColor Cyan
Write-Host "   - If frontend doesn't load: Check if Node.js is installed" -ForegroundColor White
Write-Host "   - If backend fails: Check if XAMPP is running" -ForegroundColor White
Write-Host "   - If database errors: Import schema.sql in phpMyAdmin" -ForegroundColor White
Write-Host "   - Port conflicts: Make sure ports 3000 and 8000 are free" -ForegroundColor White
Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""
Read-Host "Press Enter to close this window"


