<?php
// Profile Page - profile.php
session_start();
require_once '../db.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Fetch user details by username
$username = $_SESSION['username'];
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Query preparation failed: " . $conn->error);
}

$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $admin = $result->fetch_assoc();
} else {
    echo "<script>alert('User not found. Please log in again.'); window.location.href = 'login.php';</script>";
    exit();
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .profile-container {
            max-width: 600px;
            margin: 50px auto;
        }
        .card-header {
            background-color: #007bff;
            color: white;
            font-size: 1.5rem;
            text-align: center;
        }
        .btn-edit {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }
        .btn-edit:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="container profile-container">
    <div class="card shadow-sm">
        <div class="card-header">Your Profile</div>
        <div class="card-body">
            <p><strong>Full Name:</strong> <?= htmlspecialchars($admin['full_name']) ?></p>
            <p><strong>Username:</strong> <?= htmlspecialchars($admin['username']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($admin['email']) ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($admin['phone'] ?? 'Not provided') ?></p>
            <p><strong>Date of Birth:</strong> <?= htmlspecialchars($admin['dob'] ?? 'Not provided') ?></p>
            <p><strong>Address:</strong> <?= htmlspecialchars($admin['address'] ?? 'Not provided') ?></p>
        </div>
        <div class="card-footer text-center">
            <a href="edit_profile.php" class="btn btn-edit">Edit Profile</a>
        </div>
        <div class="card-footer text-center">
            <a href="client_dashboard.php" class="btn btn-edit">Back To Dashboad</a>
        </div>
    </div>
</div>
</body>
</html>
