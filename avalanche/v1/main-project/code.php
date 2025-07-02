<?php
header('Content-Type: text/plain');

// Fetch the remote text file
$text = file_get_contents('https://gh.cubicstudios.xyz/WebLPS/aval-project/code.txt');

if ($text === false) { // Check if the file was fetched successfully
    http_response_code(404);
    exit('File not found.');
}

// Output the text content
http_response_code(200);
echo $text;