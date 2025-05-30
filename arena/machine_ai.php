<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$options = $data['options'] ?? [];

if (!is_array($options) || count($options) === 0) {
    http_response_code(400);
    echo json_encode(['error' => 'No options received']);
    exit();
}

// Random index
$index = array_rand($options);
$chosen = $options[$index];

echo json_encode([
    'chosen_img' => $chosen['img'],
    'correct' => $chosen['correct']
]);
?>