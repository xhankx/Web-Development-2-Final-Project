<?php

/*******w******** 
    
    Name: Hang Xu   
    Date: 2024-06-30
    Description: Assignment 3 Blogging Application

****************/

require ('connect.php');
require ('authenticate.php');

// Fetch the five most recent posts
$query = "SELECT id, title, date, content FROM posts ORDER BY date DESC LIMIT 5";
$statement = $db->prepare($query);
$statement->execute();
$posts = $statement->fetchAll();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <title>All About Food</title>
</head>

<body>
    <!-- Remember that alternative syntax is good and html inside php is bad -->
    <br>
    <h1><a href="index.php">Food Around The World</a></h1>
    <h3><a href="admin.php">admin</a></h3>

    <a class="home" href="index.php">Home</a>
    <a class="newpost" href="newpost.php">Create New Recipes</a>
    <br>
    <br>
    <h2>Recently Posted Blog Entries</h2>
    <br>
    <?php foreach ($posts as $post): ?>
        <div class="post">
            <div class="post-header">
                <h3><a href="post.php?id=<?= $post['id'] ?>"><?= $post['title'] ?></a></h3>
                <small><a href="edit.php?id=<?= $post['id'] ?>">Edit</a></small>
            </div>
            <p><small><?= date('F d, Y, h:i a', strtotime($post['date'])) ?></small></p>
            <p>
                <?= nl2br(strlen($post['content']) > 200 ? substr($post['content'], 0, 200) . '...' : $post['content']) ?>
                <?php if (strlen($post['content']) > 200): ?>
                    <a href="post.php?id=<?= $post['id'] ?>">Read Full Post</a>
                <?php endif; ?>
            </p>
            <br>
        </div>
    <?php endforeach; ?>
</body>

</html>