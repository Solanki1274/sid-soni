<?php
// cancel_booking.php
session_start();
require_once '../db.php';

if (!isset($_SESSION['id'])) {
    header("Location: ../Main/login.php");
    exit();
}

$user_id = $_SESSION['id'];
$booking_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$booking_id) {
    header("Location: view_bookings.php");
    exit();
}

try {
    // Verify booking belongs to user and is pending
    $check_sql = "SELECT status FROM bookings WHERE id = ? AND user_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $booking_id, $user_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows === 0) {
        throw new Exception("Booking not found.");
    }
    
    $booking = $result->fetch_assoc();
    if ($booking['status'] !== 'pending') {
        throw new Exception("Only pending bookings can be cancelled.");
    }
    
    // Update booking status to cancelled
    $update_sql = "UPDATE bookings SET status = 'cancelled' WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("i", $booking_id);
    
    if (!$update_stmt->execute()) {
        throw new Exception("Error cancelling booking.");
    }
    
    header("Location: view_bookings.php?msg=cancelled");
    exit();
    
} catch (Exception $e) {
    header("Location: view_bookings.php?error=" . urlencode($e->getMessage()));
    exit();
}
