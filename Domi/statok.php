<link rel="stylesheet" href="css/style.css">
<?php

    require "../Connection/config.php";
    require "../Functions/message.php";

    $inquiry = "SELECT * FROM items ORDER BY RAND()";
    $f_item = $conn->query($inquiry);
    $item = $f_item->fetch_assoc();
    echo $item['name'];?>
<br><br>
<?php
    $inquiry = "SELECT * FROM item_stats WHERE item_id = $item[id]";
    $f_stats = $conn->query($inquiry);
    while($stats = $f_stats->fetch_assoc()){
        if ($stats['type'] == "flat"){
            $type = "";
        }
        else if ($stats['type'] == "%"){
            $type = "%";
        }
        echo $stats['stat_value']." ".$type." ".$stats['stat_name']."<br>";
    }
    

?>