<?php
// Set the content type to JSON
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    header('Allow: GET');
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

$url = 'https://gh.cubicstudios.xyz/WebLPS/data/avalProfiles.json';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$json = curl_exec($ch);

if ($json === false) { // Check if the cURL request failed
    http_response_code(500);

    echo json_encode([
        'error' => 'Failed to fetch remote file',
        'curl_error' => curl_error($ch),
    ]);

    curl_close($ch);
    exit;
}
curl_close($ch);

$data = json_decode($json, true);
if ($data === null) { // Check if JSON decoding failed
    http_response_code(500);

    echo json_encode([
        'error' => 'Failed to decode JSON',
        'json_last_error' => json_last_error_msg(),
    ]);

    exit;
}

// Remove non-number keys
$data = array_filter($data, function ($key) {
    return ctype_digit((string) $key);
}, ARRAY_FILTER_USE_KEY);

if (isset($_GET['id'])) { // Check if 'id' is set in the query parameters
    // Sanitize id
    $id = $_GET['id'];

    if (!ctype_digit($id)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid profile ID']);
        exit;
    }

    if (isset($data[$id])) { // Check if the requested profile exists
        http_response_code(200);
        echo json_encode($data[$id]);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Profile not found']);
    }
} else { // If no specific profile is requested, return all profiles
    http_response_code(200);
    echo json_encode($data);
}