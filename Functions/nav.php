<nav>
    <ul>
        <li><a href="../background/hatteres.php" class='nav-item'>Background</a></li>
        <li><a href="../description/leiras.php" class='nav-item'>Description</a></li>
        <li><a href="../iconguesser/iconguesser.php" class='nav-item'>Icon</a></li>
        <li><a href="../soundguesser/soundguesser.php" class='nav-item'>Sound</a></li>
        <li><a href="../stats/statok.php" class='nav-item'>Stats</a></li>
        <li><a href="../timer/idore.php" class='nav-item'>Timer</a></li>
        <?php if(isset($_COOKIE['userid'])){ ?>
            <li><a class='reglog' href="../logout.php">Logout</a></li>
        <?php } else { ?>
            <li><a class='reglog' href="../reglog.php">Login</a></li>
        <?php } ?>
    </ul>
</nav>