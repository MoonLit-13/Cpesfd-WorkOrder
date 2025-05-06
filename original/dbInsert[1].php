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

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // 1. Insert into work_orders
        $stmt = $pdo->prepare("
            INSERT INTO work_orders (
                company_name, street, city, zip, phone, fax, email,
                job_description, bill_name, bill_company, bill_address, bill_city, bill_phone,
                ship_name, ship_company, ship_address, ship_city, ship_phone,
                completed_date, signature, date
            ) VALUES (
                :company_name, :street, :city, :zip, :phone, :fax, :email,
                :job_description, :bill_name, :bill_company, :bill_address, :bill_city, :bill_phone,
                :ship_name, :ship_company, :ship_address, :ship_city, :ship_phone,
                :completed_date, :signature, :date
            )
        ");

        $stmt->execute([
            ':company_name'     => $_POST['company_name'] ?? '',
            ':street'           => $_POST['street'] ?? '',
            ':city'             => $_POST['city'] ?? '',
            ':zip'              => $_POST['zip'] ?? '',
            ':phone'            => $_POST['phone'] ?? '',
            ':fax'              => $_POST['fax'] ?? '',
            ':email'            => $_POST['email'] ?? '',
            ':job_description'  => $_POST['job_description'] ?? '',
            ':bill_name'        => $_POST['bill_name'] ?? '',
            ':bill_company'     => $_POST['bill_company'] ?? '',
            ':bill_address'     => $_POST['bill_address'] ?? '',
            ':bill_city'        => $_POST['bill_city'] ?? '',
            ':bill_phone'       => $_POST['bill_phone'] ?? '',
            ':ship_name'        => $_POST['ship_name'] ?? '',
            ':ship_company'     => $_POST['ship_company'] ?? '',
            ':ship_address'     => $_POST['ship_address'] ?? '',
            ':ship_city'        => $_POST['ship_city'] ?? '',
            ':ship_phone'       => $_POST['ship_phone'] ?? '',
            ':completed_date'   => $_POST['completed_date'] ?? null,
            ':signature'        => $_POST['signature'] ?? '',
            ':date'             => $_POST['date'] ?? null
        ]);

        $workOrderId = $pdo->lastInsertId();

        // 2. Insert into work_order_items
        $qtys = $_POST['qty'];
        $descriptions = $_POST['description'];
        $taxed = $_POST['taxed'] ?? [];
        $unit_prices = $_POST['unit_price'];

        foreach ($qtys as $index => $qty) {
            $qty = (int)$qty;
            $desc = $descriptions[$index] ?? '';
            $unit_price = (float)$unit_prices[$index] ?? 0;
            $is_taxed = in_array($index, array_keys($taxed)) ? 1 : 0;
            $line_total = $qty * $unit_price;

            $itemStmt = $pdo->prepare("
                INSERT INTO work_order_items (
                    work_order_id, qty, description, taxed, unit_price, line_total
                ) VALUES (
                    :work_order_id, :qty, :description, :taxed, :unit_price, :line_total
                )
            ");
            $itemStmt->execute([
                ':work_order_id' => $workOrderId,
                ':qty' => $qty,
                ':description' => $desc,
                ':taxed' => $is_taxed,
                ':unit_price' => $unit_price,
                ':line_total' => $line_total
            ]);
        }

        // 3. Insert into additional_charges
        $shipping = (float) ($_POST['shipping_handling'] ?? 0);
        $other = (float) ($_POST['other_charges'] ?? 0);

        // Recalculate subtotal, tax, total
        $subtotal = 0;
        $taxable = 0;
        $taxRate = 0.12;

        foreach ($qtys as $index => $qty) {
            $qty = (int)$qty;
            $unit_price = (float)$unit_prices[$index] ?? 0;
            $line = $qty * $unit_price;
            $subtotal += $line;

            if (in_array($index, array_keys($taxed))) {
                $taxable += $line;
            }
        }

        $tax = $taxable * $taxRate;
        $total = $subtotal + $tax + $shipping + $other;

        $chargesStmt = $pdo->prepare("
            INSERT INTO additional_charges (
                work_order_id, shipping_handling, other_charges,
                subtotal, taxable, tax, total
            ) VALUES (
                :work_order_id, :shipping_handling, :other_charges,
                :subtotal, :taxable, :tax, :total
            )
        ");

        $chargesStmt->execute([
            ':work_order_id' => $workOrderId,
            ':shipping_handling' => $shipping,
            ':other_charges' => $other,
            ':subtotal' => $subtotal,
            ':taxable' => $taxable,
            ':tax' => $tax,
            ':total' => $total
        ]);

        echo "Work order submitted successfully!";
    } else {
        echo "Invalid request.";
    }

} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}

header("Location: index.php");
?>