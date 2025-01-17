<?php
// db_config.php - Database configuration

$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "sonibuild";

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>