<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if (empty($username) || empty($password) || $password !== $confirm) {
        $error = "Please fill all fields correctly.";
    } else {
        // Check if the username/email already exists
        $check = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $check->bind_param("s", $username);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "⚠️ This email is already registered.";
        } else {
            // Proceed with insert
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $username, $hashed);

            if ($stmt->execute()) {
                $_SESSION['username'] = $username;
                header("Location: home.php");
                exit();
            } else {
                $error = "❌ Something went wrong during registration.";
            }

            $stmt->close();
        }

        $check->close();
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Cyberpunk Register</title>
    <style>
    body {
        margin: 0;
        background-color: #000;
        font-family: 'Courier New', monospace;
        color: #00ff00;
    }

    .register-container {
        width: 350px;
        margin: 100px auto;
        padding: 30px;
        background-color: #111;
        border: 1px solid #00ff00;
        border-radius: 10px;
        box-shadow: 0 0 20px #00ff00;
        text-align: center; /* Ensures button is visually centered */
    }

    h2 {
        text-align: center;
        margin-bottom: 20px;
    }

    input[type="text"],
    input[type="password"] {
        width: 100%;
        padding: 10px;
        background: #00ff00; /* Green background */
        color: #000;          /* Black text */
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

    .login-link {
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

<div class="register-container">
    <form method="POST">
        <h2>Register</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        <button type="submit">Register</button>
        <a class="login-link" href="login.php">Already have an account? Login</a>
    </form>
</div>

<div class="footer">
    <h1 style="font-size: 16px;"></h1>
</div>

</body>
</html>
