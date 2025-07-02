<?php
// filepath: d:\Repositories\api\index.php
header('Content-Type: application/json');
echo json_encode([
    'service' => 'cubicstudios',
    'status' => 'ok',
    'message' => 'Cubic Studios API root',
]);