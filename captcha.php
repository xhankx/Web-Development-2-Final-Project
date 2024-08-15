<?php
session_start();

// Generate a random CAPTCHA string
$captcha_text = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);

// Store the CAPTCHA text in a session
$_SESSION['captcha_text'] = $captcha_text;

// Create an image
$image = imagecreatetruecolor(150, 50);

// Set background and text colors
$bg_color = imagecolorallocate($image, 255, 255, 255); // White background
$text_color = imagecolorallocate($image, 0, 0, 0); // Black text

// Fill the background with the background color
imagefilledrectangle($image, 0, 0, 150, 50, $bg_color);

// Define the path to the font file
$font = dirname(__FILE__) . '/fonts/Roboto-Regular.ttf';

// Add the CAPTCHA text to the image
imagettftext($image, 20, 0, 15, 35, $text_color, $font, $captcha_text);

// Output the image as a PNG file
header('Content-type: image/png');
imagepng($image);

// Free up memory
imagedestroy($image);
?>
