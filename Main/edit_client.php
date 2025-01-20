<?php
// Start session
session_start();

// Include the database connection file
require_once '../db.php'; 

// Ensure connection is established
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the 'id' parameter exists in the URL
if (isset($_GET['id'])) {
    // Fetch the client id from the URL
    $client_id = $_GET['id'];

    // Prepare the SQL query to fetch client details
    $client_sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($client_sql);
    $stmt->bind_param("i", $client_id); // Bind the client id to the prepared statement
    $stmt->execute(); // Execute the query

    // Get the result and check if the client exists
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        // Fetch client data
        $client = $result->fetch_assoc();
    } else {
        // If no client is found, redirect with an alert message
        echo "<script>alert('Client not found!'); window.location.href='manage_client.php';</script>";
        exit();
    }

    // Close the prepared statement
    $stmt->close();
} else {
    // If the 'id' parameter is missing, redirect to manage_client.php
    echo "<script>alert('No client ID provided!'); window.location.href='manage_client.php';</script>";
    exit();
}

// Handle form submission to update client details
if (isset($_POST['update'])) {
    // Get form input values
    $username = $_POST['username'];
    $email = $_POST['email'];
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];

    // Prepare SQL query to update client data
    $update_sql = "UPDATE users SET username = ?, email = ?, full_name = ?, phone = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssssi", $username, $email, $full_name, $phone, $client_id);

    // Execute the update query
    if ($stmt->execute()) {
        // Success message and redirect
        echo "<script>alert('Client details updated successfully!'); window.location.href='manage_client.php';</script>";
    } else {
        // Error message
        $error_message = "Error updating client details.";
    }

    // Close the prepared statement
    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Client</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="container mt-5">
        <h1 class="text-3xl font-semibold mb-4">Edit Client Details</h1>

        <!-- Success or Failure Message -->
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger">
                <?= $error_message ?>
            </div>
        <?php endif; ?>

        <!-- Edit Client Form -->
        <form action="edit_client.php?id=<?= $client_id ?>" method="POST" class="bg-white p-6 shadow-md rounded-lg">
            <div class="mb-4">
                <label for="username" class="block text-lg font-semibold">Username</label>
                <input type="text" name="username" id="username" class="form-control" value="<?= htmlspecialchars($client['username']) ?>" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-lg font-semibold">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($client['email']) ?>" required>
            </div>
            <div class="mb-4">
                <label for="full_name" class="block text-lg font-semibold">Full Name</label>
                <input type="text" name="full_name" id="full_name" class="form-control" value="<?= htmlspecialchars($client['full_name']) ?>" required>
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-lg font-semibold">Phone</label>
                <input type="text" name="phone" id="phone" class="form-control" value="<?= htmlspecialchars($client['phone']) ?>" required>
            </div>
            <button type="submit" name="update" class="bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600">Update Client</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
