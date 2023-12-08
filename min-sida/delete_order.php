<?php
require_once "model.php";

// Check if an OrderID is provided in the request
if (isset($_GET['order_id'])) {
    $orderID = $_GET['order_id'];

    
    $connection = Model::getConnection();

    if ($success) {
        echo "Order with ID $orderID has been successfully removed.";
    } else {
        echo "Failed to remove the order.";
    }
} else {
    echo "Invalid request. Please provide an OrderID.";
}
?>
