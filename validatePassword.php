<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$password = $data['password'];

// Load passwords and their usage from a file
$passwordsFile = 'passwords.json';
if (!file_exists($passwordsFile)) {
    echo json_encode(['valid' => false]);
    exit;
}

$passwords = json_decode(file_get_contents($passwordsFile), true);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['valid' => false]);
    exit;
}

if (isset($passwords[$password]) && $passwords[$password]['uses'] < 30) {
    $passwords[$password]['uses'] += 1; // Increment usage count
    file_put_contents($passwordsFile, json_encode($passwords)); // Save updated usage
    echo json_encode(['valid' => true]);
} else {
    echo json_encode(['valid' => false]);
}
?>
