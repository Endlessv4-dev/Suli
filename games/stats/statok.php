<?php
    session_start();
    require "../../Connection/config.php";
    require "../../Functions/message.php";

    if (!isset($_SESSION['stats_guesses'])) {
        $_SESSION['stats_guesses'] = [];
    }

    if (isset($_POST['reset-btn'])) {
        unset($_SESSION['stats_target_id']);
        unset($_SESSION['stats_guesses']);
        header("Location: statok.php");
    }

    if (!isset($_SESSION['stats_target_id'])) {
        $inquiry = "SELECT * FROM items ORDER BY RAND() LIMIT 1";
        $f_item = $conn->query($inquiry);
        if ($f_item && mysqli_num_rows($f_item) > 0) {
            $item = $f_item->fetch_assoc();
            $_SESSION['stats_target_id'] = $item['id'];
        }
    }

    $target_id = $_SESSION['stats_target_id'] ?? 0;
    $item = $conn->query("SELECT * FROM items WHERE id = $target_id")->fetch_assoc();

    $personal_best = 0;
    if (isset($_COOKIE['userid'])) {
        $uid = (int)$_COOKIE['userid'];
        $pb_query = $conn->query("SELECT stats_least_guess_win FROM profile WHERE userid = $uid");
        if ($pb_query && mysqli_num_rows($pb_query) > 0) {
            $personal_best = (int)$pb_query->fetch_assoc()['stats_least_guess_win'];
        }
    }

    $game_won = false;
    $guess_checked = false;
    $error_message = "";

    if ($item && (isset($_POST['guess']) || isset($_POST['guessed_item_id']))) {
        $guess_checked = true;
        $guessed_id = 0;
        $guessed_name = "";

        if (isset($_POST['guessed_item_id'])) {
            $guessed_id = (int)$_POST['guessed_item_id'];
            $guess_query = $conn->query("SELECT name FROM items WHERE id = $guessed_id");
            if ($guess_query && $guess_query->num_rows > 0) {
                $guessed_name = $guess_query->fetch_assoc()['name'];
            }
        } else if (isset($_POST['guess']) && trim($_POST['guess']) !== '') {
            $guessed_name = trim($_POST['guess']);
            $guess_query = $conn->query("SELECT id FROM items WHERE name = '" . $conn->real_escape_string($guessed_name) . "'");
            if ($guess_query && $guess_query->num_rows > 0) {
                $guessed_id = (int)$guess_query->fetch_assoc()['id'];
            }
        }

        if ($guessed_id > 0) {
            if (!in_array($guessed_id, $_SESSION['stats_guesses'])) {
                $_SESSION['stats_guesses'][] = $guessed_id;
            }

            if ($guessed_id == $target_id) {
                $game_won = true;
                
                if (isset($_COOKIE['userid'])) {
                    $uid = (int)$_COOKIE['userid']; 
                    $current_guesses = count($_SESSION['stats_guesses']);
                    if ($personal_best == 0 || $current_guesses < $personal_best) {
                        $conn->query("UPDATE profile SET stats_least_guess_win = $current_guesses WHERE userid = $uid");
                        $personal_best = $current_guesses;
                    }
                }
            }
        } else {
            $error_message = "Item not found!";
        }
    }

    if (in_array($target_id, $_SESSION['stats_guesses'])) {
        $game_won = true;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="../../css/style.css">
    <title>Stats Guesser</title>
</head>
<body>
<?php require "../../Functions/nav.php"; ?>
<div class="container">
    <h2>Guess Today's League of Legends Item by Stats!</h2>

    <?php 
        if ($item && !$game_won) {
            $type = "";
            $inquiry = "SELECT * FROM item_stats WHERE item_id = " . $item['id'];
            $f_stats = $conn->query($inquiry);
            
            echo "<div class='descript'>";
            while ($stats = $f_stats->fetch_assoc()) {
                if ($stats['type'] == "flat") {
                    $type = "";
                } else if ($stats['type'] == "%") {
                    $type = "%";
                }
                echo htmlspecialchars($stats['stat_value'] . " " . $type . " " . ucwords(str_replace('_', ' ', $stats['stat_name']))) . "<br>";
            }
            echo "</div>";
        }
    ?>
    
    <div style="font-size: 18px; color: #9ca3af; margin-bottom: 5px;">
        Number of guesses: <strong style="color: #f59e0b;"><?= count($_SESSION['stats_guesses']); ?></strong>
    </div>
    
    <?php if (isset($_COOKIE['userid'])) { ?>
        <div class="pb-display">Your Best: <?= $personal_best > 0 ? $personal_best . ' guesses' : 'None yet'; ?></div>
    <?php } else { ?>
        <div class="pb-display" style="color: #6b7280;">Log in to save your best score!</div>
    <?php } ?>
    
    <?php if ($game_won) { ?>
        <div class="win-box">
            <h3>🎉 GG! You guessed the correct item: <?= htmlspecialchars($item['name']); ?>!</h3>
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
        $('#names').load("../../Functions/search_items.php?typed=" + encodeURIComponent(value));
    });
</script>
</body>
</html>