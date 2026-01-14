<?php
require_once '../includes/auth.php';
require_once '../config/database.php';

$error = '';
$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: " . BASE_URL . "/customers/list.php");
    exit;
}

// Fetch existing data
$stmt = $conn->prepare("SELECT * FROM customers WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$customer = $result->fetch_assoc();
$stmt->close();

if (!$customer) {
    header("Location: " . BASE_URL . "/customers/list.php?error=notfound");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    if (empty($name)) {
        $error = "Name is required.";
    } else {
        // Check if email exists for other users
        if (!empty($email)) {
             $stmt = $conn->prepare("SELECT id FROM customers WHERE email = ? AND id != ?");
             $stmt->bind_param("si", $email, $id);
             $stmt->execute();
             if ($stmt->fetch()) {
                 $error = "A customer with this email already exists.";
             }
             $stmt->close();
        }

        if (empty($error)) {
            $sql = "UPDATE customers SET name = ?, email = ?, phone = ?, address = ? WHERE id = ?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("ssssi", $name, $email, $phone, $address, $id);
                if ($stmt->execute()) {
                    header("Location: " . BASE_URL . "/customers/list.php?msg=updated");
                    exit;
                } else {
                    $error = "Something went wrong. Please try again later.";
                }
                $stmt->close();
            }
        }
    }
} else {
    // Populate variables from DB
    $name = $customer['name'];
    $email = $customer['email'];
    $phone = $customer['phone'];
    $address = $customer['address'];
}

require_once '../includes/header.php';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Customer</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?php echo BASE_URL; ?>/customers/list.php" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to List
        </a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $id; ?>" method="POST">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" required value="<?php echo htmlspecialchars($name); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="3"><?php echo htmlspecialchars($address); ?></textarea>
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary px-4">Update Customer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>

