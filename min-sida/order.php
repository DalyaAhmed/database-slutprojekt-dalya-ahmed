<?php

require_once "model.php";
require_once "order_item.php"; 

class Order extends Model {
    protected $CustomerID;
    protected $OrderID;
    protected $Status;
    protected $OrderDate;
    protected $TotalAmount;

    function __construct($CustomerID, $OrderID, $Status, $OrderDate, $TotalAmount) {
        $this->CustomerID = $CustomerID;
        $this->OrderID = $OrderID;
        $this->Status = $Status;
        $this->OrderDate = $OrderDate;
        $this->TotalAmount = $TotalAmount;
    }

    public function setCustomerId($CustomerID) {
        $this->CustomerID = $CustomerID;
    }

    public function getCustomerId() {
        return $this->CustomerID;
    }

    public function setOrderId($OrderID) {
        $this->OrderID = $OrderID;
    }

    public function getOrderId() {
        return $this->OrderID;
    }

    public function setStatus($Status) {
        $this->Status = $Status;
    }

    public function getStatus() {
        return $this->Status;
    }

    public function setCreated($OrderDate) {
        $this->OrderDate = $OrderDate;
    }

    public function getCreated() {
        return $this->OrderDate;
    }

    function print() {
        echo "<br>En order:<br>CustomerID: " . $this->CustomerID . " OrderID: " . $this->OrderID . " Status: $" . $this->Status . " Created: " . $this->OrderDate;
    }

    // Save method to update the status of an order
    function save() {
        $connection = parent::getConnection();
        $query = "UPDATE orders SET Status = ?, TotalAmount = ? WHERE OrderID = ?";
        $statement = $connection->prepare($query);
        $statement->bind_param("sdi", $this->Status, $this->TotalAmount, $this->OrderID);
        $result = $statement->execute();
        $statement->close();
        return $result;
    }
    
static function placeOrder($product, $first_name, $last_name, $social_security_number, $telephone, $address, $zip_code, $city, $email, $totalAmount) {
   
    $connection = parent::getConnection();

    $query = "INSERT INTO orders (CustomerID, OrderDate, Status, TotalAmount) VALUES (?, NOW(), ?, ?)";
    $statement = $connection->prepare($query);

  
    $customerID = self::createOrUpdateCustomer(
        $first_name,
        $last_name,
        $social_security_number,
        $telephone,
        $address,
        $zip_code,
        $city,
        $email
    );


    $status = 'Processing';

    $statement->bind_param("iss", $customerID, $status, $totalAmount);
    $result = $statement->execute();

   
    $statement->close();

   
    if ($result) {
        $orderId = $connection->insert_id; // Get the last inserted order ID
        $quantity = 1; // Assuming quantity is 1 for simplicity
        $amount = $totalAmount;

        require_once "order_item.php"; // Update  the new data to order_item

        // Create an OrderItem instance and save it
        $orderItem = new OrderItem($orderId, $product->getProductID(), $quantity, $amount);
        $orderItem->save();
    }

    return $result;
}

private static function createOrUpdateCustomer($first_name, $last_name, $social_security_number, $telephone, $address, $zip_code, $city, $email) {
    $connection = parent::getConnection();

    // Check if the customer already exists based on social security number or email
    $existingCustomerID = self::getCustomerIDBySSNOrEmail($social_security_number, $email);

    if ($existingCustomerID) {
        // Customer already exists, update the customer information if needed

        return $existingCustomerID;
    } else {
        // Customer doesn't exist, create a new customer
        $query = "INSERT INTO customers (first_name, last_name, social_security_number, telephone, address, zip_code, city, email) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $statement = $connection->prepare($query);

        $statement->bind_param("ssssssss", $first_name, $last_name, $social_security_number, $telephone, $address, $zip_code, $city, $email);
        $statement->execute();

       
        return $connection->insert_id;
    }
}

    // Function to create a new customer if not exists and return the CustomerID
 public static function getCustomerIDByEmail($connection, $email) {
    $query = "SELECT CustomerID FROM customers WHERE email = ?";
    $statement = $connection->prepare($query);
    $statement->bind_param("s", $email);
    $statement->execute();
    $result = $statement->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['CustomerID'];
    } else {
        return null; 
    }
}

    // Function to get orders by customer ID
    function getOrdersByCustomer($connection, $CustomerID) {
        $query = "SELECT orders.*, customers.first_name, customers.last_name
        FROM orders
        INNER JOIN customers ON orders.CustomerID = customers.CustomerID
        WHERE orders.CustomerID = ?";

        $statement = $connection->prepare($query);
        $statement->bind_param("i", $CustomerID);
        $statement->execute();

        $result = $statement->get_result();
        $Orders = array();
        while ($row = $result->fetch_assoc()) {
            $OrderID = $row["OrderID"];
            $Status = $row["Status"];
            $OrderDate = $row["OrderDate"];
            $TotalAmount = $row["TotalAmount"];
            $customerFirstName = $row["first_name"];
            $customerLastName = $row["last_name"];
            

            $order = new Order($CustomerID, $OrderID, $Status, $OrderDate,$TotalAmount,$customerFirstName, $customerLastName);
            $Orders[] = $order;

        }

        return $Orders;
    }

   // Function to get all orders
public static function getAllOrders($connection) {
    $query = "SELECT orders.*, customers.first_name, customers.last_name
    FROM orders
    INNER JOIN customers ON orders.CustomerID = customers.CustomerID";

    $statement = $connection->query($query);

    if ($statement === false) {
        die("Error executing the query: " . $connection->error);
    }

    $orders = array();

    while ($row = $statement->fetch_assoc()) {
        $order_id = $row["OrderID"];
        $status = $row["Status"];
        $created = $row["OrderDate"];
        $totalAmount = $row["TotalAmount"]; 
        $customerFirstName = $row["first_name"];
        $customerLastName = $row["last_name"];
    
        $order = new Order($row['CustomerID'], $order_id, $status, $created, $totalAmount,$customerFirstName, $customerLastName); 
        $orders[] = $order;
    }

    // Close the statement
    $statement->close();

    return $orders;
}

   
public static function getCustomerById($connection, $customerId) {
    $query = "SELECT * FROM customers WHERE CustomerID = ?";
    $statement = $connection->prepare($query);
    $statement->bind_param("i", $customerId);
    $statement->execute();

    $result = $statement->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        return new Customer(
            $row['CustomerID'],
            $row['first_name'],
            $row['last_name'],
            $row['social_security_number'],
            $row['telephone'],
            $row['address'],
            $row['zip_code'],
            $row['city'],
            $row['email']
        );
    } else {
        return null;
    }
}
private static function getCustomerIDBySSNOrEmail($social_security_number, $email) {
    $connection = parent::getConnection();

    // Query to check if a customer with the given SSN or email already exists
    $query = "SELECT CustomerID FROM Customers WHERE social_security_number = ? OR email = ?";
    $statement = $connection->prepare($query);
    $statement->bind_param("ss", $social_security_number, $email);
    $statement->execute();
    $statement->bind_result($customerID);

    // Fetch the result
    $result = $statement->fetch();

    
    $statement->close();

    if ($result) {
        return $customerID; 
    } else {
        return null; 
    }
}


}