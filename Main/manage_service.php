<?php
session_start();
require_once '../db.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $duration = $_POST['duration'];

        $sql = "INSERT INTO services (name, description, price, duration) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdi", $name, $description, $price, $duration);

        if ($stmt->execute()) {
            echo "<script>alert('Service added successfully!');</script>";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } elseif (isset($_POST['modify'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $duration = $_POST['duration'];

        $sql = "UPDATE services SET name=?, description=?, price=?, duration=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdii", $name, $description, $price, $duration, $id);

        if ($stmt->execute()) {
            echo "<script>alert('Service modified successfully!');</script>";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } elseif (isset($_POST['delete'])) {
        $id = $_POST['id'];

        $sql = "DELETE FROM services WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo "<script>alert('Service deleted successfully!');</script>";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

$sql = "SELECT * FROM services";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
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
<body class="bg-gray-100 p-6">
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
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-2xl font-bold mb-6 text-gray-800">Manage Services</h1>

            <!-- Service Form -->
            <form method="POST" class="space-y-6">
                <input type="hidden" name="id" id="serviceId">
                <div class="space-y-4">
                    <input type="text" name="name" id="serviceName" required class="w-full px-4 py-2 border rounded-lg" placeholder="Enter service name">
                    <textarea name="description" id="serviceDescription" rows="4" class="w-full px-4 py-2 border rounded-lg" placeholder="Enter service description"></textarea>
                    <input type="number" name="price" id="servicePrice" required step="0.01" class="w-full px-4 py-2 border rounded-lg" placeholder="Price">
                    <input type="number" name="duration" id="serviceDuration" required class="w-full px-4 py-2 border rounded-lg" placeholder="Duration (minutes)">
                </div>
                <div class="flex gap-4">
                    <button type="submit" name="add" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">Add Service</button>
                    <button type="submit" name="modify" class="bg-yellow-500 text-white px-6 py-2 rounded-lg hover:bg-yellow-600">Modify Service</button>
                    <button type="submit" name="delete" class="bg-red-500 text-white px-6 py-2 rounded-lg hover:bg-red-600">Delete Service</button>
                </div>
            </form>

            <!-- Service List -->
            <div class="mt-10">
                <h2 class="text-lg font-bold mb-4 text-gray-800">Existing Services</h2>
                <table class="table-auto w-full bg-gray-50 rounded-lg">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">ID</th>
                            <th class="px-4 py-2">Name</th>
                            <th class="px-4 py-2">Description</th>
                            <th class="px-4 py-2">Price</th>
                            <th class="px-4 py-2">Duration</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr class="border-t">
                                    <td class="px-4 py-2"><?= $row['id'] ?></td>
                                    <td class="px-4 py-2"><?= htmlspecialchars($row['name']) ?></td>
                                    <td class="px-4 py-2"><?= htmlspecialchars($row['description']) ?></td>
                                    <td class="px-4 py-2">
                                        $<?= number_format($row['price'], 2) ?>
                                    </td>
                                    <td class="px-4 py-2"><?= $row['duration'] ?> minutes</td>
                                    <td class="px-4 py-2 flex gap-2">
                                        <button class="bg-yellow-500 text-white px-4 py-1 rounded-lg" onclick="editService(<?= htmlspecialchars(json_encode($row)) ?>)">Edit</button>
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                            <button type="submit" name="delete" class="bg-red-500 text-white px-4 py-1 rounded-lg">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="px-4 py-2 text-center">No services found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function editService(service) {
            document.getElementById('serviceId').value = service.id;
            document.getElementById('serviceName').value = service.name;
            document.getElementById('serviceDescription').value = service.description;
            document.getElementById('servicePrice').value = service.price;
            document.getElementById('serviceDuration').value = service.duration;
        }
    </script>
</body>
</html>
