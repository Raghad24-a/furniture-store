-- =============================================
-- StyleX Furniture Store - Database Setup
-- IS234: Multi-tier Application Development
-- =============================================

CREATE DATABASE IF NOT EXISTS furniture_store;
USE furniture_store;

-- 1. جدول Admin
CREATE TABLE admin (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50)  NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- 2. جدول Users
CREATE TABLE users (
    id        INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    username  VARCHAR(50)  NOT NULL UNIQUE,
    email     VARCHAR(100) NOT NULL UNIQUE,
    password  VARCHAR(255) NOT NULL,
    address   TEXT,
    phone     VARCHAR(20),
    isAdmin   TINYINT(1) DEFAULT 0
);

-- 3. جدول Products
CREATE TABLE products (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(255) NOT NULL,
    description TEXT,
    price       DECIMAL(10,2) NOT NULL,
    image       VARCHAR(255)  NOT NULL,
    category    VARCHAR(100)
);

-- 4. جدول Orders
CREATE TABLE orders (
    order_id    INT AUTO_INCREMENT PRIMARY KEY,
    user_id     INT,
    product_id  INT,
    quantity    INT,
    total_price DECIMAL(10,2),
    order_date  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id)    REFERENCES users(id)    ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- =============================================
-- Sample Data
-- =============================================

-- Admins (plain text passwords for admin table)
INSERT INTO admin (username, password) VALUES
('Kinda',       'pnu2024'),
('Raghad',      'pnu2024'),
('Admin_Store', 'admin123');

-- Users (passwords hashed with PHP password_hash)
-- Note: Insert via PHP register page for proper hashing
-- These are example plain entries (login via register.php)
INSERT INTO users (full_name, username, email, password, address, phone, isAdmin) VALUES
('Admin User',  'admin',      'admin@furniture.com',  '$2y$10$examplehashedpassword', 'Riyadh', '0555555551', 1),
('Sara Ahmed',  'sara_99',    'sara@gmail.com',       '$2y$10$examplehashedpassword', 'Jeddah', '0544444442', 0),
('Layan Ali',   'layan_pnu',  'layan@pnu.edu.sa',     '$2y$10$examplehashedpassword', 'Dammam', '0533333333', 0);

-- Products (Furniture)
INSERT INTO products (name, description, price, image, category) VALUES
('Modern Sofa',
 'Comfortable 3-seater gray sofa with premium fabric upholstery. Perfect for living rooms.',
 850.00,
 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=600',
 'Living Room'),

('Dining Table',
 'Large oak wood dining table seats up to 8 people. Elegant and durable.',
 1200.00,
 'https://images.unsplash.com/photo-1449247709967-d4461a6a6103?w=600',
 'Dining Room'),

('Ergonomic Office Chair',
 'Adjustable ergonomic black chair with lumbar support. Ideal for long working hours.',
 350.00,
 'https://images.unsplash.com/photo-1580480055273-228ff5388ef8?w=600',
 'Office'),

('Coffee Table',
 'Small modern glass-top coffee table with metal legs. Minimalist design.',
 250.00,
 'https://images.unsplash.com/photo-1567538096621-38d2284b23ff?w=600',
 'Living Room'),

('King Bed Frame',
 'Solid wood king-size bed frame with headboard. Rustic and elegant finish.',
 1500.00,
 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=600',
 'Bedroom'),

('Bookshelf',
 '5-tier wooden bookshelf with adjustable shelves. Perfect for home office or library.',
 420.00,
 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=600',
 'Office');

-- Sample orders
INSERT INTO orders (user_id, product_id, quantity, total_price) VALUES
(2, 1, 1, 850.00),
(2, 2, 1, 1200.00),
(3, 3, 2, 700.00),
(2, 4, 1, 250.00);
