<?php
session_start();
include("../includes/db.php");

if(!isset($_SESSION['role']) || $_SESSION['role'] != "admin")
{
    header("Location: ../login.php");
    exit();
}

/* delete feedback */
if(isset($_GET['delete']))
{
    $id = $_GET['delete'];
    mysqli_query($conn,"DELETE FROM feedback WHERE id='$id'");
    header("Location: manage_feedback.php");
}

$query="SELECT feedback.*, 
users.name AS student_name,
m.name AS mentor_name
FROM feedback
JOIN users ON feedback.student_id = users.id
JOIN users m ON feedback.mentor_id = m.id
ORDER BY feedback.created_at DESC";

$result=mysqli_query($conn,$query);
?>

<!DOCTYPE html>
<html>

<head>
<title>Manage Feedback</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<div class="container mt-5">

<div class="card shadow p-4">

<h2 class="text-center mb-4">Manage Feedback</h2>

<?php if(mysqli_num_rows($result)>0){ ?>

<table class="table table-bordered">

<tr>
<th>Student</th>
<th>Mentor</th>
<th>Feedback</th>
<th>Date</th>
<th>Action</th>
</tr>

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<tr>

<td><?php echo $row['student_name']; ?></td>

<td><?php echo $row['mentor_name']; ?></td>

<td><?php echo $row['feedback_text']; ?></td>

<td><?php echo $row['created_at']; ?></td>

<td>

<a href="manage_feedback.php?delete=<?php echo $row['id']; ?>" 
class="btn btn-danger btn-sm"
onclick="return confirm('Delete this feedback?')">

Delete

</a>

</td>

</tr>

<?php } ?>

</table>

<?php } else { ?>

<div class="alert alert-warning text-center">
No feedback available
</div>

<?php } ?>

<div class="text-center mt-3">
<a href="dashboard.php" class="btn btn-primary">Back to Dashboard</a>
</div>

</div>

</div>

</body>
</html>