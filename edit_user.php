<?php
session_start();
require('connect.php');
require('authenticate.php');



$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = filter_input(INPUT_POST, 'username', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];

    // Update the user's information
    $query = "UPDATE users SET username = :username";
    if ($password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $query .= ", password = :password";
    }
    $query .= " WHERE id = :id";

    $statement = $db->prepare($query);
    $statement->bindValue(':username', $username);
    if ($password) {
        $statement->bindValue(':password', $hashedPassword);
    }
    $statement->bindValue(':id', $id, PDO::PARAM_INT);
    if ($statement->execute()) {
        header('Location: users.php');
        exit();
    } else {
        echo "There was an error updating the user. Please try again.";
    }
}

// Fetch the user's current information
$query = "SELECT * FROM users WHERE id = :id";
$statement = $db->prepare($query);
$statement->bindValue(':id', $id, PDO::PARAM_INT);
$statement->execute();
$user = $statement->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <title>Edit User</title>
</head>

<body>
    <h1>Edit User</h1>
    <a href="users.php">Back to User Management</a>
    <br><br>

    <form action="edit_user.php?id=<?= $id ?>" method="post">
        <label for="username">Username (Email):</label>
        <input type="email" id="username" name="username" value="<?= htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') ?>" required>
        <br><br>
        <label for="password">Password (Leave blank to keep current password):</label>
        <input type="password" id="password" name="password">
        <br><br>
        <button type="submit">Update User</button>
    </form>
</body>

</html>
