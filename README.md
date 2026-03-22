# 📦 Inventory_System (PHP + MySQL)

A simple inventory management system built with PHP and MySQL.  
This project demonstrates a clean schema setup, modular structure, and a basic login workflow.

## Setup Instructions

1. **Clone the repository**

   ```bash
   git clone https://github.com/ankitroy1st456-lang/Inventory_system.git
   cd inventory_system

   ```

2. **Start your local server**

   ◦ Ensure Apache/Nginx and MySQL are running.
   ◦ Place the project folder inside your server’s root (e.g., htdocs for XAMPP).

3. **Run the schema creation script**

   ◦ Open http://localhost/index.php (localhost in Bing) in your browser.
   ◦ This will automatically create the database admin_server and all required tables.

4. **Insert demo data** (Optional)

   ◦ Open http://localhost/demo.php (localhost in Bing).
   ◦ This will insert dummy records (categories, products, users, etc.) for testing.

5. **Login to the system**

   ◦ Navigate to **(http://localhost/inventory_system/public/index.php)** (localhost in Browser).
   ◦ Use the demo credentials **(e.g., admin / admin123)** if you ran demo.php.

## Project Structure

INVENTORY_SYSTEM/
│
├── config/ # DB connection configs
├── modules/ # Feature-specific PHP modules
├── public/ # Public-facing files (login.php, assets, etc.)
├── index.php # Schema creation script (run first)
├── demo.php # Dummy data insertion script
└── README.md # Documentation
