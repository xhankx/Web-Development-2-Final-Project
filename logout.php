<?php

/*******w******** 
    
    Name: Hang Xu   
    Date: 2024-08-12
    Description: Web Development 2---- PHP CRUD-based Content Management System (CMS)

****************/

session_start();

// Clear the session
session_unset();
session_destroy();

// Clear the "Remember Me" cookie
setcookie('remember_me', '', time() - 3600, "/"); // Expire the cookie

// Redirect to login page
header('Location: login.php');
exit();
?>