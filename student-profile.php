<?php
// Start a session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['stud_email'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

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

// Get the logged-in student's email
$studentEmail = $_SESSION['stud_email'];

// Initialize variables
$firstName = "";
$lastName = "";
$password = "";

// Fetch existing data
$sql = "SELECT * FROM starplus.student WHERE StudentEmail='$studentEmail'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $firstName = $row['FirstName'];
    $lastName = $row['LastName'];
    $password = $row['StudentPassword'];
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $newFirstName = $_POST['first-name'];
    $newLastName = $_POST['last-name'];
    $newPassword = $_POST['password'];

    // Update the database
    $stmt = $conn->prepare("UPDATE starplus.student SET FirstName=?, LastName=?, StudentPassword=? WHERE StudentEmail=?");
    $stmt->bind_param("ssss", $newFirstName, $newLastName, $newPassword, $studentEmail);

    if ($stmt->execute()) {
        echo "Profile updated successfully";
    } else {
        echo "Error updating profile: " . $stmt->error;
    }

    $stmt->close();
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            list-style: none;
            text-decoration: none;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
        }

        body {
            background-color: white;
            display: flex;
            height: 100vh;
            margin: 0;
        }

        .sidebar {
            width: 250px;
            background: #23255D;
            padding: 30px 0;
            position: fixed;
            height: 100%;
        }

        .sidebar h2 {
            color: #fff;
            text-align: center;
            margin-bottom: 30px;
        }

        .sidebar ul li {
            padding: 15px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }

        .sidebar ul li a {
            color: white;
            display: block;
            font-size: 20px;
        }

        .sidebar ul li:hover {
            background-color: #594f8d;
        }

        .main_content {
            width: calc(100% - 250px);
            margin-left: 250px;
            padding: 20px;
        }

        .card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 10px;
        }

        .card-header {
            background-color: #2e3b87;
            color: white;
            padding: 10px;
            border-radius: 5px 5px 0 0;
        }

        .card-body {
            padding: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        .form-group input {
            width: calc(50% - 10px);
            padding: 10px;
            margin-right: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-group input:last-child {
            margin-right: 0;
        }

        .form-group.full-width input {
            width: 100%;
            margin-right: 0;
        }

        .form-actions {
            text-align: right;
        }

        .form-actions button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 10px;
        }

        .form-actions .btn-cancel {
            background-color: #ccc;
        }

        .form-actions .btn-save {
            background-color: #2e3b87;
            color: white;
        }
    </style>
    <script>
        function validateForm() {
            var firstName = document.getElementById("first-name").value.trim();
            var lastName = document.getElementById("last-name").value.trim();
            var password = document.getElementById("password").value.trim();

            if (firstName === "" || lastName === "" || password === "") {
                alert("All fields must be filled out");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <div class="sidebar">
        <h2><i class='bx bxs-user'></i> My profile</h2>
        <ul>
        <li><a href="student-profile.php"><i class='bx bxs-id-card'></i> Profile</a></li>
                <li><a href="class.php"><i class='bx bx-book-open'></i> Class</a></li>
                <li><a href="subscribe.html"><i class='bx bx-receipt'></i> Subscribe</a></li>
                <li><a href="timetable.php"><i class='bx bx-calendar'></i> Timetable</a></li>
                <li><a href="bill.php"><i class='bx bx-money'></i> Bill</a></li>
                <li><a href="announcement.php"><i class='bx bx-bell'></i> Announcement</a></li>
                <li><a href="student-login.html"><i class='bx bx-log-out'></i> Logout</a></li>
        </ul>
    </div>
    <div class="main_content">
        <div class="card">
            <h3 class="card-title"><i class='bx bxs-id-card'></i> Profile</h3><br>
        </div>
            <div class="card-body">
                <h3>Edit Profile</h3>
                <form id="edit-profile-form" method="POST" action="" onsubmit="return validateForm()">
                    <div class="form-group">
                        <label for="first-name">First Name :</label>
                        <input type="text" id="first-name" name="first-name" value="<?php echo htmlspecialchars($firstName); ?>">
                        <label for="last-name">Last Name :</label>
                        <input type="text" id="last-name" name="last-name" value="<?php echo htmlspecialchars($lastName); ?>">
                    </div>
                    <div class="form-group">
                        <label for="email">Email :</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($studentEmail); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="password">Password :</label>
                        <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($password); ?>">
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn-cancel">CANCEL</button>
                        <button type="submit" class="btn-save">SAVE</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
