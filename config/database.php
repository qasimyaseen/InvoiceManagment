<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "invoice_order_managment";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start Session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Define Base URL - Update this if folder name changes
define('BASE_URL', '/InvoiceManagment');
?>