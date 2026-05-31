<?php
    session_start();
    require "../../Connection/config.php";
    require "../../Functions/message.php";

    if (!isset($_SESSION['guesses'])) {
        $_SESSION['guesses'] = [];
    }

    if (isset($_POST['reset-btn'])) {
        unset($_SESSION['leiras_target_id']);
        unset($_SESSION['guesses']);
        header("Location: leiras.php");
    }

    if (!isset($_SESSION['leiras_target_id'])) {
        $inquiry = "SELECT * FROM items WHERE description IS NOT NULL AND description != '-' AND description != '' ORDER BY RAND() LIMIT 1";
        $found_items = $conn->query($inquiry);
        if ($found_items && mysqli_num_rows($found_items) > 0) {
            $item = $found_items->fetch_assoc();
            $_SESSION['leiras_target_id'] = $item['id'];
        }
    }

    $target_id = $_SESSION['leiras_target_id'] ?? 0;
    $item = $conn->query("SELECT * FROM items WHERE id = $target_id")->fetch_assoc();

    $personal_best = 0;
    if (isset($_COOKIE['userid'])) {
        $uid = (int)$_COOKIE['userid'];
        $pb_query = $conn->query("SELECT desc_least_guess_win FROM profile WHERE userid = $uid");
        if ($pb_query && mysqli_num_rows($pb_query) > 0) {
            $personal_best = (int)$pb_query->fetch_assoc()['desc_least_guess_win'];
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
            if (!in_array($guessed_id, $_SESSION['guesses'])) {
                $_SESSION['guesses'][] = $guessed_id;
            }

            if ($guessed_id == $target_id) {
                $game_won = true;
                
                if (isset($_COOKIE['userid'])) {
                    $uid = (int)$_COOKIE['userid']; 
                    $current_guesses = count($_SESSION['guesses']);
                    // If no previous best exists (0) or current guess count is less than the previous best record
                    if ($personal_best == 0 || $current_guesses < $personal_best) {
                        $conn->query("UPDATE profile SET desc_least_guess_win = $current_guesses WHERE userid = $uid");
                        $personal_best = $current_guesses;
                    }
                }
            }
        } else {
            $error_message = "Item not found!";
        }
    }

    if (in_array($target_id, $_SESSION['guesses'])) {
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
    <title>Description</title>
</head>
<body>
<?php require "../../Functions/nav.php"; ?>
<div class="container">
    <h2>Guess Today's League of Legends Item!</h2>

    <?php 
        if ($item && !$game_won) {
            echo "<div class='descript'>";
            echo $item['tier']."<br>".$item['description'];
            echo "</div>";
        }
    ?>
    
    <div style="font-size: 18px; color: #9ca3af; margin-bottom: 5px;">
        Number of guesses: <strong style="color: #f59e0b;"><?= count($_SESSION['guesses']); ?></strong>
    </div>
    <?php if(isset($_COOKIE['userid'])){ ?>
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