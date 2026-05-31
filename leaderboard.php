<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="css/style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboards</title>
</head>
<body>
    
<div class="container">
    <h1 class="home-logo">Leaderboards</h1>

    <div class="leaderboard-grid">

        <?php
            require "Connection/config.php";

            $lekerdezes = "SELECT * FROM profile ORDER BY bg_least_guess_win ASC LIMIT 5";
            $talalt_bg = $conn->query($lekerdezes);
        ?>
        <div class="top5">
            <div class="top5-form">
                <div class="top5-header">Classic Leaderboard</div>
                <?php
                while($bg = $talalt_bg->fetch_assoc()){
                    $lekerdezes = "SELECT * FROM users WHERE id=$bg[userid]";
                    $talalt_user = $conn->query($lekerdezes);
                    $user = $talalt_user->fetch_assoc();

                    echo "<p class = 'top5-player'>".$user['username'].": ".'<span>'.$bg['bg_least_guess_win']."</span></p>";
                }
                ?>
            </div>
        </div>

        <?php
            $lekerdezes = "SELECT * FROM profile ORDER BY timer_most_streak DESC LIMIT 5";
            $talalt_timer = $conn->query($lekerdezes);
        ?>
        <div class="top5">
            <div class="top5-form">
                <div class="top5-header">Time Attack Leaderboard</div>
                <?php
                while($tr = $talalt_timer->fetch_assoc()){
                    $lekerdezes = "SELECT * FROM users WHERE id=$tr[userid]";
                    $talalt_user = $conn->query($lekerdezes);
                    $user = $talalt_user->fetch_assoc();

                    echo "<p class = 'top5-player'>".$user['username'].": ".'<span>'.$tr['timer_most_streak']."</span></p>";
                }
                ?>
            </div>
        </div>

        <?php
            $lekerdezes = "SELECT * FROM profile ORDER BY stats_least_guess_win ASC LIMIT 5";
            $talalt_stats = $conn->query($lekerdezes);
        ?>
        <div class="top5">
            <div class="top5-form">
                <div class="top5-header">Stats Leaderboard</div>
                <?php
                while($st = $talalt_stats->fetch_assoc()){
                    $lekerdezes = "SELECT * FROM users WHERE id=$st[userid]";
                    $talalt_user = $conn->query($lekerdezes);
                    $user = $talalt_user->fetch_assoc();

                    echo "<p class = 'top5-player'>".$user['username'].": ".'<span>'.$st['stats_least_guess_win']."</span></p>";
                }
                ?>
            </div>
        </div>

        <?php
            $lekerdezes = "SELECT * FROM profile ORDER BY sound_least_guess_win ASC LIMIT 5";
            $talalt_sound = $conn->query($lekerdezes);
        ?>
        <div class="top5">
            <div class="top5-form">
                <div class="top5-header">Sound Leaderboard</div>
                <?php
                while($snd = $talalt_sound->fetch_assoc()){
                    $lekerdezes = "SELECT * FROM users WHERE id=$snd[userid]";
                    $talalt_user = $conn->query($lekerdezes);
                    $user = $talalt_user->fetch_assoc();

                    echo "<p class = 'top5-player'>".$user['username'].": ".'<span>'.$snd['sound_least_guess_win']."</span></p>";
                }
                ?>
            </div>
        </div>

        <?php
            $lekerdezes = "SELECT * FROM profile ORDER BY icon_best_streak ASC LIMIT 5";
            $talalt_icon = $conn->query($lekerdezes);
        ?>
        <div class="top5">
            <div class="top5-form">
                <div class="top5-header">Icon Leaderboard</div>
                <?php
                while($ic = $talalt_icon->fetch_assoc()){
                    $lekerdezes = "SELECT * FROM users WHERE id=$ic[userid]";
                    $talalt_user = $conn->query($lekerdezes);
                    $user = $talalt_user->fetch_assoc();

                    echo "<p class = 'top5-player'>".$user['username'].": ".'<span>'.$ic['icon_best_streak']."</span></p>";
                }
                ?>
            </div>
        </div>

        <?php
            $lekerdezes = "SELECT * FROM profile ORDER BY desc_least_guess_win ASC LIMIT 5";
            $talalt_desc = $conn->query($lekerdezes);
        ?>
        <div class="top5">
            <div class="top5-form">
                <div class="top5-header">Description Leaderboard</div>
                <?php
                while($dc = $talalt_desc->fetch_assoc()){
                    $lekerdezes = "SELECT * FROM users WHERE id=$dc[userid]";
                    $talalt_user = $conn->query($lekerdezes);
                    $user = $talalt_user->fetch_assoc();

                    echo "<p class = 'top5-player'>".$user['username'].": ".'<span>'.$dc['desc_least_guess_win']."</span></p>";
                }
                ?>
            </div>
        </div>

    </div>
</div>

</body>
</html>