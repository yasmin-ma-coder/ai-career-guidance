<?php
session_start();
include("../includes/db.php");

if(!isset($_SESSION['student_id']))
{
    header("Location: ../login.php");
    exit();
}

$student_id=$_SESSION['student_id'];

if(!isset($_GET['id']))
{
    die("Invalid Appointment");
}

$id=$_GET['id'];

$query=mysqli_query($conn,"
SELECT appointments.*,users.name AS mentor_name
FROM appointments
JOIN users ON appointments.mentor_id=users.id
WHERE appointments.id='$id'
AND appointments.student_id='$student_id'
AND appointments.status='rejected'
AND appointments.payment_status='paid'
AND appointments.rebooked='no'
");

$row=mysqli_fetch_assoc($query);

if(!$row)
{
    die("Appointment not available for rebooking.");
}

if(isset($_POST['submit']))
{

$date=$_POST['appointment_date'];
$time=$_POST['appointment_time'];

mysqli_query($conn,"
UPDATE appointments
SET appointment_date='$date',
appointment_time='$time',
status='pending',
meeting_link=NULL,
meeting_status='not_started',
meeting_start=NULL,
meeting_end=NULL,
meeting_duration=NULL,
rebooked='yes'
WHERE id='$id'
");

echo "<script>
alert('Appointment rebooked successfully.');
window.location='my_appointments.php';
</script>";

exit();

}
?>

<!DOCTYPE html>

<html>

<head>

<title>Rebook Appointment</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<div class="container mt-5">

<div class="card shadow p-4">

<h2 class="text-center">Rebook Appointment</h2>

<p>

<b>Mentor :</b>

<?php echo htmlspecialchars($row['mentor_name']); ?>

</p>

<form method="POST">

<div class="mb-3">

<label>New Date</label>

<input type="date"

name="appointment_date"

class="form-control"

min="<?php echo date('Y-m-d');?>"

required>

</div>

<div class="mb-3">

<label>New Time</label>

<input type="time"

name="appointment_time"

class="form-control"

required>

</div>

<button class="btn btn-success w-100"

name="submit">

Rebook

</button>

</form>

<br>

<a href="my_appointments.php"

class="btn btn-secondary w-100">

Cancel

</a>

</div>

</div>

</body>

</html>
