<?php
session_start();
require('connect.php');

$post_id = filter_input(INPUT_POST, 'post_id', FILTER_VALIDATE_INT);
$comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING);
$captcha = filter_input(INPUT_POST, 'captcha', FILTER_SANITIZE_STRING);

// Validate the CAPTCHA
if ($captcha !== $_SESSION['captcha_text']) {
    // If CAPTCHA is incorrect, redirect back to the post page with an error message
    header("Location: post.php?id=$post_id&captcha_error=1");
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
    header("Location: post.php?id=$post_id");
    exit();
} else {
    // Handle errors
    echo "Invalid post ID or comment.";
}
?>
