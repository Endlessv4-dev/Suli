<?php

    require "..Connection/config.php";
    require "..Functions/message.php";

    $found_items = $conn->query("SELECT * FROM items WHERE description != '-' ORDER BY RAND() LIMIT 1");
    $item = $found_items->fetch_assoc();

    echo $item['name']."<br>".$item['tier']."<br>".$item['description'];


?>