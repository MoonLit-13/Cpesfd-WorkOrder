<!DOCTYPE html>
<html>
<head>
  <title>Work Order Form</title>
  <style>
  body {
    display: flex;
    justify-content: center;
    align-items: center;
    font-family: sans-serif;
    padding: 20px;
    margin: 30px;
    background: linear-gradient(140deg, skyblue, white); 
}
.container1 {
    max-width: 1000px;
    width: 800px;
    margin: 0 auto;
    border: 1px solid #ccc;
    padding: 25px 30px;
    border-radius: 5px;
    background: white;
}
/* header title */ 
.header {
  display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}
.header img {
    max-height: 80px;
}
.header h2 {
    margin: 0;
}

/* information form */
.container1 .address {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
  margin: 20px 0;
  margin-top: 20px 0;
}
.address .workorder-info {
  margin: 5px 0;
  
}
.info {
    display: flex;
    flex: wrap;
    justify-content: space-between;
    margin-bottom: 20px;
    
}
.section {
    width: 48%;
}
/*.span1{
  display: flex;
  width: 100px;
  height: 100px;
  padding: 5px;
  border: 1px solid #ccc;  
  border-radius: 1px;
  background-color: skyblue;
}*/
.section h3 {
    margin-top: 0;
}
table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}
th, td {
    border: 1px solid #ccc;
    padding: 5px;
    text-align: left;
}
th {
    background-color: #f2f2f2;
}
/* comments */
.other-comments {
  font-size: 20px;
  font-weight: 300;
}
.comments{
  font-size: 15px;
  display: flex;
  width: 100%;
  justify-content: space-between;
  margin: 10px 0;
}
.comments .totals {
  font-size: 15px;
  margin: 10px 0;
}
.comments .totals br{
margin: 5px 0;
}

.signature{
  margin: 5px 0
}
.header-signature{
  font-size: 15px;
  font-weight: 300;
}

.signature .signature-end {
  display: flex;
  width: 100%;
  justify-content: space-between;
  font-size: 15px;
    margin-top: 20px;
}
        </style>
  <link rel="stylesheet" href="styles.css">
</head>
<body>

<form action="dbinsert.php" method="post">
  
  <div class="container1" >
    <div class="header">
      <img src="your-logo.png" alt="Company Logo">
      
      <h1 style="color:skyblue;">WORK ORDER</h1>
    </div>
    
    <div class="address">

    <div>  
        <input type="text" name="company_name" placeholder="Company Name"> <br>
        <input type="text" name="street" placeholder="Stree Name"> <br>
        <input type="text" name="city" placeholder="City"> <br>
        <input type="number" name="zip" placeholder="Zip Code"> <br>
        <label for="phone">Phone: <input type="number" name="phone" placeholder="[096-204-9172]"> </label > <br>
        <label for="fax">Fax: <input type="number" name="fax" placeholder="[000-000-0000]"> </label > <br>
        <input type="email" name="email" placeholder="Web Address"> <br>
    </div>
    
    <div class="workorder-info">

        <p><label for="Work Order">Work Order: </label> </p>
        <input type="number" name="W.O. #" placeholder="00-000"> </label > <br>
        <p><label for="Date:">Date: </label> </p>
        <input type="Date" name="Date" placeholder="Complete Date"> </label > <br>

    </div> 

     </div> 
  
    <div class="info">
      <div class="section">
        <h3><span class="span1">JOB</span></h3>
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

  
    <div class="other-comments">
      <h3>Other Comments or Special Instructions</h3>
</div>
      <div class="comments">
  <div>
      <ol>
        <li>Total payment due 30 days after completion of work</li>
        <li>Please refer to the W.O. # in all your correspondence</li>
        <li>Please send correspondence regarding this work order to: <br>
         <input type="text" name="contact_info" placeholder="Name, Phone #, Email"></li>
      </ol>
  </div>
    <div class="totals">
   
        SUBTOTAL: $ <span id="subtotal">2500.00</span> <br>
  
        TAXABLE: <span id="taxable">2250.00</span><br>
      
        TAX RATE: 12% <br>
     
        TAX: $ <span id="tax">154.69</span><br>
    
        S&H: $ <input type="number" name="shipping_handling" onchange="calculate()"> <br>
   
        OTHER: $ <input type="number" name="other_charges" onchange="calculate()"> <br>
 
        TOTAL: $ <span id="total">2654.69</span> <br>
  
        Make checks payable to [Enter Company Name] <br>
   
    </div>

</div>

<div class="signature">
    <div class="header-signature">
        <h4>I agree that all work has been performed to my satisfaction.<h4>
    </div>
<div class="signature-end">
      
        Completed Date: <input type="date" name="completed_date">

        Signature: <input type="text" name="signature">

        Date: <input type="date" name="date">

      
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