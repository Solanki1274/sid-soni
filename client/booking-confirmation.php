<?php
session_start();
require_once '../db.php';
require_once '../print/phpqrcode/qrlib.php';  // Include the QR Code library

$payment_id = $_GET['payment_id'] ?? null;

// Check if the payment_id is provided
if (!$payment_id) {
    $_SESSION['error_message'] = "Payment ID is missing!";
    header("Location: book_service.php");
    exit();
}

// Query to fetch payment and related booking details
$sql = "SELECT p.*, b.*, s.name AS service_name, u.full_name AS customer_name, u.email, u.phone
        FROM payments p
        JOIN bookings b ON p.id = b.payment_id
        JOIN services s ON b.service_id = s.id
        JOIN users u ON b.user_id = u.id
        WHERE p.id = ?
        ORDER BY p.payment_date DESC
        LIMIT 1";

// Prepare and execute the query
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $payment_id);
$stmt->execute();
$result = $stmt->get_result();
$booking_data = $result->fetch_assoc();

// Check if booking data is found
if (!$booking_data) {
    $_SESSION['error_message'] = "Booking details not found!";
    header("Location: bookings.php");
    exit();
}

// Format the date and time fields for display
$booking_date = date('F j, Y', strtotime($booking_data['booking_date']));
$booking_time = date('g:i A', strtotime($booking_data['booking_time']));
$payment_date = date('F j, Y g:i A', strtotime($booking_data['payment_date']));

// Define the data for the QR code (e.g., Booking ID, Payment ID, Customer Name)
$qr_data = "Booking ID: #" . $booking_data['id'] . "\nPayment ID: #" . $payment_id . "\nCustomer: " . $booking_data['customer_name'] . "\nAmount: ₹" . number_format($booking_data['amount_paid'], 2);

// Create a temporary file to store the QR code image
$temp_qr_code = 'temp_qr_code.png';
QRcode::png($qr_data, $temp_qr_code);

// You can directly display the QR code on the page
$qr_code_path = 'temp_qr_code.png';  // Path to the QR code image
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        .confirmation-box {
            border: 2px solid #28a745;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .confirmation-check {
            color: #28a745;
            font-size: 48px;
            margin-bottom: 20px;
        }
        @media print {
            .no-print {
                display: none;
            }
            .container {
                width: 100%;
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <?php if (isset($_SESSION['success_message'])): ?>
                    <div class="alert alert-success">
                        <?php 
                        echo $_SESSION['success_message'];
                        unset($_SESSION['success_message']);
                        ?>
                    </div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <div class="confirmation-check">✓</div>
                            <h2>Booking Confirmed!</h2>
                            <p class="text-muted">Your booking has been successfully confirmed and payment processed.</p>
                            <!-- Display QR code -->
                            <img src="<?php echo $qr_code_path; ?>" alt="Booking QR Code" class="img-fluid" style="max-width: 200px; border: 2px solid #28a745; border-radius: 10px;">
                        </div>

                        <div class="confirmation-box">
                            <h4>Booking Details</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Service:</strong> <?php echo htmlspecialchars($booking_data['service_name']); ?></p>
                                    <p><strong>Date:</strong> <?php echo $booking_date; ?></p>
                                    <p><strong>Time:</strong> <?php echo $booking_time; ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Status:</strong> <span class="badge bg-success"><?php echo htmlspecialchars($booking_data['status']); ?></span></p>
                                    <p><strong>Booking ID:</strong> #<?php echo htmlspecialchars($booking_data['id']); ?></p>
                                    <?php if ($booking_data['special_requests']): ?>
                                        <p><strong>Special Requests:</strong> <?php echo htmlspecialchars($booking_data['special_requests']); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="confirmation-box">
                            <h4>Payment Information</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Amount Paid:</strong> ₹<?php echo number_format($booking_data['amount_paid'], 2); ?></p>
                                    <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($booking_data['payment_method']); ?></p>
                                    <p><strong>Payment Date:</strong> <?php echo $payment_date; ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Payment ID:</strong> #<?php echo htmlspecialchars($payment_id); ?></p>
                                    <p><strong>Payment Status:</strong> <span class="badge bg-success"><?php echo htmlspecialchars($booking_data['payment_status']); ?></span></p>
                                    <?php if ($booking_data['notes']): ?>
                                        <p><strong>Notes:</strong> <?php echo htmlspecialchars($booking_data['notes']); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="confirmation-box">
                            <h4>Customer Information</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Name:</strong> <?php echo htmlspecialchars($booking_data['customer_name']); ?></p>
                                    <p><strong>Email:</strong> <?php echo htmlspecialchars($booking_data['email']); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($booking_data['phone']); ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button class="btn btn-success no-print" onclick="window.print()">Print Confirmation</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Clean up the temporary QR code file -->
    <?php unlink($temp_qr_code); ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
