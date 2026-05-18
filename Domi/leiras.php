<br>
<link rel="stylesheet" href="css/style.css">
<?php

    require "../Connection/config.php";
    require "../Functions/message.php";

    $inquiry = "SELECT * FROM items WHERE description != '-' ORDER BY RAND() LIMIT 1";
    $found_items = $conn->query($inquiry);
    $item = $found_items->fetch_assoc();
    echo "<div class='descript'>";
    echo "  <br>".$item['tier']."<br><br>".$item['description']."<br><br>";
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

<div class="descript">
    <form method="post" class='desc-form'>
        <input type="text" name="guess" placeholder="Start Typing...">
        <button type="button" name="guess-btn" onclick="this.form.submit()">Submit</button>
    </form>
</div>