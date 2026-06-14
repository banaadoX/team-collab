<?php
include 'db_conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $stall_id       = mysqli_real_escape_string($conn, $_POST['qc_stall_id'] ?? '');
    $description    = mysqli_real_escape_string($conn, $_POST['qc_description'] ?? '');
    $issues_other   = mysqli_real_escape_string($conn, $_POST['qc_issues_other'] ?? '');
    $pest_type      = mysqli_real_escape_string($conn, $_POST['qc_pest_type'] ?? 'N/A');
    $obs_time       = mysqli_real_escape_string($conn, $_POST['qc_obs_time'] ?? '');

    $issues = isset($_POST['qc_issues']) && !empty($_POST['qc_issues']) 
              ? implode(", ", $_POST['qc_issues']) 
              : "None";

    // Photo Upload
    $photo_name = "";
    if (isset($_FILES['qc_photo']) && $_FILES['qc_photo']['error'] == 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);

        $photo_name = time() . "_" . basename($_FILES["qc_photo"]["name"]);
        $target_file = $target_dir . $photo_name;

        if (move_uploaded_file($_FILES["qc_photo"]["tmp_name"], $target_file)) {
            // success
        } else {
            $photo_name = "";
        }
    }

    $sql = "INSERT INTO issue_reports (
                stall_id, issues, pest_type, issues_other, 
                qc_photo, description, observed_at
            ) VALUES (
                '$stall_id', '$issues', '$pest_type', '$issues_other',
                '$photo_name', '$description', '$obs_time'
            )";

    if (mysqli_query($conn, $sql)) {
        echo "<script>
                alert('Issue Reported Successfully! Thank you.');
                window.location.href='index.html';
              </script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>