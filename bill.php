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

// Initialize the subscriptions array
$subscriptions = array();

// Fetch subscription details for the subscribed subjects
if (!empty($subjects)) {
    $subjectCodes = implode("','", $subjects);
    $subscriptionSql = "SELECT s.SubjectName, s.SubjectPrice 
                        FROM subject s 
                        WHERE s.SubjectCode IN ('$subjectCodes')";
    $subscriptionResult = $conn->query($subscriptionSql);

    // Store subscriptions in an array
    if ($subscriptionResult->num_rows > 0) {
        while ($subscriptionRow = $subscriptionResult->fetch_assoc()) {
            $subscriptions[] = $subscriptionRow;
        }
    }
}

// Calculate totals
$subtotal = 0;
foreach ($subscriptions as $subscription) {
    $subtotal += $subscription['SubjectPrice'];
}
$total = $subtotal;

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bill</title>
    <link href="https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css" rel="stylesheet">
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

        .subscription-container {
            width: 100%;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #fff;
        }

        .subscription-header {
            background-color: #f5f5f5;
            padding: 20px;
            border-bottom: 1px solid #ddd;
            text-align: center;
            font-size: 20px;
            font-weight: bold;
        }

        .subscription-table {
            width: 100%;
            border-collapse: collapse;
        }

        .subscription-table th,
        .subscription-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .subscription-table th {
            background-color: #f5f5f5;
        }

        .subscription-footer {
            padding: 20px;
            text-align: right;
            font-size: 16px;
        }

        @media (max-width: 1024px) {
            .wrapper {
                flex-direction: column;
                height: auto;
            }

            .sidebar {
                width: 100%;
                height: auto;
                padding: 20px 0;
            }

            .main_content {
                margin-left: 0;
                padding: 15px;
            }
        }

        @media (max-width: 768px) {
            .sidebar ul li a {
                font-size: 16px;
            }
        }
    </style>
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
                <li><a href="student-login.html"><i class='bx bx-log-out'></i> Logout</a></li>
            </ul>
        </div>
        <div class="main_content">
            <div class="card-body">
                <div class="subscription-container">
                    <div class="subscription-header">SUBSCRIPTION TOTALS</div>
                    <table class="subscription-table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Product</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($subscriptions as $subscription): ?>
                            <tr>
                                <td><i class="bx bx-x"></i></td>
                                <td>LIVE ONLINE TUITION - <?php echo $subscription['SubjectName']; ?></td>
                                <td>RM<?php echo number_format($subscription['SubjectPrice'], 2); ?> / month</td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="subscription-footer">
                        <div>Subtotal: RM<?php echo number_format($subtotal, 2); ?></div>
                        <div>Total: RM<?php echo number_format($total, 2); ?> / month</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
