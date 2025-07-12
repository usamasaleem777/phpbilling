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

if (!empty($filters['client_id'])) {
    $whereConditions[] = 'i.client_id = %i';
    $whereValues[] = $filters['client_id'];
}

if (!empty($filters['date_from'])) {
    $whereConditions[] = 'i.issue_date >= %s';
    $whereValues[] = $filters['date_from'];
}

if (!empty($filters['date_to'])) {
    $whereConditions[] = 'i.issue_date <= %s';
    $whereValues[] = $filters['date_to'];
}

if (!empty($filters['status']) && $filters['status'] !== 'all') {
    $whereConditions[] = 'i.status = %s';
    $whereValues[] = $filters['status'];
}

// Prepare the where clause
$whereClause = '';
if (!empty($whereConditions)) {
    $whereClause = 'WHERE ' . implode(' AND ', $whereConditions);
}

try {
    // Get invoices data using MeekroDB
    $invoices = DB::query("
        SELECT 
            i.id, 
            i.invoice_number, 
            CONCAT(c.first_name, ' ', c.last_name) AS client_name,
            p.name AS project_name,
            i.issue_date,
            i.due_date,
            i.status,
            i.total_amount
        FROM invoices i
        LEFT JOIN clients c ON i.client_id = c.id
        LEFT JOIN projects p ON i.project_id = p.id
        $whereClause
        ORDER BY i.issue_date DESC
    ", ...$whereValues);
    
    // Process the export based on type
    switch ($exportType) {
        case 'pdf':
            exportToPDF($invoices);
            break;
        case 'csv':
            exportToCSV($invoices);
            break;
        case 'excel':
            exportToExcel($invoices);
            break;
    }
} catch (MeekroDBException $e) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
    exit;
}

function exportToPDF($invoices) {
    // Generate HTML content
    $html = '<!DOCTYPE html>
    <html>
    <head>
        <title>Invoices Export</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; }
            h1 { color: #333; text-align: center; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th { background-color: #f2f2f2; text-align: left; padding: 8px; border: 1px solid #ddd; }
            td { padding: 8px; border: 1px solid #ddd; }
            .text-right { text-align: right; }
            .text-center { text-align: center; }
            @media print {
                body { margin: 0; padding: 0; }
                .no-print { display: none; }
            }
        </style>
    </head>
    <body>
        <h1>Invoices List</h1>
        <button class="no-print" onclick="window.print()" style="padding: 10px 20px; background: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer; margin-bottom: 20px;">
            Print/Save as PDF
        </button>
        <table>
            <thead>
                <tr>
                    <th>Invoice #</th>
                    <th>Client</th>
                    <th>Project</th>
                    <th>Issue Date</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>';
    
    foreach ($invoices as $invoice) {
        $html .= '<tr>
            <td>' . htmlspecialchars($invoice['invoice_number']) . '</td>
            <td>' . htmlspecialchars($invoice['client_name']) . '</td>
            <td>' . htmlspecialchars($invoice['project_name']) . '</td>
            <td class="text-center">' . date('m/d/Y', strtotime($invoice['issue_date'])) . '</td>
            <td class="text-center">' . date('m/d/Y', strtotime($invoice['due_date'])) . '</td>
            <td class="text-center">' . ucfirst($invoice['status']) . '</td>
            <td class="text-right">$' . number_format($invoice['total_amount'], 2) . '</td>
        </tr>';
    }
    
    $html .= '</tbody>
        </table>
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
    
    // Output the HTML
    header('Content-Type: text/html');
    echo $html;
    exit;
}

/**
 * Exports invoice data to a CSV file and forces download.
 *
 * @param array $invoices Array of invoice data.
 * @throws Exception If headers are already sent or CSV generation fails.
 */
function exportToCSV(array $invoices): void {
    // Check if headers are already sent
    if (headers_sent()) {
        throw new RuntimeException("Cannot export CSV: Headers already sent.");
    }

    // Set headers for CSV download
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="invoices_export_' . date('Y-m-d') . '.csv"');
    header('Pragma: no-cache');
    header('Expires: 0');

    // Open output stream
    $output = fopen('php://output', 'w');
    if ($output === false) {
        throw new RuntimeException("Failed to open output stream.");
    }

    // Add UTF-8 BOM (for Excel compatibility)
    fwrite($output, "\xEF\xBB\xBF");

    // Write CSV header
    fputcsv($output, [
        'Invoice Number',
        'Client',
        'Project',
        'Issue Date',
        'Due Date',
        'Status',
        'Amount'
    ]);

    // Write data rows
    foreach ($invoices as $invoice) {
        $row = [
            $invoice['invoice_number'] ?? '',
            $invoice['client_name'] ?? '',
            $invoice['project_name'] ?? '',
            isset($invoice['issue_date']) ? date('m/d/Y', strtotime($invoice['issue_date'])) : '',
            isset($invoice['due_date']) ? date('m/d/Y', strtotime($invoice['due_date'])) : '',
            isset($invoice['status']) ? ucfirst($invoice['status']) : '',
            isset($invoice['total_amount']) ? number_format((float) $invoice['total_amount'], 2) : '0.00'
        ];
        
        if (fputcsv($output, $row) === false) {
            throw new RuntimeException("Failed to write CSV data.");
        }
    }

    // Close the output stream
    fclose($output);
    exit;
}
function exportToExcel($invoices) {
    // Generate HTML content that Excel can open
    $html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <!--[if gte mso 9]>
        <xml>
            <x:ExcelWorkbook>
                <x:ExcelWorksheets>
                    <x:ExcelWorksheet>
                        <x:Name>Invoices</x:Name>
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
            table { border-collapse: collapse; width: 100%; }
            th { background-color: #f2f2f2; font-weight: bold; border: 1px solid #ddd; padding: 5px; }
            td { border: 1px solid #ddd; padding: 5px; }
        </style>
    </head>
    <body>
        <table>
            <tr>
                <th>Invoice Number</th>
                <th>Client</th>
                <th>Project</th>
                <th>Issue Date</th>
                <th>Due Date</th>
                <th>Status</th>
                <th class="text-right">Amount</th>
            </tr>';
    
    foreach ($invoices as $invoice) {
        $html .= '<tr>
            <td>' . htmlspecialchars($invoice['invoice_number']) . '</td>
            <td>' . htmlspecialchars($invoice['client_name']) . '</td>
            <td>' . htmlspecialchars($invoice['project_name']) . '</td>
            <td class="text-center">' . date('m/d/Y', strtotime($invoice['issue_date'])) . '</td>
            <td class="text-center">' . date('m/d/Y', strtotime($invoice['due_date'])) . '</td>
            <td class="text-center">' . ucfirst($invoice['status']) . '</td>
            <td class="text-right">' . number_format($invoice['total_amount'], 2) . '</td>
        </tr>';
    }
    
    $html .= '</table>
    </body>
    </html>';
    
    // Set headers for Excel download
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="invoices_export_' . date('Y-m-d') . '.xls"');
    header('Pragma: no-cache');
    header('Expires: 0');
    
    // Output the HTML
    echo $html;
    exit;
}