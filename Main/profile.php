<?php
// Profile Page - profile.php
session_start();
require_once '../db.php';

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Get admin details from the database
$admin_id = $_SESSION['id'];
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2>Profile Information</h2>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Admin Details</h5>
            <p><strong>Full Name:</strong> <?= $admin['full_name'] ?></p>
            <p><strong>Username:</strong> <?= $admin['username'] ?></p>
            <p><strong>Email:</strong> <?= $admin['email'] ?></p>
            <p><strong>Role:</strong> <?= ucfirst($admin['role']) ?></p>
            <p><strong>Joined On:</strong> <?= $admin['created_at'] ?></p>
        </div>
    </div>

    <a href="edit_profile.php" class="btn btn-primary mt-3">Edit Profile</a>
</div>

</body>
</html>

<?php
$conn->close();
?>
