<?php
require_once '../db.php';
session_start();

// Basic authentication check (modify as per your system)

// Handle service addition/editing
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    
    $features = json_encode($_POST['features'] ?? []);
    
    // Image upload handling
    $image_url = '';
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "uploads/services/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $filename = uniqid() . '_' . basename($_FILES['image']['name']);
        $target_file = $target_dir . $filename;
        
        // Basic image validation
        $check = getimagesize($_FILES['image']['tmp_name']);
        $allowed_formats = ["jpg", "jpeg", "png", "gif", "webp"];
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        if ($check && in_array($imageFileType, $allowed_formats) && $_FILES['image']['size'] <= 5000000) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $image_url = $target_file;
            }
        }
    }
    
    // Prepare SQL statement (removed 'link' column)
    $sql = "INSERT INTO services (name, description, features, image_url) 
            VALUES (?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE 
            description = VALUES(description), 
            features = VALUES(features), 
            image_url = VALUES(image_url)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $description, $features, $image_url);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Service saved successfully!";
    } else {
        $_SESSION['error'] = "Error saving service: " . $stmt->error;
    }
    
    $stmt->close();
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Delete service logic (delete service based on ID)
if (isset($_GET['delete_id'])) {
    $service_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM services WHERE id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("i", $service_id);
    
    if ($delete_stmt->execute()) {
        $_SESSION['message'] = "Service deleted successfully!";
    } else {
        $_SESSION['error'] = "Error deleting service: " . $delete_stmt->error;
    }
    $delete_stmt->close();
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch existing services
$services_query = $conn->query("SELECT * FROM services");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Services</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#007bff',
                        secondary: '#6c757d'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100 min-h-screen p-6">
    <div class="container mx-auto max-w-4xl bg-white shadow-md rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-6 text-center text-primary">Manage Services</h1>
        <h2 class="text-2xl font-bold mb-6 text-center text-primary">This Service Added At Homepage</h2>

        <?php if(isset($_SESSION['message'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <?php echo htmlspecialchars($_SESSION['message']); unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>
        
        <?php if(isset($_SESSION['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="space-y-4">
            <div>
                <label class="block text-gray-700 font-bold mb-2">Service Name</label>
                <input type="text" name="name" required 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
            </div>
            
            <div>
                <label class="block text-gray-700 font-bold mb-2">Description</label>
                <textarea name="description" required 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"></textarea>
            </div>
            
            <div>
                <label class="block text-gray-700 font-bold mb-2">Features (One per line)</label>
                <textarea name="features[]" rows="4" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"></textarea>
            </div>
            

            <div>
                <label class="block text-gray-700 font-bold mb-2">Service Icon</label>
                <input type="file" name="image" accept="image/*" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
            </div>
            
            <div class="text-center">
                <button type="submit" 
                    class="bg-primary text-white px-6 py-2 rounded-md hover:bg-blue-700 transition duration-300">
                    Save Service
                </button>
            </div>
        </form>
        
        <h2 class="text-xl font-semibold mt-8 mb-4">Existing Services</h2>
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border p-3 text-left">Name</th>
                        <th class="border p-3 text-left">Description</th>
                        <th class="border p-3 text-left">Icon</th>
                        <th class="border p-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($service = $services_query->fetch_assoc()): ?>
                    <tr class="hover:bg-gray-100">
                        <td class="border p-3"><?php echo htmlspecialchars($service['name']); ?></td>
                        <td class="border p-3"><?php echo htmlspecialchars($service['description']); ?></td>
                        <td class="border p-3">
                            <?php if(!empty($service['image_url'])): ?>
                                <img src="<?php echo htmlspecialchars($service['image_url']); ?>" 
                                     class="w-12 h-12 object-cover rounded">
                            <?php endif; ?>
                        </td>
                        <td class="border p-3 space-x-2">
                            <a href="edit_service.php?id=<?php echo $service['id']; ?>" 
                               class="text-blue-600 hover:text-blue-800">Edit</a>
                            <a href="?delete_id=<?php echo $service['id']; ?>" 
                               onclick="return confirm('Are you sure you want to delete this service?')" 
                               class="text-red-600 hover:text-red-800">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Optional: Add any client-side validations or interactions
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                // Basic client-side validation example
                const name = form.querySelector('input[name="name"]');
                if (name.value.trim() === '') {
                    e.preventDefault();
                    alert('Please enter a service name');
                }
            });
        });
    </script>
</body>
</html>
