<?php


$host = 'localhost';
$db   = 'my_project_db';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    // Fetch all work orders
    $stmt = $pdo->query("SELECT * FROM work_orders ORDER BY created_at DESC");
    $workOrders = $stmt->fetchAll();

} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
    exit;
}
//delete WO
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete-btn']) && $_POST['delete-btn'] === "Delete") {
    $id = $_POST['WOid'];
    $stmt = $pdo->prepare("DELETE FROM work_orders WHERE id = ?");
    $stmt->execute([$id]);
}


?>

<!DOCTYPE html>
<html>
<head>
    <title>Work Order Dashboard</title>
    <style>
        
        body { font-family: Arial; padding: 20px; padding: 20px; margin: 30px; background: linear-gradient(140deg, skyblue, white);}
        /*h1 {border: 1px solid #ccc; border-radius: 5px; background-color: white; }*/
        .work-order { border: 1px solid #ccc; padding: 20px; margin-bottom: 50px; padding: 25px 30px; border-radius: 5px; background: white; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #aaa; padding: 8px; text-align: left; }
        th { background: #eee; }
        h2 { margin-bottom: 5px; }

    </style>
</head>
<body>

<h1>Work Order Dashboard</h1>

<?php foreach ($workOrders as $order): ?>
    <div class="work-order">
        <h2>Work Order #<?= $order['id'] ?></h2> 
        
<!-- delete btn -->
<form method='post'>
    <input type="hidden" name="WOid" value="<?= $order['id'] ?>">
    <p><input type="submit" name="delete-btn" value="Delete"></p>
    
</form>
<!-- update btn -->
<form method="post" action="update.php">
    <input type="hidden" name="WOid" value="<?= $order['id'] ?>">
    <input type="submit" name="load-for-edit" value="Edit">
</form>


        <p><strong>Company:</strong> <?= htmlspecialchars($order['company_name']) ?></p>
        <p><strong>Job Description:</strong> <?= nl2br(htmlspecialchars($order['job_description'])) ?></p>
        <p><strong>Created:</strong> <?= $order['created_at'] ?></p>

        <?php
        // Fetch items
        $itemStmt = $pdo->prepare("SELECT * FROM work_order_items WHERE work_order_id = ?");
        $itemStmt->execute([$order['id']]);
        $items = $itemStmt->fetchAll();

        // Fetch charges
        $chargeStmt = $pdo->prepare("SELECT * FROM additional_charges WHERE work_order_id = ?");
        $chargeStmt->execute([$order['id']]);
        $charges = $chargeStmt->fetch();
        ?>

        <h3>Items</h3>
        <table>
            <thead>
                <tr>
                    <th>Qty</th>
                    <th>Description</th>
                    <th>Taxed</th>
                    <th>Unit Price</th>
                    <th>Line Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= $item['qty'] ?></td>
                        <td><?= htmlspecialchars($item['description']) ?></td>
                        <td><?= $item['taxed'] ? 'Yes' : 'No' ?></td>
                        <td>$<?= number_format($item['unit_price'], 2) ?></td>
                        <td>$<?= number_format($item['line_total'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php if ($charges): ?>
            <h3>Charges</h3>
            <p><strong>Subtotal:</strong> $<?= number_format($charges['subtotal'], 2) ?></p>
            <p><strong>Taxable:</strong> $<?= number_format($charges['taxable'], 2) ?></p>
            <p><strong>Tax:</strong> $<?= number_format($charges['tax'], 2) ?></p>
            <p><strong>Shipping & Handling:</strong> $<?= number_format($charges['shipping_handling'], 2) ?></p>
            <p><strong>Other Charges:</strong> $<?= number_format($charges['other_charges'], 2) ?></p>
            <p><strong>Total:</strong> <strong>$<?= number_format($charges['total'], 2) ?></strong></p>
        <?php endif; ?>
    </div>
    
    
<?php endforeach; ?>


<form action="form.php">
  <input type="submit" value="New Workorder">
</form>
</body>
</html>

