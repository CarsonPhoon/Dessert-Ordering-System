-- 1. Create Users Table
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'customer') DEFAULT 'customer',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 2. Create Menu Table
CREATE TABLE menu (
    menu_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    description TEXT,
    image_url VARCHAR(255)
);

-- 3. Create Orders Table
-- (Note: I usually recommend adding a user_id here to know WHO ordered, see tip below)
CREATE TABLE orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    total_price DECIMAL(10,2) NOT NULL,
    order_date DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 4. Create Order Items Table (Links Orders to Menu items)
CREATE TABLE order_items (
    item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    menu_id INT,
    quantity INT,
    FOREIGN KEY (order_id) REFERENCES orders(order_id),
    FOREIGN KEY (menu_id) REFERENCES menu(menu_id)
);

-- 5. Insert Admin User
-- The password hash below usually equals "password" in bcrypt
INSERT INTO users (username, password, role) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- 6. Insert Menu Items
-- FIXED: Changed the semicolon after roselycheesoy.jpg to a comma
INSERT INTO menu (name, price, description, image_url) VALUES 
('Signature Soy Beancurd', 13.00, 'The original, perfectly smooth and classic', 'images/signaturesoybeancurd.jpg'),
('Thai Tea Shaved Ice', 16.00, 'Shaved Ice & Soy Beancurd ith 3 toppings', 'images/thaimilk.jpg'),
('Plain Soy Beancurd', 8.00, 'A simple, soothing taste of soy', 'images/soybeancurd.jpg'),
('Peanut Taro Soy Beancurd', 16.00, 'Sweet, starchy taro meets creamy peanut', 'images/tarosoybeancurd.jpg'),
('Rose&Lychee Soy Beancurd', 16.00, 'Juicy lychee in fragrant rosy cream', 'images/roselycheesoy.jpg'),
('Dancing Cat Pudding', 6.50, 'Delicious and rich coconut flavour', 'images/catpudding.jpg'),
('Hot Mochi (2pcs)', 6.00, 'Sticky, sweet and wonderfully warm mouthfuls', 'images/hotmochi.jpg');