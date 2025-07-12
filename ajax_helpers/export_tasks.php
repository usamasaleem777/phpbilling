<?php
require('../functions.php');

// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit;
}

// Get the export type and filters from the request
$data = json_decode(file_get_contents('php://input'), true);
$exportType = $data['exportType'] ?? '';
$filters = $data['filters'] ?? [];

// Validate export type
$allowedTypes = ['pdf', 'csv', 'excel'];
if (!in_array($exportType, $allowedTypes)) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Invalid export type']);
    exit;
}

// Build the query conditions based on filters
$whereConditions = [];
$whereValues = [];

if (!empty($filters['project_id'])) {
    $whereConditions[] = 't.project_id = %i';
    $whereValues[] = $filters['project_id'];
}

if (!empty($filters['date_from'])) {
    $whereConditions[] = 't.task_date >= %s';
    $whereValues[] = $filters['date_from'];
}

if (!empty($filters['date_to'])) {
    $whereConditions[] = 't.task_date <= %s';
    $whereValues[] = $filters['date_to'];
}

if (!empty($filters['assignee_id'])) {
    $whereConditions[] = 't.assignee_id = %i';
    $whereValues[] = $filters['assignee_id'];
}

// Prepare the where clause
$whereClause = '';
if (!empty($whereConditions)) {
    $whereClause = 'WHERE ' . implode(' AND ', $whereConditions);
}

try {
    // CORRECTED QUERY - Fixed table alias from 'i' to 't'
    $tasks = DB::query("
        SELECT 
            t.id,
            t.title,
            p.name AS project_name,
            t.assignee_id,
            t.task_date,
            t.details,
            t.hours,
            t.clickup_link,
            t.created_at,
            t.updated_at
        FROM tasks t
        LEFT JOIN projects p ON t.project_id = p.id
        $whereClause
        ORDER BY t.task_date DESC
    ", ...$whereValues);
    
    // Process the export based on type
    switch ($exportType) {
        case 'pdf':
            exportToPDF($tasks);
            break;
        case 'csv':
            exportToCSV($tasks);
            break;
        case 'excel':
            exportToExcel($tasks);
            break;
    }
} catch (MeekroDBException $e) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
    exit;
}

