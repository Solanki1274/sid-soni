<?php
session_start();
require_once '../db.php';

// Initialize messages
$success_message = $error_message = '';

// Verify user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php?message=" . urlencode("Please login to book a service"));
    exit;
}

// Get current user's username from the session
$username = $_SESSION['username'];

// Fetch user details using the username
$user_query = "SELECT id, username, full_name FROM users WHERE username = ?";
$user_stmt = $conn->prepare($user_query);
$user_stmt->bind_param("s", $username);
$user_stmt->execute();
$user_result = $user_stmt->get_result();

if ($user_result->num_rows === 0) {
    header("Location: login.php?message=" . urlencode("Invalid session. Please log in again."));
    exit;
}

$currentUser = $user_result->fetch_assoc();
$user_id = $currentUser['id'];

// Get available services
$services_sql = "SELECT * FROM services ORDER BY name";
$services_result = $conn->query($services_sql);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validate inputs
        $service_id = filter_input(INPUT_POST, 'service_id', FILTER_VALIDATE_INT);
        $booking_date = filter_input(INPUT_POST, 'booking_date', FILTER_SANITIZE_STRING);
        $booking_time = filter_input(INPUT_POST, 'booking_time', FILTER_SANITIZE_STRING);
        $special_requests = filter_input(INPUT_POST, 'special_requests', FILTER_SANITIZE_STRING);

        if (!$service_id || !$booking_date || !$booking_time) {
            throw new Exception("Please fill in all required fields.");
        }

        // Check if the selected time slot is available
        $check_sql = "SELECT COUNT(*) as count FROM bookings 
                      WHERE service_id = ? AND booking_date = ? AND booking_time = ? 
                      AND status != 'cancelled'";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("iss", $service_id, $booking_date, $booking_time);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result()->fetch_assoc();

        if ($check_result['count'] > 0) {
            throw new Exception("This time slot is already booked. Please select another time.");
        }

        // Insert new booking
        $sql = "INSERT INTO bookings (user_id, service_id, booking_date, booking_time, special_requests) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iisss", $user_id, $service_id, $booking_date, $booking_time, $special_requests);
        
        if ($stmt->execute()) {
            $booking_id = $conn->insert_id;
            // Redirect to payment page with booking ID
            header("Location: process_payment.php?booking_id=" . $booking_id);
            exit();
        } else {
            throw new Exception("Error creating booking.");
        }
    } catch (Exception $e) {
        $error_message = $e->getMessage();
    }
}

// Get service price using AJAX
if (isset($_GET['get_price']) && isset($_GET['service_id'])) {
    $service_id = filter_input(INPUT_GET, 'service_id', FILTER_VALIDATE_INT);
    $price_sql = "SELECT price FROM services WHERE id = ?";
    $price_stmt = $conn->prepare($price_sql);
    $price_stmt->bind_param("i", $service_id);
    $price_stmt->execute();
    $price_result = $price_stmt->get_result()->fetch_assoc();
    echo json_encode(['price' => $price_result['price']]);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Service</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container my-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Book a Service</h2>
                    </div>
                    <div class="card-body">
                        <?php if ($success_message): ?>
                            <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
                        <?php endif; ?>
                        
                        <?php if ($error_message): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
                        <?php endif; ?>

                        <form method="POST" id="bookingForm">
                            <div class="mb-3">
                                <label for="service_id" class="form-label">Select Service</label>
                                <select name="service_id" id="service_id" class="form-control" required>
                                    <option value="">Choose a service...</option>
                                    <?php while ($service = $services_result->fetch_assoc()): ?>
                                        <option value="<?php echo $service['id']; ?>" 
                                                data-price="<?php echo $service['price']; ?>">
                                            <?php echo htmlspecialchars($service['name']); ?> - 
                                            ₹<?php echo number_format($service['price'], 2); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="selected_price" class="form-label">Price</label>
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input type="text" id="selected_price" class="form-control" readonly>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="booking_date" class="form-label">Date</label>
                                <input type="date" name="booking_date" id="booking_date" 
                                       class="form-control" 
                                       min="<?php echo date('Y-m-d'); ?>" 
                                       required>
                            </div>

                            <div class="mb-3">
                                <label for="booking_time" class="form-label">Time</label>
                                <input type="time" name="booking_time" id="booking_time" 
                                       class="form-control" 
                                       required>
                            </div>

                            <div class="mb-3">
                                <label for="special_requests" class="form-label">Special Requests</label>
                                <textarea name="special_requests" id="special_requests" 
                                          class="form-control" rows="3"></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Customer Information</label>
                                <div class="card">
                                    <div class="card-body">
                                        <p><strong>Username:</strong> <?php echo htmlspecialchars($currentUser['username']); ?></p>
                                        <p><strong>Full Name:</strong> <?php echo htmlspecialchars($currentUser['full_name']); ?></p>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Proceed to Payment</button>
                                <a href="client_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('service_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const price = selectedOption.dataset.price;
            document.getElementById('selected_price').value = price ? parseFloat(price).toFixed(2) : '';
        });

        document.getElementById('bookingForm').addEventListener('submit', function(e) {
            const service = document.getElementById('service_id').value;
            const date = document.getElementById('booking_date').value;
            const time = document.getElementById('booking_time').value;

            if (!service || !date || !time) {
                e.preventDefault();
                alert('Please fill in all required fields');
                return;
            }
        });
    </script>
</body>
</html>