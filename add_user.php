<?php
/*******w******** 
    
    Name: Hang Xu   
    Date: 2024-08-12
    Description: Web Development 2---- PHP CRUD-based Content Management System (CMS)

****************/
session_start();
require('connect.php');
require('authenticate.php');



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = filter_input(INPUT_POST, 'username', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];

    // Check if the username already exists
    $query = "SELECT * FROM users WHERE username = :username";
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $username);
    $statement->execute();
    $existingUser = $statement->fetch(PDO::FETCH_ASSOC);

    if ($existingUser) {
        echo "This email is already registered. Please use a different email.";
    } else {
        // Hash and salt the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert the new user into the database
        $query = "INSERT INTO users (username, password) VALUES (:username, :password)";
        $statement = $db->prepare($query);
        $statement->bindValue(':username', $username);
        $statement->bindValue(':password', $hashedPassword);
        if ($statement->execute()) {
            header('Location: users.php');
            exit();
        } else {
            echo "There was an error adding the user. Please try again.";
        }
    }
}
?>