<?php
include 'db_conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $last_name      = mysqli_real_escape_string($conn, $_POST['qb_last_name'] ?? '');
    $first_name     = mysqli_real_escape_string($conn, $_POST['qb_first_name'] ?? '');
    $middle_initial = mysqli_real_escape_string($conn, $_POST['qb_middle_initial'] ?? '');
    $full_name      = trim("$first_name $middle_initial $last_name");

    $address        = mysqli_real_escape_string($conn, $_POST['qb_address'] ?? '');

    $symptoms       = isset($_POST['qb_symptoms']) ? implode(", ", $_POST['qb_symptoms']) : "None";
    $symptoms_other = mysqli_real_escape_string($conn, $_POST['qb_symptoms_other'] ?? '');

    $qb_temp        = (float)($_POST['qb_temp'] ?? 0);
    $qb_pain_scale  = (int)($_POST['qb_pain_scale'] ?? 0);

    $product_type   = ($_POST['qb_product_type'] === 'Others') 
                      ? mysqli_real_escape_string($conn, $_POST['qb_product_other'] ?? '') 
                      : mysqli_real_escape_string($conn, $_POST['qb_product_type'] ?? '');

    $bought_altura  = mysqli_real_escape_string($conn, $_POST['qb_bought_altura'] ?? '');
    $stall_id       = mysqli_real_escape_string($conn, $_POST['qb_stall_id'] ?? '');

    // Photo Upload
    $qb_photo = "";
    if (isset($_FILES['qb_photo']) && $_FILES['qb_photo']['error'] == 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $qb_photo = time() . "_" . basename($_FILES["qb_photo"]["name"]);
        move_uploaded_file($_FILES["qb_photo"]["tmp_name"], $target_dir . $qb_photo);
    }

    $sql = "INSERT INTO quick_reports (
                full_name, last_name, first_name, middle_initial, address,
                symptoms, symptoms_other, qb_temp, qb_pain_scale,
                product_type, bought_altura, stall_id, qb_photo
            ) VALUES (
                '$full_name', '$last_name', '$first_name', '$middle_initial', '$address',
                '$symptoms', '$symptoms_other', $qb_temp, $qb_pain_scale,
                '$product_type', '$bought_altura', '$stall_id', '$qb_photo'
            )";

    if (mysqli_query($conn, $sql)) {
        echo "<script>
                alert('Quick Report Submitted Successfully!');
                window.location.href='index.html';
              </script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>