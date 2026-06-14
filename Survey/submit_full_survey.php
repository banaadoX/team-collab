<?php
include 'db_conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Personal Information
    $last_name      = mysqli_real_escape_string($conn, $_POST['last_name'] ?? '');
    $first_name     = mysqli_real_escape_string($conn, $_POST['first_name'] ?? '');
    $middle_initial = mysqli_real_escape_string($conn, $_POST['middle_initial'] ?? '');
    $full_name      = trim("$first_name $middle_initial $last_name");

    $age            = (int)($_POST['age'] ?? 0);
    $gender         = mysqli_real_escape_string($conn, $_POST['gender'] ?? '');
    $address        = mysqli_real_escape_string($conn, $_POST['address'] ?? '');
    $contact_no     = mysqli_real_escape_string($conn, $_POST['contact_no'] ?? '');

    // Health
    $vax_list       = isset($_POST['vax']) ? implode(", ", $_POST['vax']) : "None";
    $vax_details    = "MMR:" . ($_POST['vax_year_mmr'] ?? '') . 
                      " | Typhoid:" . ($_POST['vax_year_typ'] ?? '') .
                      " | HepA:" . ($_POST['vax_year_hep'] ?? '') .
                      " | Flu:" . ($_POST['vax_year_flu'] ?? '') .
                      " | Others:" . ($_POST['vax_other'] ?? '');

    $symptoms       = isset($_POST['symptoms']) ? implode(", ", $_POST['symptoms']) : "None";
    $symptoms_other = mysqli_real_escape_string($conn, $_POST['symptoms_other'] ?? '');
    $temp           = (float)($_POST['body_temp'] ?? 0);
    $pain           = (int)($_POST['pain_scale'] ?? 0);
    $onset_date     = mysqli_real_escape_string($conn, $_POST['onset_date'] ?? NULL);

    // Consumer Tracking
    $last_visit         = mysqli_real_escape_string($conn, $_POST['last_visit'] ?? '');
    $stall_id           = mysqli_real_escape_string($conn, $_POST['stall_id'] ?? '');
    $manual_stall_desc  = mysqli_real_escape_string($conn, $_POST['manual_stall_desc'] ?? '');

    $product_type = ($_POST['product_type'] === 'Others') 
                    ? mysqli_real_escape_string($conn, $_POST['product_other'] ?? '') 
                    : mysqli_real_escape_string($conn, $_POST['product_type'] ?? '');

    $time_consumption = mysqli_real_escape_string($conn, $_POST['time_consumption'] ?? '');
    $storage          = mysqli_real_escape_string($conn, $_POST['storage'] ?? '');
    $eat_site         = mysqli_real_escape_string($conn, $_POST['eat_site'] ?? '');

    $sql = "INSERT INTO full_surveys (
                full_name, last_name, first_name, middle_initial, age, gender, address, contact_no,
                vaccines, vax_details, symptoms, symptoms_other, temperature, pain_level, onset_date,
                last_visit, stall_id, manual_stall_desc, product_type, 
                time_consumption, storage_method, eat_site
            ) VALUES (
                '$full_name', '$last_name', '$first_name', '$middle_initial', $age, '$gender', '$address', '$contact_no',
                '$vax_list', '$vax_details', '$symptoms', '$symptoms_other', $temp, $pain, " . 
                ($onset_date ? "'$onset_date'" : "NULL") . ",
                '$last_visit', '$stall_id', '$manual_stall_desc', '$product_type',
                '$time_consumption', '$storage', '$eat_site'
            )";

    if (mysqli_query($conn, $sql)) {
        echo "<script>
                alert('Full Survey Submitted Successfully!');
                window.location.href='index.html';
              </script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>