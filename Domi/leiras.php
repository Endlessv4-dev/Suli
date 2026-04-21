<?php

    require "../Connection/config.php";
    require "../Functions/message.php";

    $found_items = $conn->query("SELECT * FROM items WHERE description != '-' ORDER BY RAND() LIMIT 1");
    $item = $found_items->fetch_assoc();
    echo "<div class='descript'>";
    echo "  <br><span class='tier'>".$item['tier']."</span><br><span class='desc'>".$item['description']."</span><br><br>";
    if(isset($_POST['guess'])){
        if($_POST['guess'] == $item['name']){
            echo "Correct!<br> The item was Heartsteel";
        }
        else{
            echo "Incorrect, try again!";
        }
    }
    echo "</div>";
    
    

?>
<br>
<link rel="stylesheet" href="css/style.css">
<div class="descript">
    <form method="post">
        <input type="text" name="guess" placeholder="Start Typing...">
        <button type="button" name="guess-btn" onclick="this.form.submit()">Submit</button>
    </form>
</div>