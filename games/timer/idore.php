<?php 
    session_start();

    require "../../Connection/config.php";

    if (isset($_POST['reset-btn'])) {
        unset($_SESSION['icon_target_id']);
        unset($_SESSION['icon_game_status']);
        unset($_SESSION['icon_streak']);
        unset($_SESSION['icon_guessed_pool']);
        unset($_SESSION['gameover_reason']);
        unset($_SESSION['last_wrong_guess']);
        header("Location: idore.php");
    }

    if (!isset($_SESSION['icon_streak'])) {
        $_SESSION['icon_streak'] = 0;
    }
    if (!isset($_SESSION['icon_guessed_pool'])) {
        $_SESSION['icon_guessed_pool'] = [];
    }
    if (!isset($_SESSION['icon_game_status'])) {
        $_SESSION['icon_game_status'] = 'playing';
    }

    if ($_SESSION['icon_game_status'] === 'playing' && !isset($_SESSION['icon_target_id'])) {
        $exclude_clause = "";
        if (!empty($_SESSION['icon_guessed_pool'])) {
            $pool_ids = array_map('intval', $_SESSION['icon_guessed_pool']);
            $exclude_clause = "WHERE id NOT IN (" . implode(',', $pool_ids) . ")";
        }

        $rand_query = $conn->query("SELECT id FROM items $exclude_clause ORDER BY RAND() LIMIT 1");

        if (!$rand_query || mysqli_num_rows($rand_query) == 0) {
            $_SESSION['icon_guessed_pool'] = []; 
            $rand_query = $conn->query("SELECT id FROM items ORDER BY RAND() LIMIT 1");
        }

        if ($rand_query && mysqli_num_rows($rand_query) > 0) {
            $rand_item = $rand_query->fetch_assoc();
            $_SESSION['icon_target_id'] = $rand_item['id'];
        }
    }
    
    $target_id = $_SESSION['icon_target_id'] ?? 0;
    $target_item = $target_id ? $conn->query("SELECT * FROM items WHERE id = $target_id")->fetch_assoc() : null;

    $personal_best = 0;
    if (isset($_COOKIE['userid'])) {
        $uid = (int)$_COOKIE['userid'];
        $pb_query = $conn->query("SELECT timer_most_streak FROM profile WHERE userid = $uid");
        if ($pb_query && mysqli_num_rows($pb_query) > 0) {
            $pb_row = $pb_query->fetch_assoc();
            $personal_best = (int)$pb_row['timer_most_streak'];
        }
    }

    if ($_SESSION['icon_game_status'] === 'playing' && $target_id) {
        if (isset($_POST['guess-btn']) && !empty($_POST['guessed_item_id'])) {
            $guessed_id = (int)$_POST['guessed_item_id'];

            if ($guessed_id == $target_id) {
                $_SESSION['icon_streak']++;
                $_SESSION['icon_guessed_pool'][] = $target_id;
                
                if (isset($_COOKIE['userid'])) {
                    $current_streak = $_SESSION['icon_streak'];
                    if ($current_streak > $personal_best) {
                        $conn->query("UPDATE profile SET timer_most_streak = $current_streak WHERE userid = $uid");
                        $personal_best = $current_streak;
                    }
                }
                
                unset($_SESSION['icon_target_id']);
                header("Location: idore.php");
                exit();
            } else {
                $guess_item_query = $conn->query("SELECT name FROM items WHERE id = $guessed_id");
                $g_item = $guess_item_query->fetch_assoc();
                
                $_SESSION['icon_game_status'] = 'gameover';
                $_SESSION['gameover_reason'] = 'wrong';
                $_SESSION['last_wrong_guess'] = $g_item['name'] ?? 'Unknown Item';

                if (isset($_COOKIE['userid'])) {
                    $current_streak = $_SESSION['icon_streak'];
                    if ($current_streak > $personal_best) {
                        $conn->query("UPDATE profile SET timer_most_streak = $current_streak WHERE userid = $uid");
                        $personal_best = $current_streak;
                    }
                }

                header("Location: idore.php");
                exit();
            }
        }

        if (isset($_POST['timeout-trigger'])) {
            $_SESSION['icon_game_status'] = 'gameover';
            $_SESSION['gameover_reason'] = 'timeout';

            if (isset($_COOKIE['userid'])) {
                $current_streak = $_SESSION['icon_streak'];
                if ($current_streak > $personal_best) {
                    $conn->query("UPDATE profile SET timer_most_streak = $current_streak WHERE userid = $uid");
                    $personal_best = $current_streak;
                }
            }

            header("Location: idore.php");
            exit();
        }
    }

    $game_status = $_SESSION['icon_game_status'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="../../css/style.css">
    <title>LoLdle - Icon Time Attack</title>
</head>
<body>
<?php require "../../Functions/nav.php"; ?>
<div class="container">
    <h2>Guess Today's League of Legends Item!</h2>
    
    <div class="item-container">
        <?php if ($game_status === 'playing' && $target_item) { ?>
            <div id="countdown-timer" style="font-size: 38px; font-weight: bold; color: #ef4444; margin: 10px 0;">10</div>
            <img class="icon-img" draggable="false" id="item-image" src="../../Items/<?= htmlspecialchars($target_item['name']); ?>/<?= htmlspecialchars($target_item['icon']); ?>" style="width: 115px; height: 115px; border-radius: 8px; border: 3px solid #4b5563; object-fit: cover; box-shadow: 0 4px 10px rgba(0,0,0,0.5);">
        <?php } else if ($target_item) { ?>
            <img class="icon-img" draggable="false" id="item-image" src="../../Items/<?= htmlspecialchars($target_item['name']); ?>/<?= htmlspecialchars($target_item['icon']); ?>" style="width: 115px; height: 115px; border-radius: 8px; border: 3px solid #4b5563; object-fit: cover; box-shadow: 0 4px 10px rgba(0,0,0,0.5);">
        <?php } ?>
        <br>Current Streak: <?= $_SESSION['icon_streak']; ?> 🔥
    </div>

    <?php if (isset($_COOKIE['userid'])) { ?>
        <div class="pb-display">Your Best Streak: <?= $personal_best; ?> 🔥</div>
    <?php } else { ?>
        <div class="pb-display" style="color: #6b7280;">Log in to save your best score!</div>
    <?php } ?>
    
    <?php if ($game_status === 'gameover') { ?>
        <div class="win-box" style="background-color: #dc2626; border: 2px solid #ef4444;">
            <?php if ($_SESSION['gameover_reason'] === 'wrong') { ?>
                <h3>❌ Game Over: Wrong Answer!</h3>
                <p>You guessed: <strong><?= htmlspecialchars($_SESSION['last_wrong_guess'] ?? 'Unknown'); ?></strong></p>
            <?php } else { ?>
                <h3>⏰ Game Over: Time's Up!</h3>
            <?php } ?>
            
            <p style="margin: 10px 0;">Final Streak Achieved: <strong><?= $_SESSION['icon_streak']; ?></strong> correct guesses!</p>
            <p style="margin-bottom: 15px;">The correct answer was: <strong><?= htmlspecialchars($target_item['name'] ?? ''); ?></strong></p>
            
            <form method="POST">
                <button type="submit" name="reset-btn">Play Again</button>
            </form>
        </div>
    <?php } else { ?>
        <input type="text" id="searchbox" placeholder="Click or type item name..." autocomplete="off" autofocus>
        <div id="names"></div>
    <?php } ?>
</div>

<script>
    <?php if ($game_status === 'playing') { ?>
        var timeLeft = 10;
        var countdownElement = $('#countdown-timer');

        var countdownInterval = setInterval(function() {
            timeLeft--;
            countdownElement.text(timeLeft);

            if (timeLeft <= 0) {
                clearInterval(countdownInterval);
                var form = $('<form method="POST"></form>');
                form.append('<input type="hidden" name="timeout-trigger" value="1">');
                $('body').append(form);
                form.submit();
            }
        }, 1000);
    <?php } ?>

    document.getElementById('searchbox').addEventListener('keyup', (e) => {
        var value = e.target.value;
        $('#names').load("../../Functions/search_items.php?typed=" + encodeURIComponent(value));
    });
</script>
</body>
</html>