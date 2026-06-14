<?php
include 'db_conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = (int)$_POST['id'];
    $stall_id = mysqli_real_escape_string($conn, $_POST['stall_id']);
    $issues   = mysqli_real_escape_string($conn, $_POST['issues']);
    $description = mysqli_real_escape_string($conn, $_POST['description'] ?? '');

    $photoName = $_POST['current_photo'] ?? '';

    // New photo uploaded?
    if (!empty($_FILES['qc_photo']['name'])) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        
        $photoName = time() . "_" . basename($_FILES['qc_photo']['name']);
        $target_file = $target_dir . $photoName;

        if (move_uploaded_file($_FILES['qc_photo']['tmp_name'], $target_file)) {
            // Optionally delete old photo here
        }
    }

    $sql = "UPDATE issue_reports SET 
                stall_id = '$stall_id',
                issues = '$issues',
                description = '$description',
                qc_photo = '$photoName'
            WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        echo "<script>
                alert('Issue report updated successfully!');
                window.location.href='AdminDashboard.php';
              </script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
mysqli_close($conn);
?>