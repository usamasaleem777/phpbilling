<?php
require('../functions.php');
header('Content-Type: application/json');


session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$userId = $_SESSION['user_id'];

try {
    // Mark all notifications as read
    DB::update('notifications', [
        'is_read' => 1
    ], "user_id = %i AND is_read = 0", $userId);

    echo json_encode(['success' => true]);

} catch (MeekroDBException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>