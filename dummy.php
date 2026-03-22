<?php
// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "inventory_db";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Dummy data inserts
    $queries = [

        // Categories
        "INSERT INTO categories (name) VALUES 
        ('Electronics'), ('Furniture'), ('Groceries')",

        // Customers
        "INSERT INTO customers (name, phone, email, address) VALUES
        ('John Doe','9876543210','john@example.com','123 Main St'),
        ('Jane Smith','9123456789','jane@example.com','456 Oak Ave')",

        // Pending Users
        "INSERT INTO pending_users (username, password) VALUES
        ('newuser','hashedpassword123')",

        // Products
        "INSERT INTO products (name, category_id, cost_price, sell_price, stock) VALUES
        ('Laptop',1,50000,60000,10),
        ('Dining Table',2,8000,9500,5),
        ('Rice Bag',3,1200,1500,50)",

        // Suppliers
        "INSERT INTO suppliers (name, contact, phone, email, address) VALUES
        ('ABC Suppliers','Mr. Kumar','9800000001','abc@suppliers.com','Kathmandu'),
        ('XYZ Traders','Ms. Sharma','9800000002','xyz@traders.com','Pokhara')",

        // Purchases
        "INSERT INTO purchases (supplier_id, total) VALUES
        (1,100000),(2,25000)",

        // Purchase Items
        "INSERT INTO purchase_items (purchase_id, product_id, qty, cost_price) VALUES
        (1,1,5,50000),
        (2,3,20,1200)",

        // Sales
        "INSERT INTO sales (customer_id, total) VALUES
        (1,60000),(2,1500)",

        // Sale Items
        "INSERT INTO sale_items (sale_id, product_id, qty, sell_price) VALUES
        (1,1,1,60000),
        (2,3,1,1500)",

        // Users
        "INSERT INTO users (username, password, role) VALUES
        ('admin', 'admin123', 'admin'),
        ('staff1', 'staff123', 'staff')"
    ];

    foreach ($queries as $sql) {
        $pdo->exec($sql);
    }

    echo "Dummy data inserted successfully.<br>";

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
