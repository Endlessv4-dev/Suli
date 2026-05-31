<?php
    require "../../Connection/config.php";
    session_start();

    if (!isset($_SESSION['sound_streak'])) {
        $_SESSION['sound_streak'] = 0;
    }

    if (isset($_POST['play-again-btn'])) {
        unset($_SESSION['sound_target_id']);
        header("Location: soundguesser.php");
        exit();
    }

    if (!isset($_SESSION['sound_target_id'])) {
        $lekerdezes = "SELECT * FROM items WHERE audio IS NOT NULL AND audio != '' ORDER BY RAND() LIMIT 1";
        $talalt_sor = $conn->query($lekerdezes);
        if ($talalt_sor && mysqli_num_rows($talalt_sor) > 0) {
            $item = $talalt_sor->fetch_assoc();
            $_SESSION['sound_target_id'] = $item['id'];
        }
    }

    $target_id = $_SESSION['sound_target_id'] ?? 0;
    $item = $conn->query("SELECT * FROM items WHERE id = $target_id")->fetch_assoc();

    $game_won = false;

    echo "<div class='item-container'>";
    echo "<h1>Guess the item!</h1>";

    $game_won = false;

    echo "<div class='item-container'>";
    echo "<h1>Guess the item!</h1>";
    
    if ($item && !empty($item['audio'])) {
        echo "<audio class = 'audio-source' controls><source src='../../Items/" . $item['name'] . "/" . $item['audio'] . "' type='audio/mpeg' >Your browser does not support the audio element.</audio> ";
        
        if (isset($_POST['guess'])) {
            if (strtolower($_POST['guess']) == strtolower($item['name'])) {
                echo "<h2>Correct!</h2>";
                echo "<h2>The answer was: " . $item['name'] . "</h2>";
                $game_won = true;
                $_SESSION['sound_streak']++;
                
                if (isset($_COOKIE['userid'])) {
                    $uid = (int)$_COOKIE['userid'];
                    $current_streak = $_SESSION['sound_streak'];
                    $pb_query = $conn->query("SELECT hang_best FROM profile WHERE id = $uid");
                    if ($pb_query && mysqli_num_rows($pb_query) > 0) {
                        $pb_row = $pb_query->fetch_assoc();
                        if ($current_streak > $pb_row['hang_best']) {
                            $conn->query("UPDATE profile SET hang_best = $current_streak WHERE id = $uid");
                        }
                    }
                }
            } else {
                echo "<h2>Wrong! The correct answer was: " . $item['name'] . "</h2>";
                $_SESSION['sound_streak'] = 0;
            }
        }
    }
    echo "<br>Current Streak: " . $_SESSION['sound_streak'] . " 🔥";
    echo "</div>";  
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/nav.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <br>
    <?php if (!$game_won && !isset($_POST['guess'])){ ?>
    <form method = "post" class = "guess-form">
        <input type="text" name = "guess" placeholder="Guess the item name" class = "guess-input" >
        <button type="button" onclick="this.form.submit()" class = "submit-btn">Submit Guess</button>
    </form>
    <?php } else { ?>
        <form method="post">
            <button type="submit" name="play-again-btn" class="submit-btn">Play Again</button>
        </form>
    <?php } ?>
</body>
</html>