<?php

/*******w******** 
    
    Name: Hang Xu   
    Date: 2024-06-30
    Description: Assignment 3 Blogging Application

****************/

require ('connect.php');

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($id == false) {
    header("Location: index.php");
    exit();
}

$query = "SELECT id, title, date, content FROM posts WHERE id = :id";
$statement = $db->prepare($query);
$statement->bindValue(':id', $id, PDO::PARAM_INT);
$statement->execute();
$post = $statement->fetch();

if ($post == false) {
    header("Location: post.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <title>My Blog Post - <?= $post['title'] ?></title>
</head>

<body>
    <!-- Remember that alternative syntax is good and html inside php is bad -->
    <br>
    <h1><a href="index.php">My Amazing Blog</a></h1>
    <a class="home" href="index.php">Home</a>
    <a class="newpost" href="newpost.php">New Post</a>
    <br><br>
    <div class="post">
    <div class="post-header">
        <h3><a href="post.php?id=<?= $post['id'] ?>"><?= $post['title'] ?></a></h3>
        <small><a href="edit.php?id=<?= $post['id'] ?>">Edit</a></small>
    </div>
    <p><small><?= date('F d, Y, h:i a', strtotime($post['date'])) ?></small></p>
    <p><?= nl2br($post['content']) ?></p>
    </div>
</body>

</html>