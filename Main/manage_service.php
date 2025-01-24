<?php
session_start();

// Include database connection
include('../db.php');

// Authentication check
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Initialize error/success messages
$error_message = '';
$success_message = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $price = isset($_POST['price']) ? floatval($_POST['price']) : 0;
    $duration = isset($_POST['duration']) ? intval($_POST['duration']) : 0;

    // Validate inputs
    if (empty($name)) {
        $error_message = "Service name is required.";
    } elseif ($price < 0) {
        $error_message = "Price cannot be negative.";
    } elseif ($duration < 0) {
        $error_message = "Duration cannot be negative.";
    } else {
        if (isset($_POST['add']) || isset($_POST['modify'])) {
            $sql = isset($_POST['add'])
                ? "INSERT INTO services (name, description, price, duration) VALUES (?, ?, ?, ?)"
                : "UPDATE services SET name=?, description=?, price=?, duration=? WHERE id=?";

            $stmt = $conn->prepare($sql);

            if ($stmt) {
                if (isset($_POST['add'])) {
                    $stmt->bind_param("ssdi", $name, $description, $price, $duration);
                } else {
                    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
                    $stmt->bind_param("ssdii", $name, $description, $price, $duration, $id);
                }

                if ($stmt->execute()) {
                    $action = isset($_POST['add']) ? 'added' : 'modified';
                    $success_message = "Service successfully $action!";
                } else {
                    $error_message = "Error: " . $stmt->error;
                }
                $stmt->close();
            }
        } elseif (isset($_POST['delete'])) {
            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
            $sql = "DELETE FROM services WHERE id=?";
            $stmt = $conn->prepare($sql);
            
            if ($stmt) {
                $stmt->bind_param("i", $id);
                if ($stmt->execute()) {
                    $success_message = "Service successfully deleted!";
                } else {
                    $error_message = "Error deleting service: " . $stmt->error;
                }
                $stmt->close();
            }
        }
    }
}

