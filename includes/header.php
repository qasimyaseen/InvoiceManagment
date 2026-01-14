<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Rudimentary active tab detection
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Manager</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <link href="<?php echo BASE_URL; ?>/assets/css/style.css" rel="stylesheet">
</head>
<body>

<?php if(isset($_SESSION['user_id'])): ?>
    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column flex-shrink-0 p-3 bg-white">
        <a href="<?php echo BASE_URL; ?>/index.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
            <i class="bi bi-receipt-cutoff fs-4 me-2 text-primary"></i>
            <span class="fs-4 fw-bold">InvoiceMgr</span>
        </a>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="<?php echo BASE_URL; ?>/index.php" class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>" aria-current="page">
                    <i class="bi bi-speedometer2"></i>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo BASE_URL; ?>/customers/list.php" class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/customers/') !== false) ? 'active' : ''; ?>">
                    <i class="bi bi-people"></i>
                    Customers
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo BASE_URL; ?>/invoices/list.php" class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/invoices/') !== false) ? 'active' : ''; ?>">
                    <i class="bi bi-file-earmark-text"></i>
                    Invoices
                </a>
            </li>
        </ul>
        <hr>
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center link-dark text-decoration-none dropdown-toggle" id="dropdownUser2" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center me-2" style="width: 32px; height: 32px;">
                    <?php echo strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)); ?>
                </div>
                <strong><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?></strong>
            </a>
            <ul class="dropdown-menu text-small shadow" aria-labelledby="dropdownUser2">
                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/logout.php">Sign out</a></li>
            </ul>
        </div>
    </div>

    <!-- Main Content Wrapper -->
    <div class="main-content">
<?php endif; ?>
