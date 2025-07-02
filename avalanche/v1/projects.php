<?php
// Set the content type to JSON
header('Content-Type: application/json');

$url = 'https://gh.cubicstudios.xyz/WebLPS/data/avalProjects.json';
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

if (isset($_GET['latest'])) { // Check if 'latest' is set in the query parameters
    $latestId = $data['latest'];
} else {
    $latestId = null;
}

// Remove non-number keys
$data = array_filter($data, function ($key) {
    return ctype_digit((string) $key);
}, ARRAY_FILTER_USE_KEY);

if (isset($_GET['latest']) && $latestId !== null) { // Check if 'latest' is set in the query parameters and if it has a valid value
    if (isset($data[$latestId])) { // Check if the latest project exists
        http_response_code(200);
        echo json_encode($data[$latestId]);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Latest project not found']);
    }
} elseif (isset($_GET['id'])) { // Check if 'id' is set in the query parameters
    $id = $_GET['id'];

    if (isset($data[$id])) { // Check if the requested project exists
        http_response_code(200);
        echo json_encode($data[$id]);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Project not found']);
    }
} else { // If no specific project or latest is requested, return all projects
    http_response_code(200);
    echo json_encode($data);
}