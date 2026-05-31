<?php

    require "Connection/config.php";

// $lekerdezes = "SELECT * FROM profile WHERE userid = '$_COOKIE[id]' ORDER BY bg_last_guess_win DESC";
$lekerdezes = "SELECT * FROM profile ORDER BY bg_least_guess_win DESC";
$talalt_sorok = $conn->query($lekerdezes);
while($sor = $talalt_sorok->fetch_assoc()){
    echo "<tr><td>".$sor["bg_least_guess_win"]."</td><td>".$sor["timer_most_streak"]."</td><td>".$sor["stats_least_guess_win"]."</td><td>".$sor["sound_least_guess_win"]."</td><td>".$sor["icon_least_guess_win"]."</td></tr>"."<tr><td>".$sor["desc_least_guess_win"]."</td><td>";
}


?>