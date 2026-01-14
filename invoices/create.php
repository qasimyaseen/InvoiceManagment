<?php
require_once '../includes/auth.php';
require_once '../config/database.php';
require_once '../includes/header.php';

// Fetch customers for dropdown
$customers = $conn->query("SELECT id, name FROM customers ORDER BY name ASC");
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Create New Invoice</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?php echo BASE_URL; ?>/invoices/list.php" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to List
        </a>
    </div>
</div>

<form action="<?php echo BASE_URL; ?>/invoices/store.php" method="POST" id="invoiceForm">
    <div class="row g-4">
        <!-- Invoice Details -->
        <div class="col-md-12">
            <div class="card border-0 shadow-sm p-3">
                <div class="card-body">
                    <h5 class="card-title mb-4">Invoice Details</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="customer_id" class="form-label">Customer <span class="text-danger">*</span></label>
                            <select class="form-select" id="customer_id" name="customer_id" required>
                                <option value="">Select Customer</option>
                                <?php if($customers && $customers->num_rows > 0): ?>
                                    <?php while($row = $customers->fetch_assoc()): ?>
                                        <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['name']); ?></option>
                                    <?php endwhile; ?>
                                <?php endif; ?>
                            </select>
                            <div class="form-text">
                                Don't see the customer? <a href="<?php echo BASE_URL; ?>/customers/add.php" target="_blank">Add new customer</a>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="invoice_date" class="form-label">Invoice Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="invoice_date" name="invoice_date" value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="pending" selected>Pending</option>
                                <option value="paid">Paid</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoice Items -->
        <div class="col-md-12">
            <div class="card border-0 shadow-sm p-3">
                <div class="card-body">
                    <h5 class="card-title mb-4">Invoice Items</h5>
                    <div class="table-responsive">
                        <table class="table" id="itemsTable">
                            <thead>
                                <tr>
                                    <th style="width: 40%">Description</th>
                                    <th style="width: 15%">Quantity</th>
                                    <th style="width: 20%">Unit Price</th>
                                    <th style="width: 20%" class="text-end">Total</th>
                                    <th style="width: 5%"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="item-row">
                                    <td>
                                        <input type="text" class="form-control" name="items[0][description]" required placeholder="Item description">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control quantity" name="items[0][quantity]" value="1" min="1" required>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" class="form-control price" name="items[0][price]" value="0.00" min="0" step="0.01" required>
                                        </div>
                                    </td>
                                    <td class="text-end align-middle">
                                        <span class="row-total fw-bold">$0.00</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <button type="button" class="btn btn-sm btn-outline-danger remove-row" disabled>
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5">
                                        <button type="button" class="btn btn-sm btn-outline-primary" id="addItem">
                                            <i class="bi bi-plus-lg"></i> Add Item
                                        </button>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <div class="row justify-content-end mt-4">
                        <div class="col-md-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="fw-bold">Grand Total:</span>
                                <span class="fw-bold fs-4" id="grandTotal">$0.00</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 text-end mb-5">
            <button type="submit" class="btn btn-primary btn-lg px-5">Save Invoice</button>
        </div>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tableBody = document.querySelector('#itemsTable tbody');
    const addItemBtn = document.getElementById('addItem');
    const grandTotalEl = document.getElementById('grandTotal');
    let itemIndex = 1;

    // Calculate totals
    function calculateTotals() {
        let grandTotal = 0;
        document.querySelectorAll('.item-row').forEach(row => {
            const qty = parseFloat(row.querySelector('.quantity').value) || 0;
            const price = parseFloat(row.querySelector('.price').value) || 0;
            const total = qty * price;
            
            row.querySelector('.row-total').textContent = '$' + total.toFixed(2);
            grandTotal += total;
        });
        grandTotalEl.textContent = '$' + grandTotal.toFixed(2);
    }

    // Add new row
    addItemBtn.addEventListener('click', function() {
        const firstRow = tableBody.firstElementChild;
        const newRow = firstRow.cloneNode(true);
        
        // Clear values & update names
        newRow.querySelector('input[name$="[description]"]').value = '';
        newRow.querySelector('input[name$="[description]"]').name = `items[${itemIndex}][description]`;
        
        newRow.querySelector('input[name$="[quantity]"]').value = '1';
        newRow.querySelector('input[name$="[quantity]"]').name = `items[${itemIndex}][quantity]`;
        
        newRow.querySelector('input[name$="[price]"]').value = '0.00';
        newRow.querySelector('input[name$="[price]"]').name = `items[${itemIndex}][price]`;
        
        newRow.querySelector('.row-total').textContent = '$0.00';
        
        // Enable remove button
        newRow.querySelector('.remove-row').disabled = false;
        
        tableBody.appendChild(newRow);
        itemIndex++;
        calculateTotals();
    });

    // Event delegation for input changes & remove button
    tableBody.addEventListener('input', function(e) {
        if (e.target.classList.contains('quantity') || e.target.classList.contains('price')) {
            calculateTotals();
        }
    });

    tableBody.addEventListener('click', function(e) {
        if (e.target.closest('.remove-row')) {
            const row = e.target.closest('tr');
            if (document.querySelectorAll('.item-row').length > 1) {
                row.remove();
                calculateTotals();
            }
        }
    });

    // Initial calculation
    calculateTotals();
});
</script>

<?php require_once '../includes/footer.php'; ?>

