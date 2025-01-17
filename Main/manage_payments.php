<?php
// Database connection
session_start();
require_once '../db.php';
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle add or update payment
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $customer_name = $_POST['customer_name'];
    $amount_paid = $_POST['amount_paid'];
    $payment_method = $_POST['payment_method'];
    $payment_status = $_POST['payment_status'];
    $notes = $_POST['notes'];

    if ($id) {
        // Update existing payment
        $sql = "UPDATE payments SET customer_name=?, amount_paid=?, payment_method=?, payment_status=?, notes=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssssi', $customer_name, $amount_paid, $payment_method, $payment_status, $notes, $id);
    } else {
        // Add new payment
        $sql = "INSERT INTO payments (customer_name, amount_paid, payment_method, payment_status, notes) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssss', $customer_name, $amount_paid, $payment_method, $payment_status, $notes);
    }

    if ($stmt->execute()) {
        $message = $id ? "Payment updated successfully!" : "Payment added successfully!";
    } else {
        $message = "Error: " . $stmt->error;
    }
}

// Handle delete payment
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM payments WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    if ($stmt->execute()) {
        $message = "Payment deleted successfully!";
    } else {
        $message = "Error: " . $stmt->error;
    }
}

// Fetch payments
$result = $conn->query("SELECT * FROM payments ORDER BY payment_date DESC");
$payments = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Payments</title>
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
            <h1 class="text-2xl font-bold mb-6 text-gray-800">Manage Payments</h1>

            <!-- Display Messages -->
            <?php if (isset($message)): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <!-- Payment Form -->
            <form method="POST" class="space-y-6">
                <input type="hidden" name="id" id="payment_id">

                <div>
                    <label class="block text-sm font-medium text-gray-700">Customer Name</label>
                    <input type="text" name="customer_name" id="customer_name" required
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                </div>

                <div class="flex gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700">Amount Paid ($)</label>
                        <input type="number" name="amount_paid" id="amount_paid" required step="0.01"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700">Payment Method</label>
                        <select name="payment_method" id="payment_method" required
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                            <option value="Cash">Cash</option>
                            <option value="Credit Card">Credit Card</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Payment Status</label>
                    <select name="payment_status" id="payment_status" required
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                        <option value="Pending">Pending</option>
                        <option value="Completed">Completed</option>
                        <option value="Failed">Failed</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Notes</label>
                    <textarea name="notes" id="notes" rows="4"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500"></textarea>
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="flex-1 bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">
                        Save Payment
                    </button>
                    <button type="reset" class="flex-1 bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600">
                        Reset
                    </button>
                </div>
            </form>
        </div>

        <!-- Payment List -->
        <div class="bg-white rounded-lg shadow-lg p-6 mt-6">
            <h2 class="text-xl font-bold mb-4 text-gray-800">Payment List</h2>
            <table class="table-auto w-full">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Customer</th>
                        <th class="px-4 py-2">Amount</th>
                        <th class="px-4 py-2">Date</th>
                        <th class="px-4 py-2">Method</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($payments as $payment): ?>
                        <tr class="text-center">
                            <td class="border px-4 py-2"><?= $payment['id'] ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($payment['customer_name']) ?></td>
                            <td class="border px-4 py-2">$<?= number_format($payment['amount_paid'], 2) ?></td>
                            <td class="border px-4 py-2"><?= $payment['payment_date'] ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($payment['payment_method']) ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($payment['payment_status']) ?></td>
                            <td class="border px-4 py-2">
                                <button class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600"
                                    onclick="editPayment(<?= htmlspecialchars(json_encode($payment)) ?>)">Edit</button>
                                <a href="?delete=<?= $payment['id'] ?>"
                                    class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function editPayment(payment) {
            document.getElementById('payment_id').value = payment.id;
            document.getElementById('customer_name').value = payment.customer_name;
            document.getElementById('amount_paid').value = payment.amount_paid;
            document.getElementById('payment_method').value = payment.payment_method;
            document.getElementById('payment_status').value = payment.payment_status;
            document.getElementById('notes').value = payment.notes;
        }
    </script>
</body>
</html>
