-- Mini Commerce Database Schema
-- Create database
CREATE DATABASE IF NOT EXISTS mini_commerce;
USE mini_commerce;

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('customer', 'admin') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Products table
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    image_url VARCHAR(500),
    stock INT DEFAULT 0,
    category VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Cart items table
CREATE TABLE cart_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_product (user_id, product_id)
);

-- Orders table
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'processing', 'shipped', 'completed', 'cancelled') DEFAULT 'pending',
    shipping_address TEXT NOT NULL,
    shipping_city VARCHAR(100) NOT NULL,
    shipping_postal_code VARCHAR(20) NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Order items table
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Insert sample admin user
INSERT INTO users (name, email, password, role) VALUES 
('Admin User', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Insert sample products
INSERT INTO products (name, description, price, image_url, stock, category) VALUES 
('Laptop Gaming', 'High-performance gaming laptop with RTX graphics', 1299.99, 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=400', 10, 'Electronics'),
('Wireless Headphones', 'Premium noise-cancelling wireless headphones', 299.99, 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400', 25, 'Electronics'),
('Smartphone', 'Latest flagship smartphone with advanced camera', 899.99, 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=400', 15, 'Electronics'),
('Running Shoes', 'Comfortable running shoes for all terrains', 129.99, 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400', 30, 'Sports'),
('Coffee Maker', 'Automatic coffee maker with programmable timer', 199.99, 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=400', 8, 'Home'),
('Backpack', 'Durable travel backpack with laptop compartment', 79.99, 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=400', 20, 'Travel'),
('Watch', 'Elegant wristwatch with leather strap', 249.99, 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=400', 12, 'Accessories'),
('Desk Lamp', 'LED desk lamp with adjustable brightness', 49.99, 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400', 18, 'Home');

-- Create indexes for better performance
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_products_category ON products(category);
CREATE INDEX idx_products_name ON products(name);
CREATE INDEX idx_cart_items_user_id ON cart_items(user_id);
CREATE INDEX idx_orders_user_id ON orders(user_id);
CREATE INDEX idx_orders_status ON orders(status);
CREATE INDEX idx_order_items_order_id ON order_items(order_id);
