<?php
require "../../utils.php";

$utils = new Utils();

// Before returning intended output, set the content type to JSON
header('Content-Type: application/json');

// The kind of request we're expecting
$utils->checkMethod($_SERVER['REQUEST_METHOD'], RequestMethod::GET);

$imageData = file_get_contents("https://gh.cubicstudios.xyz/WebLPS/aval-project/thumbnail.png");
if ($imageData === false) {
    http_response_code(404);
    echo json_encode([
        'error' => 'Image not found',
    ]);

    exit;
}

// Try to create an image resource from the data
$image = imagecreatefromstring($imageData);

if ($image === false) { // Check if the image was fetched successfully
    http_response_code(404);
    echo json_encode([
        'error' => 'Unsupported image format',
    ]);

    exit;
}

// Output the image data
http_response_code(200);

if (isset($_GET['webp'])) { // Check if request includes WebP parameter
    // Set the correct content type for WebP images
    header('Content-Type: image/webp');
    imagewebp($image);
} else {
    // Set the correct content type for JPEG images
    header('Content-Type: image/jpeg');
    imagejpeg($image);
}