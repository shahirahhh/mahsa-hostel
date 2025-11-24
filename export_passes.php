fputcsv($output, ['Pass ID', 'Student ID', 'Status', 'Date']);
$sql = "SELECT id, student_id, status, created_at FROM passes";
