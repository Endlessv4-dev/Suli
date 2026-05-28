<link rel="stylesheet" href="../../css/style.css">
<?php

    require "../../Connection/config.php";

    session_start();

    $lekerdezes = "SELECT * FROM items ORDER BY RAND() LIMIT 1";
    $talalt_sor = $conn->query($lekerdezes);
    $item = $talalt_sor->fetch_assoc();
    if (!isset($_SESSION['counter'])) {
        $_SESSION['counter'] = 5;
    }
    $counter = $_SESSION['counter'];
    if(isset($_POST['submit-btn'])) {
        $_SESSION['counter']--;
        $counter = $_SESSION['counter'];
    }
    if($counter == 0) {
        $counter == 5;
    }
    echo "<div class='item-container'>";
        echo "<h1>Guess this item!</h1>";
        echo "<img class = 'icon-img' draggable = 'false' id='item-image' src='../../Items/$item[name]/$item[icon]'> <br>";
        echo "<style>.item-container img {filter: grayscale(100%) blur(10px); transform: rotate(random(0deg, 360deg by 90deg));}</style>";
        if(isset($_POST['submit-btn'])) {
                if(strtolower($item['name']) == strtolower($_POST['guess'])) {
                echo "<h2>Correct!</h2>";
                echo "<style>.item-container img {filter: grayscale(0%) blur(0px); transform: rotate(0deg);}</style>";
            }else if ($counter == 4) {
                echo "<h2>Wrong!</h2>";
                echo "<style>.item-container img {filter:grayscale(75%) blur(10px); transform: rotate(90deg);}</style>";
            }else if ($counter > 3) {
                echo "<h2>Wrong!</h2>";
                echo "<style>.item-container img {filter: grayscale(50%) blur(8px); transform: rotate(90deg);}</style>";
            }else if ($counter > 2) {
                echo "<h2>Wrong!</h2>";
                echo "<style>.item-container img {filter: grayscale(25%)  blur(6px); transform: rotate(90deg);}</style>";
            }else if ($counter > 1) {
                echo "<h2>Wrong!</h2>";
                echo "<style>.item-container img {filter: grayscale(0%) blur(4px); transform: rotate(90deg);}</style>";
            }else if ($counter > 0) {
                echo "<h2>Wrong!</h2>";
                echo "<style>.item-container img {filter: grayscale(0%) blur(2px); transform: rotate(90deg);}</style>";
            }else{
                echo "<h2>Wrong! The correct answer was: " . $item['name'] . "</h2>";
                echo "<style>.item-container img {filter: blur(0px); transform: rotate(0deg);}</style>";
                $_SESSION['counter'] = 5;
                $counter = $_SESSION['counter'];
            }
        }

    echo "</div>";
 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Icon guesser</title>
</head>
<body>
    <form method = "post" class = "guess-form">
        <input type="text" name = "guess" placeholder="Guess the item name" class = "search-input" >
        <button type="submit" name = "submit-btn" id = 'submit-btn' class = "submit-btn">Submit Guess</button>
    </form>
</body>
</html>


