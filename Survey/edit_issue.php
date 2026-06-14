<?php
include 'db_conn.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    die("Invalid Issue ID");
}

$query = "SELECT * FROM issue_reports WHERE id = $id";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

if (!$row) {
    die("Issue report not found!");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Issue Report #<?= $id ?></title>
    <style>
        body { font-family: Arial, sans-serif; background:#f1f5f9; padding:40px; }
        .form-container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        label { display: block; margin: 15px 0 5px; font-weight: bold; }
        input, select, textarea { width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 5px; }
        button { padding: 12px 20px; background: #10b981; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; }
        .btn-back { background: #64748b; }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Edit Issue Report (ID: <?= $id ?>)</h2>

    <form method="POST" action="update_issue.php" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $id ?>">
        <input type="hidden" name="current_photo" value="<?= htmlspecialchars($row['qc_photo']) ?>">

        <label>Stall/Vendor</label>
        <input type="text" name="stall_id" value="<?= htmlspecialchars($row['stall_id']) ?>" required>

        <label>Reported Issue</label>
        <select name="issues" required>
            <?php
            $options = ["Spoiled Food","Bad Product Smell","Discolored Goods","Undercooked Food",
                        "Foreign Object","Pest","Waste Management","Drainage Issue","Others"];
            foreach ($options as $opt) {
                $selected = ($row['issues'] == $opt) ? 'selected' : '';
                echo "<option value='$opt' $selected>$opt</option>";
            }
            ?>
        </select>

        <label>Description</label>
        <textarea name="description" rows="4"><?= htmlspecialchars($row['description']) ?></textarea>

        <label>Current Photo</label>
        <?php if (!empty($row['qc_photo'])): ?>
            <a href="uploads/<?= htmlspecialchars($row['qc_photo']) ?>" target="_blank">View Current Photo</a><br><br>
        <?php endif; ?>

        <label>New Photo (optional)</label>
        <input type="file" name="qc_photo" accept="image/*">

        <button type="submit">Save Changes</button>
        <a href="AdminDashboard.php" class="btn-back" style="padding:12px 20px; color:white; text-decoration:none; border-radius:5px; margin-left:10px;">Cancel</a>
    </form>
</div>

</body>
</html>