<?php
// Example: d:\Repositories\api\avalanche\v1\index.php
header('Content-Type: application/json');
echo json_encode([
    'service' => 'avalanche',
    'status' => 'ok',
    'message' => 'Avalanche API service root',
]);