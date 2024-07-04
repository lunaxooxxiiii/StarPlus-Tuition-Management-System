<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "starplus";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch forms from the database
$sql_forms = "SELECT DISTINCT Form FROM subject";
$result_forms = $conn->query($sql_forms);

// Search for students if a search query is present
$search_result = null;
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {
    $search = $_POST['search'];
    $sql_search = "SELECT * FROM student WHERE FirstName LIKE '%$search%' OR LastName LIKE '%$search%'";
    $search_result = $conn->query($sql_search);
}

// Fetch all students for display
$sql_students = "SELECT * FROM student";
$result_students = $conn->query($sql_students);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            list-style: none;
            text-decoration: none;
            font-family: 'Verdana', 'Geneva', 'Tahoma', sans-serif;
        }

        body {
            display: flex;
            height: 100vh;
            background-color: #f0f2f5;
            margin: 0;
        }

        .sidebar {
            width: 250px;
            background: #6DC6C3;
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
            transition: background-color 0.3s ease;
        }

        .sidebar ul li a {
            color: white;
            display: flex;
            align-items: center;
            font-size: 18px;
            cursor: pointer;
        }

        .sidebar ul li a i {
            margin-right: 10px;
        }

        .sidebar ul li:hover {
            background-color: #5753A1;
        }

        .sidebar ul ul {
            display: none;
            padding-left: 20px;
        }

        .sidebar ul ul li {
            border: none;
        }

        .sidebar ul ul li a {
            font-size: 16px;
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
            padding: 20px;
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
            transition: background-color 0.3s ease;
        }

.form-actions .btn-cancel {
            background-color: #ccc;
        }

        .form-actions .btn-save {
            background-color: #2e3b87;
            color: white;
        }

        .form-actions button:hover {
            opacity: 0.8;
        }

        .cards {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            margin-top: 20px;
        }

        .card-class {
            background-color: #5F9EA0;
            width: 300px;
            margin: 10px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .card-class:hover {
            transform: scale(1.05);
        }

        .search-student {
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }

        .search-student form {
            display: flex;
            align-items: center;
            width: 100%;
        }

        .search-student input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px 0 0 5px;
        }

        .search-student button {
            padding: 10px;
            background-color: #2e3b87;
            color: white;
            border: none;
            border-radius: 0 5px 5px 0;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .search-student button:hover {
            background-color: #5753A1;
        }

        .student-list {
            margin-top: 20px;
        }

        .student-list h3 {
            margin-bottom: 10px;
            color: #2e3b87;
        }

        .student-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 10px;
        }

        .student-item p {
            margin: 0;
            font-weight: bold;
        }

        .student-item .btn-view {
            background-color: #2e3b87;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            cursor: pointer;
            margin-right: 5px;
            transition: background-color 0.3s ease;
        }

        .student-item .btn-delete {
            background-color: #e74c3c;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .student-item .btn-view:hover {
            background-color: #5753A1;
        }

.student-item .btn-delete:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>My Page</h2>
        <ul>
            <li><a href="#"><i class='bx bx-book'></i> Student</a>
                <ul>
                    <li><a href="#">Form 1</a></li>
                    <li><a href="#">Form 2</a></li>
                    <li><a href="#">Form 3</a></li>
                    <li><a href="#">Form 4</a></li>
                    <li><a href="#">Form 5</a></li>
                </ul>
            </li>
            <li><a href="#"><i class='bx bx-bell'></i> Announcement</a></li>
            <li><a href="login-choice.html"><i class='bx bx-log-out'></i> Log Out</a></li>
        </ul>
    </div>
    <div class="main_content">
        <div class="search-student">
            <form action="" method="post">
                <input type="text" placeholder="Search student..." name="search">
                <button type="submit"><i class='bx bx-search'></i></button>
            </form>
        </div>
        <div class="student-list">
            <h3>Form 5</h3>
            <?php
            if ($search_result !== null) {
                if ($search_result->num_rows > 0) {
                    while ($row = $search_result->fetch_assoc()) {
                        echo "<div class='student-item'>";
                        echo "<p>" . $row['FirstName'] . " " . $row['LastName'] . "</p>";
                        echo "<button class='btn-view'>VIEW</button>";
                        echo "<button class='btn-delete' onclick='confirmDelete(\"" . $row['FirstName'] . "\", \"" . $row['LastName'] . "\")'>delete</button>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>No results found.</p>";
                }
            } else {
                if ($result_students->num_rows > 0) {
                    while ($row = $result_students->fetch_assoc()) {
                        echo "<div class='student-item'>";
                        echo "<p>" . $row['FirstName'] . " " . $row['LastName'] . "</p>";
                        echo "<button class='btn-view'>VIEW</button>";
                        echo "<button class='btn-delete' onclick='confirmDelete(\"" . $row['FirstName'] . "\", \"" . $row['LastName'] . "\")'>delete</button>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>No students found.</p>";
                }
            }
            ?>
        </div>
    </div>

    <script>
        function confirmDelete(firstName, lastName) {
            if (confirm("Are you sure you want to delete " + firstName + " " + lastName + "?")) {
                window.location.href = "delete_student.php?firstName=" + firstName + "&lastName=" + lastName;
            }
        }

        // Show/Hide submenus in the sidebar
        document.querySelectorAll('.sidebar ul li a').forEach(function(element) {
            element.addEventListener('click', function(e) {
                const submenu = this.nextElementSibling;
                if (submenu && submenu.tagName === 'UL') {
                    e.preventDefault();
                    submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
                }
            });
        });
    </script>
</body>
</html>
