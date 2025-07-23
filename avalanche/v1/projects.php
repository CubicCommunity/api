<?php
require "../utils.php";

$utils = new Utils();

header('Content-Type: application/json');
$utils->checkMethod($_SERVER['REQUEST_METHOD'], RequestMethod::GET);

$url = 'https://gh.cubicstudios.xyz/WebLPS/data/avalProjects.json';
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

$data = json_decode($json, true);
if ($data === null) {
    http_response_code(500);

    echo json_encode([
        'error' => 'Failed to decode JSON',
        'json_last_error' => json_last_error_msg(),
    ]);

    curl_close($ch);
    exit;
}

curl_close($ch);

// Get and sanitize the latest project ID if present
$latestId = isset($data['latest']) ? $data['latest'] : null;

// Remove non-number keys (keep only project IDs)
$data = array_filter($data, function ($key) {
    return ctype_digit((string) $key);
}, ARRAY_FILTER_USE_KEY);

if (isset($_GET['latest'])) {
    // Sanitize latestId
    $latestId = (string) $latestId;

    if (!ctype_digit($latestId)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid latest project ID']);
        exit;
    }

    if (isset($data[$latestId])) {
        http_response_code(200);
        echo json_encode($data[$latestId]);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Latest project not found']);
    }
} elseif (isset($_GET['id'])) {
    $id = $_GET['id'];

    if (!ctype_digit($id)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid project ID']);
        exit;
    }

    if (isset($data[$id])) {
        http_response_code(200);
        echo json_encode($data[$id]);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Project not found']);
    }
} else {
    http_response_code(200);
    echo json_encode($data);
}