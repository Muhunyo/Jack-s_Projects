<?php
header('Content-Type: application/json'); // Always return JSON

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Database configuration
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "portfolio";
    
    // Form data validation
    $errors = [];
    $name = trim($_POST['demoName'] ?? '');
    $email = trim($_POST['demoEmail'] ?? '');
    $service = trim($_POST['demoService'] ?? '');
    $details = trim($_POST['demoMessage'] ?? '');
    
    if (empty($name)) $errors[] = "Full name is required";
    if (empty($email)) $errors[] = "Email address is required";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email format";
    if (empty($service) || $service === 'Select a service') $errors[] = "Please select a service";
    if (empty($details)) $errors[] = "Project details are required";
    
    if (empty($errors)) {
        try {
            // Create database connection
            $conn = new mysqli($servername, $username, $password, $dbname);
            
            if ($conn->connect_error) {
                throw new Exception("Database connection failed: " . $conn->connect_error);
            }
            
            // Prepare and execute SQL statement
            $stmt = $conn->prepare("INSERT INTO demotable (demoName, demoEmail, demoService, demoMessage) VALUES (?, ?, ?, ?)");
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $conn->error);
            }
            
            $stmt->bind_param("ssss", $name, $email, $service, $details);
            
            if (!$stmt->execute()) {
                throw new Exception("Execute failed: " . $stmt->error);
            }
            
            $stmt->close();
            $conn->close();
            
            echo json_encode([
                'success' => true,
                'message' => "Thank you! Your message has been sent. We'll contact you soon."
            ]);
            exit;
            
        } catch (Exception $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
    
    // If there are errors
    echo json_encode([
        'success' => false,
        'message' => implode(" ", $errors)
    ]);
    exit;
} else {
    echo json_encode([
        'success' => false,
        'message' => "Invalid request method."
    ]);
    exit;
}
?>
