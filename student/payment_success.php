<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['student_id'])) {
    header("Location: ../login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

if ($_SERVER['REQUEST_METHOD'] != "POST") {
    header("Location: mentor_booking.php");
    exit();
}

$mentor_id = $_POST['mentor_id'];
$date = $_POST['appointment_date'];
if($date < date("Y-m-d"))
{
    die("You cannot book an appointment for a past date.");
}
$time = $_POST['appointment_time'];
$amount = $_POST['amount'];
$payment_method = $_POST['payment_method'] ?? 'Online Payment';

$insert = mysqli_query($conn, "
    INSERT INTO appointments 
    (student_id, mentor_id, appointment_date, appointment_time, status, payment_status, amount)
    VALUES 
    ('$student_id', '$mentor_id', '$date', '$time', 'pending', 'paid', '$amount')
");

if (!$insert) {
    die("Appointment booking failed: " . mysqli_error($conn));
}

$appointment_id = mysqli_insert_id($conn);

$mentor_query = mysqli_query($conn, "SELECT name FROM users WHERE id='$mentor_id'");
$mentor = mysqli_fetch_assoc($mentor_query);
$mentor_name = $mentor ? $mentor['name'] : "Selected Mentor";

$transaction_id = "TXN" . date("YmdHis") . rand(100,999);
?>

<!DOCTYPE html>
<html>
<head>
<title>Payment Success</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
body{
    background:linear-gradient(135deg,#ecfdf5,#eef2ff);
    font-family:'Segoe UI', Arial, sans-serif;
}
.success-card{
    max-width:750px;
    margin:55px auto;
    background:white;
    border-radius:24px;
    box-shadow:0 18px 45px rgba(15,23,42,0.12);
    overflow:hidden;
}
.success-header{
    background:linear-gradient(135deg,#16a34a,#22c55e);
    color:white;
    text-align:center;
    padding:35px;
}
.success-icon{
    width:80px;
    height:80px;
    border-radius:50%;
    background:white;
    color:#16a34a;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:42px;
    margin:0 auto 15px;
}
.details{
    padding:35px;
}
.row-item{
    display:flex;
    justify-content:space-between;
    padding:13px 0;
    border-bottom:1px solid #e5e7eb;
}
.badge-paid{
    background:#dcfce7;
    color:#166534;
    padding:6px 12px;
    border-radius:20px;
    font-weight:600;
}
.note{
    background:#eff6ff;
    border-left:4px solid #2563eb;
    padding:15px;
    border-radius:10px;
    margin-top:25px;
}
</style>
</head>

<body>

<div class="success-card">

<div class="success-header">
    <div class="success-icon">
        <i class="bi bi-check-lg"></i>
    </div>
    <h2>Payment Successful</h2>
    <p class="mb-0">Your mentor appointment has been booked successfully.</p>
</div>

<div class="details">

<h4 class="mb-4">Booking Receipt</h4>

<div class="row-item">
    <span>Transaction ID</span>
    <b><?php echo $transaction_id; ?></b>
</div>

<div class="row-item">
    <span>Appointment ID</span>
    <b><?php echo $appointment_id; ?></b>
</div>

<div class="row-item">
    <span>Mentor</span>
    <b><?php echo htmlspecialchars($mentor_name); ?></b>
</div>

<div class="row-item">
    <span>Date</span>
    <b><?php echo htmlspecialchars($date); ?></b>
</div>

<div class="row-item">
    <span>Time</span>
    <b><?php echo htmlspecialchars($time); ?></b>
</div>

<div class="row-item">
    <span>Payment Method</span>
    <b><?php echo htmlspecialchars($payment_method); ?></b>
</div>

<div class="row-item">
    <span>Amount Paid</span>
    <b>₹<?php echo htmlspecialchars($amount); ?></b>
</div>

<div class="row-item">
    <span>Payment Status</span>
    <span class="badge-paid">Paid</span>
</div>

<div class="row-item">
    <span>Appointment Status</span>
    <b class="text-warning">Pending Mentor Approval</b>
</div>

<div class="note">
    <b>Next Step:</b> Your mentor will review and approve the appointment. Once approved, the meeting link will be available in My Appointments.
</div>

<div class="text-center mt-4">
    <a href="my_appointments.php" class="btn btn-success me-2">
        View My Appointments
    </a>
    <a href="dashboard.php" class="btn btn-outline-primary">
        Go to Dashboard
    </a>
</div>

</div>
</div>

</body>
</html>