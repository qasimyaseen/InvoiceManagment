<?php
require_once '../includes/auth.php';
require_once '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_id = $_POST['customer_id'];
    $invoice_date = $_POST['invoice_date'];
    $status = $_POST['status'];
    $items = $_POST['items'] ?? [];

    if (empty($customer_id) || empty($invoice_date) || empty($items)) {
        die("Invalid Request");
    }

    $conn->begin_transaction();

    try {
        // 1. Calculate Total Amount
        $total_amount = 0;
        foreach ($items as $item) {
            $total_amount += ($item['quantity'] * $item['price']);
        }

        // 2. Insert Invoice
        $stmt = $conn->prepare("INSERT INTO invoices (customer_id, invoice_date, total_amount, status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isds", $customer_id, $invoice_date, $total_amount, $status);
        $stmt->execute();
        $invoice_id = $conn->insert_id;
        $stmt->close();

        // 3. Insert Items
        $stmt = $conn->prepare("INSERT INTO invoice_items (invoice_id, description, quantity, unit_price, line_total) VALUES (?, ?, ?, ?, ?)");
        
        foreach ($items as $item) {
            $desc = $item['description'];
            $qty = $item['quantity'];
            $price = $item['price'];
            $line_total = $qty * $price;
            
            $stmt->bind_param("isidd", $invoice_id, $desc, $qty, $price, $line_total);
            $stmt->execute();
        }
        $stmt->close();

        $conn->commit();
        header("Location: " . BASE_URL . "/invoices/view.php?id=" . $invoice_id . "&msg=created");
        exit;

    } catch (Exception $e) {
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
}
?>

