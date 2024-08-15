<?php

/*******w******** 
    
    Name: Hang Xu   
    Date: 2024-06-30
    Description: Assignment 3 Blogging Application

****************/

require ('connect.php');
require ('authenticate.php');

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($id == false) {
    header("Location: index.php");
    exit();
}

$query = "SELECT id, title, content FROM posts WHERE id = :id";
$statement = $db->prepare($query);
$statement->bindValue(':id', $id, PDO::PARAM_INT);
$statement->execute();
$post = $statement->fetch(PDO::FETCH_ASSOC);

if ($post == false) {
    header("Location: index.php");
    exit();
}

$title = $post['title'];
$content = $post['content'];
$errors = [];

// Check if the form is submitted for updating the blog
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING);

    if (strlen(trim($title)) < 1) {
        $errors['title'] = 'Title is required and must be at least 1 character in length.';
    }

    if (strlen(trim($content)) < 1) {
        $errors['content'] = 'Content is required and must be at least 1 character in length.';
    }

    if (empty($errors)) {
        $query = "UPDATE posts SET title = :title, content = :content WHERE id = :id";
        $statement = $db->prepare($query);
        $statement->bindValue(':title', $title);
        $statement->bindValue(':content', $content);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        header("Location: post.php?id=$id");
        exit();
    }
}
// Check if the form is submitted for deleting the blog
else if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

    if ($id) {
        $query = "DELETE FROM posts WHERE id = :id";
        $statement = $db->prepare($query);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        header("Location: index.php");
        exit();
    } else {
        // Invalid id
        header("Location: index.php");
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
    <title>My Blog - Editing <?= $post['title'] ?></title>
</head>

<body>
    <!-- Remember that alternative syntax is good and html inside php is bad -->
    <h1><a href="index.php">My Amazing Blog</a></h1>
    <a class="home" href="index.php">Home</a>
    <br><br>
    <form method="post" action="edit.php?id=<?= $id ?>">
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
            <button type="submit" name="update">Submit Blog</button>
        </div>
    </form>


    <form method="post" action="edit.php?id=<?= $id ?>"
        onsubmit="return confirm('Are you sure you want to delete this post?');">
        <input type="hidden" name="id" value="<?= $id ?>">
        <button type="submit" name="delete" value="delete">Delete Post</button>
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