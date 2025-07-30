<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "portfolio";

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
            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                throw new Exception("Database connection failed: " . $conn->connect_error);
            }

            $stmt = $conn->prepare("INSERT INTO tctable (name, email, service, message) VALUES (?, ?, ?, ?)");
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $conn->error);
            }

            $stmt->bind_param("ssss", $name, $email, $service, $details);

            if (!$stmt->execute()) {
                throw new Exception("Execute failed: " . $stmt->error);
            }

            $stmt->close();
            $conn->close();

            echo json_encode(['success' => true, 'message' => 'Message sent!']);
            exit;

        } catch (Exception $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }

    // If there are errors (validation or database)
    echo json_encode(['success' => false, 'message' => implode(" ", $errors)]);
    exit;
}

// If not a POST request
echo json_encode(['success' => false, 'message' => 'Invalid request.']);
exit;
?>
