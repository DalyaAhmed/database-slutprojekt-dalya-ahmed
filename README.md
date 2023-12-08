# database-slutprojekt-dalya-ahmed

Mysql:


-- Customers Table
CREATE TABLE IF NOT EXISTS Customers (
    CustomerID INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    social_security_number VARCHAR(11) NOT NULL,
    telephone VARCHAR(15) NOT NULL,
    address VARCHAR(255) NOT NULL,
    zip_code VARCHAR(10) NOT NULL,
    city VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL
);

-- Media Table
CREATE TABLE IF NOT EXISTS Media (
    MediaID INT PRIMARY KEY AUTO_INCREMENT,
    FilePath VARCHAR(255)
);

-- Products Table
CREATE TABLE IF NOT EXISTS Products (
    ProductID INT PRIMARY KEY AUTO_INCREMENT,
    Name VARCHAR(255),
    Price DECIMAL(10, 2),
    MediaID INT,
    quantitiy INT,
    FOREIGN KEY (MediaID) REFERENCES Media(MediaID)
);

-- ShippingOptions Table
CREATE TABLE IF NOT EXISTS ShippingOptions (
    ShippingOptionID INT PRIMARY KEY AUTO_INCREMENT,
    Name VARCHAR(50),
    Amount DECIMAL(10, 2)
);

-- Orders Table
CREATE TABLE IF NOT EXISTS Orders (
    OrderID INT PRIMARY KEY AUTO_INCREMENT,
    CustomerID INT,
    Status VARCHAR(20),
    OrderDate DATE,
    TotalAmount DECIMAL(10, 2),
    ShippingOptionID INT,
    DiscountID INT,
    FOREIGN KEY (CustomerID) REFERENCES Customers(CustomerID),
    FOREIGN KEY (ShippingOptionID) REFERENCES ShippingOptions(ShippingOptionID),
    FOREIGN KEY (DiscountID) REFERENCES Discounts(DiscountID)
);

CREATE TABLE order_items (
    OrderItemID INT AUTO_INCREMENT PRIMARY KEY,
    OrderID INT,
    ProductID INT,
    Quantity INT,
    Amount DECIMAL(10, 2),
    FOREIGN KEY (OrderID) REFERENCES orders(OrderID),
    FOREIGN KEY (ProductID) REFERENCES products(ProductID)
);

-- Discounts Table
CREATE TABLE IF NOT EXISTS Discounts (
    DiscountID INT PRIMARY KEY AUTO_INCREMENT,
    Code VARCHAR(20),
    Amount DECIMAL(10, 2)
);

-- OrderItems Table
INSERT INTO OrderItems (OrderID, ProductID, Quantity, Amount)
VALUES
    (1, 101, 2, 25.99),
    (1, 102, 1, 14.50),
    (2, 105, 3, 30.75),
    (3, 103, 1, 18.99),
    (3, 104, 2, 22.50);



INSERT INTO Customers (first_name, last_name, social_security_number, telephone, address, zip_code, city, email)
VALUES
    ('John', 'Doe', '12345678901', '123-456-7890', '123 Main St', '12345', 'Anytown', 'john.doe@example.com'),
    ('Jane', 'Smith', '98765432109', '987-654-3210', '456 Oak St', '54321', 'Somecity', 'jane.smith@example.com'),
    ('Bob', 'Johnson', '45678901234', '555-123-4567', '789 Elm St', '67890', 'Othercity', 'bob.johnson@example.com'),
    ('Alice', 'Williams', '78901234567', '888-555-1234', '101 Pine St', '54321', 'Anothercity', 'alice.williams@example.com'),
    ('Charlie', 'Brown', '23456789012', '777-444-5555', '202 Cedar St', '98765', 'Newcity', 'charlie.brown@example.com');


-- INSERT statements

-- Customers
INSERT INTO Customer (first_name, last_name, social_security_number, telephone, address, zip_code, city, email)
VALUES
('John', 'Doe', '123-45-6789', '555-1234', '123 Main St', '12345', 'Anytown', 'john.doe@example.com'),
('Jane', 'Smith', '987-65-4321', '555-5678', '456 Oak St', '67890', 'Anothercity', 'jane.smith@example.com'),
('Bob', 'Johnson', '456-78-9012', '555-8765', '789 Pine St', '54321', 'Bigcity', 'bob.johnson@example.com'),
('Alice', 'Williams', '789-01-2345', '555-4321', '321 Elm St', '67890', 'Smalltown', 'alice.williams@example.com'),
('Charlie', 'Brown', '234-56-7890', '555-2109', '987 Maple St', '54321', 'Village', 'charlie.brown@example.com');


-- Media
INSERT INTO Media (FilePath)
VALUES
('DB-slutprjekt/images/pic-01.webp'),
('DB-slutprjekt/images/pic-02.jpeg');

-- Products
INSERT INTO Products (Name, Price, MediaID,quantitiy)
VALUES
('Product 1', 19.99, 1,7),
('Product 2', 29.99, 2,8),
('Product 3', 40.99, 1,6),
('Product 4', 80.99, 3,3),
('Product 5', 40.99, 1,5);

-- Orders
INSERT INTO Orders (CustomerID, Status, OrderDate, TotalAmount)
VALUES
(3, 'Processing', '2023-01-01', 49.98),
(1, 'Processing', '2023-01-01', 49.98),
(2, 'Processing', '2023-01-01', 49.98),
(4, 'Processing', '2023-01-01', 49.98),
(5, 'Completed', '2023-01-02', 69.97);

-- OrderItems
INSERT INTO OrderItems (OrderID, ProductID, Quantity, Amount)
VALUES
(1, 1, 2, 39.98),
(2, 2, 1, 29.99),
(2, 2, 1, 29.99),
(1, 1, 2, 39.98),
(1, 1, 2, 39.98),;

-- Discounts
INSERT INTO Discounts (Code, Amount)
VALUES
('DISC10', 10.00),
('FREESHIP', 5.00),
('DISC10', 10.00),
('DISC10', 10.00),
('DISC10', 10.00),;

-- ShippingOptions
INSERT INTO ShippingOptions (Name, Amount)
VALUES
('Standard Shipping', 5.00),
('Express Shipping', 10.00),
('Express Shipping', 10.00),
('Express Shipping', 10.00),
('Express Shipping', 10.00);

