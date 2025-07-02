<?php
// Before returning intended output, set the content type to JSON
header('Content-Type: application/json');

// Fetch the remote text file
$text = file_get_contents('https://gh.cubicstudios.xyz/WebLPS/aval-project/code.txt');

if ($text === false) { // Check if the file was fetched successfully
    http_response_code(404);
    echo json_encode(['error' => 'File not found']);
    exit;
}

// Set the content type to plain text
header('Content-Type: text/plain');

// Output the text content
http_response_code(200);
echo $text;