<?php
require "../utils.php";

$utils = new Utils();

// Set the content type to JSON
header('Content-Type: application/json');

// The kind of request we're expecting
$utils->checkMethod($_SERVER['REQUEST_METHOD'], RequestMethod::GET);

$url = 'https://gh.cubicstudios.xyz/WebLPS/data/avalProfiles.json';
$ch = curl_init($url);

curl_setopt($ch, CURLOPT_USERAGENT, $utils->agent);
curl_setopt_array($ch, $utils->headersMinimal);

$json = curl_exec($ch);
if ($json === false) {
    $errorMsg = curl_error($ch);
    $errorCode = curl_errno($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    http_response_code(500);
    echo json_encode([
        'error' => 'Failed to fetch remote file',
        'curl_error' => $errorMsg,
        'curl_errno' => $errorCode,
        'http_code' => $httpCode,
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