<?php 
    session_start();

    require "../../Connection/config.php";

    if (isset($_POST['reset-btn'])) {
        unset($_SESSION['target_id']);
        unset($_SESSION['guesses']);
        header("Location: hatteres.php");
        exit();
    }

    if (!isset($_SESSION['target_id'])) {
        $rand_query = $conn->query("SELECT id FROM items ORDER BY RAND() LIMIT 1");
        if (mysqli_num_rows($rand_query) > 0) {
            $rand_item = $rand_query->fetch_assoc();
            $_SESSION['target_id'] = $rand_item['id'];
            $_SESSION['guesses'] = [];
        }
    }
    
    $target_id = $_SESSION['target_id'];

    $target_item = $conn->query("SELECT * FROM items WHERE id = $target_id")->fetch_assoc();

    $target_stats_query = $conn->query("SELECT stat_name FROM item_stats WHERE item_id = $target_id");
    $target_stats = [];
    while ($stat = $target_stats_query->fetch_assoc()) {
        $target_stats[] = $stat['stat_name'];
    }

    if (!isset($_SESSION['guesses'])) {
        $_SESSION['guesses'] = [];
    }

    $game_won = in_array($target_id, $_SESSION['guesses']);

    $error_message = "";

    if (isset($_POST['guess-btn']) && !empty($_POST['guessed_item_id']) && !$game_won) {
        $guessed_id = (int)$_POST['guessed_item_id'];
        
        $check_query = $conn->query("SELECT id FROM items WHERE id = $guessed_id");
        if (mysqli_num_rows($check_query) > 0) {
            if (!in_array($guessed_id, $_SESSION['guesses'])) {
                $_SESSION['guesses'][] = $guessed_id;
                
                if ($guessed_id == $target_id) {
                    $game_won = true;
                }
            } else {
                $error_message = "You have already guessed this item!";
            }
        } else {
            $error_message = "Selected item does not exist!";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>

<div class="container">
    <h2>Guess Today's League of Legends Item!</h2>
    
    <?php if ($game_won) { ?>
        <div class="win-box">
            <h3>🎉 GG! You guessed the correct item: <?= htmlspecialchars($target_item['name']); ?>!</h3>
            <form method="POST">
                <button type="submit" name="reset-btn" style="background-color: #2563eb;">Play Again</button>
            </form>
        </div>
    <?php } else { ?>
        <div class="search-wrapper" id="search-wrapper">
            <input type="text" id="search-input" class="search-input" placeholder="Click or type item name..." autocomplete="off">
            
            <div id="dropdown-menu" class="dropdown-menu"></div>
        </div>
        
        <?php if (!empty($error_message)) { ?>
            <p class="error-msg"><?= $error_message; ?></p>
        <?php } ?>
    <?php } ?>

    <?php if (!empty($_SESSION['guesses'])) { ?>
        <table>
            <thead>
                <tr>
                    <th>Icon</th>
                    <th>Name</th>
                    <th>Stats</th> <th>Effect Type</th>
                    <th>Item Tier</th>
                    <th>Cost</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $reversed_guesses = array_reverse($_SESSION['guesses']);
                foreach ($reversed_guesses as $g_id) {
                    $g_item = $conn->query("SELECT * FROM items WHERE id = $g_id")->fetch_assoc();
                    
                    if (!$g_item) {
                        continue;
                    }
                    
                    $g_stats_query = $conn->query("SELECT stat_name FROM item_stats WHERE item_id = $g_id");
                    $g_stats = [];
                    while($st = $g_stats_query->fetch_assoc()) {
                        $g_stats[] = $st['stat_name'];
                    }

                    $matched_stats_count = 0;
                    foreach ($g_stats as $g_stat) {
                        foreach ($target_stats as $t_stat) {
                            if ($g_stat == $t_stat) {
                                $matched_stats_count++;
                            }
                        }
                    }

                    if ($matched_stats_count == count($target_stats) && count($g_stats) == count($target_stats)) {
                        $stats_class = "correct";
                    } else if ($matched_stats_count > 0) {
                        $stats_class = "partial";
                    } else {
                        $stats_class = "incorrect";
                    }

                    $stats_output = "";
                    if (count($g_stats) == 0) {
                        $stats_output = "None";
                    } else {
                        for ($i = 0; $i < count($g_stats); $i++) {
                            $clean_stat_name = ucwords(str_replace('_', ' ', $g_stats[$i]));
                            
                            $stats_output .= $clean_stat_name;
                            if ($i < count($g_stats) - 1) {
                                $stats_output .= ", ";
                            }
                        }
                    }

                    if ($g_item['effect_type'] == $target_item['effect_type']) {
                        $effect_class = "correct";
                    } else {
                        $effect_class = "incorrect";
                    }

                    if ($g_item['tier'] == $target_item['tier']) {
                        $tier_class = "correct";
                    } else {
                        $tier_class = "incorrect";
                    }

                    $cost_arrow = "";
                    if ($g_item['cost'] == $target_item['cost']) {
                        $cost_class = "correct";
                    } else {
                        $cost_class = "incorrect";
                        if ($target_item['cost'] > $g_item['cost']) {
                            $cost_arrow = "<span class='arrow'>↑</span>";
                        } else {
                            $cost_arrow = "<span class='arrow'>↓</span>";
                        }
                    }
                ?>
                    <tr>
                        <td style="background-color: #1f2937;">
                            <img class="item-icon" src="../../Items/<?= $g_item['name']; ?>/<?= $g_item['icon']; ?>" alt="Icon">
                        </td>
                        <td style="background-color: #1f2937;"><?= $g_item['name']; ?></td>
                        
                        <td class="<?= $stats_class; ?>"><?= $stats_output; ?></td>
                        <td class="<?= $effect_class; ?>"><?= $g_item['effect_type']; ?></td>
                        <td class="<?= $tier_class; ?>"><?= $g_item['tier']; ?></td>
                        <td class="<?= $cost_class; ?>">
                            <?= $g_item['cost']; ?>
                            <?= $cost_arrow; ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } ?>
</div>

<script>
    $('#search-input').on('focus input', function() {
        var value = $(this).val();
        $('#dropdown-menu').load('search_items.php?keresett=' + value);
        
        $('#dropdown-menu').show();
    });

    $(document).click(function(e) {
        if (!$(e.target).closest('#search-wrapper').length) {
            $('#dropdown-menu').hide();
        }
    });
</script>

</body>
</html>