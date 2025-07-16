<?php
// Before returning intended output, set the content type to JSON
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    header('Allow: GET');
    header('Content-Type: application/json');
    echo json_encode([
        'error' => 'Method Not Allowed',
    ]);
    exit;
}

if (!isset($_GET['url']) || empty($_GET['url'])) { // Check if 'url' parameter is set and not empty
    http_response_code(400);
    echo json_encode([
        'error' => 'Missing or empty url parameter',
    ]);
    exit;
}

function extract_youtube_id($url)
{
    $patterns = [
        '/youtu\.be\/([^\?&]+)/',
        '/youtube\.com\/watch\?v=([^\?&]+)/',
        '/youtube\.com\/embed\/([^\?&]+)/',
        '/youtube\.com\/v\/([^\?&]+)/',
        '/youtube\.com\/shorts\/([^\?&]+)/',
        '/youtube\.com\/.*[?&]v=([^\?&]+)/',
    ];

    foreach ($patterns as $pattern) {
        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }
    }

    $parts = parse_url($url);
    if (isset($parts['query'])) {
        parse_str($parts['query'], $qs);
        if (isset($qs['v'])) {
            return $qs['v'];
        }
    }

    return null;
}

$videoUrl = $_GET['url']; // Get the YouTube video URL from the query parameter
$videoId = extract_youtube_id($videoUrl); // Extract the video ID using the function

if (!$videoId) { // Check if a valid video ID was extracted
    http_response_code(400);
    echo json_encode([
        'error' => 'Invalid YouTube URL',
    ]);
    exit;
}

$thumbUrl = "https://img.youtube.com/vi/$videoId/maxresdefault.jpg"; // Construct the thumbnail URL
$image = file_get_contents($thumbUrl); // Fetch the thumbnail image

if ($image === false) { // Check if the thumbnail was fetched successfully
    http_response_code(404);
    echo json_encode([
        'error' => 'Thumbnail not found',
    ]);
    exit;
}

// Set the content type to JPEG
header('Content-Type: image/jpeg');

// Output the image data
http_response_code(200);
echo $image;