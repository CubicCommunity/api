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

$url = 'https://gh.cubicstudios.xyz/WebLPS/data/avalProjects.json';
$ch = curl_init($url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FAILONERROR, true);

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
if ($data === null) { // Check if JSON decoding failed
    http_response_code(500);

    echo json_encode([
        'error' => 'Failed to decode JSON',
        'json_last_error' => json_last_error_msg(),
    ]);

    curl_close($ch);
    exit;
}

curl_close($ch);

$latestId = null;
isset($_GET['latest']) ? $latestId = $data['latest'] : $latestId = null; // Check if 'latest' is set in the query parameters

// Remove non-number keys
$data = array_filter($data, function ($key) {
    return ctype_digit((string) $key);
}, ARRAY_FILTER_USE_KEY);

if (isset($_GET['latest']) && $latestId !== null) {
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
    // Sanitize id
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