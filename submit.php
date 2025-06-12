<?php
// submit.php - handles enrollment form submission

// Database configuration - update with your credentials
$host = 'localhost';
$db   = 'database_name';
$user = 'db_user';
$pass = 'db_password';

// Create connection
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
    $studentName = trim($_POST['studentName'] ?? '');
    $age         = intval($_POST['age'] ?? 0);
    $parentName  = trim($_POST['parentName'] ?? '');
    $email       = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $phone       = trim($_POST['phone'] ?? '');
    $courseLevel = trim($_POST['courseLevel'] ?? '');
    $message     = trim($_POST['message'] ?? '');

    if (!$studentName || !$age || !$parentName || !$email || !$phone || !$courseLevel) {
        exit('Error: Please fill in all required fields.');
    }

    // Prepare and bind
    $stmt = $conn->prepare(
        "INSERT INTO enrollments 
         (student_name, age, parent_name, email, phone, course_level, message) 
         VALUES (?, ?, ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param(
        'sisssss',
        $studentName,
        $age,
        $parentName,
        $email,
        $phone,
        $courseLevel,
        $message
    );

    if ($stmt->execute()) {
        echo 'Enrollment Successful!';
    } else {
        echo 'Error: ' . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo 'Invalid request.';
}
