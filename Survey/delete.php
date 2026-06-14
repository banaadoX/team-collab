<?php
include 'db_conn.php';

if (isset($_GET['id']) && isset($_GET['type'])) {
    $id   = (int)$_GET['id'];           // Force integer
    $type = strtoupper(trim($_GET['type']));

    if ($type === 'FULL') {
        $sql = "DELETE FROM full_surveys WHERE id = $id";
    } elseif ($type === 'QUICK') {
        $sql = "DELETE FROM quick_reports WHERE id = $id";
    } elseif ($type === 'ISSUE') {
        $sql = "DELETE FROM issue_reports WHERE id = $id";
    } else {
        
        echo "<script>alert('Invalid record type!'); window.location.href='AdminDashboard.php';</script>";
        exit;
    }

    if (mysqli_query($conn, $sql)) {
        if (mysqli_affected_rows($conn) > 0) {
            echo "<script>
                    alert('Record deleted successfully!');
                    window.location.href='AdminDashboard.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Record not found or already deleted.');
                    window.location.href='AdminDashboard.php';
                  </script>";
        }
    } else {
        echo "<script>
                alert('Error deleting record: " . addslashes(mysqli_error($conn)) . "');
                window.location.href='AdminDashboard.php';
              </script>";
    }
} else {
    echo "<script>
            alert('Invalid request!');
            window.location.href='AdminDashboard.php';
          </script>";
}

mysqli_close($conn);
?>

