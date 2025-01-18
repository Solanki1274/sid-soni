<?php
session_start();
require_once '../db.php';

$errors = [];
$success_message = "";

// Predefined security questions
$security_questions = [
    'What is your mother\'s maiden name?',
    'What was the name of your first pet?',
    'What is the name of the street you grew up on?',
    'What was your childhood nickname?',
    'In what city were you born?'
];

$username = $email = $full_name = $phone = $dob = $address = $gender = $security_question = $security_answer = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize form inputs
    $username = isset($_POST['username']) ? trim($conn->real_escape_string($_POST['username'])) : '';
    $email = isset($_POST['email']) ? trim($conn->real_escape_string($_POST['email'])) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
    $full_name = isset($_POST['full_name']) ? trim($conn->real_escape_string($_POST['full_name'])) : '';
    $phone = isset($_POST['phone']) ? trim($conn->real_escape_string($_POST['phone'])) : '';
    $dob = isset($_POST['dob']) ? $_POST['dob'] : '';
    $address = isset($_POST['address']) ? trim($conn->real_escape_string($_POST['address'])) : '';
    $gender = isset($_POST['gender']) ? $_POST['gender'] : '';
    $security_question = isset($_POST['security_question']) ? $_POST['security_question'] : '';
    $security_answer = isset($_POST['security_answer']) ? trim($conn->real_escape_string($_POST['security_answer'])) : '';

    // Validation
    if (empty($username)) {
        $errors[] = "Username is required";
    } elseif (strlen($username) < 3) {
        $errors[] = "Username must be at least 3 characters long";
    }

    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    if (empty($password)) {
        $errors[] = "Password is required";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long";
    }

    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match";
    }

    if (!empty($phone) && !preg_match("/^\+?[\d\s-]{10,}$/", $phone)) {
        $errors[] = "Invalid phone number format";
    }

    if (empty($dob)) {
        $errors[] = "Date of birth is required";
    }

    if (empty($address)) {
        $errors[] = "Address is required";
    }

    if (empty($gender) || !in_array($gender, ['male', 'female', 'other'])) {
        $errors[] = "Please select a valid gender";
    }

    if (empty($security_question)) {
        $errors[] = "Security question is required";
    }

    if (empty($security_answer)) {
        $errors[] = "Security answer is required";
    }

    // Check if username already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $errors[] = "Username already exists";
    }
    $stmt->close();

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $errors[] = "Email already exists";
    }
    $stmt->close();

    // If no errors, insert into database
    if (empty($errors)) {
        // Insert the form data into the users table
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, full_name, phone, dob, address, gender, security_question, security_answer) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssss", $username, $email, $password, $full_name, $phone, $dob, $address, $gender, $security_question, $security_answer);
        
        if ($stmt->execute()) {
            $_SESSION['registration_success'] = "Account created successfully! Please login.";
            header("Location: login.php");
            exit();
        } else {
            $errors[] = "Error creating account: " . $conn->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .form-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9fafb;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .form-header {
            font-size: 24px;
            font-weight: 600;
            color: #4c51bf;
            text-align: center;
            margin-bottom: 20px;
        }

        .error-list {
            background-color: #fff3f3;
            color: #e53e3e;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            border: 1px solid #e53e3e;
        }

        .error-list ul {
            list-style-type: none;
            padding: 0;
        }

        .error-list ul li {
            margin-bottom: 5px;
        }

        .form-input, .form-select, .form-textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            margin-bottom: 15px;
        }

        .form-submit {
            width: 100%;
            padding: 12px;
            background-color: #4c51bf;
            color: white;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
        }

        .form-submit:hover {
            background-color: #434190;
        }

        .form-select {
            cursor: pointer;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="form-container">
        <div class="form-header">Create Your Account</div>

        <?php if (!empty($errors)): ?>
            <div class="error-list">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="space-y-6">
                <div>
                    <label for="username" class="text-sm font-medium text-gray-700">Username</label>
                    <input id="username" name="username" type="text" required 
                           class="form-input" 
                           value="<?php echo htmlspecialchars($username ?? ''); ?>">
                </div>

                <div>
                    <label for="email" class="text-sm font-medium text-gray-700">Email</label>
                    <input id="email" name="email" type="email" required 
                           class="form-input" 
                           value="<?php echo htmlspecialchars($email ?? ''); ?>">
                </div>

                <div>
                    <label for="password" class="text-sm font-medium text-gray-700">Password</label>
                    <input id="password" name="password" type="password" required 
                           class="form-input">
                </div>

                <div>
                    <label for="confirm_password" class="text-sm font-medium text-gray-700">Confirm Password</label>
                    <input id="confirm_password" name="confirm_password" type="password" required 
                           class="form-input">
                </div>

                <div>
                    <label for="full_name" class="text-sm font-medium text-gray-700">Full Name</label>
                    <input id="full_name" name="full_name" type="text" 
                           class="form-input" 
                           value="<?php echo htmlspecialchars($full_name ?? ''); ?>">
                </div>

                <div>
                    <label for="phone" class="text-sm font-medium text-gray-700">Phone</label>
                    <input id="phone" name="phone" type="text" 
                           class="form-input" 
                           value="<?php echo htmlspecialchars($phone ?? ''); ?>">
                </div>

                <div>
                    <label for="dob" class="text-sm font-medium text-gray-700">Date of Birth</label>
                    <input id="dob" name="dob" type="date" required 
                           class="form-input" 
                           value="<?php echo htmlspecialchars($dob ?? ''); ?>">
                </div>

                <div>
                    <label for="address" class="text-sm font-medium text-gray-700">Address</label>
                    <textarea id="address" name="address" rows="4" 
                              class="form-textarea"><?php echo htmlspecialchars($address ?? ''); ?></textarea>
                </div>

                <div>
                    <label for="gender" class="text-sm font-medium text-gray-700">Gender</label>
                    <select id="gender" name="gender" required 
                            class="form-select">
                        <option value="male" <?php echo ($gender == 'male' ? 'selected' : ''); ?>>Male</option>
                        <option value="female" <?php echo ($gender == 'female' ? 'selected' : ''); ?>>Female</option>
                        <option value="other" <?php echo ($gender == 'other' ? 'selected' : ''); ?>>Other</option>
                    </select>
                </div>

                <div>
                    <label for="security_question" class="text-sm font-medium text-gray-700">Security Question</label>
                    <select id="security_question" name="security_question" required 
                            class="form-select">
                        <?php foreach ($security_questions as $question): ?>
                            <option value="<?php echo htmlspecialchars($question); ?>" 
                                <?php echo ($security_question == $question ? 'selected' : ''); ?>>
                                <?php echo htmlspecialchars($question); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="security_answer" class="text-sm font-medium text-gray-700">Security Answer</label>
                    <input id="security_answer" name="security_answer" type="text" required 
                           class="form-input" 
                           value="<?php echo htmlspecialchars($security_answer ?? ''); ?>">
                </div>

                <div>
                    <button type="submit" class="form-submit">Register</button>
                </div>
            </div>
        </form>
    </div>
</body>
</html>
