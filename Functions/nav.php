<nav>
    <ul>
        <li><a href="../../index.php" class='nav-item' style="color: #4d669b;">Home</a></li>
        <li><a href="../background/hatteres.php" class='nav-item'>Classic</a></li>
        <li><a href="../description/leiras.php" class='nav-item'>Description</a></li>
        <li><a href="../iconguesser/iconguesser.php" class='nav-item'>Icon</a></li>
        <li><a href="../soundguesser/soundguesser.php" class='nav-item'>Sound</a></li>
        <li><a href="../stats/statok.php" class='nav-item'>Stats</a></li>
        <li><a href="../timer/idore.php" class='nav-item'>Time Attack</a></li>
        <?php if(isset($_COOKIE['userid'])){ ?>
            <li><a class='reglog' href="../../logout.php" style="color: #ff0000;">Logout</a></li>
            <?php if($_COOKIE['adminid'] == 1){ ?>
                <li><a class='admin' href="../../Admin/admin.php" style="color: #ff6060;">Admin</a></li>  
            <?php } ?>
        <?php } else { ?>
            <li><a class='reglog' href="../../reglog.php" style="color: #008000">Login</a></li>
        <?php } ?>
    </ul>
</nav>