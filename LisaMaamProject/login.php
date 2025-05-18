<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $row = $res->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $username;
            header("Location: home.php");
            exit();
        }
    }
    $error = "Invalid username or password.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cyberpunk Login</title>
    <style>
    body {
        margin: 0;
        background-color: #000;
        font-family: 'Courier New', monospace;
        color: #00ff00;
    }

    .login-container {
        width: 350px;
        margin: 100px auto;
        padding: 30px;
        background-color: #111;
        border: 1px solid #00ff00;
        border-radius: 10px;
        box-shadow: 0 0 20px #00ff00;
        text-align: center; /* Ensures inner elements like button appear centered */
    }

    h2 {
        text-align: center;
        margin-bottom: 20px;
    }

    input[type="text"],
    input[type="password"] {
        width: 100%;
        padding: 10px;
        background: #00ff00; /* green background */
        color: #000;          /* black text */
        border: 1px solid #00ff00;
        border-radius: 5px;
        margin-bottom: 15px;
        font-size: 14px;
    }

    button {
        width: 100%;
        padding: 10px;
        background-color: #00ff00;
        color: #000;
        border: none;
        border-radius: 5px;
        font-weight: bold;
        cursor: pointer;
        font-size: 14px;
        margin-top: 10px;
    }

    button:hover {
        background-color: #00cc00;
    }

    .error {
        color: red;
        text-align: center;
    }

    .register-link {
        text-align: center;
        display: block;
        margin-top: 10px;
        color: #00ff00;
    }

    .footer {
        text-align: center;
        color: #00ff00;
        font-size: 14px;
        margin-top: 40px;
    }
</style>

</head>
<body>

<div class="login-container">
    <form method="POST">
        <h2>Login</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
        <a class="register-link" href="register.php">Don't have an account? Register</a>
    </form>
</div>

<div class="footer">
   <h1 style="font-size: 16px;"></h1>
</div>

</body>
</html>
