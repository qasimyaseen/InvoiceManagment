<?php
require_once '../includes/auth.php';
require_once '../config/database.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: " . BASE_URL . "/invoices/list.php");
    exit;
}

// Fetch invoice details
$stmt = $conn->prepare("SELECT i.*, c.name, c.email, c.phone, c.address FROM invoices i JOIN customers c ON i.customer_id = c.id WHERE i.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$invoice = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$invoice) {
    die("Invoice not found");
}

// Fetch items
$stmt = $conn->prepare("SELECT * FROM invoice_items WHERE invoice_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$items_result = $stmt->get_result();
$stmt->close();

require_once '../includes/header.php';
?>

<div class="d-print-none d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">View Invoice #<?php echo str_pad($invoice['id'], 5, '0', STR_PAD_LEFT); ?></h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="javascript:window.print()" class="btn btn-sm btn-outline-primary me-2">
            <i class="bi bi-printer"></i> Print
        </a>
        <a href="<?php echo BASE_URL; ?>/invoices/list.php" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="card border-0 shadow-none print-card">
    <div class="card-body p-5">
        <!-- Header -->
        <div class="row mb-5">
            <div class="col-6">
                <h2 class="text-primary fw-bold">INVOICE</h2>
                <p class="text-muted">Invoice #<?php echo str_pad($invoice['id'], 5, '0', STR_PAD_LEFT); ?><br>
                Date: <?php echo date('M d, Y', strtotime($invoice['invoice_date'])); ?><br>
                Status: <span class="badge badge-status-<?php echo $invoice['status']; ?>"><?php echo ucfirst($invoice['status']); ?></span></p>
            </div>
            <div class="col-6 text-end">
                <h4 class="fw-bold">My Company Name</h4>
                <p class="text-muted">123 Business Street<br>
                Tech City, TC 90210<br>
                support@mycompany.com</p>
            </div>
        </div>

        <!-- Bill To -->
        <div class="row mb-5">
            <div class="col-6">
                <h6 class="text-uppercase text-muted fw-bold mb-3">Bill To:</h6>
                <h5 class="fw-bold"><?php echo htmlspecialchars($invoice['name']); ?></h5>
                <p class="text-muted">
                    <?php echo nl2br(htmlspecialchars($invoice['address'])); ?><br>
                    <?php echo htmlspecialchars($invoice['email']); ?><br>
                    <?php echo htmlspecialchars($invoice['phone']); ?>
                </p>
            </div>
        </div>

        <!-- Items -->
        <div class="table-responsive mb-5">
            <table class="table table-striped">
                <thead class="bg-light">
                    <tr>
                        <th scope="col" class="border-0">Description</th>
                        <th scope="col" class="text-center border-0" style="width: 100px;">Qty</th>
                        <th scope="col" class="text-end border-0" style="width: 150px;">Price</th>
                        <th scope="col" class="text-end border-0" style="width: 150px;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($item = $items_result->fetch_assoc()): ?>
                    <tr>
                        <td class="border-0"><?php echo htmlspecialchars($item['description']); ?></td>
                        <td class="text-center border-0"><?php echo $item['quantity']; ?></td>
                        <td class="text-end border-0">$<?php echo number_format($item['unit_price'], 2); ?></td>
                        <td class="text-end border-0">$<?php echo number_format($item['line_total'], 2); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Totals -->
        <div class="row justify-content-end">
            <div class="col-md-4">
                <table class="table table-borderless">
                    <tr>
                        <td class="fw-bold text-end">Grand Total:</td>
                        <td class="fw-bold text-end fs-5 text-primary">$<?php echo number_format($invoice['total_amount'], 2); ?></td>
                    </tr>
                </table>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="text-center text-muted mt-5 pt-5 border-top">
            <small>Thank you for your business!</small>
        </div>
    </div>
</div>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    .print-card, .print-card * {
        visibility: visible;
    }
    .print-card {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        border: none !important;
        box-shadow: none !important;
    }
    .main-content {
        margin: 0 !important;
        padding: 0 !important;
    }
}
</style>

<?php require_once '../includes/footer.php'; ?>

