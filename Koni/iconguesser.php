<?php

    require '../Connection/config.php';


    $lekerdezes = "SELECT * FROM items ORDER BY RAND() LIMIT 1";
    $talalt_sor = $conn->query($lekerdezes);
    $item = $talalt_sor->fetch_assoc();

    echo $item['name'] . "<br>";
    echo "<img src='$item[icon]' alt='$item[name]'>";
?>