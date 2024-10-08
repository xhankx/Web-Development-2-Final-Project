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
//require('authenticate.php');

$title = '';
$content = '';
$errors = [];

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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate title and content
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING);

    // Validate title
    if (strlen(trim($title)) < 1) {
        $errors['title'] = 'Title is required and must be at least 1 character in length.';
    }

    // Validate content
    if (strlen(trim($content)) < 1) {
        $errors['content'] = 'Content is required and must be at least 1 character in length.';
    }

    // Handle image upload
    $image = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['image']['tmp_name'];
        $fileName = time() . '-' . basename($_FILES['image']['name']);
        $uploadFileDir = 'uploads/images/';
        $dest_path = $uploadFileDir . $fileName;

        // Check if the uploaded file is indeed an image
        $imageInfo = getimagesize($fileTmpPath);
        if ($imageInfo === false) {
            $errors['image'] = 'The uploaded file is not a valid image.';
        } else {
            // Move the file to the upload directory
            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                // Resize the image
                $maxWidth = 800;
                $maxHeight = 600;
                resizeImage($dest_path, $dest_path, $maxWidth, $maxHeight);
                $image = $fileName;
            } else {
                $errors['image'] = 'There was an error moving the uploaded file.';
            }
        }
    }

    // Proceed if no validation errors
    if (empty($errors)) {
        $query = "INSERT INTO posts (title, date, content, image) VALUES (:title, NOW(), :content, :image)";
        $statement = $db->prepare($query);
        $statement->bindValue(':title', $title, PDO::PARAM_STR);
        $statement->bindValue(':content', $content, PDO::PARAM_STR);
        $statement->bindValue(':image', $image, PDO::PARAM_STR);
        $statement->execute();

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
    <title>Food Hub - Creating</title>
    <!-- CKEditor 5 CDN -->
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

</head>

<body>
    <h1><a href="index.php">Create New Recipes</a></h1>
    <a class="home" href="userlogin.php">Return to Hub</a>
    <br><br>

    <form method="post" action="newpost.php" enctype="multipart/form-data">
        <div>
            <label for="title">The Recipe</label>
            <br>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>">
            <br><br>
        </div>
        <div>
            <label for="content">More About The Recipe</label>
            <br>
            <br>
            <textarea id="content" name="content"><?= htmlspecialchars($content, ENT_QUOTES, 'UTF-8') ?></textarea>
            <br><br>
        </div>

        <div>
            <label for="image">Upload Image</label>
            <br>
            <input type="file" id="image" name="image">
            <br><br>
        </div>
        <div>
            <button type="submit" name="update">Submit the Recipe</button>
        </div>
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