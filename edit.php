<?php

/*******w******** 
    
    Name: Hang Xu   
    Date: 2024-08-12
    Description: Web Development 2---- PHP CRUD-based Content Management System (CMS)

****************/

session_start();

// Define the admin login credentials
define('ADMIN_LOGIN', 'wally');
define('ADMIN_PASSWORD', 'mypass');

// Check if the user is logged in via session or HTTP Basic Authentication
if (
    (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) &&
    (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']) ||
        ($_SERVER['PHP_AUTH_USER'] != ADMIN_LOGIN) ||
        ($_SERVER['PHP_AUTH_PW'] != ADMIN_PASSWORD))
) {
    // If neither session-based nor HTTP Basic Authentication is valid, redirect to login
    header('Location: login.php');
    exit();
}

// Include database connection and other necessary files
require('connect.php');
//require('authenticate.php');;

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($id === false) {
    header("Location: index.php");
    exit();
}

// Fetch the post to be edited
$query = "SELECT id, title, content, vegetarian_id, image FROM posts WHERE id = :id";
$statement = $db->prepare($query);
$statement->bindValue(':id', $id, PDO::PARAM_INT);
$statement->execute();
$post = $statement->fetch(PDO::FETCH_ASSOC);

if ($post === false) {
    header("Location: index.php");
    exit();
}

$title = htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8');
$content = htmlspecialchars($post['content'], ENT_QUOTES, 'UTF-8');
$vegetarian_id = $post['vegetarian_id'];
$image = $post['image'];
$errors = [];

// Fetch categories
$categoryQuery = "SELECT id, name FROM vegetarian";
$categoryStatement = $db->prepare($categoryQuery);
$categoryStatement->execute();
$categories = $categoryStatement->fetchAll(PDO::FETCH_ASSOC);

// Resize image function
function resizeImage($source, $destination, $maxWidth, $maxHeight)
{
    list($width, $height, $type) = getimagesize($source);
    $ratio = $width / $height;

    if ($maxWidth / $maxHeight > $ratio) {
        $maxWidth = $maxHeight * $ratio;
    } else {
        $maxHeight = $maxWidth / $ratio;
    }

    $newImage = imagecreatetruecolor($maxWidth, $maxHeight);

    switch ($type) {
        case IMAGETYPE_JPEG:
            $sourceImage = imagecreatefromjpeg($source);
            break;
        case IMAGETYPE_PNG:
            $sourceImage = imagecreatefrompng($source);
            break;
        case IMAGETYPE_GIF:
            $sourceImage = imagecreatefromgif($source);
            break;
        default:
            return false;
    }

    imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $maxWidth, $maxHeight, $width, $height);
    imagejpeg($newImage, $destination, 90);

    imagedestroy($newImage);
    imagedestroy($sourceImage);

    return true;
}

