<?php
session_start();
require_once '../db.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get admin data
$stmt = $conn->prepare("SELECT username FROM clients WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
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
    <title>Admin Dashboard</title>
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
                    <div>
                        <h2 class="text-lg font-semibold"><?php echo htmlspecialchars($admin['username']); ?></h2>
                        <p class="text-sm text-gray-400">Administrator</p>
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

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Header -->
            <header class="bg-white shadow-sm">
                <div class="flex items-center justify-between p-4">
                    <div class="flex items-center space-x-4">
                        <h1 class="text-2xl font-semibold text-gray-800">Dashboard</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button class="p-2 rounded-full hover:bg-gray-100">
                            <i class="fas fa-bell text-gray-600"></i>
                        </button>
                        <button class="p-2 rounded-full hover:bg-gray-100">
                            <i class="fas fa-envelope text-gray-600"></i>
                        </button>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto bg-gray-100 p-4">
                <!-- Dashboard Content -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                    <!-- Total Clients Card -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500">Total Clients</p>
                                <h3 class="text-2xl font-bold text-gray-800">256</h3>
                            </div>
                            <div class="bg-blue-100 p-3 rounded-full">
                                <i class="fas fa-users text-blue-600"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Active Services Card -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500">Active Services</p>
                                <h3 class="text-2xl font-bold text-gray-800">42</h3>
                            </div>
                            <div class="bg-green-100 p-3 rounded-full">
                                <i class="fas fa-cogs text-green-600"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Monthly Revenue Card -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500">Monthly Revenue</p>
                                <h3 class="text-2xl font-bold text-gray-800">$12,456</h3>
                            </div>
                            <div class="bg-yellow-100 p-3 rounded-full">
                                <i class="fas fa-dollar-sign text-yellow-600"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Pending Issues Card -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500">Pending Issues</p>
                                <h3 class="text-2xl font-bold text-gray-800">5</h3>
                            </div>
                            <div class="bg-red-100 p-3 rounded-full">
                                <i class="fas fa-exclamation-circle text-red-600"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activities Section -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Recent Activities</h2>
                    <div class="space-y-4">
                        <!-- Activity items -->
                        <div class="flex items-center space-x-4">
                            <div class="bg-blue-100 p-2 rounded-full">
                                <i class="fas fa-user-plus text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-gray-800">New client registered</p>
                                <p class="text-sm text-gray-500">2 minutes ago</p>
                            </div>
                        </div>
                        <!-- Add more activity items as needed -->
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Add active class to current page link
        document.addEventListener('DOMContentLoaded', function() {
            const currentPage = window.location.pathname;
            const links = document.querySelectorAll('.sidebar-link');
            
            links.forEach(link => {
                if (link.getAttribute('href') === currentPage.split('/').pop()) {
                    link.classList.add('active-link');
                } else {
                    link.classList.remove('active-link');
                }
            });
        });
    </script>
</body>
</html>