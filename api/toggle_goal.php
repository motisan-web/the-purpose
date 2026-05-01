<?php
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['date'], $input['index'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input']);
    exit;
}

$date  = $input['date'];
$index = (int)$input['index'];

if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid date format']);
    exit;
}

$data_file = dirname(__FILE__) . '/../data/data.json';
$all = file_exists($data_file) ? (json_decode(file_get_contents($data_file), true) ?: []) : [];

if (!isset($all[$date]['goals'][$index])) {
    http_response_code(404);
    echo json_encode(['error' => 'Goal not found']);
    exit;
}

$all[$date]['goals'][$index]['achieved'] = true;

file_put_contents($data_file, json_encode($all, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo json_encode(['ok' => true]);