// Check if the form is submitted for updating the blog
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING);
    $vegetarian_id = filter_input(INPUT_POST, 'vegetarian_id', FILTER_VALIDATE_INT);
    $remove_image = isset($_POST['remove_image']) ? true : false;

    if (strlen(trim($title)) < 1) {
        $errors['title'] = 'Title is required and must be at least 1 character in length.';
    }

    if (strlen(trim($content)) < 1) {
        $errors['content'] = 'Content is required and must be at least 1 character in length.';
    }

    // Handle image upload or removal
    if ($remove_image && $image) {
        // Remove the image file from the server
        $imagePath = 'uploads/images/' . $image;
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
        $image = null; // Reset image in the database

        // Update the image path in the database to NULL
        $query = "UPDATE posts SET image = NULL WHERE id = :id";
        $statement = $db->prepare($query);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();
    } else if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['image']['tmp_name'];
        $fileName = time() . '-' . basename($_FILES['image']['name']);
        $uploadFileDir = 'uploads/images/';
        $dest_path = $uploadFileDir . $fileName;

        // Check if the uploaded file is indeed an image
        $imageInfo = getimagesize($fileTmpPath);
        if ($imageInfo === false) {
            $errors['image'] = 'The uploaded file is not a valid image.';
        } else {
            // Resize the image before saving
            if (resizeImage($fileTmpPath, $dest_path, 800, 800)) {
                // Delete the old image if a new one is uploaded
                if ($image) {
                    $oldImagePath = 'uploads/images/' . $image;
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
                $image = $fileName;
            } else {
                $errors['image'] = 'There was an error processing the uploaded image.';
            }
        }
    }

    if (empty($errors)) {
        $query = "UPDATE posts SET title = :title, content = :content, vegetarian_id = :vegetarian_id";
        if ($image !== null) {
            $query .= ", image = :image";
        }
        $query .= " WHERE id = :id";

        $statement = $db->prepare($query);
        $statement->bindValue(':title', $title, PDO::PARAM_STR);
        $statement->bindValue(':content', $content, PDO::PARAM_STR);
        $statement->bindValue(':vegetarian_id', $vegetarian_id, PDO::PARAM_INT);
        if ($image !== null) {
            $statement->bindValue(':image', $image, PDO::PARAM_STR);
        }
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        header("Location: userlogin.php");
        exit();
    }
}

// Check if the form is submitted for deleting the blog
else if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

    if ($id) {
        // Remove the image file from the server
        if ($image) {
            $imagePath = 'uploads/images/' . $image;
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $query = "DELETE FROM posts WHERE id = :id";
        $statement = $db->prepare($query);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        header("Location: userlogin.php");
        exit();
    } else {
        // Invalid id
        header("Location: userlogin.php");
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
    <title>Food Hub - Editing <?= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') ?></title>

    <!-- CKEditor 5 CDN -->
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

</head>

<body>
    <h1><a href="index.php">Editing Recipes</a></h1>
    <a class="home" href="userlogin.php">Return to Hub</a>
    <br><br>

    <form method="post" action="edit.php?id=<?= $id ?>" enctype="multipart/form-data">
        <div>
            <label for="title">The Recipe</label>
            <br>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>">
            <br><br>
        </div>
        <div>
            <label for="content">More About The Recipe</label>
            <br><br>
            <textarea id="content" name="content"><?= htmlspecialchars($content, ENT_QUOTES, 'UTF-8') ?></textarea>
            <br>
        </div>
        <div>
            <label for="vegetarian_id">Category</label>
            <br>
            <select id="vegetarian_id" name="vegetarian_id">
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['id'] ?>" <?= $category['id'] == $vegetarian_id ? 'selected' : '' ?>>
                        <?= htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <br><br>
        </div>

        <?php if ($image): ?>
            <div>
                <label>Current Image:</label>
                <br>
                <img src="uploads/images/<?= htmlspecialchars($image, ENT_QUOTES, 'UTF-8') ?>" alt="Current Image"
                    style="max-width: 200px;">
                <br><br>
                <label>
                    <input type="checkbox" name="remove_image" value="1"> Remove current image
                </label>
                <br><br>
            </div>
        <?php endif; ?>

        <div>
            <label for="image">Upload New Image</label>
            <br>
            <input type="file" id="image" name="image">
            <br><br>
        </div>
        <div>
            <button type="submit" name="update">Update the Recipes</button>
        </div>
    </form>

    <form method="post" action="edit.php?id=<?= $id ?>"
        onsubmit="return confirm('Are you sure you want to delete this post?');">
        <input type="hidden" name="id" value="<?= $id ?>">
        <button type="submit" name="delete" value="delete">Delete the Recipes</button>
    </form>
    <?php if (!empty($errors)): ?>
        <div class="errors">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Initialize CKEditor 5 -->
    <script>
        ClassicEditor
            .create(document.querySelector('#content'))
            .catch(error => {
                console.error(error);
            });
    </script>
</body>

</html>