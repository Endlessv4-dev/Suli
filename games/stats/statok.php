<link rel="stylesheet" href="../../css/style.css">
<?php
    session_start();
    require "../../Connection/config.php";
    require "../../Functions/message.php";

    if (!isset($_SESSION['stats_streak'])) {
        $_SESSION['stats_streak'] = 0;
    }

    if (isset($_POST['play-again-btn'])) {
        unset($_SESSION['stats_target_id']);
        header("Location: statok.php");
        exit();
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
?>
<br><br>
<?php
    if ($item) {
        $type = "";
        $inquiry = "SELECT * FROM item_stats WHERE item_id = " . $item['id'];
        $f_stats = $conn->query($inquiry);
        echo '<div class="descript">';
        while ($stats = $f_stats->fetch_assoc()) {
            if ($stats['type'] == "flat") {
                $type = "";
            } else if ($stats['type'] == "%") {
                $type = "%";
            }
            echo $stats['stat_value'] . " " . $type . " " . ucwords(str_replace('_', ' ', $stats['stat_name'])) . "<br>";
        }
        echo "</div>";
    }

    $game_won = false;
    echo "<div class='descript'>";
    if (isset($_POST['guess']) && $item) {
        if (strtolower($_POST['guess']) == strtolower($item['name'])) {
            echo "Correct! The Item was " . htmlspecialchars($item['name']);
            $game_won = true;
            $_SESSION['stats_streak']++;
            
            if (isset($_COOKIE['userid'])) {
                $uid = (int)$_COOKIE['userid'];
                $current_streak = $_SESSION['stats_streak'];
                $pb_query = $conn->query("SELECT stat_best FROM profile WHERE id = $uid");
                if ($pb_query && mysqli_num_rows($pb_query) > 0) {
                    $pb_row = $pb_query->fetch_assoc();
                    if ($current_streak > $pb_row['stat_best']) {
                        $conn->query("UPDATE profile SET stat_best = $current_streak WHERE id = $uid");
                    }
                }
            }
        } else {
            echo "Incorrect!";
            $_SESSION['stats_streak'] = 0;
        }
    }
    echo "<br>Current Streak: " . $_SESSION['stats_streak'] . " 🔥";
    echo "</div>";
?>

    <?php if (!$game_won && !isset($_POST['guess'])) { ?>
    <form method="post" class='desc-form'>
        <input type="text" name="guess" placeholder="Start Typing...">
        <button type="button" name="guess-btn" onclick="this.form.submit()">Submit</button>
    </form>
    <?php } else { ?>
    <form method="post" style="margin-top: 15px;">
        <button type="submit" name="play-again-btn">Play Again</button>
    </form>
    <?php } ?>
</div>