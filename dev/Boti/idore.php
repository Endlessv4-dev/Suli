<?php 
    session_start();

    require "../Connection/config.php";

    if (isset($_POST['reset-btn'])) {
        unset($_SESSION['icon_target_id']);
        unset($_SESSION['icon_game_status']);
        unset($_SESSION['icon_streak']);
        unset($_SESSION['icon_guessed_pool']);
        unset($_SESSION['gameover_reason']);
        unset($_SESSION['last_wrong_guess']);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
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

    if ($_SESSION['icon_game_status'] === 'playing' && $target_id) {
        
        if (isset($_POST['guess-btn']) && !empty($_POST['guessed_item_id'])) {
            $guessed_id = (int)$_POST['guessed_item_id'];

            if ($guessed_id == $target_id) {
                $_SESSION['icon_streak']++;
                $_SESSION['icon_guessed_pool'][] = $target_id;
                unset($_SESSION['icon_target_id']);
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            } else {
                $guess_item_query = $conn->query("SELECT name FROM items WHERE id = $guessed_id");
                $g_item = $guess_item_query->fetch_assoc();
                
                $_SESSION['icon_game_status'] = 'gameover';
                $_SESSION['gameover_reason'] = 'wrong';
                $_SESSION['last_wrong_guess'] = $g_item['name'] ?? 'Unknown Item';
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            }
        }

        if (isset($_POST['timeout-trigger'])) {
            $_SESSION['icon_game_status'] = 'gameover';
            $_SESSION['gameover_reason'] = 'timeout';
            header("Location: " . $_SERVER['PHP_SELF']);
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
    <title>LoLdle - Icon Time Attack</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="css/styles.css">
    
    <style>
        #dropdown-menu img {
            display: none !important;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Guess the Item in 5 Seconds!</h2>
    
    <div style="font-size: 22px; font-weight: bold; margin-bottom: 15px; color: #34d399;">
        Current Streak: <?= $_SESSION['icon_streak']; ?> 🔥
    </div>
    
    <?php if ($game_status === 'playing' && $target_item) { ?>
        
        <div id="countdown-timer" style="font-size: 38px; font-weight: bold; color: #ef4444; margin: 10px 0;">5</div>

        <div style="margin-bottom: 25px;">
            <img src="../Items/<?= htmlspecialchars($target_item['name']); ?>/<?= htmlspecialchars($target_item['icon']); ?>" 
                 style="width: 115px; height: 115px; border-radius: 8px; border: 3px solid #4b5563; object-fit: cover; box-shadow: 0 4px 10px rgba(0,0,0,0.5);">
        </div>

        <div class="search-wrapper" id="search-wrapper">
            <input type="text" id="search-input" class="search-input" placeholder="Type item name..." autocomplete="off" autofocus>
            <div id="dropdown-menu" class="dropdown-menu"></div>
        </div>

    <?php } else { ?>
        
        <div class="win-box" style="background-color: #991b1b; border-color: #ef4444;">
            <?php if ($_SESSION['gameover_reason'] === 'lost_wrong' || $_SESSION['gameover_reason'] === 'wrong') { ?>
                <h3>❌ Game Over: Wrong Answer!</h3>
                <p>You guessed: <strong><?= htmlspecialchars($_SESSION['last_wrong_guess'] ?? 'Unknown'); ?></strong></p>
            <?php } else { ?>
                <h3>⏰ Game Over: Time's Up!</h3>
            <?php } ?>
            
            <p style="margin: 10px 0;">Final Streak Achieved: <strong><?= $_SESSION['icon_streak']; ?></strong> correct guesses!</p>
            <p style="margin-bottom: 15px;">The correct answer was: <strong><?= htmlspecialchars($target_item['name'] ?? ''); ?></strong></p>
            
            <?php if ($target_item) { ?>
                <img src="../Items/<?= htmlspecialchars($target_item['name']); ?>/<?= htmlspecialchars($target_item['icon']); ?>" style="width: 70px; height: 70px; border-radius: 6px; object-fit: cover; margin-bottom: 15px; border: 2px solid white;">
            <?php } ?>

            <form method="POST">
                <button type="submit" name="reset-btn" style="background-color: #2563eb; margin: 5px 0 0 0;">Try Again</button>
            </form>
        </div>

    <?php } ?>
</div>

<script>
    <?php if ($game_status === 'playing') { ?>
        var timeLeft = 5;
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

    $('#search-input').on('input', function() {
        var value = $(this).val().trim();
        if (value.length >= 1) {
            $('#dropdown-menu').load('search_items.php?keresett=' + value);
            $('#dropdown-menu').show();
        } else {
            $('#dropdown-menu').hide().empty();
        }
    });

    $('#search-input').on('focus', function() {
        var value = $(this).val().trim();
        if (value.length >= 1) {
            $('#dropdown-menu').show();
        }
    });

    $(document).click(function(e) {
        if (!$(e.target).closest('#search-wrapper').length) {
            $('#dropdown-menu').hide();
        }
    });
</script>

</body>
</html>