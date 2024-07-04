<?php
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

// Check if firstName and lastName are set in the URL
if (isset($_GET['firstName']) && isset($_GET['lastName'])) {
    $firstName = $conn->real_escape_string($_GET['firstName']);
    $lastName = $conn->real_escape_string($_GET['lastName']);

    // Delete student from database
    $sql = "DELETE FROM student WHERE FirstName='$firstName' AND LastName='$lastName'";

    if ($conn->query($sql) === TRUE) {
        echo "Student deleted successfully.";
    } else {
        echo "Error deleting student: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}

$conn->close();

// Redirect back to the admin dashboard
header("Location: admin_dashboard.php");
exit();
?>
