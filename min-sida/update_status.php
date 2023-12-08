<?php


require_once "model.php";

function updateOrderStatus($orderId, $newStatus) {
    $connection = Model::getConnection();

    if (empty($orderId) || empty($newStatus)) {
        return false;
    }

    $updateQuery = "UPDATE Orders SET Status = ? WHERE OrderID = ?";
    $updateStatement = $connection->prepare($updateQuery);
    $updateStatement->bind_param("si", $newStatus, $orderId);
    $updateResult = $updateStatement->execute();

   
    if ($updateResult) {
        return true;
    } else {
        return false;
    }
}
?>
