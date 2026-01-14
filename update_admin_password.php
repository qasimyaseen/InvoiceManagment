<?php
require_once './config/database.php';

$password = '123123123';
$email = 'qasim@gmail.com';

$stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
$stmt->bind_param("ss", $password, $email);

if ($stmt->execute()) {
    echo "Password updated successfully to 'password123' for user 'admin@example.com'. <br> You can now <a href='".BASE_URL."/login.php'>Login</a>.";
} else {
    echo "Error updating password: " . $conn->error;
}
$stmt->close();
$conn->close();
?>
