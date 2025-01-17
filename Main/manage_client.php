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
        clients.id AS client_id, 
        clients.username, 
        clients.email, 
        clients.full_name, 
        clients.phone, 
        clients.created_at,
        GROUP_CONCAT(services.name SEPARATOR ', ') AS services_booked,
        SUM(payments.amount_paid) AS total_payments
    FROM clients
    LEFT JOIN bookings ON clients.id = bookings.user_id
    LEFT JOIN services ON bookings.service_id = services.id
    LEFT JOIN payments ON payments.customer_name = clients.full_name
    GROUP BY clients.id
";

$result = $conn->query($sql);
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
<body>
<div class="flex h-screen">
        <!-- Sidebar -->
        <div class="bg-gray-800 text-white w-64 flex flex-col">
            <!-- Admin Profile Section -->
            <div class="p-4 border-b border-gray-700">
                <div class="flex items-center space-x-3">
                    <div class="bg-gray-600 rounded-full p-2">
                        <i class="fas fa-user text-xl"></i>
                    </div>

                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 p-4">
                <ul class="space-y-2">
                    <li>
                        <a href="admin_dashboard.php" class="sidebar-link active-link flex items-center space-x-3 p-3 rounded-lg transition-all duration-200">
                            <i class="fas fa-tachometer-alt w-6"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    
                    <!-- Services Management -->
                    <li>
                        <a href="manage_service.php" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg transition-all duration-200">
                            <i class="fas fa-cogs w-6"></i>
                            <span>Manage Services</span>
                        </a>
                    </li>
                    
                    <!-- Client Management -->
                    <li>
                        <a href="manage_client.php" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg transition-all duration-200">
                            <i class="fas fa-users w-6"></i>
                            <span>Manage Clients</span>
                        </a>
                    </li>
                    
                    <!-- Payment Management -->
                    <li>
                        <a href="manage_payments.php" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg transition-all duration-200">
                            <i class="fas fa-credit-card w-6"></i>
                            <span>Manage Payments</span>
                        </a>
                    </li>
                    
                    <!-- Settings -->
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
<div class="container my-5">
    <h1 class="mb-4">Manage Clients</h1>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
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
                        <td>
                            <a href="edit_client.php?id=<?= $row['client_id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                            <a href="delete_client.php?id=<?= $row['client_id'] ?>" class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center">No clients found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
