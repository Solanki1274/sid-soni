<?php
// view_bookings.php
session_start();
require_once '../db.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../Main/login.php");
    exit();
}

$user_id = $_SESSION['username'];

// Get all bookings for the user
$sql = "SELECT b.id, b.booking_date, b.booking_time, b.status, b.payment_id, s.name as service_name, s.price, s.duration,
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
            background-color: #f8f9fa;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .badge {
            font-size: 0.9rem;
        }
        .btn {
            margin: 0 5px;
        }
    </style>
</head>
<body>
    <div class="container my-4">
        <h2>My Bookings</h2>
        
        <div class="card">
            <div class="card-body">
                <?php if ($result->num_rows > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Service</th>
                                    <th>Date</th>
                                    <th>Time</th>
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
                                        <td>$<?php echo htmlspecialchars(number_format($booking['price'], 2)); ?></td>
                                        <td>
                                            <a href="view_booking.php?id=<?php echo $booking['id']; ?>" 
                                               class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <?php if ($booking['status'] === 'pending'): ?>
                                                <a href="cancel_booking.php?id=<?php echo $booking['id']; ?>" 
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
                    <a href="book_service.php" class="btn btn-primary">Book New Service</a>
                    <a href="client_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
