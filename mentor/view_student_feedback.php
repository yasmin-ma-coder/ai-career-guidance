<?php
session_start();
include("../includes/db.php");

if(!isset($_SESSION['role']) || $_SESSION['role'] != "mentor"){
    header("Location: ../login.php");
    exit();
}

$mentor_id = $_SESSION['user_id'];

$query = mysqli_query($conn, "
    SELECT sf.*, u.name AS student_name, a.appointment_date, a.appointment_time
    FROM session_feedback sf
    JOIN users u ON sf.student_id = u.id
    JOIN appointments a ON sf.appointment_id = a.id
    WHERE sf.mentor_id = '$mentor_id'
    AND sf.student_rating IS NOT NULL
    ORDER BY sf.created_at DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Student Feedback</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">
<div class="card shadow p-4">

<h2 class="text-center mb-4">Student Feedback</h2>

<?php if(mysqli_num_rows($query) > 0) { ?>

<?php while($row = mysqli_fetch_assoc($query)) { ?>

<div class="card mb-3 p-3">
<h4 class="text-primary"><?php echo htmlspecialchars($row['student_name']); ?></h4>

<p><b>Date:</b> <?php echo htmlspecialchars($row['appointment_date']); ?></p>
<p><b>Time:</b> <?php echo htmlspecialchars($row['appointment_time']); ?></p>

<p><b>Rating:</b>
<?php echo str_repeat("★", $row['student_rating']); ?>
</p>

<p><b>Comment:</b><br>
<?php echo htmlspecialchars($row['student_comment']); ?>
</p>
</div>

<?php } ?>

<?php } else { ?>

<div class="alert alert-warning text-center">
No student feedback available yet.
</div>

<?php } ?>

<div class="text-center mt-4">
<a href="dashboard.php" class="btn btn-primary">Back to Dashboard</a>
</div>

</div>
</div>

</body>
</html>