<?php
session_start();
if (empty($_SESSION['authed'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['date'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input']);
    exit;
}

$date = $input['date'];
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid date format']);
    exit;
}

$items = array_slice(array_map('strval', $input['items'] ?? ['', '', '']), 0, 3);
while (count($items) < 3) $items[] = '';

$raw_goals = $input['goals'] ?? [];
$goals = [];
foreach ($raw_goals as $g) {
    if (!isset($g['time'], $g['text'])) continue;
    if (!preg_match('/^\d{2}:\d{2}$/', $g['time'])) continue;
    $goals[] = [
        'time'     => $g['time'],
        'text'     => strval($g['text']),
        'achieved' => isset($g['achieved']) ? (bool)$g['achieved'] : false,
    ];
}

usort($goals, fn($a, $b) => strcmp($a['time'], $b['time']));

$data_file = dirname(__FILE__) . '/../data/data.json';
$all = file_exists($data_file) ? (json_decode(file_get_contents($data_file), true) ?: []) : [];

$all[$date] = ['items' => $items, 'goals' => $goals];

file_put_contents($data_file, json_encode($all, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo json_encode(['ok' => true]);
