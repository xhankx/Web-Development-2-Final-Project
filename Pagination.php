<?php

/*******w******** 
    
    Name: Hang Xu   
    Date: 2024-08-12
    Description: Web Development 2---- PHP CRUD-based Content Management System (CMS)

****************/

require('connect.php');

// Fetch all categories
$categoryQuery = "SELECT id, name FROM vegetarian";
$categoryStatement = $db->prepare($categoryQuery);
$categoryStatement->execute();
$categories = $categoryStatement->fetchAll(PDO::FETCH_ASSOC);

$category_id = filter_input(INPUT_GET, 'category', FILTER_VALIDATE_INT);
$search = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_STRING);

// Set up pagination variables
$limit = 3; // Number of posts per page
$page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
$offset = ($page - 1) * $limit;

// Build the query
$query = "SELECT id, title, date, content FROM posts WHERE 1=1";
$params = [];

// Apply category filter if selected
if ($category_id) {
    $query .= " AND vegetarian_id = :category_id";
    $params[':category_id'] = $category_id;
}

// Apply search filter if a keyword is entered
if ($search) {
    $query .= " AND (title LIKE :search OR content LIKE :search)";
    $params[':search'] = "%$search%";
}

// Add order and limit/offset for pagination
$query .= " ORDER BY date DESC LIMIT :limit OFFSET :offset";
$params[':limit'] = $limit;
$params[':offset'] = $offset;

$statement = $db->prepare($query);
foreach ($params as $key => $value) {
    $statement->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
}
$statement->execute();
$posts = $statement->fetchAll(PDO::FETCH_ASSOC);

// Get total number of posts for pagination
$countQuery = "SELECT COUNT(*) FROM posts WHERE 1=1";
$countStatement = $db->prepare($countQuery);
foreach ($params as $key => $value) {
    if ($key !== ':limit' && $key !== ':offset') {
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
    <!-- Remember that alternative syntax is good and html inside php is bad -->
    <br>
    <h1><a href="index.php">Food Hub</a></h1>
    <h3><a href="admin.php">Admin</a></h3>

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
    <h2>Search Results</h2>

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