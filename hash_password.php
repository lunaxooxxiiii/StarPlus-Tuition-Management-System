<?php
// Example plain text password
$plain_password = '123';

// Hash the password
$hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);

// Output the hashed password
echo "Plain Password: " . $plain_password . "<br>";
echo "Hashed Password: " . $hashed_password;
?>
