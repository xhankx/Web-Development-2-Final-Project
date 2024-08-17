<?php

/*******w******** 
    
    Name: Hang Xu   
    Date: 2024-08-12
    Description: Assignment 3 Blogging Application

****************/

require('connect.php');

// Fetch all categories
$categoryQuery = "SELECT id, name FROM vegetarian";
$categoryStatement = $db->prepare($categoryQuery);
$categoryStatement->execute();
$categories = $categoryStatement->fetchAll(PDO::FETCH_ASSOC);

$category_id = filter_input(INPUT_GET, 'category', FILTER_VALIDATE_INT);
$search = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_STRING);

// Set the number of posts per page
$limit = 5;
$page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1; // Current page number, default is 1
$offset = ($page - 1) * $limit; // Calculate the offset for SQL query

// Build the query based on category and search with pagination
$query = "SELECT id, title, date, content FROM posts WHERE 1=1";

$params = [];
if ($category_id) {
    $query .= " AND vegetarian_id = :category_id";
    $params[':category_id'] = $category_id;
}

if ($search) {
    $query .= " AND (title LIKE :search OR content LIKE :search)";
    $params[':search'] = "%$search%";
}

// Add order, limit, and offset for pagination
$query .= " ORDER BY date DESC LIMIT :limit OFFSET :offset";
$params[':limit'] = $limit;
$params[':offset'] = $offset;

$statement = $db->prepare($query);
foreach ($params as $key => $value) {
    $statement->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
}
$statement->execute();
$posts = $statement->fetchAll(PDO::FETCH_ASSOC);

// Count the total number of posts for pagination
$countQuery = "SELECT COUNT(*) FROM posts WHERE 1=1";

if ($category_id) {
    $countQuery .= " AND vegetarian_id = :category_id";
}

if ($search) {
    $countQuery .= " AND (title LIKE :search OR content LIKE :search)";
}

$countStatement = $db->prepare($countQuery);
foreach ($params as $key => $value) {
    if ($key !== ':limit' && $key !== ':offset') { // Exclude limit and offset from count query
        $countStatement->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
    }
}
$countStatement->execute();
$totalPosts = $countStatement->fetchColumn();
$totalPages = ceil($totalPosts / $limit);

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

    <div class="head">
        <h1><a href="index.php">Food Hub</a></h1>
        <h3><a href="admin.php">Admin</a></h3>
    </div>


    <!-- Sign-Up and Login Buttons -->
    <div class="signAndLogin">
        <!-- Sign-Up Button -->
        <a href="register.php">
            <button type="button">Sign Up</button>
        </a>

        <!-- Login Button -->
        <a href="login.php">
            <button type="button">Login</button>
        </a>
    </div>

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
    <div>
        <form action="index.php" method="get">
            <input type="text" name="search" placeholder="Search...">
            <select name="category">
                <option value="">All Categories</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['id'] ?>" <?= $category_id == $category['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($category['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Search</button>
        </form>
    </div>

    <br>
    <h2>Recently Recipes</h2>
    <br>
    <?php foreach ($posts as $post): ?>
        <div class="post">
            <div class="post-header">
                <h3><a
                        href="/wd2/Assignments/Project/Web-Development-2-Final-Project/post.php?id=<?= $post['id'] ?>&slug=<?= strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $post['title']), '-')) ?>">
                        <?= htmlspecialchars($post['title']) ?>
                    </a></h3>
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

    <!-- Pagination Links -->
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a
                href="index.php?search=<?= urlencode($search) ?>&category=<?= $category_id ?>&page=<?= $page - 1 ?>">Previous</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="index.php?search=<?= urlencode($search) ?>&category=<?= $category_id ?>&page=<?= $i ?>" <?= $i == $page ? 'class="active"' : '' ?>><?= $i ?></a>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
            <a href="index.php?search=<?= urlencode($search) ?>&category=<?= $category_id ?>&page=<?= $page + 1 ?>">Next</a>
        <?php endif; ?>
    </div>
</body>

</html>