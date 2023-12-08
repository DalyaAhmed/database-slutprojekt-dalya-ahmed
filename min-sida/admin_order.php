<?php
// Enable error reporting for debugging purposes
error_reporting(E_ALL);
ini_set('display_errors', 1);


require_once "order.php";
require_once "order_item.php";
require_once "customer.php";
require_once "update_status.php";


function getOrderItemsByOrderId($connection, $orderId)
{
    $order_items = array();

    $query = "SELECT * FROM order_items WHERE OrderID = ?";


    $statement = $connection->prepare($query);


    $statement->bind_param("i", $orderId);


    $statement->execute();


    $result = $statement->get_result();


    while ($row = $result->fetch_assoc()) {
       
        $orderItem = new OrderItem($row['ProductID'], $row['OrderID'], $row['Quantity'], $row['Amount']);
        $order_items[] = $orderItem;
    }


    $statement->close();

    return $order_items;
}

echo "<h1>Admin page</h1>";


$connection = getDatabaseConnection();


if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Handle form submission for deleting orders
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deleteOrder'])) {
    $orderIdToDelete = $_POST['orderIdToDelete'];

    // Perform the order deletion
    $deleteQuery = "DELETE FROM Orders WHERE OrderID = ?";
    $deleteStatement = $connection->prepare($deleteQuery);
    $deleteStatement->bind_param("i", $orderIdToDelete);
    $deleteResult = $deleteStatement->execute();

    if ($deleteResult) {
        echo "<p>Order ID: $orderIdToDelete has been successfully deleted.</p>";
    } else {
        echo "<p>Error deleting the order. Please try again.</p>";
    }


    $deleteStatement->close();
}

// Handle form submission for updating order status
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['updateStatus'])) {
    $orderIdToUpdate = $_POST['orderIdToUpdate'];
    $newStatus = $_POST['newStatus'];

    // Perform the order status update
    $updateResult = updateOrderStatus($orderIdToUpdate, $newStatus);

    if ($updateResult) {
        echo "<p>Order ID: $orderIdToUpdate has been successfully updated to status: $newStatus.</p>";
    } else {
        echo "<p>Error updating the order status. Please try again.</p>";
    }
}

// Handle form submission for adding shipping options
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addShippingOption'])) {
    $shippingOptionName = $_POST['shippingOptionName'];
    $shippingOptionAmount = $_POST['shippingOptionAmount'];



    // Display existing shipping options
    $queryShippingOptions = "SELECT * FROM ShippingOptions";
    $statementShippingOptions = $connection->query($queryShippingOptions);

    if ($statementShippingOptions === false) {
        die("Error executing the query for shipping options: " . $connection->error);
    }

    echo "<h2>Shipping Options</h2>";
    echo "<ul>";
    while ($shippingOption = $statementShippingOptions->fetch_assoc()) {
        echo "<li>Name: {$shippingOption['Name']}, Amount: {$shippingOption['Amount']} SEK</li>";
    }
    echo "</ul>";


    $statementShippingOptions->close();
}

// Display existing discounts
$queryDiscounts = "SELECT * FROM Discounts";
$statementDiscounts = $connection->query($queryDiscounts);

if ($statementDiscounts === false) {
    die("Error executing the query for discounts: " . $connection->error);
}

echo "<h2>Discounts</h2>";
echo "<ul>";
while ($discount = $statementDiscounts->fetch_assoc()) {
    echo "<li>Code: {$discount['Code']}, Amount: {$discount['Amount']} SEK</li>";
}
echo "</ul>";


$statementDiscounts->close();


$query = "SELECT * FROM Orders";


$statement = $connection->query($query);


if ($statement === false) {
    die("Error executing the query: " . $connection->error);
}

$Orders = array();
while ($result = $statement->fetch_assoc()) {
    $CustomerID = $result["CustomerID"];
    $OrderID = $result["OrderID"];
    $Status = $result["Status"];
    $OrderDate = $result["OrderDate"];
    $TotalAmount = $result["TotalAmount"];


    $order = new Order($CustomerID, $OrderID, $Status, $OrderDate, $TotalAmount);


    $Orders[] = $order;
}

// Display orders, order items, and customer details
foreach ($Orders as $order) {
    echo "<h2>Order ID: {$order->getOrderId()}</h2>";
    echo "<p>Status: {$order->getStatus()}</p>";
    echo "<p>Date: {$order->getCreated()}</p>";

    // Retrieve order items for the current order
    $orderItems = getOrderItemsByOrderId($connection, $order->getOrderId());

    echo "<ul>";
    foreach ($orderItems as $orderItem) {
        echo "<li>";
        echo "Product ID: {$orderItem->getProductID()}, ";
        echo "Total: {$orderItem->getAmount()} SEK";
        echo "</li>";
    }
    echo "</ul>";

    // Retrieve customer details for the current order
    $customer = Customer::getCustomerById($connection, $order->getCustomerId());

    echo "<p>Customer: {$customer->getfirst_name()} {$customer->getlast_name()}</p>";
    echo "<p>Email: {$customer->getEmail()}</p>";
    echo "<p>Address: {$customer->getAddress()}, {$customer->getZipCode()}, {$customer->getCity()}</p>";

    // delete order form
    echo "<form method='post'>";
    echo "<input type='hidden' name='orderIdToDelete' value='{$order->getOrderId()}'>";
    echo "<button type='submit' name='deleteOrder'>Delete Order</button>";
    echo "</form>";

    echo "<hr>";

    //  update order status form
    echo "<form method='post'>";
    echo "<input type='hidden' name='orderIdToUpdate' value='{$order->getOrderId()}'>";
    echo "<label for='newStatus'>New Status:</label>";
    echo "<select name='newStatus' required>";
    echo "<option value='Processing'>Processing</option>";
    echo "<option value='Completed'>Completed</option>";
    echo "<option value='Shipped'>Shipped</option>";
    echo "<option value='Canceled'>Canceled</option>";
    echo "</select>";
    echo "<button type='submit' name='updateStatus'>Update Status</button>";
    echo "</form>";
}







$statement->close();


$connection->close();
?>