<?php

/*******w******** 
    
    Name: Hang Xu   
    Date: 2024-08-12
    Description: Web Development 2---- PHP CRUD-based Content Management System (CMS)

****************/

session_start();
require('connect.php');

$post_id = filter_input(INPUT_POST, 'post_id', FILTER_VALIDATE_INT);
$comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING);
$captcha = filter_input(INPUT_POST, 'captcha', FILTER_SANITIZE_STRING);

// Generate the slug for the post based on the title
$query = "SELECT title FROM posts WHERE id = :post_id";
$statement = $db->prepare($query);
$statement->bindValue(':post_id', $post_id, PDO::PARAM_INT);
$statement->execute();
$post = $statement->fetch(PDO::FETCH_ASSOC);
$slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $post['title']), '-'));

if ($captcha !== $_SESSION['captcha_text']) {
    // If CAPTCHA is incorrect, redirect back to the original post page with an error message
    header("Location: /wd2/Assignments/Project/Web-Development-2-Final-Project/post.php?id=$post_id&slug=$slug&captcha_error=1");
    exit();
}

// Clear the CAPTCHA session value to prevent reuse
unset($_SESSION['captcha_text']);

// Proceed to insert the comment if CAPTCHA is correct
if ($post_id && $comment) {
    $query = "INSERT INTO comments (post_id, comment) VALUES (:post_id, :comment)";
    $statement = $db->prepare($query);
    $statement->bindValue(':post_id', $post_id, PDO::PARAM_INT);
    $statement->bindValue(':comment', $comment);
    $statement->execute();

    // Redirect back to the post page after submission
    header("Location: /wd2/Assignments/Project/Web-Development-2-Final-Project/post.php?id=$post_id&slug=$slug");
    exit();
} else {
    // Handle errors
    header("Location: /wd2/Assignments/Project/Web-Development-2-Final-Project/index.php");
    exit();
}

?>