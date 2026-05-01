<?php
session_start();
if (empty($_SESSION['authed'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

header('Content-Type: application/json; charset=utf-8');

$data_file = dirname(__FILE__) . '/../data/data.json';

if (!file_exists($data_file)) {
    echo json_encode([]);
    exit;
}

$all = json_decode(file_get_contents($data_file), true) ?: [];
krsort($all);

echo json_encode($all, JSON_UNESCAPED_UNICODE);
