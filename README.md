# Mini Commerce

A full-stack e-commerce application built with Next.js 14.2.5 frontend and PHP backend API.

## 🏗️ Project Structure

```
/project-root
│
├── /frontend-nextjs               # Next.js 14.2.5
│   ├── app/                       # App Router (pages & routes)
│   │   ├── page.tsx               # Halaman katalog produk
│   │   ├── login/                 # Halaman login
│   │   ├── register/              # Halaman register
│   │   ├── cart/                  # Halaman keranjang
│   │   ├── checkout/              # Halaman checkout
│   │   └── admin/                 # Halaman admin (CRUD produk, pesanan)
│   │
│   ├── lib/                       # Helper fetch API
│   │   └── api.ts
│   │
│   ├── components/                # Komponen UI (Navbar, Card, Form, dsb)
│   ├── public/                    # Asset (logo, gambar produk, dsb)
│   ├── styles/                    # Tailwind/global CSS
│   ├── .env.local                 # PHP_API_BASE, secret key, dsb
│   ├── package.json
│   └── next.config.js
│
├── /backend-php                   # API berbasis PHP (di Laragon/XAMPP)
│   ├── public/
│   │   └── index.php              # Front controller (router API)
│   │
│   ├── config/
│   │   └── db.php                 # Koneksi database
│   │
│   ├── app/
│   │   ├── controllers/           # Controller: Auth, Product, Order, Admin
│   │   ├── models/                # Model: User, Product, Order
│   │   ├── helpers/               # JWT, response, validator
│   │   └── middleware/            # (opsional) auth middleware
│   │
│   ├── vendor/                    # Jika pakai Composer (opsional)
│   └── composer.json              # (opsional)
│
├── /database
│   └── schema.sql                 # Skema tabel mini-commerce
│
└── README.md                      # Dokumentasi setup
```

## 🚀 Features

### Frontend (Next.js)
- **Product Catalog**: Browse and search products
- **User Authentication**: Login/Register with JWT
- **Shopping Cart**: Add/remove items, update quantities
- **Checkout Process**: Complete order placement
- **Admin Dashboard**: Manage products and orders
- **Responsive Design**: Mobile-friendly UI with Tailwind CSS

### Backend (PHP)
- **RESTful API**: Clean API endpoints
- **JWT Authentication**: Secure user authentication
- **Database Integration**: MySQL with PDO
- **Admin Controls**: Product and order management
- **Cart Management**: Session-based cart functionality

## 🛠️ Setup Instructions

### Prerequisites
- **Node.js** (v18 or higher)
- **PHP** (v7.4 or higher)
- **MySQL** (v5.7 or higher)
- **Laragon/XAMPP** (for local PHP development)

### 1. Database Setup

1. Start your MySQL server (via Laragon/XAMPP)
2. Import the database schema:
   ```sql
   mysql -u root -p < database/schema.sql
   ```
3. Update database credentials in `backend-php/config/db.php` if needed

### 2. Backend Setup (PHP)

1. Navigate to the backend directory:
   ```bash
   cd backend-php
   ```

2. Start PHP development server:
   ```bash
   php -S localhost:8000 -t public
   ```

3. The API will be available at `http://localhost:8000`

### 3. Frontend Setup (Next.js)

1. Navigate to the frontend directory:
   ```bash
   cd frontend-nextjs
   ```

2. Install dependencies:
   ```bash
   npm install
   ```

3. Create environment file:
   ```bash
   cp .env.local.example .env.local
   ```

4. Update `.env.local` with your configuration:
   ```env
   PHP_API_BASE=http://localhost:8000
   NEXTAUTH_SECRET=your-secret-key-here
   JWT_SECRET=your-jwt-secret-here
   ```

5. Start the development server:
   ```bash
   npm run dev
   ```

6. Open [http://localhost:3000](http://localhost:3000) in your browser

## 📡 API Endpoints

### Authentication
- `POST /auth/login` - User login
- `POST /auth/register` - User registration
- `POST /auth/logout` - User logout
- `GET /auth/me` - Get current user

### Products
- `GET /products` - Get all products
- `GET /products/{id}` - Get product by ID
- `POST /products` - Create product (Admin only)
- `PUT /products/{id}` - Update product (Admin only)
- `DELETE /products/{id}` - Delete product (Admin only)

### Cart
- `GET /cart` - Get user cart
- `POST /cart/add` - Add item to cart
- `PUT /cart/update` - Update cart item quantity
- `DELETE /cart/remove/{id}` - Remove item from cart
- `DELETE /cart/clear` - Clear cart

### Orders
- `GET /orders` - Get user orders
- `GET /orders/{id}` - Get order by ID
- `POST /orders` - Create new order
- `PUT /orders/{id}/status` - Update order status

### Admin
- `GET /admin/orders` - Get all orders (Admin only)
- `GET /admin/users` - Get all users (Admin only)
- `PUT /admin/orders/{id}/status` - Update order status (Admin only)

## 🔐 Default Admin Account

```
Email: admin@example.com
Password: password
```

## 🎨 Technologies Used

### Frontend
- **Next.js 14.2.5** - React framework with App Router
- **TypeScript** - Type safety
- **Tailwind CSS** - Utility-first CSS framework
- **Axios** - HTTP client
- **React Hook Form** - Form handling
- **React Hot Toast** - Notifications
- **js-cookie** - Cookie management

### Backend
- **PHP** - Server-side language
- **MySQL** - Database
- **PDO** - Database abstraction layer
- **JWT** - Authentication tokens
- **RESTful API** - API design pattern

## 📱 Pages & Features

### Public Pages
- **Home** (`/`) - Product catalog with search
- **Login** (`/login`) - User authentication
- **Register** (`/register`) - User registration

### Protected Pages
- **Cart** (`/cart`) - Shopping cart management
- **Checkout** (`/checkout`) - Order placement
- **Admin** (`/admin`) - Admin dashboard (Admin only)

## 🔧 Development

### Running in Development Mode

1. **Backend**: `php -S localhost:8000 -t public` (in backend-php directory)
2. **Frontend**: `npm run dev` (in frontend-nextjs directory)

### Building for Production

1. **Frontend**:
   ```bash
   cd frontend-nextjs
   npm run build
   npm start
   ```

2. **Backend**: Deploy to your PHP hosting service

## 🐛 Troubleshooting

### Common Issues

1. **CORS Errors**: Ensure the backend is running on the correct port
2. **Database Connection**: Check MySQL credentials in `config/db.php`
3. **JWT Errors**: Verify JWT secret matches between frontend and backend
4. **Port Conflicts**: Make sure ports 3000 and 8000 are available

### Environment Variables

Make sure all environment variables are properly set:
- `PHP_API_BASE` - Backend API URL
- `NEXTAUTH_SECRET` - Next.js secret key
- `JWT_SECRET` - JWT signing secret

## 📄 License

This project is open source and available under the [MIT License](LICENSE).

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📞 Support

If you have any questions or need help with setup, please open an issue in the repository.
