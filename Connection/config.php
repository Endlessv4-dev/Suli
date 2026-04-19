<?php 

    $conn = new mysqli("localhost", "root", "", "gtlol");

    if ($conn->connect_error) {
        die("Connection error!".$conn->connect_error);
    }

?>