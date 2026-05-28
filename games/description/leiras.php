<<<<<<< HEAD
=======
<br>
<link rel="stylesheet" href="../../css/style.css">
>>>>>>> 7e5e77947916b46449bf0523209c8cfb1989f5ba
<?php
    require "../../Connection/config.php";
    require "../../Functions/message.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/nav.css">
    <title>Description</title>
</head>
<body>
<?php
    require "../../Functions/nav.php";

    $inquiry = "SELECT * FROM items WHERE description != '-' ORDER BY RAND() LIMIT 1";
    $found_items = $conn->query($inquiry);
    $item = $found_items->fetch_assoc();
    echo "<div class='descript'>";
    echo "  <br>".$item['tier'].$item['description'];
    echo "</div>";
    echo "<div class='descript'>";
    if(isset($_POST['guess'])){
        if(strtolower($_POST['guess']) == strtolower($item['name'])){
            echo "Correct!<br> The item was $item[name]";
        }
        else{
            echo "Incorrect, try again!";
        }
    }
    
?>

    <form method="post" class='desc-form'>
        <input type="text" name="guess" placeholder="Start Typing...">
        <button type="button" name="guess-btn" onclick="this.form.submit()">Submit</button>
    </form>
</div>
</body>
</html>