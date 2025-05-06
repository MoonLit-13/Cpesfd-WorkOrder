<!DOCTYPE html>
<html>
<head>
    <title>Create Work Order</title>
</head>
<body>
    <h1>Create Work Order</h1>
    <form action="/store" method="POST">
        <input type="text" name="company_name" placeholder="Company Name" required>
        <textarea name="job_description" placeholder="Job Description" required></textarea>
        <button type="submit">Submit</button>
    </form>
</body>
</html>