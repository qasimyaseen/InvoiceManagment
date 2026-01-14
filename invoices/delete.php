<?php
require_once '../includes/auth.php';
require_once '../config/database.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Invoices are set to CASCADE DELETE items in schema, so we just delete invoice.
    
    $stmt = $conn->prepare("DELETE FROM invoices WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        header("Location: " . BASE_URL . "/invoices/list.php?msg=deleted");
    } else {
        header("Location: " . BASE_URL . "/invoices/list.php?error=deletefailed");
    }
    $stmt->close();
} else {
    header("Location: " . BASE_URL . "/invoices/list.php");
}
$conn->close();
?>

