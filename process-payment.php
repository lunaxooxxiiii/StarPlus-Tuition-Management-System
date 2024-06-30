<?php
// Start a session
session_start();

// Retrieve cart details from the form submission
$cart = isset($_POST['cart']) ? json_decode($_POST['cart'], true) : array();
$total = isset($_POST['total']) ? $_POST['total'] : 0.00;

// Include database connection
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

// Get the current user ID (Assuming you have user authentication and session management in place)
$userId = $_SESSION['stud_email'];

// Assuming you are storing multiple subject codes in a single column as a comma-separated list
foreach ($cart as $subjectCode => $item) {
    // First, retrieve the current SubjectCode from the database
    $getSubjectSql = "SELECT SubjectCode FROM student WHERE StudentEmail = ?";
    $getSubjectStmt = $conn->prepare($getSubjectSql);
    $getSubjectStmt->bind_param("s", $userId);
    $getSubjectStmt->execute();
    $getSubjectStmt->bind_result($currentSubjectCodes);
    $getSubjectStmt->fetch();
    $getSubjectStmt->close();

    // Split the existing subject codes into an array
    $subjectCodesArray = explode(',', $currentSubjectCodes);

    // Add the new subject code to the array if it's not already there
    if (!in_array($subjectCode, $subjectCodesArray)) {
        $subjectCodesArray[] = $subjectCode;
    }

    // Join the subject codes back into a comma-separated string
    $updatedSubjectCodes = implode(',', $subjectCodesArray);

    // Update the student table with the new list of subject codes
    $updateSql = "UPDATE student SET SubjectCode = ? WHERE StudentEmail = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("ss", $updatedSubjectCodes, $userId);
    $updateStmt->execute();
    $updateStmt->close();
}

// Close connection
$conn->close();

echo '<script>
    alert("Payment processed and database updated.");
    window.location.href = "class.php";
</script>';
?>
