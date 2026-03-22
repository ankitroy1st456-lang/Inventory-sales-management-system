<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>creating db</title>
</head>
<body>
    <?php
// Database connection settings
$host = "localhost";
$user = "root";        // change if needed
$pass = "";            // change if needed
$dbname = "inventory_db"; // database name

try {
    // Connect to MySQL (without selecting DB yet)
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create database if not exists
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` 
                CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
    echo "Database created successfully.<br>";

    // Switch to the new database
    $pdo->exec("USE `$dbname`");

    // Schema definition
    $schema = "
    CREATE TABLE IF NOT EXISTS categories (
      id INT(11) NOT NULL AUTO_INCREMENT,
      name VARCHAR(100) DEFAULT NULL,
      PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

    CREATE TABLE IF NOT EXISTS customers (
      id INT(11) NOT NULL AUTO_INCREMENT,
      name VARCHAR(100) DEFAULT NULL,
      phone VARCHAR(20) DEFAULT NULL,
      email VARCHAR(100) DEFAULT NULL,
      address TEXT DEFAULT NULL,
      PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

    CREATE TABLE IF NOT EXISTS pending_users (
      id INT(11) NOT NULL AUTO_INCREMENT,
      username VARCHAR(100) DEFAULT NULL,
      password VARCHAR(255) DEFAULT NULL,
      requested_at DATETIME DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

    CREATE TABLE IF NOT EXISTS products (
      id INT(11) NOT NULL AUTO_INCREMENT,
      name VARCHAR(100) DEFAULT NULL,
      category_id INT(11) DEFAULT NULL,
      cost_price DECIMAL(10,2) DEFAULT NULL,
      sell_price DECIMAL(10,2) DEFAULT NULL,
      stock INT(11) DEFAULT 0,
      PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

    CREATE TABLE IF NOT EXISTS purchases (
      id INT(11) NOT NULL AUTO_INCREMENT,
      supplier_id INT(11) DEFAULT NULL,
      date DATETIME DEFAULT CURRENT_TIMESTAMP,
      total DECIMAL(10,2) DEFAULT NULL,
      PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

    CREATE TABLE IF NOT EXISTS purchase_items (
      id INT(11) NOT NULL AUTO_INCREMENT,
      purchase_id INT(11) DEFAULT NULL,
      product_id INT(11) DEFAULT NULL,
      qty INT(11) DEFAULT NULL,
      cost_price DECIMAL(10,2) DEFAULT NULL,
      PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

    CREATE TABLE IF NOT EXISTS sales (
      id INT(11) NOT NULL AUTO_INCREMENT,
      customer_id INT(11) DEFAULT NULL,
      date DATETIME DEFAULT CURRENT_TIMESTAMP,
      total DECIMAL(10,2) DEFAULT NULL,
      PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

    CREATE TABLE IF NOT EXISTS sale_items (
      id INT(11) NOT NULL AUTO_INCREMENT,
      sale_id INT(11) DEFAULT NULL,
      product_id INT(11) DEFAULT NULL,
      qty INT(11) DEFAULT NULL,
      sell_price DECIMAL(10,2) DEFAULT NULL,
      PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

    CREATE TABLE IF NOT EXISTS suppliers (
      id INT(11) NOT NULL AUTO_INCREMENT,
      name VARCHAR(100) DEFAULT NULL,
      contact VARCHAR(100) DEFAULT NULL,
      phone VARCHAR(20) DEFAULT NULL,
      email VARCHAR(100) DEFAULT NULL,
      address TEXT DEFAULT NULL,
      PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

    CREATE TABLE IF NOT EXISTS users (
      id INT(11) NOT NULL AUTO_INCREMENT,
      username VARCHAR(50) DEFAULT NULL,
      password VARCHAR(255) DEFAULT NULL,
      role ENUM('admin','staff') DEFAULT 'staff',
      PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ";

    // Execute schema
    $pdo->exec($schema);
    echo "All tables created successfully.<br>";

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

</body>
</html>