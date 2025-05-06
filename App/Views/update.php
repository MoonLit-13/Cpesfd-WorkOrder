<!DOCTYPE html>
<html>
<head>
    <title>Edit Work Order</title>
</head>
<body>
    <h1>Edit Work Order</h1>
    <form action="/update/<?= $workOrder['id'] ?>" method="POST">
        <input type="text" name="company_name" value="<?= htmlspecialchars($workOrder['company_name']) ?>" required>
        <textarea name="job_description" required><?= htmlspecialchars($workOrder['job_description']) ?></textarea>
        <button type="submit">Update</button>
    </form>
</body>
</html>