<?php
require_once '../includes/auth.php';
require_once '../config/database.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Check for related invoices first (optional, but good practice. For now CASCADE will handle it in DB schema)
    // The Schema has ON DELETE CASCADE, so we can just delete.
    
    $stmt = $conn->prepare("DELETE FROM customers WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        header("Location: " . BASE_URL . "/customers/list.php?msg=deleted");
    } else {
        header("Location: " . BASE_URL . "/customers/list.php?error=deletefailed");
    }
    $stmt->close();
} else {
    header("Location: " . BASE_URL . "/customers/list.php");
}
$conn->close();
?>

