-- ================================
-- Beads & Beyond Database
-- ================================

USE beads_and_beyond;

-- ================================
-- USERS
-- ================================
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ================================
-- CATEGORIES
-- ================================
CREATE TABLE categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL
);

INSERT INTO categories (name) VALUES
('Bracelets'),
('Waist Beads'),
('Anklets'),
('Beaded Bags');

-- ================================
-- PRODUCTS
-- ================================
CREATE TABLE products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    base_price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    is_customizable BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(category_id)
);

-- ================================
-- CUSTOMIZATION OPTIONS
-- ================================
CREATE TABLE customization_options (
    option_id INT AUTO_INCREMENT PRIMARY KEY,
    option_name VARCHAR(50) NOT NULL
);

INSERT INTO customization_options (option_name) VALUES
('Color'),
('Size'),
('Material'),
('Charm');

-- ================================
-- CUSTOMIZATION VALUES
-- ================================
CREATE TABLE customization_values (
    value_id INT AUTO_INCREMENT PRIMARY KEY,
    option_id INT NOT NULL,
    value_name VARCHAR(50) NOT NULL,
    price_modifier DECIMAL(10,2) DEFAULT 0.00,
    FOREIGN KEY (option_id) REFERENCES customization_options(option_id)
);

-- ================================
-- CART
-- ================================
CREATE TABLE cart (
    cart_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

-- ================================
-- CART ITEMS
-- ================================
CREATE TABLE cart_items (
    cart_item_id INT AUTO_INCREMENT PRIMARY KEY,
    cart_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT DEFAULT 1,
    custom_details TEXT,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (cart_id) REFERENCES cart(cart_id),
    FOREIGN KEY (product_id) REFERENCES products(product_id)
);

-- ================================
-- ORDERS
-- ================================
CREATE TABLE orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    order_status ENUM('Pending','Paid','Shipped','Completed','Cancelled') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

-- ================================
-- ORDER ITEMS
-- ================================
CREATE TABLE order_items (
    order_item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    custom_details TEXT,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(order_id),
    FOREIGN KEY (product_id) REFERENCES products(product_id)
);

-- ================================
-- PAYMENTS (PAYSTACK)
-- ================================
CREATE TABLE payments (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    reference VARCHAR(100) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_status ENUM('Pending','Successful','Failed') DEFAULT 'Pending',
    payment_method VARCHAR(50) DEFAULT 'Paystack',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(order_id)
);

ALTER TABLE products
ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE customization_values
ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE orders
ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- ================================
-- 2️⃣ Add stock_quantity to products
-- ================================
ALTER TABLE products
ADD COLUMN stock_quantity INT DEFAULT 0;

-- ================================
-- 3️⃣ Add is_admin to users
-- ================================
ALTER TABLE users
ADD COLUMN is_admin BOOLEAN DEFAULT FALSE;

-- ================================
-- 4️⃣ Add indexes for faster queries
-- ================================
CREATE INDEX idx_cart_user ON cart(user_id);
CREATE INDEX idx_order_user ON orders(user_id);
CREATE INDEX idx_cart_items_product ON cart_items(product_id);
CREATE INDEX idx_order_items_product ON order_items(product_id);

-- ================================
-- 5️⃣ Update foreign keys with cascading deletes
-- ================================
-- Note: Replace the FK names with actual ones if different
ALTER TABLE cart_items
DROP FOREIGN KEY cart_items_ibfk_1,
ADD CONSTRAINT fk_cart_items_cart
FOREIGN KEY (cart_id) REFERENCES cart(cart_id) ON DELETE CASCADE;

ALTER TABLE cart_items
DROP FOREIGN KEY cart_items_ibfk_2,
ADD CONSTRAINT fk_cart_items_product
FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE;

ALTER TABLE order_items
DROP FOREIGN KEY order_items_ibfk_1,
ADD CONSTRAINT fk_order_items_order
FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE;

ALTER TABLE order_items
DROP FOREIGN KEY order_items_ibfk_2,
ADD CONSTRAINT fk_order_items_product
FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE;

-- ================================
-- 6️⃣ Add validation checks
-- ================================
ALTER TABLE customization_values
MODIFY price_modifier DECIMAL(10,2) DEFAULT 0.00 CHECK (price_modifier >= 0);

ALTER TABLE cart_items
MODIFY quantity INT DEFAULT 1 CHECK (quantity >= 1);

ALTER TABLE order_items
MODIFY quantity INT NOT NULL CHECK (quantity >= 1);

INSERT INTO users (user_id, full_name, email, password_hash, phone, created_at, is_admin) VALUES 
(
    2,
    'Deborah AGOSSOU',
    'deborah.maxime@ashesi.edu.gh',
    '$2y$10$zB35hTGFzlssf7mqUsJ9KOmRui.Iirp2zEhcw5iQw79tpLX.6mqjC', -- hashed password
    '0503840174',
    '2025-12-14 02:16:30',
    1
);

ALTER TABLE products
DROP FOREIGN KEY IF EXISTS products_ibfk_1;

ALTER TABLE products
ADD CONSTRAINT fk_products_category
FOREIGN KEY (category_id)
REFERENCES categories(category_id)
ON DELETE RESTRICT;


-- Cart → Cart Items
ALTER TABLE cart_items
DROP FOREIGN KEY IF EXISTS fk_cart_items_cart;

ALTER TABLE cart_items
ADD CONSTRAINT fk_cart_items_cart
FOREIGN KEY (cart_id) REFERENCES cart(cart_id)
ON DELETE CASCADE;

-- Products → Cart Items
ALTER TABLE cart_items
DROP FOREIGN KEY IF EXISTS fk_cart_items_product;

ALTER TABLE cart_items
ADD CONSTRAINT fk_cart_items_product
FOREIGN KEY (product_id) REFERENCES products(product_id)
ON DELETE CASCADE;


