<?php
session_start();
include("../includes/db.php");

if(!isset($_SESSION['role']) || $_SESSION['role'] != "mentor")
{
    header("Location: ../login.php");
    exit();
}

$mentor_id = $_SESSION['user_id'];

if(isset($_GET['action']) && isset($_GET['id']))
{
    $appointment_id = mysqli_real_escape_string($conn, $_GET['id']);
    $action = mysqli_real_escape_string($conn, $_GET['action']);

    if($action == "approve")
    {
        $meeting_link = "https://meet.jit.si/ai_career_guidance_appointment_" . $appointment_id;

        mysqli_query($conn, "
            UPDATE appointments 
            SET status='approved', meeting_link='$meeting_link'
            WHERE id='$appointment_id' AND mentor_id='$mentor_id'
        ");
    }
    elseif($action == "reject")
    {
        mysqli_query($conn, "
    UPDATE appointments
    SET status='rejected'
    WHERE id='$appointment_id'
    AND mentor_id='$mentor_id'
");
    }

    header("Location: appointments.php");
    exit();
}

$query = "
SELECT appointments.*, users.name AS student_name
FROM appointments
JOIN users ON appointments.student_id = users.id
WHERE appointments.mentor_id = '$mentor_id'
ORDER BY appointments.appointment_date ASC, appointments.appointment_time ASC
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Mentor Appointments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5 mb-5">
<div class="card shadow p-4">

<h2 class="text-center mb-4">Student Appointments</h2>

<?php if(mysqli_num_rows($result) > 0) { ?>

<table class="table table-bordered table-striped">
<tr>
    <th>Student</th>
    <th>Date</th>
    <th>Time</th>
    <th>Payment</th>
    <th>Status</th>
    <th>Meeting</th>
    <th>Duration</th>
    <th>Action</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)) { ?>

<?php
$meeting_status = !empty($row['meeting_status']) ? $row['meeting_status'] : 'not_started';
?>

<tr>
<td><?php echo htmlspecialchars($row['student_name']); ?></td>
<td><?php echo htmlspecialchars($row['appointment_date']); ?></td>
<td><?php echo htmlspecialchars($row['appointment_time']); ?></td>

<td>
<?php if($row['payment_status'] == "paid") { ?>
<span class="badge bg-success">Paid</span>
<?php } else { ?>
<span class="badge bg-warning text-dark">Pending</span>
<?php } ?>
</td>

<td>
<?php if($row['status'] == "approved") { ?>
<span class="badge bg-success">Approved</span>
<?php } elseif($row['status'] == "rejected") { ?>
<span class="badge bg-danger">Rejected</span>
<?php } else { ?>
<span class="badge bg-secondary">Pending</span>
<?php } ?>
</td>

<td>
<?php if($row['status'] == "approved" && !empty($row['meeting_link'])) { ?>

<a href="<?php echo htmlspecialchars($row['meeting_link']); ?>" target="_blank" class="btn btn-primary btn-sm">
Join
</a>

<br><br>

<span class="badge bg-info text-dark">
<?php echo ucfirst(str_replace("_", " ", $meeting_status)); ?>
</span>

<br><br>

<?php if($meeting_status == "not_started") { ?>

<a href="update_meeting.php?id=<?php echo $row['id']; ?>&status=started" class="btn btn-success btn-sm">
Start
</a>

<?php } elseif($meeting_status == "started") { ?>

<a href="update_meeting.php?id=<?php echo $row['id']; ?>&status=completed" class="btn btn-danger btn-sm">
End
</a>

<?php } ?>

<?php } else { ?>

<span class="text-muted">Not Available</span>

<?php } ?>
</td>

<td>
<?php echo !empty($row['meeting_duration']) ? htmlspecialchars($row['meeting_duration']) : "-"; ?>
</td>

<td>
<?php if($row['status'] == "pending") { ?>

<a href="appointments.php?action=approve&id=<?php echo $row['id']; ?>" class="btn btn-success btn-sm mb-1">
Approve
</a>

<a href="appointments.php?action=reject&id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm mb-1">
Reject
</a>

<?php } elseif($row['status'] == "approved") { ?>

<a href="feedback.php?appointment_id=<?php echo $row['id']; ?>" class="btn btn-info btn-sm">
Give Feedback
</a>

<?php } else { ?>

<span class="text-muted">No Action</span>

<?php } ?>
</td>
</tr>

<?php } ?>
</table>

<?php } else { ?>

<div class="alert alert-warning text-center">No appointments found.</div>

<?php } ?>

<div class="text-center mt-3">
<a href="dashboard.php" class="btn btn-primary">Back to Dashboard</a>
</div>

</div>
</div>

</body>
</html>