// Fetch all services
$sql = "SELECT * FROM services ORDER BY id ASC";
$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
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
                       
                        <p class="text-sm text-gray-400">Administrator</p>
                    </div>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 p-4">
                <ul class="space-y-2">
                    <li>
                        <a href="dashboard.php" class="sidebar-link active-link flex items-center space-x-3 p-3 rounded-lg transition-all duration-200">
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
    <!-- Main Content -->
    <div class="lg:pl-64 flex flex-col flex-1">
        <!-- Top Navigation -->
        <div class="sticky top-0 z-10 flex-shrink-0 flex h-16 bg-white shadow">
            <div class="flex-1 px-4 flex justify-between">
                <div class="flex-1 flex">
                    <h1 class="text-2xl font-semibold text-gray-900 my-auto">Service Management</h1>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <main class="flex-1 p-6">
            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    <?= $_SESSION['success_message'] ?>
                    <?php unset($_SESSION['success_message']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    <?= $_SESSION['error_message'] ?>
                    <?php unset($_SESSION['error_message']); ?>
                </div>
            <?php endif; ?>

            <!-- Service Form -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-xl font-semibold mb-6">Add/Modify Service</h2>
                <form method="POST" class="space-y-6">
                    <input type="hidden" name="id" id="serviceId">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Service Name</label>
                            <input type="text" name="name" id="serviceName" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea name="description" id="serviceDescription" rows="3"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Price ($)</label>
                            <input type="number" name="price" id="servicePrice" required step="0.01"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Duration (minutes)</label>
                            <input type="number" name="duration" id="serviceDuration" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <button type="submit" name="add"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-plus mr-2"></i> Add Service
                        </button>
                        <button type="submit" name="modify"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                            <i class="fas fa-edit mr-2"></i> Modify Service
                        </button>
                    </div>
                </form>
            </div>

            <!-- Services Table -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800">Existing Services</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if ($result && $result->num_rows > 0): ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $row['id'] ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= htmlspecialchars($row['name']) ?></td>
                                        <td class="px-6 py-4 text-sm text-gray-500"><?= htmlspecialchars($row['description']) ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-medium">$<?= number_format($row['price'], 2) ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 font-medium"><?= $row['duration'] ?> min</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button onclick="editService(<?= htmlspecialchars(json_encode($row)) ?>)"
                                                class="inline-flex items-center px-3 py-1 border border-transparent rounded-md text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 mr-2">
                                                <i class="fas fa-edit mr-1"></i> Edit
                                            </button>
                                            <form method="POST" class="inline-block">
                                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                                <button type="submit" name="delete"
                                                    onclick="return confirm('Are you sure you want to delete this service?')"
                                                    class="inline-flex items-center px-3 py-1 border border-transparent rounded-md text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                    <i class="fas fa-trash-alt mr-1"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No services found.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>



    <script>
        function editService(service) {
            document.getElementById('serviceId').value = service.id;
            document.getElementById('serviceName').value = service.name;
            document.getElementById('serviceDescription').value = service.description;
            document.getElementById('servicePrice').value = service.price;
            document.getElementById('serviceDuration').value = service.duration;

            // Scroll to form
            document.querySelector('form').scrollIntoView({ behavior: 'smooth' });

            // Highlight form fields briefly
            const formInputs = document.querySelectorAll('.form-input');
            formInputs.forEach(input => {
                input.classList.add('ring-2', 'ring-yellow-200');
                setTimeout(() => {
                    input.classList.remove('ring-2', 'ring-yellow-200');
                }, 1000);
            });
        }

        // Add confirmation for delete
        document.querySelectorAll('button[name="delete"]').forEach(button => {
            button.addEventListener('click', function (e) {
                if (!confirm('Are you sure you want to delete this service?')) {
                    e.preventDefault();
                }
            });
        });

        // Form validation
        document.querySelector('form').addEventListener('submit', function (e) {
            const price = document.getElementById('servicePrice').value;
            const duration = document.getElementById('serviceDuration').value;

            if (price < 0) {
                e.preventDefault();
                alert('Price cannot be negative');
                return;
            }

            if (duration < 0) {
                e.preventDefault();
                alert('Duration cannot be negative');
                return;
            }
        });

        // Add smooth transitions for success messages
        if (document.querySelector('.success-message')) {
            setTimeout(() => {
                document.querySelector('.success-message').classList.add('opacity-0');
                setTimeout(() => {
                    document.querySelector('.success-message').remove();
                }, 300);
            }, 3000);
        }

        // Add responsive menu toggle for mobile
        const menuToggle = document.createElement('button');
        menuToggle.className = 'md:hidden fixed top-4 right-4 z-50 bg-blue-600 text-white p-2 rounded-lg';
        menuToggle.innerHTML = '<i class="fas fa-bars"></i>';
        document.body.appendChild(menuToggle);

        menuToggle.addEventListener('click', () => {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('translate-x-0');
            sidebar.classList.toggle('-translate-x-full');
        });

        // Add loading state to buttons
        document.querySelectorAll('.btn').forEach(button => {
            button.addEventListener('click', function () {
                if (this.type === 'submit') {
                    const originalContent = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
                    this.disabled = true;

                    // Reset button after form submission
                    setTimeout(() => {
                        this.innerHTML = originalContent;
                        this.disabled = false;
                    }, 2000);
                }
            });
        });

        // Add tooltip functionality
        document.querySelectorAll('[data-tooltip]').forEach(element => {
            element.addEventListener('mouseenter', e => {
                const tooltip = document.createElement('div');
                tooltip.className = 'absolute bg-gray-800 text-white text-sm px-2 py-1 rounded-lg transform -translate-y-full -translate-x-1/2 left-1/2 -top-2';
                tooltip.textContent = e.target.dataset.tooltip;
                e.target.appendChild(tooltip);
            });

            element.addEventListener('mouseleave', e => {
                const tooltip = e.target.querySelector('.absolute');
                if (tooltip) tooltip.remove();
            });
        });

        // Add keyboard shortcuts
        document.addEventListener('keydown', e => {
            // Ctrl/Cmd + S to submit form
            if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                e.preventDefault();
                document.querySelector('button[name="add"]').click();
            }

            // Esc to clear form
            if (e.key === 'Escape') {
                document.querySelector('form').reset();
            }
        });

        // Auto-resize textarea
        const textarea = document.getElementById('serviceDescription');
        textarea.addEventListener('input', function () {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });

        // Format price input
        const priceInput = document.getElementById('servicePrice');
        priceInput.addEventListener('blur', function () {
            const value = parseFloat(this.value);
            if (!isNaN(value)) {
                this.value = value.toFixed(2);
            }
        });
    </script>
</body>

</html>