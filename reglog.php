<?php 

    require "Connection/config.php";
    require "Functions/message.php";

    if (isset($_POST['reg-btn'])) {
        $stmt = "SELECT * FROM users WHERE username = '$_POST[username]'";
        $result = $conn->query($stmt);

        if (mysqli_num_rows($result) == 0) {
            
        }
        else {
            Error("This username is already used.");
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Sign Up</title>
</head>
<body>
    <form method="post" id="login">
        <input type="text" name="username" placeholder="Username">
        <input type="password" name="password" placeholder="Password">
        <input type="submit" name="login-btn" value="Login">
        <a>You don't have an account? <a href="#" onclick="SwapForm('reg')">Sign Up</a></a>
    </form>
    <form method="post" id="reg" style="display: none;">
        <input type="text" name="username" placeholder="Username">
        <input type="password" name="pass1" placeholder="Password">
        <input type="password" name="pass2" placeholder="Password Again">
        <input type="submit" name="reg-btn" value="Sign Up">
        <a>Already have an account? <a href="#" onclick="SwapForm('login')">Login</a></a>
    </form>
    <script>
        function SwapForm(form) {
            if (form == 'reg') {
                document.getElementById('reg').style.display = "block";
                document.getElementById('login').style.display = "none";
            }
            else {
                document.getElementById('login').style.display = "block";
                document.getElementById('reg').style.display = "none";
            }
        }
    </script>
</body>
</html>