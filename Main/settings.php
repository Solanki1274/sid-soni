<?php
// Edit Profile Page - edit_profile.php
session_start();
require_once '../db.php';

// Verify session
if (!isset($_SESSION['username'])) {
    echo "<script>alert('You are not logged in.'); window.location.href = 'login.php';</script>";
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

// Provide default values
$phone = $admin['phone'] ?? '';
$dob = $admin['dob'] ?? '';
$address = $admin['address'] ?? '';

// Update profile logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $updated_full_name = $_POST['full_name'] ?? $admin['full_name'];
    $updated_username = $_POST['username'] ?? $admin['username'];
    $updated_email = $_POST['email'] ?? $admin['email'];
    $updated_phone = $_POST['phone'] ?? $phone;
    $updated_dob = $_POST['dob'] ?? $dob;
    $updated_address = $_POST['address'] ?? $address;

    // Update query
    $update_sql = "UPDATE users SET full_name = ?, username = ?, email = ?, phone = ?, dob = ?, address = ? WHERE username = ?";
    $stmt = $conn->prepare($update_sql);

    if (!$stmt) {
        die("Query preparation failed: " . $conn->error);
    }

    $stmt->bind_param("sssssss", $updated_full_name, $updated_username, $updated_email, $updated_phone, $updated_dob, $updated_address, $username);

    // Execute the query and handle the result
    if ($stmt->execute()) {
        // Update session username if it was changed
        $_SESSION['username'] = $updated_username;

        echo "<script>alert('Profile updated successfully!'); window.location.href = 'admin_dashboard.php';</script>";
    } else {
        echo "<script>alert('Error updating profile. Please try again.'); window.location.href = 'settings.php';</script>";
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

<div class="container mt-5">
    <h2>Edit Profile</h2>

    <form method="POST">
        <div class="mb-3">
            <label for="full_name" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="full_name" name="full_name" value="<?= $admin['full_name'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" value="<?= $admin['username'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= $admin['email'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">New Password (Leave blank to keep current)</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>
        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>
</div>

</body>
</html>

<?php
$conn->close();
?>
