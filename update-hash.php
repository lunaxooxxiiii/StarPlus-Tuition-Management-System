<?php
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

// Query to get all students
$sql = "SELECT StudentEmail, StudentPassword FROM starplus.student";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $email = $row['StudentEmail'];
        $plain_password = $row['StudentPassword'];

        // Hash the plain text password
        $hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);

        // Update the student's password to the hashed password
        $update_sql = "UPDATE starplus.student SET StudentPassword='$hashed_password' WHERE StudentEmail='$email'";
        $conn->query($update_sql);
    }
    echo "Passwords updated successfully.";
} else {
    echo "No students found.";
}

// Close connection
$conn->close();
?>
