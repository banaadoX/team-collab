<?php
include 'db_conn.php';

$id = (int)($_GET['id'] ?? 0);
$type = $_GET['type'] ?? '';

if (!$id || !in_array($type, ['FULL', 'QUICK'])) {
    die("Invalid request!");
}

if ($type === 'FULL') {
    $table = 'full_surveys';
    $result = mysqli_query($conn, "SELECT * FROM full_surveys WHERE id = $id");
} else {
    $table = 'quick_reports';
    $result = mysqli_query($conn, "SELECT * FROM quick_reports WHERE id = $id");
}

$row = mysqli_fetch_assoc($result);

if (!$row) {
    die("Record not found!");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Health Record</title>
    <style>
body{
    font-family: Arial, sans-serif;
    background:#f1f5f9;
    padding:40px;
}

.form-container{
    max-width:500px;
    margin:auto;
    background:white;
    padding:30px;
    border-radius:10px;
    box-shadow:0 2px 8px rgba(0,0,0,0.1);
}

h2{
    margin-bottom:20px;
    color:#334155;
}

label{
    display:block;
    margin-top:15px;
    margin-bottom:5px;
    font-weight:bold;
}

input, select{
    width:100%;
    padding:10px;
    border:1px solid #cbd5e1;
    border-radius:5px;
}

button{
    margin-top:20px;
    padding:12px 18px;
    border:none;
    border-radius:5px;
    cursor:pointer;
    font-weight:bold;
}

.btn-save{
    background:#10b981;
    color:white;
}

.btn-back{
    background:#64748b;
    color:white;
    text-decoration:none;
    padding:12px 18px;
    border-radius:5px;
    margin-left:10px;
}
</style>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        input, select, textarea { width: 100%; padding: 8px; margin: 8px 0; }
        button { padding: 10px 20px; background: #10b981; color: white; border: none; border-radius: 5px; cursor: pointer; }
    </style>
</head>
<body>
    <h2>Edit <?= $type ?> Record (ID: <?= $id ?>)</h2>
    <form method="POST" action="update_health.php">
        <input type="hidden" name="id" value="<?= $id ?>">
        <input type="hidden" name="type" value="<?= $type ?>">

        <label>First Name:</label>
        <input type="text" name="first_name" value="<?= htmlspecialchars($row['first_name'] ?? '') ?>">

        <label>Last Name:</label>
        <input type="text" name="last_name" value="<?= htmlspecialchars($row['last_name'] ?? '') ?>">

        <label>Middle Initial:</label>
        <input type="text" name="middle_initial" value="<?= htmlspecialchars($row['middle_initial'] ?? '') ?>">

        <label>Product Type:</label>
        <input type="text" name="product_type" value="<?= htmlspecialchars($row['product_type'] ?? '') ?>">

        <label>Stall ID:</label>
        <input type="text" name="stall_id" value="<?= htmlspecialchars($row['stall_id'] ?? '') ?>">

        <label>Symptoms:</label>
        <textarea name="symptoms"><?= htmlspecialchars($row['symptoms'] ?? '') ?></textarea>

        <?php if ($type === 'FULL'): ?>
            <label>Temperature:</label>
            <input type="text" name="temperature" value="<?= htmlspecialchars($row['temperature'] ?? '') ?>">
        <?php else: ?>
            <label>Temperature:</label>
            <input type="text" name="temperature" value="<?= htmlspecialchars($row['qb_temp'] ?? '') ?>">
        <?php endif; ?>

        <button type="submit">Update Record</button>
        <a href="AdminDashboard.php" style="margin-left: 20px; color: red;">Cancel</a>
    </form>
</body>
</html>