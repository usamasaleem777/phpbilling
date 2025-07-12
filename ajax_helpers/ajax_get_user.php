<?php
require('../functions.php');

header('Content-Type: application/json');

try {
    $action = $_GET['action'] ?? '';

    // Enable error logging for debugging
    error_log("Received action: " . $action);
    
    switch ($action) {
        case 'get_users':
            $status_filter = $_GET['status_filter'] ?? '';
            $role_filter = $_GET['role_filter'] ?? '';
            $id = $_GET['user_id'] ?? null;

            $where = [];
            $params = [];

            if (!empty($id)) {
                $where[] = 'u.user_id = %i';
                $params[] = $id;
            }

            if (!empty($status_filter)) {
                $where[] = 'u.status = %s';
                $params[] = $status_filter;
            }

            if (!empty($role_filter)) {
                $where[] = 'r.id = %i';
                $params[] = $role_filter;
            }

            $query = "SELECT 
                u.user_id,
                u.first_name,
                u.last_name,
                u.email,
                u.name,
                u.status,
                u.last_active,
                u.picture,
                r.name as role_name
            FROM users u
            LEFT JOIN roles r ON u.role_id = r.id";

            if (!empty($where)) {
                $query .= " WHERE " . implode(' AND ', $where);
            }

            error_log("Executing query: " . $query);
            error_log("With params: " . print_r($params, true));

            $users = DB::query($query, ...$params);
            
            error_log("Found " . count($users) . " users");
            
            echo json_encode([
                'success' => true,
                'data' => $users,
                'debug' => [
                    'query' => $query,
                    'params' => $params
                ]
            ]);
            break;

        case 'get_roles':
            $roles = DB::query("SELECT * FROM roles");
            echo json_encode(['success' => true, 'data' => $roles]);
            break;

        default:
            echo json_encode([
                'success' => false,
                'message' => 'Invalid action specified'
            ]);
    }
} catch (Exception $e) {
    error_log("Error in get_users.php: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'trace' => $e->getTrace() // Only in development!
    ]);
}