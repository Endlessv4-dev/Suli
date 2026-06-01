<?php
    require "../../Connection/config.php";
    session_start();

    if (!isset($_SESSION['icon_guesser_streak'])) {
        $_SESSION['icon_guesser_streak'] = 0;
    }

    if (isset($_POST['reset-btn'])) {
        unset($_SESSION['icon_guesser_target_id']);
        $_SESSION['counter'] = 5;
        header("Location: iconguesser.php");
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

    $game_won = false;
    $game_over = false;
    $error_message = "";

    $personal_best = 0;
    if (isset($_COOKIE['userid'])) {
        $uid = (int)$_COOKIE['userid'];
        $pb_query = $conn->query("SELECT icon_best_streak FROM profile WHERE userid = $uid");
        if ($pb_query && mysqli_num_rows($pb_query) > 0) {
            $pb_row = $pb_query->fetch_assoc();
            $personal_best = (int)$pb_row['icon_best_streak'];
        }
    }

    if (isset($_POST['guess-btn'])) {
        $guesses_made = 5 - $_SESSION['counter'] + 1;
        $_SESSION['counter']--;
        $counter = $_SESSION['counter'];
    } else {
        $counter = $_SESSION['counter'];
        $guesses_made = 5 - $counter;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../../css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Icon guesser</title>
</head>
<body>
<?php require "../../Functions/nav.php"; ?>

<div class="container">
    <h2>Guess Today's League of Legends Item!</h2>

    <?php 
    echo "<div class='item-container'>";
    
    if ($item) {
        ?>
        <img class='icon-img' draggable='false' id='item-image' src="../../Items/<?= $item['name']; ?>/<?= $item['icon']; ?>"><br>
<?php
        if (isset($_POST['guess-btn'])) {
            if ((int)$_POST['guessed_item_id'] === (int)$item['id']) {
                echo "<h2>Correct!</h2>";
                echo "<style>.item-container img {filter: grayscale(0%) blur(0px); transform: rotate(0deg);}</style>";
                $game_won = true;
                $_SESSION['icon_guesser_streak']++;
                
                if (isset($_COOKIE['userid'])) {
                    $current_streak = $_SESSION['icon_guesser_streak'];
                    if ($current_streak > $personal_best) {
                        $conn->query("UPDATE profile SET icon_best_streak = $current_streak WHERE userid = $uid");
                        $personal_best = $current_streak;
                    }
                }
            } else if ($counter == 4) {
                echo "<h2>Wrong!</h2>";
                echo "<style>.item-container img {filter: grayscale(75%) blur(10px); transform: rotate(90deg);}</style>";
            } else if ($counter == 3) {
                echo "<h2>Wrong!</h2>";
                echo "<style>.item-container img {filter: grayscale(50%) blur(8px); transform: rotate(180deg);}</style>";
            } else if ($counter == 2) {
                echo "<h2>Wrong!</h2>";
                echo "<style>.item-container img {filter: grayscale(25%) blur(6px); transform: rotate(270deg);}</style>";
            } else if ($counter == 1) {
                echo "<h2>Wrong!</h2>";
                echo "<style>.item-container img {filter: grayscale(0%) blur(4px); transform: rotate(90deg);}</style>";
            } else {
                echo "<h2>Wrong! The correct answer was: " . htmlspecialchars($item['name']) . "</h2>";
                echo "<style>.item-container img {filter: blur(0px); transform: rotate(0deg);}</style>";
                $_SESSION['counter'] = 5;
                $_SESSION['icon_guesser_streak'] = 0;
                $game_over = true;
            }
        } else {
            echo "<style>.item-container img {filter: grayscale(100%) blur(10px); transform: rotate(90deg);}</style>";
        }
    }
    echo "<br>Current Streak: " . $_SESSION['icon_guesser_streak'] . " 🔥";
    echo "</div>";
    ?>
    
    <div style="font-size: 18px; color: #9ca3af; margin-bottom: 5px;">
        Number of guesses: <strong style="color: #f59e0b;"><?= $guesses_made; ?> / 5</strong>
    </div>

    <?php if(isset($_COOKIE['userid'])){ ?>
        <div class="pb-display">Your Best Streak: <?= $personal_best; ?> 🔥</div>
    <?php } else { ?>
        <div class="pb-display" style="color: #6b7280;">Log in to save your best score!</div>
    <?php } ?>
    
    <?php if ($game_won || $game_over) { ?>
        <div class="win-box" style="background-color: <?= $game_won ? '#16a34a' : '#dc2626'; ?>; border: 2px solid <?= $game_won ? '#22c55e' : '#ef4444'; ?>;">
            <h3><?= $game_won ? "🎉 GG! You guessed the correct item: " : "❌ Game Over! The item was: "; ?><?= htmlspecialchars($item['name']); ?>!</h3>
            <form method="POST">
                <button type="submit" name="reset-btn">Play Again</button>
            </form>
        </div>
    <?php } else { ?>
        <input type="text" id="searchbox" placeholder="Click or type item name..." autocomplete="off" autofocus>
        <div id="names"></div>
        
        <?php if (!empty($error_message)) { ?>
            <p class="error-msg"><?= $error_message; ?></p>
        <?php } ?>
    <?php } ?>
</div>

<script>
    document.getElementById('searchbox').addEventListener('keyup', (e) => {
        var value = e.target.value;
        $('#names').load("../../Functions/search_items.php?typed=" + value);
    });
</script>
</body>
</html>