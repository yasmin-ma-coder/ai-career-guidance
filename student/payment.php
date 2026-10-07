<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['student_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] != "POST") {
    header("Location: mentor_booking.php");
    exit();
}

$mentor_id = $_POST['mentor_id'];
$date = $_POST['appointment_date'];
$time = $_POST['appointment_time'];
$amount = 1000;

$mentor_query = mysqli_query($conn, "SELECT name FROM users WHERE id='$mentor_id'");
$mentor = mysqli_fetch_assoc($mentor_query);
$mentor_name = $mentor ? $mentor['name'] : "Selected Mentor";
?>

<!DOCTYPE html>
<html>
<head>
<title>Secure Payment</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
body{
    background:linear-gradient(135deg,#eef2ff,#f8fafc);
    font-family:'Segoe UI', Arial, sans-serif;
}
.payment-wrapper{
    max-width:1000px;
    margin:45px auto;
}
.payment-card{
    background:white;
    border-radius:24px;
    overflow:hidden;
    box-shadow:0 18px 45px rgba(15,23,42,0.12);
}
.left-panel{
    background:linear-gradient(135deg,#2563eb,#4f46e5);
    color:white;
    padding:35px;
    height:100%;
}
.secure-badge{
    background:rgba(255,255,255,0.18);
    display:inline-block;
    padding:8px 14px;
    border-radius:30px;
    font-size:14px;
    margin-bottom:25px;
}
.summary-item{
    display:flex;
    justify-content:space-between;
    border-bottom:1px solid rgba(255,255,255,0.2);
    padding:13px 0;
}
.amount-box{
    background:white;
    color:#111827;
    border-radius:16px;
    padding:20px;
    margin-top:25px;
}
.right-panel{
    padding:35px;
}
.method-box{
    border:1px solid #e5e7eb;
    border-radius:14px;
    padding:14px;
    margin-bottom:12px;
    cursor:pointer;
}
.method-box:hover{
    border-color:#2563eb;
    background:#f8fbff;
}
.form-control,.form-select{
    padding:12px;
    border-radius:10px;
}
.pay-btn{
    padding:14px;
    border-radius:12px;
    font-size:17px;
    font-weight:600;
}
.small-note{
    font-size:13px;
    color:#64748b;
}
</style>
</head>

<body>

<div class="container payment-wrapper">
<div class="payment-card">
<div class="row g-0">

<div class="col-md-5">
<div class="left-panel">

<div class="secure-badge">
<i class="bi bi-shield-lock-fill"></i> Secure Checkout
</div>

<h2 class="mb-2">Career Guidance Payment</h2>
<p class="mb-4">Complete your mentor booking securely.</p>

<div class="summary-item">
<span>Mentor</span>
<b><?php echo htmlspecialchars($mentor_name); ?></b>
</div>

<div class="summary-item">
<span>Session Date</span>
<b><?php echo htmlspecialchars($date); ?></b>
</div>

<div class="summary-item">
<span>Session Time</span>
<b><?php echo htmlspecialchars($time); ?></b>
</div>

<div class="summary-item">
<span>Session Type</span>
<b>Online Guidance</b>
</div>

<div class="amount-box">
<p class="mb-1 text-muted">Total Amount</p>
<h1 class="mb-0">₹<?php echo $amount; ?></h1>
</div>

<p class="mt-4 small">
<i class="bi bi-lock-fill"></i>
This is a demo payment page for your career guidance system.
</p>

</div>
</div>

<div class="col-md-7">
<div class="right-panel">

<h3 class="mb-1">Payment Details</h3>
<p class="small-note mb-4">Choose a payment method and confirm your booking.</p>

<form action="payment_success.php" method="POST">

<input type="hidden" name="mentor_id" value="<?php echo htmlspecialchars($mentor_id); ?>">
<input type="hidden" name="appointment_date" value="<?php echo htmlspecialchars($date); ?>">
<input type="hidden" name="appointment_time" value="<?php echo htmlspecialchars($time); ?>">
<input type="hidden" name="amount" value="<?php echo $amount; ?>">

<label class="form-label fw-bold">Payment Method</label>
<select name="payment_method" class="form-select mb-3" required>
    <option value="Card">Credit / Debit Card</option>
    <option value="UPI">UPI</option>
    <option value="Net Banking">Net Banking</option>
</select>

<div class="mb-3">
<label class="form-label">Card Holder Name</label>
<input type="text" name="card_holder" class="form-control" placeholder="Enter card holder name">
</div>

<div class="mb-3">
<label class="form-label">Card Number</label>
<input type="text" name="card_number" class="form-control" placeholder="1234 5678 9012 3456" maxlength="19">
</div>

<div class="row">
<div class="col-md-6 mb-3">
<label class="form-label">Expiry Date</label>
<input type="text" name="expiry" class="form-control" placeholder="MM/YY">
</div>

<div class="col-md-6 mb-3">
<label class="form-label">CVV</label>
<input type="password" name="cvv" class="form-control" placeholder="***" maxlength="3">
</div>
</div>

<div class="mb-3">
<label class="form-label">UPI ID / Transaction Reference</label>
<input type="text" name="upi_reference" class="form-control" placeholder="example@upi or transaction id">
</div>

<div class="alert alert-light border">
<i class="bi bi-info-circle-fill text-primary"></i>
After payment, your appointment will be created and marked as paid.
</div>

<button type="submit" class="btn btn-success w-100 pay-btn">
<i class="bi bi-credit-card"></i>
Pay ₹<?php echo $amount; ?> Securely
</button>

<a href="mentor_booking.php" class="btn btn-outline-secondary w-100 mt-3">
Cancel Payment
</a>

</form>

</div>
</div>

</div>
</div>
</div>

</body>
</html>