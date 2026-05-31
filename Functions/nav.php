<nav>
    <ul>
        <li><a href="../background/hatteres.php" class='nav-item'>Classic</a></li>
        <li><a href="../description/leiras.php" class='nav-item'>Description</a></li>
        <li><a href="../iconguesser/iconguesser.php" class='nav-item'>Icon</a></li>
        <li><a href="../soundguesser/soundguesser.php" class='nav-item'>Sound</a></li>
        <li><a href="../stats/statok.php" class='nav-item'>Stats</a></li>
        <li><a href="../timer/idore.php" class='nav-item'>Time Attack</a></li>
        <?php if(isset($_COOKIE['userid'])){ ?>
            <li><a class='reglog' href="../logout.php" style="color: #ff0000;">Logout</a></li>
        <?php } else { ?>
            <li><a class='reglog' href="../reglog.php" style="color: '#008000';">Login</a></li>
        <?php } ?>
        <?php
        
            // require "../Connection/config.php";

            // $inquiry = "SELECT * FROM users WHERE id=$_COOKIE[id]";
            // $found = $conn->query($inquiry);
            // $user = $found->fetch_assoc();

            // if($user['admin'] == 1){
            ?>
                <!-- <li><a class='reglog' href="../Admin/a_login.php">Admin Page</a></li> -->
            <?php
            // }
            ?>
    </ul>
</nav>