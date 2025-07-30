<?php
// 1. Properly handle form submission with error checking
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("This script only accepts POST requests");
}

// 2. Validate all required fields
$required_fields = ['fullName', 'email', 'subject', 'message'];
foreach ($required_fields as $field) {
    if (empty($_POST[$field])) {
        die(ucfirst($field) . " is required");
    }
}

// 2b. Validate subject against allowed options
$allowed_subjects = [
    'General Inquiry',
    'Collaboration',
    'Job Opportunity',
    'Project Proposal',
    'Feedback',
    'Other'
];
if (!in_array($_POST['subject'], $allowed_subjects)) {
    die("Invalid subject selected");
}

// 3. Sanitize and validate inputs
$fullName = htmlspecialchars(trim($_POST['fullName']));
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid email format");
}
$subject = $_POST['subject'];
$message = htmlspecialchars(trim($_POST['message']));

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

    // 7. Prepare and execute statement with error checking
    $stmt = $conn->prepare("INSERT INTO porttable (fullName, email, subject, message) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ssss", $fullName, $email, $subject, $message);

    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }

    echo "Message sent successfully";

} catch (Exception $e) {
    die("Error: " . $e->getMessage());
} finally {
    // 8. Always close connections
    if (isset($stmt)) $stmt->close();
    if (isset($conn)) $conn->close();
}
?>