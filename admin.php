<?php

/*******w******** 
    
    Name: Hang Xu   
    Date: 2024-06-30
    Description: Assignment 3 Blogging Application

****************/

require('connect.php');
require('authenticate.php');

// Determine the sort order (default is by date)
$sort = filter_input(INPUT_GET, 'sort', FILTER_SANITIZE_STRING);
$sortOrder = 'date DESC';  // Default sorting by date (latest first)

if ($sort === 'title') {
    $sortOrder = 'title ASC';
} elseif ($sort === 'created_at') {
    $sortOrder = 'date ASC';  // Earliest first
} elseif ($sort === 'updated_at') {
    $sortOrder = 'date DESC';  // Latest updated first
}

// Fetch posts with the determined sort order
$query = "SELECT id, title, date, content FROM posts ORDER BY $sortOrder";
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
    <h2>Recent Recipes</h2>
    <br>
    <div>
        <strong>Sort by:</strong>
        <a href="admin.php?sort=title" class="<?= $sort === 'title' ? 'active' : '' ?>">Title</a> |
        <a href="admin.php?sort=created_at" class="<?= $sort === 'created_at' ? 'active' : '' ?>">Date Created</a> |
        <a href="admin.php?sort=updated_at" class="<?= $sort === 'updated_at' ? 'active' : '' ?>">Date Updated</a>
    </div>

    <br>
    <?php foreach ($posts as $post): ?>
        <div class="post">
            <div class="post-header">
                <h3><a href="delete_comment.php?id=<?= $post['id'] ?>"><?= htmlspecialchars($post['title']) ?></a></h3>
                <small><a href="edit.php?id=<?= $post['id'] ?>">Edit</a></small>
            </div>
            <p><small><?= date('F d, Y, h:i a', strtotime($post['date'])) ?></small></p>
            <br>
        </div>
    <?php endforeach; ?>
</body>

</html>
