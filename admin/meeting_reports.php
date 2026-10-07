<?php
session_start();
include("../includes/db.php");

if(!isset($_SESSION['role']) || $_SESSION['role'] != "admin")
{
    header("Location: ../login.php");
    exit();
}

$query = mysqli_query($conn, "
SELECT
    mr.*,
    su.name AS student_name,
    mu.name AS mentor_name,
    a.appointment_date,
    a.appointment_time,
    a.meeting_link
FROM meeting_reports mr
LEFT JOIN appointments a ON mr.appointment_id = a.id
LEFT JOIN users su ON a.student_id = su.id
LEFT JOIN users mu ON a.mentor_id = mu.id
ORDER BY mr.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Meeting Reports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5 mb-5">
<div class="card shadow p-4">

<h2 class="text-center mb-4">Meeting Reports</h2>

<?php if(mysqli_num_rows($query) > 0) { ?>

<table class="table table-bordered table-striped">
<tr>
    <th>ID</th>
    <th>Student</th>
    <th>Mentor</th>
    <th>Appointment Date</th>
    <th>Appointment Time</th>
    <th>Start Time</th>
    <th>End Time</th>
    <th>Duration</th>
    <th>Notes</th>
</tr>

<?php while($row = mysqli_fetch_assoc($query)) { ?>

<tr>
<td><?php echo htmlspecialchars($row['id']); ?></td>

<td>
<?php echo !empty($row['student_name']) ? htmlspecialchars($row['student_name']) : 'Unknown Student'; ?>
</td>

<td>
<?php echo !empty($row['mentor_name']) ? htmlspecialchars($row['mentor_name']) : 'Unknown Mentor'; ?>
</td>

<td><?php echo htmlspecialchars($row['appointment_date']); ?></td>

<td><?php echo htmlspecialchars($row['appointment_time']); ?></td>

<td><?php echo htmlspecialchars($row['start_time']); ?></td>

<td><?php echo htmlspecialchars($row['end_time']); ?></td>

<td><?php echo htmlspecialchars($row['duration_minutes']); ?> Minutes</td>

<td><?php echo htmlspecialchars($row['notes']); ?></td>
</tr>

<?php } ?>

</table>

<?php } else { ?>

<div class="alert alert-warning text-center">
No Meeting Reports Found
</div>

<?php } ?>

<div class="text-center">
<a href="dashboard.php" class="btn btn-primary">Back to Dashboard</a>
</div>

</div>
</div>

</body>
</html>