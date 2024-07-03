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
    $sql = "SELECT * FROM starplus.student WHERE StudentEmail='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch the user data
        $row = $result->fetch_assoc();
        
        // Verify password
        if ($plain_password == $row['StudentPassword']) {
            // Set session variables
            $_SESSION['stud_email'] = $row['StudentEmail'];

            // Redirect to student profile page
            header("Location: class.php");
            exit();
        } else {
            // Incorrect password
            echo "<script>alert('Invalid password.'); window.location.href='student-login.html';</script>";
        }
    } else {
        // User does not exist
        echo "<script>alert('User does not exist!'); window.location.href='student-login.html';</script>";
    }
} else {
    // Form data not set
    echo "<script>alert('Please enter email and password.'); window.location.href='student-login.html';</script>";
}

// Close connection
$conn->close();
?>
