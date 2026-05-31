<?php
    require "../../Connection/config.php";
    require "../../Functions/nav.php";

    session_start();

    if (!isset($_SESSION['icon_guesser_streak'])) {
        $_SESSION['icon_guesser_streak'] = 0;
    }

    if (isset($_POST['play-again-btn'])) {
        unset($_SESSION['icon_guesser_target_id']);
        $_SESSION['counter'] = 5;
        header("Location: iconguesser.php");
        exit();
    }

    if (!isset($_SESSION['icon_guesser_target_id'])) {
        $lekerdezes = "SELECT * FROM items ORDER BY RAND() LIMIT 1";
        $talalt_sor = $conn->query($lekerdezes);
        if ($talalt_sor && mysqli_num_rows($talalt_sor) > 0) {
            $item = $talalt_sor->fetch_assoc();
            $_SESSION['icon_guesser_target_id'] = $item['id'];
        }
    }

    $target_id = $_SESSION['icon_guesser_target_id'] ?? 0;
    $item = $conn->query("SELECT * FROM items WHERE id = $target_id")->fetch_assoc();

    if (!isset($_SESSION['counter'])) {
        $_SESSION['counter'] = 5;
    }

    if (isset($_POST['submit-btn'])) {
        $_SESSION['counter']--;
    }

    $counter = $_SESSION['counter'];
    $game_won = false;
    $game_over = false;

    echo "<div class='item-container'>";
    echo "<h1>Guess this item!</h1>";
    
    if ($item) {
        echo "<img class = 'icon-img' draggable = 'false' id='item-image' src='../../Items/" . $item['name'] . "/" . $item['icon'] . "'> <br>";
        
        if (isset($_POST['submit-btn'])) {
            if (strtolower($item['name']) == strtolower($_POST['guess'])) {
                echo "<h2>Correct!</h2>";
                echo "<style>.item-container img {filter: grayscale(0%) blur(0px); transform: rotate(0deg);}</style>";
                $game_won = true;
                $_SESSION['icon_guesser_streak']++;
                
                if (isset($_COOKIE['userid'])) {
                    $uid = (int)$_COOKIE['userid'];
                    $current_streak = $_SESSION['icon_guesser_streak'];
                    $pb_query = $conn->query("SELECT icon_best FROM profile WHERE id = $uid");
                    if ($pb_query && mysqli_num_rows($pb_query) > 0) {
                        $pb_row = $pb_query->fetch_assoc();
                        if ($current_streak > $pb_row['icon_best']) {
                            $conn->query("UPDATE profile SET icon_best = $current_streak WHERE id = $uid");
                        }
                    }
                }
            } else if ($counter == 4) {
                echo "<h2>Wrong!</h2>";
                echo "<style>.item-container img {filter:grayscale(75%) blur(10px); transform: rotate(90deg);}</style>";
            } else if ($counter == 3) {
                echo "<h2>Wrong!</h2>";
                echo "<style>.item-container img {filter: grayscale(50%) blur(8px); transform: rotate(90deg);}</style>";
            } else if ($counter == 2) {
                echo "<h2>Wrong!</h2>";
                echo "<style>.item-container img {filter: grayscale(25%)  blur(6px); transform: rotate(90deg);}</style>";
            } else if ($counter == 1) {
                echo "<h2>Wrong!</h2>";
                echo "<style>.item-container img {filter: grayscale(0%) blur(4px); transform: rotate(90deg);}</style>";
            } else {
                echo "<h2>Wrong! The correct answer was: " . $item['name'] . "</h2>";
                echo "<style>.item-container img {filter: blur(0px); transform: rotate(0deg);}</style>";
                $_SESSION['counter'] = 5;
                $counter = $_SESSION['counter'];
                $_SESSION['icon_guesser_streak'] = 0;
                $game_over = true;
            }
        } else {
            echo "<style>.item-container img {filter: grayscale(100%) blur(10px); transform: rotate(0deg);}</style>";
        }
    }
    echo "<br>Current Streak: " . $_SESSION['icon_guesser_streak'] . " 🔥";
    echo "</div>";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/nav.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Icon guesser</title>
</head>
<body>
    <?php if (!$game_won && !$game_over){?>
    <div class="search-wrapper">
        <form method = "post" class = "guess-form">
            <input type="text" id = 'guess' name = "guess" placeholder="Guess the item name" class = "search-input" >
            <button type="submit" name = "submit-btn" id = 'submit-btn' class = "submit-btn">Submit Guess</button>
        </form>
    </div>
    <div class="search"></div>
    <?php } else { ?>
        <form method="post">
            <button type="submit" name="play-again-btn" class="submit-btn">Play Again</button>
        </form>
    <?php } ?>
</body>
</html>
<script>
document.getElementById('guess').addEventListener('keyup', (e) => {
    var ertek = e.target.value;
    $('#search').load('../../Functions/search_items.php?keresett=' + ertek);
});
</script>