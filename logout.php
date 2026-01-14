<?php
session_start();
$_SESSION = array();
session_destroy();
require_once './config/database.php';
header("Location: " . BASE_URL . "/login.php");
exit;
?>
