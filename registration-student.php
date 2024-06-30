<?php
// Start session
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

// Check if form data is set
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $plain_password = $_POST['password'];

    // Protect against SQL injection
    $firstName = $conn->real_escape_string($firstName);
    $lastName = $conn->real_escape_string($lastName);
    $email = $conn->real_escape_string($email);
    $plain_password = $conn->real_escape_string($plain_password);

    // Check if email already exists
    $sql = "SELECT * FROM starplus.student WHERE StudentEmail='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $message = "Email already exists. Please use a different email.";
        echo "<script type='text/javascript'>alert('$message');</script>";
    } else {
        // Insert new student into the database
        $sql = "INSERT INTO starplus.student (FirstName, LastName, StudentEmail, StudentPassword) VALUES ('$firstName', '$lastName', '$email', '$plain_password')";

        if ($conn->query($sql) === TRUE) {
            $message = "New student registered successfully.";
            echo "<script type='text/javascript'>alert('$message'); setTimeout(function(){ window.location.href = 'student-login.html'; }, 1000);</script>";
        } else {
            $message = "Error: " . $sql . "<br>" . $conn->error;
            echo "<script type='text/javascript'>alert('$message');</script>";
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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
</head>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap');

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Poppins', sans-serif;
    }

    body {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        background: #23255D;
    }

    .register-box {
        display: flex;
        justify-content: center;
        flex-direction: column;
        width: 440px;
        height: 480px;
        padding: 30px;
    }

    .input-box .input-field {
        width: 100%;
        height: 60px;
        font-size: 17px;
        padding: 0 25px;
        margin-bottom: 15px;
        border-radius: 30px;
        border: none;
        box-shadow: 0px 5px 10px 1px rgba(0, 0, 0, 0.05);
        outline: none;
    }

    ::placeholder {
        font-weight: 500;
        color: #222;
    }

    .input-submit {
        position: relative;
    }

    .submit-btn {
        width: 100%;
        height: 60px;
        background: #C40248;
        border: none;
        border-radius: 30px;
        cursor: pointer;
        transition: .3s;
    }

    .input-submit label {
        position: absolute;
        top: 45%;
        left: 50%;
        color: #fff;
        transform: translate(-50%, -50%);
        cursor: pointer;
    }

    .submit-btn:hover {
        background: red;
        transform: scale(1.05, 1);
    }
</style>
<body>
    <div class="register-box">
        <div class="register">
            <img src="logoStud.jpg" alt="StarPlus Logo" width="100%" height="auto">
        </div>
        <form action="" method="post">
            <div class="input-box">
                <input type="text" class="input-field" placeholder="First Name" name="firstName" autocomplete="off" required>
            </div>
            <div class="input-box">
                <input type="text" class="input-field" placeholder="Last Name" name="lastName" autocomplete="off" required>
            </div>
            <div class="input-box">
                <input type="text" class="input-field" placeholder="Email" name="email" autocomplete="off" required>
            </div>
            <div class="input-box">
                <input type="password" class="input-field" placeholder="Password" name="password" autocomplete="off" required>
            </div>
            <div class="input-submit">
                <button type="submit" class="submit-btn" id="submit">Register</button>
            </div>
        </form>
    </div>
</body>
</html>
