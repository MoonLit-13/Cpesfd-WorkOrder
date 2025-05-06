<!DOCTYPE html>
<html>
<head>
    <title>Work Order Dashboard</title>
</head>
<body>
    <h1>Work Order Dashboard</h1>
    <a href="/create">Create New Work Order</a>
    <?php foreach ($workOrders as $order): ?>
        <div>
            <h2>Work Order #<?= $order['id'] ?></h2>
            <p>Company: <?= htmlspecialchars($order['company_name']) ?></p>
            <p>Job Description: <?= nl2br(htmlspecialchars($order['job_description'])) ?></p>
            <a href="/edit/<?= $order['id'] ?>">Edit</a>
            <a href="/delete/<?= $order['id'] ?>">Delete</a>
        </div>
    <?php endforeach; ?>
</body>
</html>