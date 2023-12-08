<?php
require_once "model.php";

class Customer extends Model {
    protected $CustomerID;
    protected $first_name;
    protected $last_name;
    protected $social_security_number;
    protected $telephone;
    protected $address;
    protected $zip_code;
    protected $city;
    protected $email;

    function __construct($CustomerID, $first_name, $last_name, $social_security_number, $telephone, $address, $zip_code, $city, $email) {
        $this->CustomerID = $CustomerID;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->social_security_number = $social_security_number;
        $this->telephone = $telephone;
        $this->address = $address;
        $this->zip_code = $zip_code;
        $this->city = $city;
        $this->email = $email;
    }

    public function getId() {
        return $this->CustomerID;
    }

    public function getfirst_name() {
        return $this->first_name;
    }

    public function getlast_name() {
        return $this->last_name;
    }

    public function getSocialSecurityNumber() {
        return $this->social_security_number;
    }

    public function getTelephone() {
        return $this->telephone;
    }

    public function getAddress() {
        return $this->address;
    }

    public function getZipCode() {
        return $this->zip_code;
    }

    public function getCity() {
        return $this->city;
    }

    public function getEmail() {
        return $this->email;
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
    public static function addCustomer($connection, $first_name, $last_name, $social_security_number, $telephone, $address, $zip_code, $city, $email) {
        $query = "INSERT INTO customers (first_name, last_name, social_security_number, telephone, address, zip_code, city, email) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $statement = $connection->prepare($query);
        $statement->bind_param("ssssssss", $first_name, $last_name, $social_security_number, $telephone, $address, $zip_code, $city, $email);
        
        return $statement->execute();
    }
}
?>
