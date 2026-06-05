<?php 
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guess The Lol</title>
    <link rel="stylesheet" href="css/style.css"> 
</head>
<body>

    <div class="top-right-auth">
        <?php if(isset($_COOKIE['userid'])){ ?>
            <a href="logout.php" class="reglog" style="background-color: #b91c1c;">Logout</a>
        <?php } else { ?>
            <a href="reglog.php" class="reglog" style="background-color: #2563eb;">Login / Sign Up</a>
        <?php } ?>
    </div>

    <div class="container">
        <div class="home-logo">Guess The Lol</div>
        
        <p style="margin-bottom: 30px; color: #9ca3af;">Select a game mode to start playing.</p>

        <div class="game-grid">
            <a href="./games/background/hatteres.php" class="game-card">Background</a>
            <a href="./games/description/leiras.php" class="game-card">Description</a>
            <a href="./games/iconguesser/iconguesser.php" class="game-card">Icon</a>
            <a href="./games/soundguesser/soundguesser.php" class="game-card">Sound</a>
            <a href="./games/stats/statok.php" class="game-card">Stats</a>
            <a href="./games/timer/idore.php" class="game-card">Time Attack</a>
            <a href="./leaderboard.php" class="game-card">Leaderboard</a>
            <?php 
            if(isset($_COOKIE["adminid"])){?>
                <a href="./Admin/admin.php" class="game-card" style="color: black; background-color: #ff6060;">Admin</a>
            <?php } ?>
        </div>
    </div>

</body>
</html>