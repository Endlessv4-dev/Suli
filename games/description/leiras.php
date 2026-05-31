<?php
    session_start();
    require "../../Connection/config.php";
    require "../../Functions/message.php";

    if (!isset($_SESSION['leiras_streak'])) {
        $_SESSION['leiras_streak'] = 0;
    }

    if (isset($_POST['play-again-btn'])) {
        unset($_SESSION['leiras_target_id']);
        header("Location: leiras.php");
        exit();
    }

    if (!isset($_SESSION['leiras_target_id'])) {
        $inquiry = "SELECT * FROM items WHERE description != '-' ORDER BY RAND() LIMIT 1";
        $found_items = $conn->query($inquiry);
        if ($found_items && mysqli_num_rows($found_items) > 0) {
            $item = $found_items->fetch_assoc();
            $_SESSION['leiras_target_id'] = $item['id'];
        }
    }

    $target_id = $_SESSION['leiras_target_id'] ?? 0;
    $item = $conn->query("SELECT * FROM items WHERE id = $target_id")->fetch_assoc();

    $game_won = false;
    $guess_checked = false;
    $guess_correct = false;

    if (isset($_POST['guess']) && $item) {
        $guess_checked = true;
        if (strtolower($_POST['guess']) == strtolower($item['name'])) {
            $guess_correct = true;
            $game_won = true;
            $_SESSION['leiras_streak']++;
            
            if (isset($_COOKIE['userid'])) {
                $uid = (int)$_COOKIE['userid'];
                $current_streak = $_SESSION['leiras_streak'];
                $pb_query = $conn->query("SELECT leiras_best FROM profile WHERE id = $uid");
                if ($pb_query && mysqli_num_rows($pb_query) > 0) {
                    $pb_row = $pb_query->fetch_assoc();
                    if ($current_streak > $pb_row['leiras_best']) {
                        $conn->query("UPDATE profile SET leiras_best = $current_streak WHERE id = $uid");
                    }
                }
            }
        } else {
            $_SESSION['leiras_streak'] = 0;
        }
    }
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
    
    if ($item) {
        echo "<div class='descript'>";
        echo "  <br>".$item['tier'].$item['description'];
        echo "</div>";
    }

    echo "<div class='descript'>";
    if ($guess_checked) {
        if ($guess_correct) {
            echo "Correct!<br> The item was " . htmlspecialchars($item['name']);
        } else {
            echo "Incorrect, try again!";
        }
    }
    echo "<br>Current Streak: " . $_SESSION['leiras_streak'] . " 🔥";
    echo "</div>";
?>

    <?php if (!$game_won) { ?>
    <form method="post" class='desc-form'>
        <input type="text" name="guess" placeholder="Start Typing...">
        <button type="button" name="guess-btn" onclick="this.form.submit()">Submit</button>
    </form>
    <?php } else { ?>
    <form method="post" style="margin-top: 15px;">
        <button type="submit" name="play-again-btn">Play Again</button>
    </form>
    <?php } ?>
</body>
</html>