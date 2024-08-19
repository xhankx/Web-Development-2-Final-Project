<?php

/*******w******** 
    
    Name: Hang Xu   
    Date: 2024-08-12
    Description: Web Development 2---- PHP CRUD-based Content Management System (CMS)

****************/

require('connect.php');

// Get the ID and slug from the URL
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$slug = filter_input(INPUT_GET, 'slug', FILTER_SANITIZE_STRING);

if ($id === false || empty($slug)) {
    header("Location: index.php");
    exit();
}

// Fetch the post from the database
$query = "SELECT id, title, date, content, image FROM posts WHERE id = :id";
$statement = $db->prepare($query);
$statement->bindValue(':id', $id, PDO::PARAM_INT);
$statement->execute();
$post = $statement->fetch();

if ($post === false) {
    header("Location: index.php");
    exit();
}

// Generate the correct slug based on the post title
$correct_slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $post['title']), '-'));

// Check if the slug matches the correct slug
if ($slug !== $correct_slug) {
    // Redirect to the "super pretty" URL if the slug doesn't match
    header("Location: /wd2/Assignments/Project/Web-Development-2-Final-Project/post.php?id=$id&slug=$correct_slug", true, 301);
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
    <link rel="stylesheet" href="/wd2/Assignments/Project/Web-Development-2-Final-Project/main.css">
    <title>Food Hub - <?= htmlspecialchars($post['title']) ?></title>
</head>

<body>
    <br>
    <h1><a href="/wd2/Assignments/Project/Web-Development-2-Final-Project/index.php">Food Hub</a></h1>
    <a class="home" href="/wd2/Assignments/Project/Web-Development-2-Final-Project/index.php">Home</a>

    <br><br>
    <div class="post">
        <div class="post-header">
            <h3><?= htmlspecialchars($post['title']) ?></h3>
        </div>
        <p><small><?= date('F d, Y, h:i a', strtotime($post['date'])) ?></small></p>
        <br>
        <!-- Display the image if it exists -->
        <?php if (!empty($post['image'])): ?>
            <img src="/wd2/Assignments/Project/Web-Development-2-Final-Project/uploads/images/<?= htmlspecialchars($post['image']) ?>"
                alt="<?= htmlspecialchars($post['title']) ?>" style="max-width:100%; height:auto;">
        <?php endif; ?>

        <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
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

        <input type="hidden" name="post_id" value="<?= htmlspecialchars($post['id']) ?>">

        <label for="comment">Comment:</label>
        <textarea name="comment" id="comment" required></textarea>

        <br>
        <br>
        <label for="captcha">Enter the code:</label>
        <img src="captcha.php" alt="CAPTCHA Image">
        <input type="text" name="captcha" id="captcha" required>

        <?php if (isset($_GET['captcha_error'])): ?>
            <p style="color:red;">CAPTCHA was incorrect. Please try again.</p>
        <?php endif; ?>

        <br><br>
        <button type="submit">Submit</button>
    </form>
</body>

</html>