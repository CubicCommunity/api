<?php
require "../../utils.php";

$utils = new Utils();

// Before returning intended output, set the content type to JSON
header('Content-Type: application/json');

// The kind of request we're expecting
$utils->checkMethod($_SERVER['REQUEST_METHOD'], RequestMethod::GET);

if (!isset($_GET['id']) || empty($_GET['id'])) { // Check if 'id' parameter is set and not empty
    http_response_code(400);
    echo json_encode([
        'error' => 'Missing or empty id parameter',
    ]);
    exit;
}

// Sanitize id
$id = $_GET['id'];

if (!ctype_digit($id)) {
    http_response_code(400);
    echo json_encode([
        'error' => 'Invalid profile ID',
    ]);
    exit;
}

$id = urlencode($_GET['id']);
$url = "https://levelthumbs.prevter.me/thumbnail/$id";

$imageData = file_get_contents($url);
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

imagedestroy($image);