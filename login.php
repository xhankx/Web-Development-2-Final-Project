<?php
session_start();
require('connect.php');

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = filter_var($_POST['username'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $rememberMe = isset($_POST['remember_me']);

    // Fetch the user from the database
    $query = "SELECT * FROM users WHERE username = :username";
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $username);
    $statement->execute();
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Correct login, regenerate session ID and set session variables
        session_regenerate_id(true);
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $user['username'];

        // If "Remember Me" is checked, set a cookie
        if ($rememberMe) {
            $cookie_value = base64_encode(json_encode(['username' => $user['username'], 'password' => $user['password']]));
            setcookie('remember_me', $cookie_value, time() + (86400 * 30), "/"); // 30 days expiration
        }

        // Redirect to the user login page
        header('Location: userlogin.php');
        exit();
    } else {
        // Invalid credentials
        $errors[] = 'Invalid username or password. Please try again.';
    }
}

// Check if the "Remember Me" cookie exists
if (isset($_COOKIE['remember_me'])) {
    $cookie_data = json_decode(base64_decode($_COOKIE['remember_me']), true);
    if ($cookie_data) {
        $username = $cookie_data['username'];
        $password = $cookie_data['password'];

        // Fetch the user from the database
        $query = "SELECT * FROM users WHERE username = :username";
        $statement = $db->prepare($query);
        $statement->bindValue(':username', $username);
        $statement->execute();
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        if ($user && $user['password'] === $password) {
            // Correct login via cookie, set session variables
            session_regenerate_id(true);
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $user['username'];

            // Redirect to the user login page
            header('Location: userlogin.php');
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="main.css">
</head>

<body>
    <div class="login-container">
        <h1>Login</h1>
        <a class="home" href="index.php">Home</a>
        <br><br>

        <?php if (!empty($errors)): ?>
            <div class="errors">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="login.php" method="post">
            <label for="username">Email:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label>
                <input type="checkbox" name="remember_me" value="1"> Remember Me
            </label>

            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Sign up here.</a></p>
    </div>
</body>

</html>