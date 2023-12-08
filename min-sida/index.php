<?php
require_once "database.php";
require_once "product.php";
require_once "order.php";
require_once "customer.php";
require_once "order_item.php";

echo "<h1>Grit Store</h1>";


$connection = getDatabaseConnection();


$error_message = "";
$email = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if customer_email is set in the form submission
    if (isset($_POST["customer_email"])) {
        $email = $_POST["customer_email"];
        $existingCustomerID = Order::getCustomerIDByEmail($connection, $email);

        if ($existingCustomerID) {
            // Customer exists,then proceed with the order
            $existingCustomer = Customer::getCustomerById($connection, $existingCustomerID);

            // Check if the product ID is set in the form submission
            if (isset($_POST["product_id"])) {
                $product_id = $_POST["product_id"];

                
                $selected_product = Product::getProductById($connection, $product_id);

                if ($selected_product) {
                    // Set total amount based on the product price.
                    $totalAmount = $selected_product->getPrice();

                    //  Store it in the database
                    $order_placed = Order::placeOrder(
                        $selected_product,
                        $existingCustomer->getfirst_name(),
                        $existingCustomer->getlast_name(),
                        $existingCustomer->getSocialSecurityNumber(),
                        $existingCustomer->getTelephone(),
                        $existingCustomer->getAddress(),
                        $existingCustomer->getZipCode(),
                        $existingCustomer->getCity(),
                        $existingCustomer->getEmail(),
                        $totalAmount
                    );

                    
                    if ($order_placed) {
                        $error_message = "Order placed successfully!";
                    } else {
                        $error_message = "Failed to place the order. Please try again.";
                    }
                } else {
                    $error_message = "Product not found!";
                }
            } else {

                $error_message = "Please select a product before placing the order.";
            }
        } else {
            // Customer not found? Handle the customer information form submission.
            if (isset($_POST["submit_customer_info"])) {
                // Collect customer information from the form
                $first_name = $_POST["first_name"];
                $last_name = $_POST["last_name"];
                $social_security_number = $_POST["social_security_number"];
                $telephone = $_POST["telephone"];
                $address = $_POST["address"];
                $zip_code = $_POST["zip_code"];
                $city = $_POST["city"];
                $email = $_POST["customer_email"];

                // Insert customer information into the database
                $customer_added = Customer::addCustomer(
                    $connection,
                    $first_name,
                    $last_name,
                    $social_security_number,
                    $telephone,
                    $address,
                    $zip_code,
                    $city,
                    $email
                );

                // Provide feedback to the user
                if ($customer_added) {
                    $error_message = "Customer information added successfully!";
                } else {
                    $error_message = "Failed to add customer information. Please try again.";
                }
            } else {
                // Display the customer information input form.
                echo "<h1>Enter your information</h1>";
                echo "<form method='post' style='max-width: 400px; margin: auto;'>";
                echo "<label for='first_name'>First Name:</label>";
                echo "<input type='text' name='first_name' required class='form-control'><br>";

                echo "<label for='last_name'>Last Name:</label>";
                echo "<input type='text' name='last_name' required class='form-control' style='margin-top: 10px;'><br>";

                echo "<label for='social_security_number'>Social Number:</label>";
                echo "<input type='text' name='social_security_number' required class='form-control' style='margin-top: 10px;'><br>";

                echo "<label for='telephone'>Telephone:</label>";
                echo "<input type='text' name='telephone' required class='form-control' style='margin-top: 10px;'><br>";

                echo "<label for='address'>Address:</label>";
                echo "<input type='text' name='address' required class='form-control' style='margin-top: 10px;'><br>";

                echo "<label for='zip_code'>Zip Code:</label>";
                echo "<input type='text' name='zip_code' required class='form-control' style='margin-top: 10px;'><br>";

                echo "<label for='city'>City:</label>";
                echo "<input type='text' name='city' required class='form-control' style='margin-top: 10px;'><br>";

                echo "<input type='hidden' name='customer_email' value='$email'>";
                echo "<input type='submit' name='submit_customer_info' value='Submit' class='btn btn-success' style='margin-top: 10px;'>";
                echo "</form>";
            }
        }
    } else {
       
        echo "<h4>Enter your email</h4>";
        echo "<form method='post' style='max-width: 400px; margin: auto;'>";
        echo "<label for='customer_email'>Email:</label>";
        echo "<input type='email' name='customer_email' required class='form-control' value='$email'><br>";
        echo "<input type='submit' name='place_order' value='Place Order' class='btn btn-success' style='margin-top: 10px;'>";
        echo "</form>";
    }
}


if (!empty($error_message)) {
    echo "<p style='color: red;'>$error_message</p>";
}


echo "<h3>Product List:</h3>";
$products = getProducts($connection);

foreach ($products as $product) {
    echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 10px;'>";
    echo "Name: {$product->getName()}<br>";
    echo "Price: {$product->getPrice()} SEK<br>";

    // Display media information
    $media = $product->getMedia();
    if ($media) {
        echo "<img src='{$media->getFilePath()}' alt='Product Image' style='max-width: 100px; max-height: 100px;'><br>";
    }

    echo "<form method='post' style='margin-top: 10px;'>";
    echo "<input type='hidden' name='product_id' value='{$product->getProductID()}'>";
    echo "<input type='hidden' name='customer_email' value='$email'>";
    echo "<input type='submit' name='place_order' value='Place Order' class='btn btn-success'>";
    echo "</form>";

    echo "</div>";
}

// Display the email input form
echo "<h2>Place an Order:</h2>";
echo "<form method='post' style='max-width: 400px; margin: auto;'>";
echo "<label for='customer_email'>Email:</label>";
echo "<input type='email' name='customer_email' required class='form-control' value='$email'><br>";
echo "<input type='submit' name='place_order' value='Place Order' class='btn btn-success' style='margin-top: 10px;'>";
echo "</form>";
?>
