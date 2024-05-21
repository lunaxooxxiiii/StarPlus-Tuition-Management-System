<?php
$day = $_GET['day'];

$servername = "your_servername";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM timetable WHERE ClassDay='$day'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo '<div class="table-responsive">';
    echo '<table class="table">';
    echo '<tbody>';
    while($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<th scope="row">' . $row['time'] . '</th>';
        echo '<td>';
        echo '<span class="text-primary">' . $row['code'] . '</span>: <strong>' . $row['subject'] . '</strong><br>';
        echo '<span class="text-primary">Bilik:</span> ' . $row['room'] . '<br>';
        echo '<span class="text-primary">Kumpulan:</span> ' . $row['group'] . '</td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
    echo '</div>';
} else {
    echo '<p>No classes scheduled for ' . $day . '.</p>';
}

$conn->close();
?>
