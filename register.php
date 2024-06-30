<?php
// Start session
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
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $plain_password = $_POST['password'];

    // Protect against SQL injection
    $firstName = $conn->real_escape_string($firstName);
    $lastName = $conn->real_escape_string($lastName);
    $email = $conn->real_escape_string($email);
    $plain_password = $conn->real_escape_string($plain_password);

    // Check if email already exists
    $sql = "SELECT * FROM starplus.student WHERE StudentEmail='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "Email already exists. Please use a different email.";
    } else {
        // Insert new student into the database
        $sql = "INSERT INTO starplus.student (FirstName, LastName, StudentEmail, StudentPassword) VALUES ('$firstName', '$lastName', '$email', '$plain_password')";

        if ($conn->query($sql) === TRUE) {
            echo "New student registered successfully.";
            // Optionally, redirect to a login page or profile page
            header("Location: login.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Close connection
$conn->close();
?>
