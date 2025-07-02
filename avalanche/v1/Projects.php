<?php
// filepath: public_html/api/projects.php
header('Content-Type: application/json');
$data = json_decode(file_get_contents('https://gh.cubicstudios.xyz/WebLPS/data/avalProjects.json'), true);

if ($data === null) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to retrieve data']);
    exit;
} else {
    unset($data['$schema']);

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
}