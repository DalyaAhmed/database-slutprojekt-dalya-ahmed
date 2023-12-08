<?php
require_once "model.php";

class OrderItem extends Model {
    protected $OrderItemID;
    protected $OrderID;
    protected $ProductID;
    protected $Quantity;
    protected $Amount;

    function __construct($OrderID, $ProductID, $Quantity, $Amount) {
        $this->OrderID = $OrderID;
        $this->ProductID = $ProductID;
        $this->Quantity = $Quantity;
        $this->Amount = $Amount;
    }

    function geOrderItemID() {
        return $this->OrderItemID;
    }

    function setOrderItemID($value) {
        $this->OrderItemID = $value;
    }

    function getOrderID() {
        return $this->OrderID;
    }

    function setOrderID($value) {
        $this->OrderID = $value;
    }

    function getProductID() {
        return $this->ProductID;
    }

    function setProductID($value) {
        $this->ProductID = $value;
    }

    function getQuantity() {
        return $this->Quantity;
    }

    function setQuantity($value) {
        $this->Quantity = $value;
    }

    function getAmount() {
        return $this->Quantity;
    }

    function setAmount($value) {
        $this->Quantity = $value;
    }


    // Save method to insert order items into the database
    public function save() {
        $connection = parent::getConnection();
        $query = "INSERT INTO order_items (OrderID, ProductID, Quantity, Amount) VALUES (?, ?, ?, ?)";
        $statement = $connection->prepare($query);
        $statement->bind_param("iiid", $this->OrderID, $this->ProductID, $this->Quantity, $this->Amount);
        $result = $statement->execute();
        $statement->close();
        return $result;
    }
}
?>
