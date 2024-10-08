<?php

/*******w******** 
    
    Name: Hang Xu   
    Date: 2024-08-12
    Description: Web Development 2---- PHP CRUD-based Content Management System (CMS)

****************/

session_start();
require('connect.php');
require('authenticate.php');



$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id) {
    // Delete the user
    $query = "DELETE FROM users WHERE id = :id";
    $statement = $db->prepare($query);
    $statement->bindValue(':id', $id, PDO::PARAM_INT);
    if ($statement->execute()) {
        header('Location: users.php');
        exit();
    } else {
        echo "There was an error deleting the user. Please try again.";
    }
}
?>