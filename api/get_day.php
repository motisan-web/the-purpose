<?php
header('Content-Type: application/json; charset=utf-8');

$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid date format']);
    exit;
}

$data_file = dirname(__FILE__) . '/../data/data.json';

if (!file_exists($data_file)) {
    echo json_encode(['items' => ['', '', ''], 'goals' => []]);
    exit;
}

$all = json_decode(file_get_contents($data_file), true) ?: [];
$day = isset($all[$date]) ? $all[$date] : ['items' => ['', '', ''], 'goals' => []];

echo json_encode($day);
