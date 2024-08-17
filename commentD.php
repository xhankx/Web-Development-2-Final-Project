<?php

/*******w******** 
    
    Name: Hang Xu   
    Date: 2024-08-12
    Description: Web Development 2---- PHP CRUD-based Content Management System (CMS)

****************/

require('connect.php');

// Get the comment ID from POST request
$comment_id = filter_input(INPUT_POST, 'comment_id', FILTER_VALIDATE_INT);
$post_id = filter_input(INPUT_POST, 'post_id', FILTER_VALIDATE_INT);

if ($comment_id && $post_id) {
    // Prepare and execute delete query
    $delete_query = "DELETE FROM comments WHERE id = :id";
    $statement = $db->prepare($delete_query);
    $statement->bindValue(':id', $comment_id, PDO::PARAM_INT);

    if ($statement->execute()) {
        // Redirect back to the post after deletion
        header("Location: delete_comment.php?id=" . $post_id);
        exit();
    } else {
        echo "Error deleting comment.";
    }
} else {
    // Redirect if no valid comment ID or post ID is provided
    header("Location: index.php");
    exit();
}

?>