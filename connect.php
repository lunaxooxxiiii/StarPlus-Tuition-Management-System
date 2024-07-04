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

// Check if form data is set
if (isset($_POST['email']) && isset($_POST['password'])) {
    // Get form data
    $email = $_POST['email'];
    $plain_password = $_POST['password'];

    // Protect against SQL injection
    $email = $conn->real_escape_string($email);
    $plain_password = $conn->real_escape_string($plain_password);

    // Query to check if the email exists
    $sql = "SELECT * FROM starplus.admin WHERE AdminEmail='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch the user data
        $row = $result->fetch_assoc();
        
        // Verify password
        if ($plain_password == $row['AdminPassword']) {
            // Set session variables
            $_SESSION['admin_email'] = $row['AdminEmail'];

            // Redirect to student profile page
            header("Location: admin-dashboard.php");
            exit();
        } else {
            // Incorrect password
            echo "Invalid email or password.";
        }
    }
} else {
    // Form data not set
    echo "Please enter email and password.";
}

// Close connection
$conn->close();
?>
