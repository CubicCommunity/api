<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    header('Allow: GET');
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

echo json_encode([
    'service' => 'cubicstudios',
    'status' => 'ok',
    'message' => 'Cubic Studios API root',
]);