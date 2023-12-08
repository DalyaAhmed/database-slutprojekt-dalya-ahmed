<?php

require_once "model.php";
require_once "media.php";

class Product extends Model {
    protected $ProductID;
    protected $Name;
    protected $Price;
    protected $MediaID;

    function __construct($ProductID, $Name, $Price, $MediaID) {
        $this->ProductID = $ProductID;
        $this->Name = $Name;
        $this->Price = $Price;
        $this->MediaID = $MediaID;
    }

    function getProductID() {
        return $this->ProductID;
    }

    function setProductID($value) {
        $this->ProductID = $value;
    }

    function getName() {
        return $this->Name;
    }

    function setName($value) {
        $this->Name = $value;
    }

    function getPrice() {
        return $this->Price;
    }

    function setPrice($value) {
        $this->Price = $value;
    }

    function getMediaID() {
        return $this->MediaID;
    }

    function setMediaID($value) {
        $this->MediaID = $value;
    }

    public function printInfo() {
        return "Product ID: " . $this->ProductID . " Product Name: " . $this->Name . " Price: $" . $this->Price . " Image URL: " . $this->MediaID;
    }

    function getMedia() {
        // Retrieve media information based on the mediaID using a prepared statement
        $connection = $this->getConnection();
        $query = "SELECT * FROM media WHERE MediaID = ?";
        $statement = $connection->prepare($query);
        $statement->bind_param("i", $this->MediaID);
        $statement->execute();

        $result = $statement->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $filePath = $row['FilePath'];
            return new Media($row['MediaID'], $filePath);
        }

        return null;
    }

    static function getProductById($connection, $ProductID) {
        // Retrieve a product by ID 
        $query = "SELECT * FROM products WHERE ProductID = ?";
        $statement = $connection->prepare($query);
        $statement->bind_param("i", $ProductID);
        $statement->execute();

        $result = $statement->get_result()->fetch_assoc();

        if ($result !== null) {
            $product = new Product(
                $result["ProductID"],
                $result["Name"],
                $result["Price"],
                $result["MediaID"]
            );

            return $product;
        } else {
            throw new Exception("Product not found!");
        }
    }
}

function getProducts($connection) {
    // Retrieve all products 
    $query = "SELECT * FROM products";
    $result = $connection->query($query);

    $products = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $product = new Product(
                $row['ProductID'],
                $row['Name'],
                $row['Price'],
                $row['MediaID']
            );
            $products[] = $product;
        }
    }

    return $products;
}
?>
