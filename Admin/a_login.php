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
    <link rel="stylesheet" href="../css/style.css">
    <title>Admin</title>
</head>
<body>
    <div class="container auth-container">
        <form method="post" class="auth-form">
            
            <div class="auth-group">
                <input type="text" name="username" placeholder="Username" class="auth-input" required>
            </div>
            
            <div class="auth-group-last">
                <input type="password" name="password" placeholder="Password" class="auth-input" required>
            </div>
            
            <button type="submit" name="login-btn" class="auth-btn-login">Login</button>
            
        </form>
    </div>
</body>
</html>