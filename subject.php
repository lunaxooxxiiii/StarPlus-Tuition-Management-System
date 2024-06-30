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

// Query to fetch subjects
$sql = "SELECT SubjectCode, subjectImage FROM subject 
        WHERE SubjectCode IN ('ACC_F4', 'ACC_F5', 'MALAY_F4', 'MALAY_F5', 'MATH_F4', 'MATH_F5')";
$result = $conn->query($sql);

// Array to store fetched subjects
$subjects = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $subjects[] = $row;
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
    <title>Subscribe</title>
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
    <style>
        .row {
            display: flex;
            justify-content: space-between;
            gap: 15px;
        }

        .card {
            background: #fff;
            text-align: center;
            flex: 1;
        }

        .card-body {
            padding: 20px;
            font-size: 1em;
        }

        .card-body img {
            width: 300px;
            height: auto;
            object-fit: cover;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2><i class='bx bxs-user'></i> My profile</h2>
        <ul>
            <li><a href="student-profile.html"><i class='bx bxs-id-card'></i> Profile</a></li>
            <li><a href="class.html"><i class='bx bx-book-open'></i> Class</a></li>
            <li><a href="subscribe.html"><i class='bx bx-receipt'></i> Subscribe</a></li>
            <li><a href="timetable.html"><i class='bx bx-calendar'></i> Timetable</a></li>
            <li><a href="bill.html"><i class='bx bx-money'></i> Bill</a></li>
            <li><a href="announcement.html"><i class='bx bx-bell'></i> Announcement</a></li>
            <li><a href="student-login.html"><i class='bx bx-log-out'></i> Logout</a></li>
        </ul>
    </div>
    <div class="main_content">
        <a href="subscribe.html"><i class='bx bx-arrow-back'> BACK </i></a>
        
        <!-- Dynamically generate cards based on fetched subjects -->
        <div class="row">
            <?php foreach ($subjects as $subject) : ?>
                <div class="card">
                    <div class="card-body">
                        <a href="subscribe-class.php?subjectCode=<?php echo $subject['SubjectCode']; ?>">
                            <img src="<?php echo $subject['subjectImage']; ?>" alt="Subject Image">
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
    </div>
</body>
</html>
