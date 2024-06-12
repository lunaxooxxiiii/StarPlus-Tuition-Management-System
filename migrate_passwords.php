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

// Select all users
$sql = "SELECT * FROM starplus.admin";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $id = $row['id']; // Assuming you have an 'id' field
    $plain_password = $row['AdminPassword'];
    
    // Hash the plain text password
    $hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);
    
    // Update the password to the hashed version
    $update_sql = "UPDATE starplus.admin SET AdminPassword='$hashed_password' WHERE id=$id";
    $conn->query($update_sql);
}

// Close connection
$conn->close();

echo "Passwords have been hashed and updated successfully.";
?>
