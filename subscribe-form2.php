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

$sql_form_2 = "SELECT SubjectCode, SubjectName, subjectImage, SubjectPrice FROM subject WHERE Form = 'F2'";
$result_form_2 = $conn->query($sql_form_2);

// Array to store fetched subjects
$subjects_form_2 = array();

if ($result_form_2->num_rows > 0) {
    while ($row = $result_form_2->fetch_assoc()) {
        $subjects_form_2[] = $row;
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
    <title>Form 2 Subjects</title>
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
    <style>
        .row {
            display: flex;
            flex-wrap: wrap; /* Ensure items wrap to new line */
            justify-content: space-between;
            gap: 15px;
            margin-top: 20px; /* Add margin for spacing */
        }

        .card {
            background: #fff;
            text-align: center;
            flex: 1;
            max-width: calc(25% - 15px); /* Set maximum width for each card (4 cards per row) */
            box-sizing: border-box; /* Include padding and border in width calculation */
            margin-bottom: 15px; /* Add margin at bottom for spacing */
        }

        .card-body {
            padding: 20px;
            font-size: 1em;
        }

        .card-body img {
            width: 100%;
            height: auto;
            object-fit: cover;
            cursor: pointer;
        }

        /* Media query for responsive adjustments */
        @media (max-width: 1200px) {
            .card {
                max-width: calc(33.33% - 15px); /* 3 cards per row on medium screens */
            }
        }

        @media (max-width: 768px) {
            .card {
                max-width: calc(50% - 15px); /* 2 cards per row on smaller screens */
            }
        }

        @media (max-width: 480px) {
            .card {
                max-width: 100%; /* 1 card per row on extra small screens */
            }
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
        
        <h3>Form 2 Subjects</h3>
        <div class="row">
            <?php 
            $count = 0;
            foreach ($subjects_form_2 as $subject) : 
                $count++;
            ?>
                <div class="card">
                    <div class="card-body">
                    <a href="cart-page.php?subjectCode=<?php echo $subject['SubjectCode']; ?>&form=2">
                        <img src="<?php echo $subject['subjectImage']; ?>" alt="<?php echo $subject['SubjectName']; ?>">
                    </a>
                    </div>
                </div>
                <?php if ($count % 3 == 0 && $count < count($subjects_form_2)) : ?>
                    </div><div class="row">
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        
    </div>
</body>
</html>
