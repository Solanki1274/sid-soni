<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['user_id'])) {  // Changed from username to user_id
    header("Location: ../Main/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];  // Use the actual user_id from session

// Get all bookings for the user with service details
$sql = "SELECT 
            b.id, 
            b.booking_date, 
            b.booking_time, 
            b.status, 
            b.payment_id, 
            s.name as service_name, 
            s.price, 
            s.duration,
            DATE_FORMAT(b.booking_date, '%M %d, %Y') as formatted_date,
            TIME_FORMAT(b.booking_time, '%h:%i %p') as formatted_time
        FROM bookings b
        JOIN services s ON b.service_id = s.id
        WHERE b.user_id = ?
        ORDER BY b.booking_date DESC, b.booking_time DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// For debugging
if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        .container {
            max-width: 1200px;
            padding: 2rem 1rem;
        }

        h2 {
            color: #1a237e;
            font-weight: 600;
            margin-bottom: 1.5rem;
            position: relative;
            padding-bottom: 0.5rem;
        }

        h2::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 60px;
            height: 4px;
            background: linear-gradient(to right, #1a237e, #3949ab);
            border-radius: 2px;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .table {
            margin-bottom: 0;
        }

        .table th {
            background-color: #f8f9fa;
            color: #1a237e;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            padding: 1rem;
            border-top: none;
        }

        .table td {
            padding: 1rem;
            vertical-align: middle;
            color: #37474f;
            font-size: 0.95rem;
        }

        .badge {
            font-size: 0.85rem;
            padding: 0.5em 1em;
            border-radius: 6px;
            font-weight: 500;
            letter-spacing: 0.5px;
        }

        .price {
            font-weight: 600;
            color: #2e7d32;
            font-size: 1.1rem;
        }

        .btn {
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
            margin: 0.25rem;
        }

        .btn-sm {
            padding: 0.4rem 0.8rem;
            font-size: 0.875rem;
        }

        .btn-primary {
            background: linear-gradient(45deg, #1a237e, #3949ab);
            border: none;
            box-shadow: 0 4px 15px rgba(26, 35, 126, 0.2);
        }

        .btn-primary:hover {
            background: linear-gradient(45deg, #3949ab, #1a237e);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(26, 35, 126, 0.3);
        }

        .btn-secondary {
            background: #eceff1;
            border: none;
            color: #37474f;
        }

        .btn-secondary:hover {
            background: #cfd8dc;
            color: #263238;
        }

        .btn-info {
            background: #e3f2fd;
            border: none;
            color: #1565c0;
        }

        .btn-info:hover {
            background: #bbdefb;
            color: #0d47a1;
        }

        .btn-success {
            background: #e8f5e9;
            border: none;
            color: #2e7d32;
        }

        .btn-success:hover {
            background: #c8e6c9;
            color: #1b5e20;
        }

        .btn-danger {
            background: #ffebee;
            border: none;
            color: #c62828;
        }

        .btn-danger:hover {
            background: #ffcdd2;
            color: #b71c1c;
        }

        .alert {
            border: none;
            border-radius: 12px;
            padding: 1rem 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .table-responsive {
            border-radius: 12px;
            overflow: hidden;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.02);
        }

        .table-hover tbody tr:hover {
            background-color: rgba(26, 35, 126, 0.05);
            transition: background-color 0.3s ease;
        }

        .fas {
            margin-right: 0.5rem;
        }

        /* Status badge custom styles */
        .bg-success {
            background: #e8f5e9 !important;
            color: #2e7d32 !important;
        }

        .bg-warning {
            background: #fff3e0 !important;
            color: #ef6c00 !important;
        }

        .bg-danger {
            background: #ffebee !important;
            color: #c62828 !important;
        }

        .bg-info {
            background: #e3f2fd !important;
            color: #1565c0 !important;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .btn-sm {
                padding: 0.3rem 0.6rem;
                font-size: 0.8rem;
            }
            
            .table td, .table th {
                padding: 0.75rem;
            }
            
            .badge {
                font-size: 0.75rem;
            }
        }
    </style>
</head>
<body>
   <div class="modal fade" id="bookingDetailsModal" tabindex="-1" aria-labelledby="bookingDetailsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="bookingDetailsModalLabel">Booking Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="bookingDetails"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>


    <div class="container my-4">
        <h2>My Bookings</h2>
        
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?php echo $_SESSION['message_type'] ?? 'info'; ?> alert-dismissible fade show">
                <?php 
                    echo $_SESSION['message'];
                    unset($_SESSION['message']);
                    unset($_SESSION['message_type']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <?php if ($result && $result->num_rows > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Service</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Duration</th>
                                    <th>Status</th>
                                    <th>Price</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($booking = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($booking['service_name']); ?></td>
                                        <td><?php echo htmlspecialchars($booking['formatted_date']); ?></td>
                                        <td><?php echo htmlspecialchars($booking['formatted_time']); ?></td>
                                        <td><?php echo htmlspecialchars($booking['duration']); ?> mins</td>
                                        <td>
                                            <span class="badge bg-<?php 
                                                echo match($booking['status']) {
                                                    'confirmed' => 'success',
                                                    'pending' => 'warning',
                                                    'cancelled' => 'danger',
                                                    'completed' => 'info',
                                                    default => 'secondary'
                                                };
                                            ?>">
                                                <?php echo ucfirst(htmlspecialchars($booking['status'])); ?>
                                            </span>
                                        </td>
                                        <td class="price">₹<?php echo htmlspecialchars(number_format($booking['price'], 2)); ?></td>
                                        <td>
                                            <a href="view_bookings.php?id=<?php echo $booking['id']; ?>" 
                                               class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <?php if ($booking['status'] === 'pending'): ?>
                                                <a href="process_payment.php?booking_id=<?php echo $booking['id']; ?>" 
                                                   class="btn btn-sm btn-success">
                                                    <i class="fas fa-credit-card"></i> Pay
                                                </a>
                                                <a href="cancel_bookings.php?id=<?php echo $booking['id']; ?>" 
                                                   class="btn btn-sm btn-danger"
                                                   onclick="return confirm('Are you sure you want to cancel this booking?')">
                                                    <i class="fas fa-times"></i> Cancel
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted">No bookings found.</p>
                <?php endif; ?>
                
                <div class="mt-3">
                    <a href="book_service.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Book New Service
                    </a>
                    <a href="client_dashboard.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>