<?php
// Start a session
session_start();

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "starplus";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$email = $_POST['email'];
$password = $_POST['password'];

// Protect against SQL injection
$email = $conn->real_escape_string($email);

// Query to check if the email exists
$sql = "SELECT * FROM starplus.admin WHERE AdminEmail='$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Fetch the user data
    $row = $result->fetch_assoc();
    
    // Debug: Print hashed password from database
    echo "Hashed Password from DB: " . $row['AdminPassword'] . "<br>";

    // Verify password
    if (password_verify($password, $row['AdminPassword'])) {
        // Set session variables
        $_SESSION['admin_email'] = $row['AdminEmail'];
        
        // Redirect to admin dashboard
        header("Location: timetable.html");
        exit();
    } else {
        // Incorrect password
        echo "Invalid email or password.";
    }
} else {
    // Email does not exist
    echo "Invalid email or password.";
}

// Close connection
$conn->close();
?>
