<?php
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

// Update profile logic
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $dob = $_POST['dob'];
    $address = $_POST['address'];

    $update_sql = "UPDATE users SET full_name = ?, email = ?, phone = ?, dob = ?, address = ? WHERE username = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssssss", $full_name, $email, $phone, $dob, $address, $username);

    if ($stmt->execute()) {
        echo "<script>alert('Profile updated successfully!'); window.location.href = 'profile.php';</script>";
    } else {
        echo "<script>alert('Error updating profile.');</script>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container profile-container">
    <div class="card shadow-sm">
        <div class="card-header">Edit Profile</div>
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label for="full_name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="full_name" name="full_name" value="<?= htmlspecialchars($admin['full_name']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($admin['email']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($admin['phone'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label for="dob" class="form-label">Date of Birth</label>
                    <input type="date" class="form-control" id="dob" name="dob" value="<?= htmlspecialchars($admin['dob'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control" id="address" name="address"><?= htmlspecialchars($admin['address'] ?? '') ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Update Profile</button>
                <div class="card-footer text-center">
            <a href="client_dashboard.php" class="btn btn-edit">Back To Dashboad</a>
        </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
