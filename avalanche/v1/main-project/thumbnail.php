<?php
// Set the correct content type for PNG images
header('Content-Type: image/png');

// Fetch the remote image
$image = file_get_contents('https://gh.cubicstudios.xyz/WebLPS/aval-project/thumbnail.png');

if ($image === false) { // Check if the image was fetched successfully
    http_response_code(404);
    exit('Image not found.');
}

// Output the image data
http_response_code(200);
echo $image;