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

// Fetch the announcement IDs for the student
$announcementSql = "SELECT AnnouncementID FROM student WHERE StudentEmail = ?";
$stmt = $conn->prepare($announcementSql);
$stmt->bind_param("s", $userId);
$stmt->execute();
$result = $stmt->get_result();

// Store announcement IDs in an array
$announcementIds = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $announcementIds = explode(',', $row['AnnouncementID']);
    }
}
$stmt->close();

// Initialize the announcements array
$announcements = array();

// Fetch announcements based on the IDs
if (!empty($announcementIds)) {
    $announcementIds = implode("','", $announcementIds);
    $announcementsSql = "SELECT AnnouncementID, AnnouncementDescription, AdminEmail FROM announcement WHERE AnnouncementID IN ('$announcementIds')";
    $announcementsResult = $conn->query($announcementsSql);

    // Store announcements in an array
    if ($announcementsResult->num_rows > 0) {
        while ($announcementRow = $announcementsResult->fetch_assoc()) {
            $announcements[] = $announcementRow;
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
    <title>Student Announcement</title>
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style-student.css">
    <style>
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
            <li><a href="login-choice.html"><i class='bx bx-log-out'></i> Logout</a></li>
        </ul>
    </div>
    <div class="main_content">
        <div class="card">
            <h3 class="card-title"><i class='bx bx-bell'></i> Announcement</h3><br>
            <div class="card-body">
                <?php
                if (!empty($announcements)) {
                    foreach ($announcements as $announcement) {
                        echo "<div class='announcement'>
                                <p><strong>Description:</strong> {$announcement['AnnouncementDescription']}</p>
                                <hr>
                              </div>";
                    }
                } else {
                    echo "<p>No announcements found.</p>";
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>
