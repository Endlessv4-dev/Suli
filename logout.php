<?php

    setcookie("userid", 0, time(), "/");
    setcookie("adminid", 0, time(), "/");
    header("Location: index.php");

?>