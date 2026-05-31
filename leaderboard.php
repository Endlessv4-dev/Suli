<?php

    require "Connection/config.php";

    $lekerdezes = "SELECT * FROM profile ORDER BY bg_least_guess_win ASC LIMIT 5";
    $talalt_bg = $conn->query($lekerdezes);
?>
<table class = "top5">
    <th>Classic Leaderboard</th>
<?php
    while($bg = $talalt_bg->fetch_assoc()){
        $lekerdezes = "SELECT * FROM users WHERE id=$bg[userid]";
        $talalt_user = $conn->query($lekerdezes);
        $user = $talalt_user->fetch_assoc();

        echo "<tr><td>".$user['username']."<span>".$bg['bg_least_guess_win']."</span></tr></td>";
        
    }
?>
</table>

<!-- -------------------------------------- -->
<?php
    $lekerdezes = "SELECT * FROM profile ORDER BY timer_most_streak DESC LIMIT 5";
    $talalt_timer = $conn->query($lekerdezes);
?>
<table class = "top5">
    <th>Time Attack Leaderboard</th>
<?php
    while($tr = $talalt_timer->fetch_assoc()){
        $lekerdezes = "SELECT * FROM users WHERE id=$tr[userid]";
        $talalt_user = $conn->query($lekerdezes);
        $user = $talalt_user->fetch_assoc();

        echo "<tr><td>".$user['username']."<span>".$tr['timer_most_streak']."</span></tr></td>";
        
    }
?>
</table>

<!-- -------------------------------------- -->
<?php
    $lekerdezes = "SELECT * FROM profile ORDER BY stats_least_guess_win ASC LIMIT 5";
    $talalt_stats = $conn->query($lekerdezes);
?>
<table class = "top5">
    <th>Stats Leaderboard</th>
<?php
    while($st = $talalt_stats->fetch_assoc()){
        $lekerdezes = "SELECT * FROM users WHERE id=$st[userid]";
        $talalt_user = $conn->query($lekerdezes);
        $user = $talalt_user->fetch_assoc();

        echo "<tr><td>".$user['username']."<span>".$st['stats_least_guess_win']."</span></tr></td>";
        
    }
?>
</table>

<!-- -------------------------------------- -->
<?php
    $lekerdezes = "SELECT * FROM profile ORDER BY sound_least_guess_win ASC LIMIT 5";
    $talalt_sound = $conn->query($lekerdezes);
?>
<table class = "top5">
    <th>Sound Leaderboard</th>
<?php
    while($snd = $talalt_sound->fetch_assoc()){
        $lekerdezes = "SELECT * FROM users WHERE id=$snd[userid]";
        $talalt_user = $conn->query($lekerdezes);
        $user = $talalt_user->fetch_assoc();

        echo "<tr><td>".$user['username']."<span>".$snd['sound_least_guess_win']."</span></tr></td>";
        
    }
?>
</table>

<!-- -------------------------------------- -->
<?php
    $lekerdezes = "SELECT * FROM profile ORDER BY icon_least_guess_win ASC LIMIT 5";
    $talalt_icon = $conn->query($lekerdezes);
?>
<table class = "top5">
    <th>Icon Leaderboard</th>
<?php
    while($ic = $talalt_icon->fetch_assoc()){
        $lekerdezes = "SELECT * FROM users WHERE id=$ic[userid]";
        $talalt_user = $conn->query($lekerdezes);
        $user = $talalt_user->fetch_assoc();

        echo "<tr><td>".$user['username']."<span>".$ic['icon_least_guess_win']."</span></tr></td>";
        
    }
?>
</table>

<!-- -------------------------------------- -->
<?php
    $lekerdezes = "SELECT * FROM profile ORDER BY desc_least_guess_win ASC LIMIT 5";
    $talalt_desc = $conn->query($lekerdezes);
?>
<table class = "top5">
    <th>Description Leaderboard</th>
<?php
    while($dc = $talalt_desc->fetch_assoc()){
        $lekerdezes = "SELECT * FROM users WHERE id=$dc[userid]";
        $talalt_user = $conn->query($lekerdezes);
        $user = $talalt_user->fetch_assoc();

        echo "<tr><td>".$user['username']."<span>".$dc['icon_least_guess_win']."</span></tr></td>";
        
    }
?>
</table>