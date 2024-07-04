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
    $classSql = "SELECT c.ClassID, c.ClassTime, c.ClassDay, c.LinkClass, c.TutorName, c.SubjectCode, s.SubjectName 
                 FROM class c 
                 JOIN subject s ON c.SubjectCode = s.SubjectCode 
                 WHERE c.SubjectCode IN ('$subjectCodes')";
    $classResult = $conn->query($classSql);

    // Store classes in an array
    if ($classResult->num_rows > 0) {
        while ($classRow = $classResult->fetch_assoc()) {
            $day = $classRow['ClassDay'];
            if (!isset($classes[$day])) {
                $classes[$day] = array();
            }
            $classes[$day][] = $classRow;
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
    <title>Student Timetable</title>
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
        }

        .wrapper {
            display: flex;
            position: relative;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background: #23255d;
            padding: 30px 0;
            position: fixed;
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

        .card-body {
            background-color: #f9f9f9;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .nav-tabs {
            display: flex;
            background-color: #5e8d8c;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .nav-item {
            display: inline-block;
            padding: 10px 20px;
            margin: auto;
            color: #fff;
            border: 1px solid transparent;
            border-radius: 5px;
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
            cursor: pointer;
            background-color: #5F9EA0;
        }

        .nav-item:hover {
            background-color: #4b8282;
        }

        .nav-item.active {
            font-weight: bold;
            background-color: #5F9EA0;
            color: white;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .card-title {
            color: #333;
        }

        .table td,
        .table th {
            border-top: 1px solid #ccc;
            padding: 10px;
        }

        .text-primary {
            color: black;
        }

        @media (max-width: 768px) {
            .nav-tabs {
                flex-direction: column;
            }

            .nav-item {
                margin-bottom: 10px;
                margin-right: 0;
            }
        }
    </style>
    <link href="https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css" rel="stylesheet"/>
</head>

<body>
    <div class="wrapper">
        <div class="sidebar">
            <h2><i class="bx bxs-user"></i> My profile</h2>
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
            <div class="card-body">
                <h3 class="card-title"><i class="bx bx-calendar"></i> Timetable</h3>
                <br />
                <div class="tab-content mt-2">
                    <?php foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'] as $day): ?>
                    <div class="tab-pane fade <?php echo $day == 'Monday' ? 'show active' : ''; ?>" id="<?php echo $day; ?>">
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <?php if (isset($classes[$day]) && count($classes[$day]) > 0): ?>
                                        <?php foreach ($classes[$day] as $class): ?>
                                        <tr>
                                            <th scope="row"><?php echo $class['ClassTime']; ?></th>
                                            <td>
                                                <span class="text-primary"><?php echo $class['ClassDay']; ?></span><br />
                                                <span class="text-primary"><?php echo $class['SubjectName']; ?></span><br />
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                    <tr>
                                        <td>No classes scheduled for <?php echo $day; ?>.</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $(".nav-item").click(function (e) {
                e.preventDefault();
                $(".nav-item").removeClass("active");
                $(this).addClass("active");
                var day = $(this).data("day");
                $(".tab-pane").removeClass("show active");
                $("#" + day).addClass("show active");
            });

            // Load Monday data by default
            $("#Monday").addClass("show active");
        });
    </script>
</body>
</html>
