<?php 

    require "Connection/config.php";
    require "Functions/message.php";

    if (isset($_POST['reg-btn'])) {
        $stmt = "SELECT * FROM users WHERE username = '$_POST[username]'";
        $result = $conn->query($stmt);

        if (mysqli_num_rows($result) == 0) {
            if ($_POST['pass1'] === $_POST['pass2']) {
                $hash = password_hash($_POST['pass1'], PASSWORD_DEFAULT);

                $conn->query("INSERT INTO users VALUES (id, '$_POST[username]', '$hash', 0)");
                $stmt = "SELECT id FROM users WHERE username = '$_POST[username]'";
                $result = $conn->query($stmt);
                $row = $result->fetch_assoc();

                $conn->query("INSERT INTO profile VALUES (id, $row[id], 0, 0, 0, 0, 0, 0)");

                setcookie("userid", $row['id'], time() + 3600, "/");
                header("Location: index.php");
            }
            else {
                Error("Passwords don't match.");
            }
        }
        else {
            Error("This username is already used.");
        }
    }

    if (isset($_POST['login-btn'])) {
        $stmt = "SELECT * FROM users WHERE username = '$_POST[username]'";
        $result = $conn->query($stmt);

        if (mysqli_num_rows($result) == 1) {
            $row = $result->fetch_assoc();
            if (password_verify($_POST['password'], $row['password'])) {
                setcookie("userid", $row['id'], time() + 3600, "/");
                header("Location: index.php");
            }
            else {
                Error("Wrong password.");
            }
        }
        else {
            Error("No user found with this username.");
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Sign Up</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container auth-container">
    
    <form method="post" id="login" class="auth-form">
        <h2>Login to Your Account</h2>
        
        <div class="auth-group">
            <input type="text" name="username" placeholder="Username" class="auth-input" required>
        </div>
        
        <div class="auth-group-last">
            <input type="password" name="password" placeholder="Password" class="auth-input" required>
        </div>
        
        <button type="submit" name="login-btn" class="auth-btn-login">Login</button>
        
        <p class="auth-switch">
            You don't have an account? <a href="#" onclick="SwapForm('reg')">Sign Up</a>
        </p>
    </form>

    <form method="post" id="reg" class="auth-form" style="display: none;">
        <h2>Create an Account</h2>
        
        <div class="auth-group">
            <input type="text" name="username" placeholder="Username" class="auth-input" required>
        </div>
        
        <div class="auth-group">
            <input type="password" name="pass1" placeholder="Password" class="auth-input" required>
        </div>
        
        <div class="auth-group-last">
            <input type="password" name="pass2" placeholder="Password Again" class="auth-input" required>
        </div>
        
        <button type="submit" name="reg-btn" class="auth-btn-reg">Sign Up</button>
        
        <p class="auth-switch">
            Already have an account? <a href="#" onclick="SwapForm('login')">Login</a>
        </p>
    </form>
</div>

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