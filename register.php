<?php

/*******w******** 
    
    Name: Hang Xu   
    Date: 2024-06-30
    Description: Registration Script

****************/

require('connect.php');

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate email
    $username = filter_input(INPUT_POST, 'username', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if username is valid
    if (!$username) {
        $errors[] = "Please provide a valid email address.";
    }

    // Validate that the username (email) is not already in use
    if (empty($errors)) {
        $query = "SELECT * FROM users WHERE username = :username";
        $statement = $db->prepare($query);
        $statement->bindValue(':username', $username);
        $statement->execute();
        $existingUser = $statement->fetch(PDO::FETCH_ASSOC);

        if ($existingUser) {
            $errors[] = "This email is already registered. Please use a different email.";
        }
    }

    // Validate that passwords match
    if ($password !== $confirm_password) {
        $errors[] = "The passwords do not match. Please try again.";
    }

    // If no errors, proceed with registration
    if (empty($errors)) {
        // Hash and salt the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user into the database
        $query = "INSERT INTO users (username, password) VALUES (:username, :password)";
        $statement = $db->prepare($query);
        $statement->bindValue(':username', $username);
        $statement->bindValue(':password', $hashedPassword);

        if ($statement->execute()) {
            header('Location: login.php'); // Redirect to login page after successful registration
            exit();
        } else {
            $errors[] = "There was an error registering your account. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="main.css">
</head>

<body>
    <h1>Register</h1>
    <a class="home" href="index.php">Home</a>
    <br>
    <br>

    <?php if (!empty($errors)): ?>
        <div class="errors">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="register.php" method="post">
        <label for="username">Email:</label>
        <input type="email" id="username" name="username" required><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>

        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required><br>

        <button type="submit">Register</button>
    </form>

    <p>Already have an account? <a href="login.php">Login here</a>.</p>
</body>

</html>