<?php
include 'db_conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id     = (int)$_POST['id'];
    $type   = mysqli_real_escape_string($conn, $_POST['type']);

    $first_name     = mysqli_real_escape_string($conn, $_POST['first_name'] ?? '');
    $last_name      = mysqli_real_escape_string($conn, $_POST['last_name'] ?? '');
    $middle_initial = mysqli_real_escape_string($conn, $_POST['middle_initial'] ?? '');
    $product_type   = mysqli_real_escape_string($conn, $_POST['product_type'] ?? '');
    $stall_id       = mysqli_real_escape_string($conn, $_POST['stall_id'] ?? '');
    $symptoms       = mysqli_real_escape_string($conn, $_POST['symptoms'] ?? '');

    if ($type === 'FULL') {
        $temp = (float)($_POST['temperature'] ?? 0);
        $sql = "UPDATE full_surveys SET 
                    first_name = '$first_name',
                    last_name = '$last_name',
                    middle_initial = '$middle_initial',
                    product_type = '$product_type',
                    stall_id = '$stall_id',
                    symptoms = '$symptoms',
                    temperature = $temp
                WHERE id = $id";
    } else {
        $temp = (float)($_POST['temperature'] ?? 0);
        $sql = "UPDATE quick_reports SET 
                    first_name = '$first_name',
                    last_name = '$last_name',
                    middle_initial = '$middle_initial',
                    product_type = '$product_type',
                    stall_id = '$stall_id',
                    symptoms = '$symptoms',
                    qb_temp = $temp
                WHERE id = $id";
    }

    if (mysqli_query($conn, $sql)) {
        echo "<script>
                alert('Record updated successfully!');
                window.location.href='AdminDashboard.php';
              </script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>