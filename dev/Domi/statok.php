<link rel="stylesheet" href="css/style.css">
<?php

    require "../../Connection/config.php";
    require "../../Functions/message.php";

    $inquiry = "SELECT * FROM items ORDER BY RAND()";
    $f_item = $conn->query($inquiry);
    $item = $f_item->fetch_assoc();
    
    ?>
<br><br>
<?php
    $type = "";
    $inquiry = "SELECT * FROM item_stats WHERE item_id = $item[id]";
    $f_stats = $conn->query($inquiry);
    echo '<div class="descript">';
    while($stats = $f_stats->fetch_assoc()){
        if ($stats['type'] == "flat"){
            $type = "";
        }
        else if ($stats['type'] == "%"){
            $type = "%";
        }

        echo $stats['stat_value']." ".$type." ".ucwords(str_replace('_',' ',$stats['stat_name']))."<br>";
    }
    echo "</div>";

    echo "<div class='descript'>";
    if(isset($_POST['guess'])){
        if(strtolower($_POST['guess']) == strtolower($item['name'])){
            echo "Correct! The Item was $item[name]";
        }
        else{
            echo "Incorrect!";
        }
    }
?>

    <form method="post" class='desc-form'>
        <input type="text" name="guess" placeholder="Start Typing...">
        <button type="button" name="guess-btn" onclick="this.form.submit()">Submit</button>
    </form>
</div>