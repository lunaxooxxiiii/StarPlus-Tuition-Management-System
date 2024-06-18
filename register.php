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
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$email = $_POST['email'];
$password = $_POST['password'];

// Protect against SQL injection
$firstName = $conn->real_escape_string($firstName);
$lastName = $conn->real_escape_string($lastName);
$email = $conn->real_escape_string($email);
$password = $conn->real_escape_string($password);

// Insert user data into the database
$sql = "INSERT INTO student (StudentEmail, StudentPassword, FirstName, LastName) 
        VALUES ('$email', '$password', '$firstName', '$lastName')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
    // Redirect to a success page or login page
    header("Location: student-login.html");
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close connection
$conn->close();
?>
