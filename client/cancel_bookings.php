<?php
// cancel_booking.php
session_start();
require_once '../db.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../Main/login.php");
    exit();
}

// Get the username from the session
$username = $_SESSION['username'];

// Fetch user details using the username
$user_query = "SELECT id FROM users WHERE username = ?";
$user_stmt = $conn->prepare($user_query);
$user_stmt->bind_param("s", $username);
$user_stmt->execute();
$user_result = $user_stmt->get_result();

if ($user_result->num_rows === 0) {
    header("Location: ../Main/login.php?message=" . urlencode("Invalid session. Please log in again."));
    exit();
}

$currentUser = $user_result->fetch_assoc();
$user_id = $currentUser['id']; // Use the user's ID for database operations

// Get the booking ID from the request
$booking_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$booking_id) {
    header("Location: view_bookings.php");
    exit();
}

try {
    // Verify that the booking belongs to the user and is pending
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
    
    // Update the booking status to 'cancelled'
    $update_sql = "UPDATE bookings SET status = 'cancelled' WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("i", $booking_id);
    
    if (!$update_stmt->execute()) {
        throw new Exception("Error cancelling booking.");
    }
    
    // Redirect with a success message
    header("Location: view_bookings.php?msg=cancelled");
    exit();
    
} catch (Exception $e) {
    // Redirect with an error message
    header("Location: view_bookings.php?error=" . urlencode($e->getMessage()));
    exit();
}
?>
