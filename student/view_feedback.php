<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['student_id'])) {
    header("Location: ../login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

$query = mysqli_query($conn, "
    SELECT sf.*, u.name AS mentor_name, a.appointment_date, a.appointment_time
    FROM session_feedback sf
    JOIN users u ON sf.mentor_id = u.id
    JOIN appointments a ON sf.appointment_id = a.id
    WHERE sf.student_id = '$student_id'
    AND sf.mentor_remarks IS NOT NULL
    AND sf.mentor_remarks != ''
    ORDER BY sf.created_at DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Mentor Feedback</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">
<div class="card shadow p-4">

<h2 class="text-center mb-4">Mentor Feedback</h2>

<?php if(mysqli_num_rows($query) > 0) { ?>

<?php while($row = mysqli_fetch_assoc($query)) { ?>

<div class="card mb-4 p-3">
<h4 class="text-primary"><?php echo htmlspecialchars($row['mentor_name']); ?></h4>

<p><b>Date:</b> <?php echo htmlspecialchars($row['appointment_date']); ?></p>
<p><b>Time:</b> <?php echo htmlspecialchars($row['appointment_time']); ?></p>

<hr>

<p><b>Recommended Career Path:</b><br>
<?php echo htmlspecialchars($row['recommended_career']); ?></p>

<p><b>Student Strengths:</b><br>
<?php echo htmlspecialchars($row['student_strengths']); ?></p>

<p><b>Areas for Improvement:</b><br>
<?php echo htmlspecialchars($row['improvement_areas']); ?></p>

<p><b>Recommended Skills:</b><br>
<?php echo htmlspecialchars($row['recommended_skills']); ?></p>

<p><b>Suggested Certifications:</b><br>
<?php echo htmlspecialchars($row['suggested_certifications']); ?></p>

<p><b>Mentor Remarks:</b><br>
<?php echo htmlspecialchars($row['mentor_remarks']); ?></p>
</div>

<?php } ?>

<?php } else { ?>

<div class="alert alert-warning text-center">
No feedback available yet.
</div>

<?php } ?>

<div class="text-center mt-4">
<a href="dashboard.php" class="btn btn-primary">Back to Dashboard</a>
</div>

</div>
</div>

</body>
</html>