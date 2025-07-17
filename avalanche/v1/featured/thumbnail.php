<?php
require "../../utils.php";

$utils = new Utils();

// Before returning intended output, set the content type to JSON
header('Content-Type: application/json');

// The kind of request we're expecting
$utils->checkMethod($_SERVER['REQUEST_METHOD'], RequestMethod::GET);

// Fetch the remote image
$image = file_get_contents('https://gh.cubicstudios.xyz/WebLPS/aval-project/thumbnail.png');
if ($image === false) { // Check if the image was fetched successfully
    http_response_code(404);
    echo json_encode([
        'error' => 'Image not found',
    ]);
    exit;
}

// Set the correct content type for PNG images
header('Content-Type: image/png');

// Output the image data
http_response_code(200);
echo $image;