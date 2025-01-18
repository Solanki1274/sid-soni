<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

require_once '../db.php';

// Get user details from the database
$user_id = $_SESSION['id'];
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Check if user exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $stmt->close();
    } else {
        echo "User not found.";
        exit();
    }
} else {
    echo "Error: Unable to prepare the SQL query.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2>Welcome, <?= htmlspecialchars($user['full_name']); ?>!</h2>
    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']); ?></p>
    <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone']); ?></p>

</div>

<!-- Footer -->
<footer class="mt-5 text-center">
    <p>&copy; 2025 Your Company Name. All rights reserved.</p>
</footer>

</body>
</html>

<?php
$conn->close();
?>
