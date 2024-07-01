<?php
// Start a session
session_start();

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

// Fetch subscribed subjects from the student table
$sql = "SELECT SubjectCode FROM student WHERE StudentEmail = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userId);
$stmt->execute();
$result = $stmt->get_result();

// Store subscribed subjects in an array
$subjects = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $subjectCodes = explode(',', $row['SubjectCode']);
        $subjects = array_merge($subjects, $subjectCodes);
    }
}
$stmt->close();

// Remove duplicate subject codes
$subjects = array_unique($subjects);

// Initialize the classes array
$classes = array();

// Fetch classes for the subscribed subjects
if (!empty($subjects)) {
    $subjectCodes = implode("','", $subjects);
    $classSql = "SELECT DISTINCT c.ClassID, c.ClassTime, c.ClassDay, c.LinkClass, c.TutorName, c.SubjectCode, s.SubjectName 
                 FROM class c 
                 JOIN subject s ON c.SubjectCode = s.SubjectCode 
                 WHERE c.SubjectCode IN ('$subjectCodes')";
    $classResult = $conn->query($classSql);

    // Store classes in an array
    if ($classResult->num_rows > 0) {
        while ($classRow = $classResult->fetch_assoc()) {
            $classes[] = $classRow;
        }
    }
}

// Close connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Class</title>
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
            margin-bottom: 20px;
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

        .cards {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            margin-top: 20px;
        }

        .card-class {
            background-color: #5F9EA0;
            width: 30%;
            padding: 20px;
            margin-bottom: 20px;
            color: black;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .card-class h3 {
            margin: 0 0 10px;
            font-size: 18px;
        }

        .card-class p {
            color: white;
            margin: 5px 0;
            font-size: 14px;
        }

        .card-class a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        .card-class a:hover {
            text-decoration: underline;
        }
    </style>
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
            <h3 class="card-title"><i class='bx bx-book-open'></i> Class</h3><br>
        </div>
        <div class="cards">
            <?php
            if (!empty($classes)) {
                $displayedClasses = array();
                foreach ($classes as $class) {
                    if (!in_array($class['SubjectName'], $displayedClasses)) {
                        echo "<div class='card-class'>
                                <h3>LIVE CLASS</h3>
                                <p>{$class['SubjectName']}</p>
                                <p>LINK CLASS: <a href='{$class['LinkClass']}'>{$class['LinkClass']}</a></p>
                              </div>";
                        $displayedClasses[] = $class['SubjectName'];
                    }
                }
            } else {
                echo "<p>No subscribed classes found.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>
