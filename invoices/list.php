<?php
require_once '../includes/auth.php';
require_once '../config/database.php';
require_once '../includes/header.php';

// Fetch invoices with customer names
$sql = "SELECT i.*, c.name as customer_name 
        FROM invoices i 
        JOIN customers c ON i.customer_id = c.id 
        ORDER BY i.created_at DESC";
$result = $conn->query($sql);
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Invoices</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?php echo BASE_URL; ?>/invoices/create.php" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-lg"></i> Create Invoice
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col" class="ps-4">Invoice #</th>
                        <th scope="col">Customer</th>
                        <th scope="col">Date</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="text-end">Total</th>
                        <th scope="col" class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td class="ps-4 fw-medium">#<?php echo str_pad($row['id'], 5, '0', STR_PAD_LEFT); ?></td>
                                <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                                <td><?php echo date('M d, Y', strtotime($row['invoice_date'])); ?></td>
                                <td>
                                    <span class="badge rounded-pill badge-status-<?php echo $row['status']; ?> px-3">
                                        <?php echo ucfirst($row['status']); ?>
                                    </span>
                                </td>
                                <td class="text-end fw-bold">$<?php echo number_format($row['total_amount'], 2); ?></td>
                                <td class="text-end pe-4">
                                    <div class="btn-group">
                                        <a href="<?php echo BASE_URL; ?>/invoices/view.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-secondary" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="<?php echo BASE_URL; ?>/invoices/delete.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this invoice?');" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="bi bi-file-earmark-text fs-1 d-block mb-2"></i>
                                No invoices found. <a href="<?php echo BASE_URL; ?>/invoices/create.php">Create your first invoice</a>.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>