function exportToPDF($tasks) {
    $html = '<!DOCTYPE html>
    <html>
    <head>
        <title>Tasks Export</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; }
            h1 { color: #333; text-align: center; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th { background-color: #f2f2f2; text-align: left; padding: 8px; border: 1px solid #ddd; }
            td { padding: 8px; border: 1px solid #ddd; }
            .text-right { text-align: right; }
            .text-center { text-align: center; }
            .status-completed { color: green; }
            .status-pending { color: orange; }
            @media print {
                body { margin: 0; padding: 0; }
                .no-print { display: none; }
            }
        </style>
    </head>
    <body>
        <h1>Tasks List</h1>
        <button class="no-print" onclick="window.print()" style="padding: 10px 20px; background: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer; margin-bottom: 20px;">
            Print/Save as PDF
        </button>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Project</th>
                    <th>Assignee ID</th>
                    <th>Task Date</th>
                    <th>Hours</th>
                    <th>Details</th>
                    <th>ClickUp Link</th>
                </tr>
            </thead>
            <tbody>';
    
    foreach ($tasks as $task) {
        $html .= '<tr>
            <td>' . htmlspecialchars($task['id']) . '</td>
            <td>' . htmlspecialchars($task['title']) . '</td>
            <td>' . htmlspecialchars($task['project_name']) . '</td>
            <td class="text-center">' . htmlspecialchars($task['assignee_id']) . '</td>
            <td class="text-center">' . date('m/d/Y', strtotime($task['task_date'])) . '</td>
            <td class="text-center">' . htmlspecialchars($task['hours']) . '</td>
            <td>' . nl2br(htmlspecialchars($task['details'])) . '</td>
            <td>' . (!empty($task['clickup_link']) ? htmlspecialchars($task['clickup_link']) : 'N/A') . '</td>
        </tr>';
    }
    
    $html .= '</tbody>
        </table>
        <p>Generated on: ' . date('Y-m-d H:i:s') . '</p>
        <script>
            window.onload = function() {
                window.print();
                setTimeout(function() {
                    window.close();
                }, 1000);
            };
        </script>
    </body>
    </html>';
    
    header('Content-Type: text/html');
    echo $html;
    exit;
}

function exportToCSV(array $tasks): void {
    if (headers_sent()) {
        throw new RuntimeException("Cannot export CSV: Headers already sent.");
    }

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="tasks_export_' . date('Y-m-d') . '.csv"');
    header('Pragma: no-cache');
    header('Expires: 0');

    $output = fopen('php://output', 'w');
    if ($output === false) {
        throw new RuntimeException("Failed to open output stream.");
    }

    fwrite($output, "\xEF\xBB\xBF"); // UTF-8 BOM

    // CSV Header with ALL fields
    fputcsv($output, [
        'ID',
        'Title',
        'Project',
        'Assignee ID',
        'Task Date',
        'Hours',
        'Details',
        'ClickUp Link',
        'Created At',
        'Updated At'
    ]);

    foreach ($tasks as $task) {
        $row = [
            $task['id'] ?? '',
            $task['title'] ?? '',
            $task['project_name'] ?? '',
            $task['assignee_id'] ?? '',
            isset($task['task_date']) ? date('Y-m-d', strtotime($task['task_date'])) : '',
            $task['hours'] ?? '',
            $task['details'] ?? '',
            $task['clickup_link'] ?? 'N/A',
            isset($task['created_at']) ? date('Y-m-d H:i:s', strtotime($task['created_at'])) : '',
            isset($task['updated_at']) ? date('Y-m-d H:i:s', strtotime($task['updated_at'])) : ''
        ];
        
        fputcsv($output, $row);
    }

    fclose($output);
    exit;
}

function exportToExcel($tasks) {
    $html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <!--[if gte mso 9]>
        <xml>
            <x:ExcelWorkbook>
                <x:ExcelWorksheets>
                    <x:ExcelWorksheet>
                        <x:Name>Tasks</x:Name>
                        <x:WorksheetOptions>
                            <x:DisplayGridlines/>
                        </x:WorksheetOptions>
                    </x:ExcelWorksheet>
                </x:ExcelWorksheets>
            </x:ExcelWorkbook>
        </xml>
        <![endif]-->
        <style>
            .text-right { text-align: right; }
            .text-center { text-align: center; }
            .status-completed { color: green; }
            .status-pending { color: orange; }
            table { border-collapse: collapse; width: 100%; }
            th { background-color: #f2f2f2; font-weight: bold; border: 1px solid #ddd; padding: 5px; }
            td { border: 1px solid #ddd; padding: 5px; }
        </style>
    </head>
    <body>
        <table>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Project</th>
                <th>Assignee ID</th>
                <th>Task Date</th>
                <th>Hours</th>
                <th>Details</th>
                <th>ClickUp Link</th>
                <th>Created At</th>
            </tr>';
    
    foreach ($tasks as $task) {
        $html .= '<tr>
            <td>' . htmlspecialchars($task['id']) . '</td>
            <td>' . htmlspecialchars($task['title']) . '</td>
            <td>' . htmlspecialchars($task['project_name']) . '</td>
            <td class="text-center">' . htmlspecialchars($task['assignee_id']) . '</td>
            <td class="text-center">' . date('Y-m-d', strtotime($task['task_date'])) . '</td>
            <td class="text-center">' . htmlspecialchars($task['hours']) . '</td>
            <td>' . htmlspecialchars($task['details']) . '</td>
            <td>' . (!empty($task['clickup_link']) ? htmlspecialchars($task['clickup_link']) : 'N/A') . '</td>
            <td class="text-center">' . date('Y-m-d H:i:s', strtotime($task['created_at'])) . '</td>
        </tr>';
    }
    
    $html .= '</table>
    <p>Generated on: ' . date('Y-m-d H:i:s') . '</p>
    </body>
    </html>';
    
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="tasks_export_' . date('Y-m-d') . '.xls"');
    header('Pragma: no-cache');
    header('Expires: 0');
    
    echo $html;
    exit;
}