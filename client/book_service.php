<?php
// book_service.php
session_start();
require_once '../db.php';

require_once '../includes/session_manager.php';

// Verify user is logged in and has client role
checkUserRole('client');

// Get current user data
$currentUser = getCurrentUser();

$user_id = $_SESSION['id'];
$success_message = $error_message = '';

// Get available services
$services_sql = "SELECT * FROM services ORDER BY name";
$services_result = $conn->query($services_sql);

// Handle form submission
if ($_SERVER['METHOD'] === 'POST') {
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
            $success_message = "Booking successfully created!";
        } else {
            throw new Exception("Error creating booking.");
        }
    } catch (Exception $e) {
        $error_message = $e->getMessage();
    }
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
        <h2>Book a Service</h2>
        
        <?php if ($success_message): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>
        
        <?php if ($error_message): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <form method="POST" class="card p-4">
            <div class="mb-3">
                <label for="service_id" class="form-label">Select Service</label>
                <select name="service_id" id="service_id" class="form-control" required>
                    <option value="">Choose a service...</option>
                    <?php while ($service = $services_result->fetch_assoc()): ?>
                        <option value="<?php echo $service['id']; ?>">
                            <?php echo htmlspecialchars($service['name']); ?> - 
                            $<?php echo number_format($service['price'], 2); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
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
                <button type="submit" class="btn btn-primary">Book Now</button>
                <a href="client_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
