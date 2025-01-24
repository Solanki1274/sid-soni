<?php
session_start();

// Database connection
require_once '../db.php';

if (!isset($conn) || $conn->connect_error) {
    die("Connection failed: " . ($conn->connect_error ?? "Unknown error"));
}

// Initialize variables
$error_message = "";
$user = [];
$recent_bookings = [];
$services = [];

// Check for proper authentication
if (!isset($_SESSION['user_id']) && !isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Fetch user details and related data
try {
    // Get user details using either user_id or username
    $user_identifier = $_SESSION['user_id'] ?? $_SESSION['username'];
    $user_stmt = null;
    
    if (isset($_SESSION['user_id'])) {
        $user_stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
        $user_stmt->bind_param("i", $_SESSION['user_id']);
    } else {
        $user_stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $user_stmt->bind_param("s", $_SESSION['username']);
    }

    $user_stmt->execute();
    $user_result = $user_stmt->get_result();
    
    if ($user_result->num_rows === 0) {
        throw new Exception("User not found");
    }
    
    $user = $user_result->fetch_assoc();
    $user_stmt->close();
    
    // Store the user ID in session if not already set
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['user_id'] = $user['id'];
    }

    // Get user's recent bookings
    $booking_sql = "
        SELECT b.*, s.name AS service_name, s.price, s.duration,
               DATE_FORMAT(b.booking_date, '%M %d, %Y') AS formatted_date,
               TIME_FORMAT(b.booking_time, '%h:%i %p') AS formatted_time
        FROM bookings b
        JOIN services s ON b.service_id = s.id
        WHERE b.user_id = ?
        ORDER BY b.booking_date DESC, b.booking_time DESC
        LIMIT 5";
    $stmt = $conn->prepare($booking_sql);
    $stmt->bind_param("i", $user['id']);
    $stmt->execute();
    $bookings_result = $stmt->get_result();
    while ($booking = $bookings_result->fetch_assoc()) {
        $recent_bookings[] = $booking;
    }
    $stmt->close();

    // Get available services
    $services_sql = "SELECT * FROM services WHERE active = 1 ORDER BY name";
    $services_result = $conn->query($services_sql);
    if ($services_result) {
        while ($service = $services_result->fetch_assoc()) {
            $services[] = $service;
        }
    }
} catch (Exception $e) {
    error_log("Dashboard error: " . $e->getMessage());
   
    
    // If there's a serious error, clear the session and redirect to login
    if ($e->getMessage() === "User not found") {
        session_destroy();
        header("Location: login.php?error=session_expired");
        exit();
    }
}

// Format user information for display
$display_name = htmlspecialchars($user['full_name'] ?? $user['username'] ?? 'User');
$display_email = htmlspecialchars($user['email'] ?? '');
$display_phone = htmlspecialchars($user['phone'] ?? 'Not provided');
$display_role = ucfirst(htmlspecialchars($user['role'] ?? 'client'));


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">Service Booking System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">
                            <i class="fas fa-user"></i> Profile
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-4">
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Welcome, <?php echo htmlspecialchars($user['full_name'] ?? 'User'); ?>!</h5>
                        <p class="card-text">
                            <strong>Email:</strong> <?php echo htmlspecialchars($user['email'] ?? ''); ?><br>
                            <strong>Phone:</strong> <?php echo htmlspecialchars($user['phone'] ?? 'Not provided'); ?><br>
                            <strong>Role:</strong> <?php echo ucfirst(htmlspecialchars($user['role'] ?? 'client')); ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Quick Actions</h5>
                        <div class="d-grid gap-2 d-md-block">
                            <a href="book_service.php" class="btn btn-primary">
                                <i class="fas fa-calendar-plus"></i> Book New Service
                            </a>
                            <a href="view_bookings.php" class="btn btn-info">
                                <i class="fas fa-calendar-alt"></i> View All Bookings
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Recent Bookings</h5>
                        <?php if (!empty($recent_bookings)): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
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
                                        <?php foreach ($recent_bookings as $booking): ?>
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
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <?php if ($booking['status'] === 'pending'): ?>
                                                        <a href="cancel_bookings.php?id=<?php echo $booking['id']; ?>" 
                                                           class="btn btn-sm btn-danger"
                                                           onclick="return confirm('Are you sure you want to cancel this booking?')">
                                                            <i class="fas fa-times"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p class="text-muted">No bookings found. Why not book a service now?</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
// First, fetch all services from the database
$sql = "SELECT * FROM services ORDER BY name ASC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$services = $result->fetch_all(MYSQLI_ASSOC);
?>

<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-4">Available Services</h5>
                <?php if (!empty($services)): ?>
                    <div class="row">
                        <?php foreach ($services as $service): ?>
                            <div class="col-md-4 mb-3">
                                <div class="card h-100 hover-shadow">
                                    <div class="card-body d-flex flex-column">
                                        <h6 class="card-title text-primary mb-3">
                                            <?php echo htmlspecialchars($service['name']); ?>
                                        </h6>
                                        <p class="card-text flex-grow-1">
                                            <?php echo htmlspecialchars($service['description']); ?>
                                        </p>
                                        <div class="service-details mt-3">
                                            <p class="card-text mb-2">
                                                <i class="fas fa-rupee-sign"></i>
                                                <strong>Price:</strong> 
                                                <span class="text-success">
                                                    ₹<?php echo htmlspecialchars(number_format($service['price'], 2)); ?>
                                                </span>
                                            </p>
                                            <p class="card-text mb-3">
                                                <i class="fas fa-clock"></i>
                                                <strong>Duration:</strong> 
                                                <span class="text-muted">
                                                    <?php echo htmlspecialchars($service['duration']); ?> minutes
                                                </span>
                                            </p>
                                            <a href="book_service.php?service_id=<?php echo $service['id']; ?>" 
                                               class="btn btn-primary btn-sm w-100">
                                                <i class="fas fa-calendar-plus"></i> Book Now
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> No services available at the moment.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
.hover-shadow {
    transition: all 0.3s ease;
}

.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.service-details {
    border-top: 1px solid #eee;
    padding-top: 1rem;
}

.card-title {
    font-size: 1.1rem;
    font-weight: 600;
}

.fas {
    margin-right: 0.5rem;
}
</style>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
