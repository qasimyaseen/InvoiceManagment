<?php
session_start();
require_once '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        header("Location: " . BASE_URL . "/login.php?error=All fields are required");
        exit;
    }

    $sql = "SELECT id, name, password FROM users WHERE email = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($id, $name, $stored_password);
            if ($stmt->fetch()) {
                // Direct plain text comparison (Not recommended for production, but requested)
                if ($password === $stored_password) {
                    // Password is correct, start session
                    $_SESSION['user_id'] = $id;
                    $_SESSION['user_name'] = $name;
                    header("Location: " . BASE_URL . "/index.php");
                    exit;
                } else {
                    header("Location: " . BASE_URL . "/login.php?error=Invalid password");
                    exit;
                }
            }
        } else {
            header("Location: " . BASE_URL . "/login.php?error=User not found");
            exit;
        }
        $stmt->close();
    }
}
$conn->close();
?>
