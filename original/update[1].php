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

    $WOid = $_POST['WOid'];
    // Fetch work order based on ID (safely using a prepared statement)
    $stmt = $pdo->prepare("SELECT * FROM work_orders WHERE id = ? ORDER BY created_at DESC");
    $stmt->execute([$WOid]);
    $workOrders = $stmt->fetch();

} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
    exit;
}



?>

<!DOCTYPE html>
<html>
<head>
  <title>Work Order Form</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>

<form action="dbinsert.php" method="post">
  <div class="container1" >
    <div class="header">
      <img src="your-logo.png" alt="Company Logo">
      
      <h2>WORK ORDER</h2>
    </div>
    <div class="address">
        <input type="text" name="company_name" value="<?php echo htmlspecialchars($workOrders['company_name']); ?>">
        <input type="text" name="street" placeholder="Street Name" value='sdasdasd'>
        <input type="text" name="city" placeholder="City">
        <input type="number" name="zip" placeholder="Zip Code">
        <label for="phone">Phone: </label >
        <input type="number" name="phone" placeholder="[096-204-9172]"> 
        <label for="fax">Fax: </label>
        <input type="number" name="fax" placeholder="[000-000-0000]"> 
        <input type="email" name="email" placeholder="Web Address">
        
    </div>
    <div class="workorder-info">
      <h2>Work Order</h2>
      <h2>W.O. #</h2>
      <h2>Date</h2>
    </div>
    


    <div class="info">
      <div class="section">
        <h3>JOB</h3>
        <textarea name="job_description" rows="4" cols="50"></textarea>
      </div>
      <div class="section">
        <h3>BILL TO</h3>
        <input type="text" name="bill_name" placeholder="Name"><br>
        <input type="text" name="bill_company" placeholder="Company Name"><br>
        <input type="text" name="bill_address" placeholder="Street Address"><br>
        <input type="text" name="bill_city" placeholder="City, ST ZIP"><br>
        <input type="text" name="bill_phone" placeholder="Phone">
      </div>
      <div class="section">
        <h3>SHIP TO (if different)</h3>
        <input type="text" name="ship_name" placeholder="Name"><br>
        <input type="text" name="ship_company" placeholder="Company Name"><br>
        <input type="text" name="ship_address" placeholder="Street Address"><br>
        <input type="text" name="ship_city" placeholder="City, ST ZIP"><br>
        <input type="text" name="ship_phone" placeholder="Phone">
      </div>
    </div>
    <table>
      <thead>
        <tr>
          <th>QTY</th>
          <th>DESCRIPTION</th>
          <th>TAXED</th>
          <th>UNIT PRICE</th>
          <th>LINE TOTAL</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><input type="number" name="qty[]" value="15" onchange="calculate()"></td>
          <td><input type="text" name="description[]" value="Part XYZ"></td>
          <td><input type="checkbox" name="taxed[]" value="1" checked onchange="calculate()"></td>
          <td><input type="number" name="unit_price[]" value="150.00" onchange="calculate()"></td>
          <td class="line-total">2250.00</td>
        </tr>
        
      </tbody>
    </table>
    <button type="button" onclick="addRow()">Add Item</button>
    <div class="comments">
      <h3>Other Comments or Special Instructions</h3>
      <ol>
        <li>Total payment due 30 days after completion of work</li>
        <li>Please refer to the W.O. # in all your correspondence</li>
        <li>Please send correspondence regarding this work order to: <input type="text" name="contact_info" placeholder="Name, Phone #, Email"></li>
      </ol>
    </div>
    <div class="totals">
      <p>
        SUBTOTAL: $ <span id="subtotal">2500.00</span>
      </p>
      <p>
        TAXABLE: <span id="taxable">2250.00</span>
      </p>
      <p>
        TAX RATE: 12%
      </p>
      <p>
        TAX: $ <span id="tax">154.69</span>
      </p>
      <p>
        S&H: $ <input type="number" name="shipping_handling" onchange="calculate()">
      </p>
      <p>
        OTHER: $ <input type="number" name="other_charges" onchange="calculate()">
      </p>
      <p>
        TOTAL: $ <span id="total">2654.69</span>
      </p>
      <p>
        Make checks payable to [Enter Company Name]
      </p>
    </div>
    <div class="signature">
      <p>
        I agree that all work has been performed to my satisfaction.
      </p>
      <p>
        Completed Date: <input type="date" name="completed_date">
      </p>
      <p>
        Signature: <input type="text" name="signature">
      </p>
      <p>
        Date: <input type="date" name="date">
      </p>
    </div>
    <p>
      <em>Thank You For Your Business!</em>
    </p>
    <button type="submit" name="submit" value="submit" onclick="submitForm()">Submit</button>
  </div>
</form>

  <script>
    function calculate() {
      //Change tax rate here.
      const taxRate = 0.12; //example, change to 0.07 for 7% etc.

      let rows = document.querySelectorAll("table tbody tr");
      let subtotal = 0;
      let taxable = 0;

      rows.forEach(row => {
        let qty = parseFloat(row.querySelector("input[name='qty[]']").value) || 0;
        let unitPrice = parseFloat(row.querySelector("input[name='unit_price[]']").value) || 0;
        let taxed = row.querySelector("input[name='taxed[]']").checked;
        let lineTotal = qty * unitPrice;
        row.querySelector(".line-total").textContent = lineTotal.toFixed(2);
        subtotal += lineTotal;
        if (taxed) {
          taxable += lineTotal;
        }
      });

      let tax = taxable * taxRate;
      let shipping = parseFloat(document.querySelector("input[name='shipping_handling']").value) || 0;
      let other = parseFloat(document.querySelector("input[name='other_charges']").value) || 0;
      let total = subtotal + tax + shipping + other;

      document.getElementById("subtotal").textContent = subtotal.toFixed(2);
      document.getElementById("taxable").textContent = taxable.toFixed(2);
      document.getElementById("tax").textContent = tax.toFixed(2);
      document.getElementById("total").textContent = total.toFixed(2);
    }

    function submitForm() {
      alert("Form submitted successfully!");
      // You can add further actions here, like sending the data to a server.
    }

    calculate(); // Initial calculation on page load

    function addRow() {
  const tableBody = document.querySelector("table tbody");
  const newRow = document.createElement("tr");

  newRow.innerHTML = `
    <td><input type="number" name="qty[]" value="0" onchange="calculate()"></td>
    <td><input type="text" name="description[]" value=""></td>
    <td><input type="checkbox" name="taxed[]" value="1" onchange="calculate()"></td>
    <td><input type="number" name="unit_price[]" value="0.00" onchange="calculate()"></td>
    <td class="line-total">0.00</td>
    <td><button type="button" onclick="deleteRow(this)">🗑️</button></td>
  `;

  tableBody.appendChild(newRow);
  calculate();
}

function deleteRow(button) {
  const row = button.closest("tr");
  row.remove();
  calculate(); // recalculate totals after row deletion
}


  </script>

</body>
</html>