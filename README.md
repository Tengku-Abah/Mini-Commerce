# Mini Commerce

Aplikasi e-commerce full-stack yang dibangun dengan Next.js 14.2.5 frontend dan PHP backend API.

## 🚀 Panduan Instalasi Cepat

### Yang Diperlukan
- **Node.js** (v18 atau lebih tinggi) - [Download di sini](https://nodejs.org/)
- **XAMPP** (untuk PHP dan MySQL) - [Download di sini](https://www.apachefriends.org/)
- **Git** (untuk cloning) - [Download di sini](https://git-scm.com/)

### 📥 Langkah 1: Clone dari GitHub

1. **Buka Command Prompt atau PowerShell**
2. **Masuk ke direktori yang diinginkan** (contoh: `C:\Projects\`)
3. **Clone repository**:
   ```bash
   git clone https://github.com/yourusername/mini-commerce.git
   ```
4. **Masuk ke direktori project**:
   ```bash
   cd mini-commerce
   ```

### 🛠️ Langkah 2: Install Dependencies

Jalankan script instalasi otomatis:
```bash
.\install.ps1
```

Script ini akan otomatis:
- ✅ Cek apakah Node.js dan npm sudah terinstall
- ✅ Cek apakah PHP/XAMPP tersedia
- ✅ Install semua dependencies frontend
- ✅ Test instalasi Next.js

### 🗄️ Langkah 3: Setup Database

1. **Buka XAMPP Control Panel**
2. **Start Apache dan MySQL services**
3. **Buka phpMyAdmin**: http://localhost/phpmyadmin
4. **Buat database**: `mini_commerce`
5. **Import schema**: Upload dan import file `database/schema.sql`

### 🏃‍♂️ Langkah 4: Jalankan Aplikasi

```bash
.\run.bat
```

Ini akan:
- ✅ Start PHP backend di port 8000
- ✅ Start Next.js frontend di port 3000
- ✅ Buka browser otomatis

### 🌐 Langkah 5: Akses Aplikasi

- **Frontend**: http://localhost:3000
- **Backend API**: http://localhost:8000
- **phpMyAdmin**: http://localhost/phpmyadmin

### 🔐 Kredensial Login
```
Admin: admin@example.com / password
Customer: afifj27@gmail.com / (password Anda)
```

---

## 🐛 Troubleshooting

### Jika install.ps1 tidak berfungsi:
```bash
# Instalasi manual
cd frontend-nextjs
npm install
cd ..
```

### Jika run.bat tidak berfungsi:
```bash
# Start backend manual
cd backend-php
php -S localhost:8000 -t public

# Start frontend manual (di terminal baru)
cd frontend-nextjs
npm run dev
```

### Masalah Umum:
- **Error PowerShell**: Jalankan `Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser`
- **Node.js tidak ditemukan**: Download dan install dari [nodejs.org](https://nodejs.org/)
- **XAMPP tidak ditemukan**: Download dan install dari [apachefriends.org](https://www.apachefriends.org/)
- **Port konflik**: Pastikan port 3000 dan 8000 tidak digunakan aplikasi lain

## 📞 Bantuan
Wa ke Tengku Muhamad Afif Alghomidy
instagram tengkuafif.04

Jika ada pertanyaan, silakan buat issue di repository.
