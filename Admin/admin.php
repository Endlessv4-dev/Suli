<?php 

    require "config.php";

    if (!isset($_COOKIE['admin'])) {
        header("Location: a_login.php");
    }

?>