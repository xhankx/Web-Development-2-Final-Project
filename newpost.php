<?php

/*******w******** 
    
    Name: Hang Xu   
    Date: 2024-06-30
    Description: Assignment 3 Blogging Application

****************/
require ('connect.php');
require ('authenticate.php');

$title = '';
$content = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING);

    if (strlen(trim($title)) < 1) {
        $errors['title'] = 'Title is required and must be at least 1 character in length.';
    }

    if (strlen(trim($content)) < 1) {
        $errors['content'] = 'Content is required and must be at least 1 character in length.';
    }

    if (empty($errors)) {
        $query = "INSERT INTO posts (title, date, content) VALUES (:title, NOW(), :content)";
        $statement = $db->prepare($query);
        $statement->bindValue(':title', $title);
        $statement->bindValue(':content', $content);
        $statement->execute();

        header("Location: admin.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <title>My Blog - Post a New Blog</title>
</head>
<body>
    <!-- Remember that alternative syntax is good and html inside php is bad -->
    <h1><a href="index.php">My Amazing Blog</a></h1>
    <a class="home" href="admin.php">Return to Admin</a>
    <br><br>
    <form method="post" action="newpost.php">
        <div>
            <label for="title">Title</label>
            <br>
            <input type="text" id="title" name="title" value="<?= $title ?>">
            <br><br>      
        </div>
        <div>
            <label for="content">Content</label>
            <br>
            <textarea id="content" name="content"><?= $content ?></textarea>
            <br><br>
        </div>
        <div>
            <button type="submit">Submit Recipes</button>
        </div>
    </form>
    <?php if (!empty($errors)): ?>
        <div class="errors">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
</body>
</html>
