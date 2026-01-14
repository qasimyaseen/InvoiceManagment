<?php
require_once './includes/auth.php';
require_once './config/database.php';
require_once './includes/header.php';

// Fetch stats
$customer_count = 0;
$invoice_count = 0;
$pending_amount = 0.00;
$paid_invoices = 0;

$result = $conn->query("SELECT COUNT(*) as count FROM customers");
if ($result) $customer_count = $result->fetch_assoc()['count'];

$result = $conn->query("SELECT COUNT(*) as count FROM invoices");
if ($result) $invoice_count = $result->fetch_assoc()['count'];

$result = $conn->query("SELECT COUNT(*) as count FROM invoices WHERE status = 'paid'");
if ($result) $paid_invoices = $result->fetch_assoc()['count'];

$result = $conn->query("SELECT SUM(total_amount) as total FROM invoices WHERE status = 'pending'");
if ($result) {
    $row = $result->fetch_assoc();
    $pending_amount = $row['total'] ?? 0.00;
}
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="<?php echo BASE_URL; ?>/invoices/create.php" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-plus-lg"></i> Create Invoice
            </a>
            <a href="<?php echo BASE_URL; ?>/customers/add.php" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-person-plus"></i> Add Customer
            </a>
        </div>
    </div>
</div>

<div class="row g-4 mb-5">
    <!-- Total Customers -->
    <div class="col-md-6 col-lg-3">
        <div class="card card-dashboard p-3 h-100">
            <div class="d-flex align-items-center">
                <div class="card-icon bg-primary bg-opacity-10 text-primary me-3">
                    <i class="bi bi-people"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-0">Total Customers</h6>
                    <h3 class="mb-0 fw-bold"><?php echo $customer_count; ?></h3>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Total Invoices -->
    <div class="col-md-6 col-lg-3">
        <div class="card card-dashboard p-3 h-100">
            <div class="d-flex align-items-center">
                <div class="card-icon bg-info bg-opacity-10 text-info me-3">
                    <i class="bi bi-file-text"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-0">Total Invoices</h6>
                    <h3 class="mb-0 fw-bold"><?php echo $invoice_count; ?></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Paid Invoices -->
    <div class="col-md-6 col-lg-3">
        <div class="card card-dashboard p-3 h-100">
            <div class="d-flex align-items-center">
                <div class="card-icon bg-success bg-opacity-10 text-success me-3">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-0">Paid Invoices</h6>
                    <h3 class="mb-0 fw-bold"><?php echo $paid_invoices; ?></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Amount -->
    <div class="col-md-6 col-lg-3">
        <div class="card card-dashboard p-3 h-100">
            <div class="d-flex align-items-center">
                <div class="card-icon bg-warning bg-opacity-10 text-warning me-3">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-0">Pending Amount</h6>
                    <h3 class="mb-0 fw-bold">$<?php echo number_format($pending_amount, 2); ?></h3>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Invoices (Optional) -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0">Recent Invoices</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col" class="ps-4">Invoice #</th>
                        <th scope="col">Customer</th>
                        <th scope="col">Date</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="text-end pe-4">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $recents = $conn->query("SELECT i.*, c.name as customer_name FROM invoices i JOIN customers c ON i.customer_id = c.id ORDER BY i.created_at DESC LIMIT 5");
                    if ($recents && $recents->num_rows > 0):
                        while($row = $recents->fetch_assoc()):
                    ?>
                    <tr>
                        <td class="ps-4">#<?php echo str_pad($row['id'], 5, '0', STR_PAD_LEFT); ?></td>
                        <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                        <td><?php echo date('M d, Y', strtotime($row['invoice_date'])); ?></td>
                        <td>
                            <span class="badge rounded-pill badge-status-<?php echo $row['status']; ?> px-3 py-2">
                                <?php echo ucfirst($row['status']); ?>
                            </span>
                        </td>
                        <td class="text-end pe-4 fw-bold">$<?php echo number_format($row['total_amount'], 2); ?></td>
                    </tr>
                    <?php endwhile; else: ?>
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">No invoices found</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once './includes/footer.php'; ?>
