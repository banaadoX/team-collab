
<?php
/*
$conn = mysqli_connect("localhost", "root", "", "altura_health_db");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
*/
?>

<?php
// InfinityFree Database Configuration
$host = "sql300.infinityfree.com";      // Replace XXX with your actual SQL server number (e.g., sql105.epizy.com)
$username = "if0_42178641";       // Your InfinityFree database username
$password = "dQcyjNTb6x0";      // Your InfinityFree vPanel password
$database = "if0_42178641_XXX";  // Your full database name

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
