<?php
// Before returning intended output, set the content type to JSON
header('Content-Type: application/json');

if (!isset($_GET['id']) || empty($_GET['id'])) { // Check if 'id' parameter is set and not empty
    http_response_code(400);
    echo json_encode(['error' => 'Missing or empty id parameter']);
    exit;
}

$id = urlencode($_GET['id']);
$url = "https://levelthumbs.prevter.me/thumbnail/$id";

$image = file_get_contents($url);

if ($image === false) { // Check if the image was fetched successfully
    http_response_code(404);
    echo json_encode(['error' => 'Image not found']);
    exit;
}

// Set the correct content type for PNG images
header('Content-Type: image/webp');

// Output the image data
http_response_code(200);
echo $image;