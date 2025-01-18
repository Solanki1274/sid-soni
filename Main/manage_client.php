<?php
// Database connection
session_start();
require_once '../db.php';
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all clients with their services and payments
$sql = "
    SELECT 
        users.id AS client_id, 
        users.username, 
        users.email, 
        users.full_name, 
        users.phone, 
        users.created_at,
        GROUP_CONCAT(services.name SEPARATOR ', ') AS services_booked,
        SUM(payments.amount_paid) AS total_payments
    FROM users
    LEFT JOIN bookings ON users.id = bookings.user_id
    LEFT JOIN services ON bookings.service_id = services.id
    LEFT JOIN payments ON payments.customer_name = users.full_name
    WHERE users.role = 'client'
    GROUP BY users.id
";

$result = $conn->query($sql);

// Delete client logic
if (isset($_GET['delete'])) {
    $client_id = $_GET['delete'];
    $delete_sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $client_id);
    if ($stmt->execute()) {
        echo "<script>alert('Client deleted successfully!');</script>";
        // Redirect to refresh the page after deletion
        echo "<script>window.location.href='manage_client.php';</script>";
    } else {
        echo "<script>alert('Error deleting client.');</script>";
    }
    $stmt->close();
}

// Modify client logic (redirecting to edit page)
if (isset($_GET['edit'])) {
    $client_id = $_GET['edit'];
    // Ensure you are redirecting with the correct URL parameter
    header("Location: edit_client.php?id=$client_id");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Clients</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .sidebar-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        .active-link {
            background-color: rgba(255, 255, 255, 0.1);
            border-left: 4px solid white;
        }
    </style>
</head>
<body class="bg-gray-100">

<div class="flex h-screen">

    <!-- Sidebar -->
    <div class="bg-gray-800 text-white w-64 flex flex-col">
        <!-- Admin Profile Section -->
        <div class="p-4 border-b border-gray-700">
            <div class="flex items-center space-x-3">
                <div class="bg-gray-600 rounded-full p-2">
                    <i class="fas fa-user text-xl"></i>
                </div>
                <!-- Admin info can be added here -->
            </div>
        </div>

        <!-- Sidebar Links -->
        <nav class="flex-1 p-4">
            <ul class="space-y-2">
                <li>
                    <a href="admin_dashboard.php" class="sidebar-link active-link flex items-center space-x-3 p-3 rounded-lg transition-all duration-200">
                        <i class="fas fa-tachometer-alt w-6"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="manage_service.php" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg transition-all duration-200">
                        <i class="fas fa-cogs w-6"></i>
                        <span>Manage Services</span>
                    </a>
                </li>
                <li>
                    <a href="manage_client.php" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg transition-all duration-200">
                        <i class="fas fa-users w-6"></i>
                        <span>Manage Clients</span>
                    </a>
                </li>
                <li>
                    <a href="manage_payments.php" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg transition-all duration-200">
                        <i class="fas fa-credit-card w-6"></i>
                        <span>Manage Payments</span>
                    </a>
                </li>
                <li>
                    <a href="settings.php" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg transition-all duration-200">
                        <i class="fas fa-cog w-6"></i>
                        <span>Settings</span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Logout Button -->
        <div class="p-4 border-t border-gray-700">
            <a href="logout.php" class="flex items-center space-x-3 text-red-400 hover:text-red-300 transition-colors duration-200">
                <i class="fas fa-sign-out-alt w-6"></i>
                <span>Logout</span>
            </a>
        </div>
    </div>

    <!-- Client Management Section -->
    <div class="flex-1 p-6">
        <h1 class="text-3xl font-semibold mb-6">Manage Clients</h1>

        <div class="overflow-x-auto bg-white p-4 shadow-md rounded-lg">
            <table class="table table-bordered table-hover w-full">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th>Client ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Full Name</th>
                        <th>Phone</th>
                        <th>Registered Services</th>
                        <th>Total Payments</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row['client_id'] ?></td>
                                <td><?= $row['username'] ?></td>
                                <td><?= $row['email'] ?></td>
                                <td><?= $row['full_name'] ?></td>
                                <td><?= $row['phone'] ?></td>
                                <td><?= $row['services_booked'] ?: 'None' ?></td>
                                <td>$<?= $row['total_payments'] ? number_format($row['total_payments'], 2) : '0.00' ?></td>
                                <td class="flex space-x-2">
                                    <a href="edit_client.php?id=<?= $row['client_id'] ?>" class="bg-blue-500 text-white p-2 rounded-lg hover:bg-blue-600 transition duration-200">Edit</a>
                                    <a href="manage_client.php?delete=<?= $row['client_id'] ?>" class="bg-red-500 text-white p-2 rounded-lg hover:bg-red-600 transition duration-200" onclick="return confirm('Are you sure you want to delete this client?')">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center text-gray-500">No clients found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
