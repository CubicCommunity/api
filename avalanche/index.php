<?php
require "./utils.php";

$utils = new Utils();

// Set the content type to JSON
header('Content-Type: application/json');

// The kind of request we're expecting
$utils->checkMethod($_SERVER['REQUEST_METHOD'], RequestMethod::GET);

echo json_encode([
    'service' => 'avalanche',
    'status' => 'ok',
    'message' => 'Avalanche API service root',
]);