<?php
// filepath: d:\Repositories\api\avalanche\v1\Projects.php
header('Content-Type: application/json');

$url = 'https://gh.cubicstudios.xyz/WebLPS/data/avalProjects.json';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$json = curl_exec($ch);

if ($json === false) {
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
if ($data === null) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Failed to decode JSON',
        'json_last_error' => json_last_error_msg(),
    ]);
    exit;
}

unset($data['$schema']);

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    if (isset($data[$id])) {
        echo json_encode($data[$id]);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Project not found']);
    }
} else {
    echo json_encode($data);
}