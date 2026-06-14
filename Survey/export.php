<?php
include 'db_conn.php';
date_default_timezone_set('Asia/Manila');

function outputCSV($filename, $headers, $data) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    $output = fopen('php://output', 'w');
    fputcsv($output, $headers);
    
    foreach ($data as $row) {
        fputcsv($output, $row);
    }
    fclose($output);
    exit;
}

// ========================= HEALTH LOGS EXPORT =========================
if (isset($_GET['type']) && $_GET['type'] === 'health') {
    
    $sql = "
        (SELECT id, 'FULL' as type, first_name, middle_initial, last_name, 
                product_type, stall_id, symptoms, temperature, pain_level, 
                onset_date, created_at 
         FROM full_surveys)
        UNION
        (SELECT id, 'QUICK' as type, first_name, middle_initial, last_name, 
                product_type, stall_id, symptoms, qb_temp as temperature, 
                qb_pain_scale as pain_level, NULL as onset_date, created_at 
         FROM quick_reports)
        ORDER BY created_at DESC";

    $result = mysqli_query($conn, $sql);
    
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $displayID = $row['type'] . str_pad($row['id'], 3, '0', STR_PAD_LEFT);
        
        $data[] = [
            $displayID,
            $row['first_name'],
            $row['middle_initial'],
            $row['last_name'],
            $row['product_type'],
            $row['stall_id'],
            $row['symptoms'],
            $row['temperature'],
            $row['pain_level'],
            $row['onset_date'],
            $row['created_at']
        ];
    }

    $headers = ['ID', 'First Name', 'M.I.', 'Last Name', 'Product Type', 
                'Stall ID', 'Symptoms', 'Temperature (°C)', 'Pain Level', 
                'Onset Date', 'Date Submitted'];

    outputCSV('Health_Monitoring_Logs_' . date('Y-m-d_H-i') . '.csv', $headers, $data);
}

// ========================= ISSUES EXPORT =========================
elseif (isset($_GET['type']) && $_GET['type'] === 'issue') {
    
    $sql = "SELECT id, stall_id, issues, pest_type, issues_other, 
                   description, qc_photo, observed_at, created_at 
            FROM issue_reports 
            ORDER BY created_at DESC";

    $result = mysqli_query($conn, $sql);
    
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $displayID = "ISSUE" . str_pad($row['id'], 3, '0', STR_PAD_LEFT);
        
        $data[] = [
            $displayID,
            $row['stall_id'],
            $row['issues'],
            $row['pest_type'],
            $row['issues_other'],
            $row['description'],
            $row['qc_photo'] ? 'uploads/' . $row['qc_photo'] : 'No Photo',
            $row['observed_at'],
            $row['created_at']
        ];
    }

    $headers = ['ID', 'Stall ID', 'Issues', 'Pest Type', 'Other Issues', 
                'Description', 'Photo', 'Observed At', 'Date Submitted'];

    outputCSV('Market_Issues_Report_' . date('Y-m-d_H-i') . '.csv', $headers, $data);
}

else {
    echo "Invalid export type!";
}

mysqli_close($conn);
?>