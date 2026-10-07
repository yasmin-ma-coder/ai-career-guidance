<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['student_id'])) {
    header("Location: ../login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

$query = mysqli_query($conn, "
    SELECT appointments.*, users.name AS mentor_name
    FROM appointments
    JOIN users ON appointments.mentor_id = users.id
    WHERE appointments.student_id = '$student_id'
    ORDER BY appointments.appointment_date DESC, appointments.appointment_time DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>My Appointments</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5 mb-5">
<div class="card shadow p-4">

<h2 class="text-center mb-4">My Mentor Appointments</h2>

<?php if(mysqli_num_rows($query) > 0) { ?>

<table class="table table-bordered table-striped">
<tr>
<th>Mentor</th>
<th>Date</th>
<th>Time</th>
<th>Payment</th>
<th>Status</th>
<th>Video Session</th>
<th>Feedback</th>
</tr>

<?php while($row = mysqli_fetch_assoc($query)) { ?>
<tr>
<td><?php echo htmlspecialchars($row['mentor_name']); ?></td>
<td><?php echo htmlspecialchars($row['appointment_date']); ?></td>
<td><?php echo htmlspecialchars($row['appointment_time']); ?></td>

<td>
<?php if($row['payment_status'] == 'paid') { ?>
<span class="badge bg-success">Paid</span>
<?php } else { ?>
<span class="badge bg-warning text-dark">Pending</span>
<?php } ?>
</td>

<td>
<?php if($row['status'] == 'approved') { ?>
<span class="badge bg-success">Approved</span>
<?php } elseif($row['status'] == 'rejected') { ?>

<span class="badge bg-danger">Rejected</span>

<?php if($row['payment_status']=="paid" && $row['rebooked']=="no") { ?>

<br><br>

<a href="rebook_appointment.php?id=<?php echo $row['id']; ?>"
class="btn btn-warning btn-sm">
Rebook Appointment
</a>

<?php } ?>

<?php } else { ?>
<span class="badge bg-secondary">Pending</span>
<?php } ?>
</td>

<td>
<?php if($row['status'] == 'approved' && !empty($row['meeting_link'])) { ?>
<a href="<?php echo htmlspecialchars($row['meeting_link']); ?>" target="_blank" class="btn btn-primary btn-sm">
Join Meeting
</a>
<?php } else { ?>
<span class="text-muted">Available after approval</span>
<?php } ?>
</td>

<td>
<?php if($row['status'] == 'approved' && $row['payment_status'] == 'paid') { ?>
<a href="give_feedback.php?appointment_id=<?php echo $row['id']; ?>" class="btn btn-success btn-sm">
Give Mentor Feedback
</a>
<?php } else { ?>
<span class="badge bg-secondary">Not Available</span>
<?php } ?>
</td>

</tr>
<?php } ?>

</table>

<?php } else { ?>

<div class="alert alert-warning text-center">No appointments found.</div>

<?php } ?>

<div class="text-center mt-3">
<a href="mentor_booking.php" class="btn btn-warning">Book New Appointment</a>
<a href="dashboard.php" class="btn btn-primary">Back to Dashboard</a>
</div>

</div>
</div>

</body>
</html>