<?php

/*******w******** 
    
    Name: Hang Xu   
    Date: 2024-06-30
    Description: Assignment 3 Blogging Application

****************/

require('connect.php');

// Fetch all categories
$categoryQuery = "SELECT id, name FROM vegetarian";
$categoryStatement = $db->prepare($categoryQuery);
$categoryStatement->execute();
$categories = $categoryStatement->fetchAll(PDO::FETCH_ASSOC);

$category_id = filter_input(INPUT_GET, 'category', FILTER_VALIDATE_INT);
if ($category_id) {
    // Fetch posts by selected category
    $query = "SELECT id, title, date, content FROM posts WHERE vegetarian_id = :category_id ORDER BY date DESC LIMIT 5";
    $statement = $db->prepare($query);
    $statement->bindValue(':category_id', $category_id, PDO::PARAM_INT);
} else {
    // Fetch all posts if no category is selected
    $query = "SELECT id, title, date, content FROM posts ORDER BY date DESC LIMIT 5";
    $statement = $db->prepare($query);
}
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
    <h1><a href="index.php">Food Hub</a></h1>
    <h3><a href="admin.php">admin</a></h3>

    <a class="home" href="index.php">Home</a>

    <div>
        <h4>Categories</h4>
        <ul>
            <li><a href="index.php">All</a></li> <!-- Link to show all posts -->
            <?php foreach ($categories as $category): ?>
                <li><a href="index.php?category=<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <br>
    <h2>Recently Recipes</h2>
    <br>
    <?php foreach ($posts as $post): ?>
        <div class="post">
            <div class="post-header">
                <h3><a href="post.php?id=<?= $post['id'] ?>"><?= htmlspecialchars($post['title']) ?></a></h3>
            </div>
            <p><small><?= date('F d, Y, h:i a', strtotime($post['date'])) ?></small></p>
            <p>
                <?= nl2br(strlen($post['content']) > 200 ? substr($post['content'], 0, 200) . '...' : htmlspecialchars($post['content'])) ?>
                <?php if (strlen($post['content']) > 200): ?>
                    <a href="post.php?id=<?= $post['id'] ?>">Read Full Post</a>
                <?php endif; ?>
            </p>
            <br>
        </div>
    <?php endforeach; ?>
</body>

</html>
