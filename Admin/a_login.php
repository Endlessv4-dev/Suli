<?php 

    require "../Connection/config.php";
    require "../Functions/message.php";

    if (isset($_POST['login-btn'])) {
        $stmt = "SELECT * FROM users WHERE username = '$_POST[username]' AND admin = 1";
        $result = $conn->query($stmt);

        if (mysqli_num_rows($result) == 1) {
            $user = $result->fetch_assoc();

            if (password_verify($_POST['password'], $user['password'])) {
                setcookie("admin", $user['id'], time() + 3600, "/");
                header("Location: admin.php");
            }
            else {
                Error("Wrong password.");
            }
        }
        else {
            Error("This user is not an admin.");
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
</head>
<body>
    <form method="post">
        <input type="text" name="username" placeholder="Username">
        <input type="password" name="password" placeholder="Password">
        <input type="submit" name="login-btn" value="Login">
    </form>
</body>
</html>