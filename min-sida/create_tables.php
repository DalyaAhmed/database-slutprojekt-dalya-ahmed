Oh talk of your bill was hoping there's just no connection applause and I will love to listen Close what's material that I'll be negotiable
<?php

require_once "database.php";


$connection = getDatabaseConnection();

// Create 'customers' table if it does not exist
$sqlCustomers = "
CREATE TABLE IF NOT EXISTS customers (
    CustomerID INT AUTO_INCREMENT PRIMARY KEY,
    FirstName VARCHAR(255) NOT NULL,
    Email VARCHAR(255) NOT NULL
)";

// Create 'orders' table if it does not exist
$sqlOrders = "
CREATE TABLE IF NOT EXISTS orders (
    OrderID INT AUTO_INCREMENT PRIMARY KEY,
    CustomerID INT,
    Status VARCHAR(255),
    OrderDate DATETIME,
    FOREIGN KEY (CustomerID) REFERENCES customers(CustomerID)
)";

// Create 'products' table if it does not exist
$sqlProducts = "
CREATE TABLE IF NOT EXISTS products (
    ProductID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(255) NOT NULL,
    Price DECIMAL(10, 2) NOT NULL,
    MediaID INT,
    FOREIGN KEY (MediaID) REFERENCES media(MediaID)
)";

// Create 'media' table if it does not exist
$sqlMedia = "
CREATE TABLE IF NOT EXISTS media (
    MediaID INT AUTO_INCREMENT PRIMARY KEY,
    FilePath VARCHAR(255) NOT NULL
)";

// Execute the SQL statements
$connection->query($sqlCustomers);
$connection->query($sqlOrders);
$connection->query($sqlProducts);
$connection->query($sqlMedia);

// Close the connection
$connection->close();

echo "Tables created successfully!";
?>
