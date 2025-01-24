<?php
session_start();
require_once '../db.php';
require_once '../print/phpqrcode/qrlib.php';

$booking_id = $_GET['booking_id'] ?? null;
$booking_details = null;

// Fetch booking details if booking_id exists
if ($booking_id) {
    $stmt = $conn->prepare("SELECT b.*, u.full_name as customer_name, s.price 
                           FROM bookings b 
                           JOIN users u ON b.user_id = u.id 
                           JOIN services s ON b.service_id = s.id 
                           WHERE b.id = ?");
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $booking_details = $result->fetch_assoc();
}

// Function to process payment
function processPayment($conn, $data) {
    try {
        $conn->begin_transaction();
        
        $stmt = $conn->prepare("INSERT INTO payments (customer_name, amount_paid, payment_method, payment_status, notes) 
                               VALUES (?, ?, ?, ?, ?)");
        
        $stmt->bind_param("sdsss", 
            $data['customer_name'],
            $data['amount'],
            $data['payment_method'],
            $data['payment_status'],
            $data['notes']
        );
        
        $stmt->execute();
        $payment_id = $conn->insert_id;
        
        $stmt = $conn->prepare("UPDATE bookings SET status = 'confirmed', payment_id = ? WHERE id = ?");
        $stmt->bind_param("ii", $payment_id, $data['booking_id']);
        $stmt->execute();
        
        $conn->commit();
        return ["success" => true, "payment_id" => $payment_id];
        
    } catch (Exception $e) {
        $conn->rollback();
        return ["success" => false, "message" => $e->getMessage()];
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $payment_data = [
        'booking_id' => $_POST['booking_id'],
        'customer_name' => $_POST['customer_name'],
        'amount' => $_POST['amount'],
        'payment_method' => $_POST['payment_method'],
        'payment_status' => 'Completed',
        'notes' => $_POST['notes']
    ];
    
    $result = processPayment($conn, $payment_data);
    
    if ($result['success']) {
        $_SESSION['success_message'] = "Payment processed successfully!";
        header("Location: booking-confirmation.php?payment_id=" . $result['payment_id']);
        exit();
    } else {
        $_SESSION['error_message'] = "Payment processing failed: " . $result['message'];
    }
}

// Generate UPI QR Code with correct amount
if ($booking_details && isset($booking_details['price'])) {
    $amount = number_format($booking_details['price'], 2, '.', ''); // Format amount with 2 decimal places
    $upi_data = "upi://pay?pa=solankiatulr2011@okaxis"
              . "&pn=Harsh%20Solanki"
              . "&am=" . $amount  // Add amount parameter
              . "&cu=INR"
              . "&aid=uGICAgMCCwdLJOQ";
              
    $qr_code_file = 'uploads/upi_qr_' . $booking_id . '.png';
    QRcode::png($upi_data, $qr_code_file);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Process Payment</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Payment Details</h3>
                    </div>
                    <div class="card-body">
                        <?php if (isset($_SESSION['error_message'])): ?>
                            <div class="alert alert-danger">
                                <?php 
                                echo $_SESSION['error_message'];
                                unset($_SESSION['error_message']);
                                ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="" id="paymentForm">
                            <input type="hidden" name="booking_id" value="<?php echo htmlspecialchars($booking_id); ?>">
                            
                            <div class="mb-3">
                                <label for="customer_name" class="form-label">Customer Name</label>
                                <input type="text" class="form-control" id="customer_name" name="customer_name" 
                                       value="<?php echo htmlspecialchars($booking_details['customer_name'] ?? ''); ?>" required readonly>
                            </div>

                            <div class="mb-3">
                                <label for="amount" class="form-label">Amount (₹)</label>
                                <input type="number" step="0.01" class="form-control" id="amount" name="amount" 
                                       value="<?php echo htmlspecialchars($booking_details['price'] ?? ''); ?>" required readonly>
                            </div>

                            <div class="mb-3">
                                <label for="payment_method" class="form-label">Payment Method</label>
                                <select class="form-select" id="payment_method" name="payment_method" required>
                                    <option value="">Select Payment Method</option>
                                    <option value="Cash">Cash</option>
                                    <option value="Card">Card (Offline)</option>
                                    <option value="Bank Transfer">Bank Transfer</option>
                                    <option value="Check">Check</option>
                                    <option value="UPI" selected>UPI</option> <!-- UPI selected by default -->
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="notes" class="form-label">Notes (Optional)</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                            </div>

                            <!-- Display UPI QR Code -->
                            <div class="mb-3">
                                <label for="qr_code" class="form-label">Scan to Pay</label>
                                <img src="<?php echo $qr_code_file; ?>" alt="UPI QR Code" class="img-fluid">
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Process Payment</button>
                                <a href="view_booking.php" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
