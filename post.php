<?php

/*******w******** 
    
    Name: Hang Xu   
    Date: 2024-06-30
    Description: Assignment 3 Blogging Application

****************/

require('connect.php');

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

// Fetch comments for this post
$comments_query = "SELECT comment FROM comments WHERE post_id = :post_id";
$comments_statement = $db->prepare($comments_query);
$comments_statement->bindValue(':post_id', $id, PDO::PARAM_INT);
$comments_statement->execute();
$comments = $comments_statement->fetchAll();

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
    <h1><a href="index.php">Food Hub</a></h1>
    <a class="home" href="index.php">Home</a>
    <br><br>
    <div class="post">
        <div class="post-header">
            <h3><a href="post.php?id=<?= $post['id'] ?>"><?= $post['title'] ?></a></h3>
        </div>
        <p><small><?= date('F d, Y, h:i a', strtotime($post['date'])) ?></small></p>
        <p><?= nl2br($post['content']) ?></p>
    </div>

    <h2>Comments</h2>
    <div class="comments">
        <?php if ($comments): ?>
            <?php foreach ($comments as $comment): ?>
                <div class="comment">
                    <p><?= nl2br(htmlspecialchars($comment['comment'])) ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No comments yet. Be the first to comment!</p>
        <?php endif; ?>
    </div>


    <form action="submit_comment.php" method="post">
        <input type="hidden" name="post_id" value="<?= $post['id'] ?>">

        <label for="comment">Comment:</label>
        <textarea name="comment" id="comment" required></textarea>

        <label for="captcha">Enter the code:</label>
        <img src="captcha.php" alt="CAPTCHA Image">
        <input type="text" name="captcha" id="captcha" required>

        <?php if (isset($_GET['captcha_error'])): ?>
            <p style="color:red;">CAPTCHA was incorrect. Please try again.</p>
        <?php endif; ?>

        <button type="submit">Submit</button>
    </form>

</body>

</html>