<?php
// filepath: d:\Repositories\api\avalanche\v1\Projects.php
header('Content-Type: application/json');

// Fetch the remote JSON file
$json = file_get_contents('https://gh.cubicstudios.xyz/WebLPS/data/avalProjects.json');
if ($json === false) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch remote file']);
    exit;
}

// Decode the JSON data
$data = json_decode($json, true);
if ($data === null) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Failed to decode JSON',
        'json_last_error' => json_last_error_msg(),
    ]);
    exit;
}

// Remove $schema if present
unset($data['$schema']);

// Handle query by id
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    if (isset($data[$id])) {
        echo json_encode($data[$id]);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Profile not found']);
    }
} else {
    echo json_encode($data);
}