<?php

function getDatabaseConnection(){
    $host = "localhost";
    $port = 3306;
    $database = "finalproject";
    $username = "root";
    $password = "";

    $connection = new mysqli($host, $username, $password, $database, $port);

    if($connection->connect_error != null){
        die("Anslutningen misslyckades: " . $connection->connect_error);
    } else {
        //echo "Anslutningen lyckas";
        return $connection;
    }
}
?>

