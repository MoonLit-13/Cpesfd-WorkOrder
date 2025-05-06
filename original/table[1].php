<?php
$host = 'localhost';
$db   = 'my_project_db'; // Change this if your DB name is different
$user = 'root';
$pass = ''; // Default for XAMPP
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    // Create work_orders table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS work_orders (
            id INT AUTO_INCREMENT PRIMARY KEY,
            company_name VARCHAR(100) NOT NULL,
            street VARCHAR(255) NOT NULL,
            city VARCHAR(100) NOT NULL,
            zip VARCHAR(20) NOT NULL,
            phone VARCHAR(20),
            fax VARCHAR(20),
            email VARCHAR(100),
            job_description TEXT,
            bill_name VARCHAR(100),
            bill_company VARCHAR(100),
            bill_address VARCHAR(255),
            bill_city VARCHAR(100),
            bill_phone VARCHAR(20),
            ship_name VARCHAR(100),
            ship_company VARCHAR(100),
            ship_address VARCHAR(255),
            ship_city VARCHAR(100),
            ship_phone VARCHAR(20),
            completed_date DATE,
            signature VARCHAR(255),
            date DATE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );
    ");

    // Create work_order_items table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS work_order_items (
            id INT AUTO_INCREMENT PRIMARY KEY,
            work_order_id INT NOT NULL,
            qty INT NOT NULL,
            description VARCHAR(255) NOT NULL,
            taxed BOOLEAN DEFAULT 0,
            unit_price DECIMAL(10, 2) NOT NULL,
            line_total DECIMAL(10, 2) NOT NULL,
            FOREIGN KEY (work_order_id) REFERENCES work_orders(id) ON DELETE CASCADE
        );
    ");

    // Create additional_charges table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS additional_charges (
            id INT AUTO_INCREMENT PRIMARY KEY,
            work_order_id INT NOT NULL,
            shipping_handling DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
            other_charges DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
            subtotal DECIMAL(10, 2) NOT NULL,
            taxable DECIMAL(10, 2) NOT NULL,
            tax DECIMAL(10, 2) NOT NULL,
            total DECIMAL(10, 2) NOT NULL,
            FOREIGN KEY (work_order_id) REFERENCES work_orders(id) ON DELETE CASCADE
        );
    ");

    echo "Tables created successfully!";
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
?>