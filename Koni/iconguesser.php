<link rel="stylesheet" href="css/style.css">


<?php

    require '../Connection/config.php';


    $lekerdezes = "SELECT * FROM items ORDER BY RAND() LIMIT 1";
    $talalt_sor = $conn->query($lekerdezes);
    $item = $talalt_sor->fetch_assoc();
    echo "<div class='item-container'>";
        echo "<h1>Guess the item!</h1>";
        echo "<img src='../Items/$item[name]/$item[icon]'>";
            if(isset($_POST['guess'])) {
                if($item['name'] == $_POST['guess']) {
                    echo "<h2>Correct!</h2>";
            }
            else {
                echo "<h2>Wrong! The correct answer was: " . $item['name'] . "</h2>";
            }
        }
    echo "</div>";
    
    
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method = "post" class = "guess-form">
        <input type="text" name = "guess" placeholder="Guess the item name" class = "guess-input" >
        <button type="button" onclick="this.form.submit()" class = "submit-btn">Submit Guess</button>
    </form>
</body>
</html>



