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
    // Get unread notifications
    $unread = DB::query("
        SELECT n.*, u.first_name, u.last_name 
        FROM notifications n
        JOIN users u ON n.source_id = u.id
        WHERE n.user_id = %i AND n.is_read = 0
        ORDER BY n.created_at DESC
    ", $userId);

    // Get read notifications (limit to 10 most recent)
    $read = DB::query("
        SELECT n.*, u.first_name, u.last_name 
        FROM notifications n
        JOIN users u ON n.source_id = u.id
        WHERE n.user_id = %i AND n.is_read = 1
        ORDER BY n.created_at DESC
        LIMIT 10
    ", $userId);

    // Get unread count
    $unreadCount = DB::queryFirstField("
        SELECT COUNT(*) 
        FROM notifications 
        WHERE user_id = %i AND is_read = 0
    ", $userId);

    echo json_encode([
        'unread' => $unread,
        'read' => $read,
        'unread_count' => (int)$unreadCount
    ]);

} catch (MeekroDBException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>