<?php
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Database configuration
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "portfolio";
    
    // Form data validation
    $errors = [];
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $service = trim($_POST['service'] ?? '');
    $details = trim($_POST['details'] ?? '');
    
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
            $stmt = $conn->prepare("INSERT INTO softwaretable (name, email, service, details) VALUES (?, ?, ?, ?)");
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $conn->error);
            }
            
            $stmt->bind_param("ssss", $name, $email, $service, $details);
            
            if (!$stmt->execute()) {
                throw new Exception("Execute failed: " . $stmt->error);
            }
            
            $success = "Thank you! your message has been sent. We'll contact you soon.";
            
            // Close connections
            $stmt->close();
            $conn->close();
            
        } catch (Exception $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
}
?>
