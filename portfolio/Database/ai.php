<?php
// connect.php - Updated version

// 1. Properly handle form submission with error checking
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("This script only accepts POST requests");
}

// 2. Validate all required fields
$required_fields = ['name', 'email', 'password', 'company'];
foreach ($required_fields as $field) {
    if (empty($_POST[$field])) {
        die(ucfirst($field) . " is required");
    }
}

// 3. Sanitize and validate inputs
$fullName = htmlspecialchars(trim($_POST['name']));
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid email format");
}
$password = $_POST['password']; // Will be hashed
$confirm_password = $_POST['confirm_password'] ?? '';
$company = htmlspecialchars(trim($_POST['company']));

// Add this check:
if ($password !== $confirm_password) {
    die("Passwords do not match");
}

// 4. Database connection with proper credentials
$servername = "localhost";
$username = "root"; // Default XAMPP username
$password = ""; // Default XAMPP password (empty)
$dbname = "portfolio"; // Ensure this database exists

// 5. Create connection with error handling
try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // 6. Hash password before storing
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // 7. Prepare and execute statement with error checking
    $stmt = $conn->prepare("INSERT INTO aitable (fullName, email, password, company) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ssss", $fullName, $email, $hashed_password, $company);
    
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }

    echo "Account created successfully";

} catch (Exception $e) {
    die("Error: " . $e->getMessage());
} finally {
    // 8. Always close connections
    if (isset($stmt)) $stmt->close();
    if (isset($conn)) $conn->close();
}
?>