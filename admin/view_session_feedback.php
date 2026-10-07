<?php
session_start();
include("../includes/db.php");

if(!isset($_SESSION['role']) || $_SESSION['role'] != "admin"){
    header("Location: ../login.php");
    exit();
}

$query = mysqli_query($conn,"
SELECT sf.*, s.name AS student_name, m.name AS mentor_name
FROM session_feedback sf
JOIN users s ON sf.student_id = s.id
JOIN users m ON sf.mentor_id = m.id
ORDER BY sf.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Session Feedback Reports</title>
<style>
body{
    font-family:Arial;
    background:#f4f6f9;
    margin:0;
}
.container{
    width:95%;
    margin:30px auto;
}
.card{
    background:white;
    padding:25px;
    margin-bottom:25px;
    border-radius:14px;
    box-shadow:0 6px 18px rgba(0,0,0,0.1);
}
h2{text-align:center;}
h3{
    color:#4f46e5;
    border-bottom:1px solid #ddd;
    padding-bottom:8px;
}
p{
    line-height:1.6;
}
.badge{
    background:#eef2ff;
    padding:6px 10px;
    border-radius:6px;
}
</style>
</head>
<body>

<div class="container">
<h2>Session Feedback Reports</h2>

<?php while($row = mysqli_fetch_assoc($query)){ ?>
<div class="card">

<h3>
Student: <?php echo $row['student_name']; ?> |
Mentor: <?php echo $row['mentor_name']; ?>
</h3>

<p><b>Appointment ID:</b> <?php echo $row['appointment_id']; ?></p>

<h3>Student → Mentor Feedback</h3>
<p><b>Rating:</b> 
<?php 
if($row['student_rating']){
    echo str_repeat("★", $row['student_rating']);
} else {
    echo "Not submitted";
}
?>
</p>

<p><b>Student Comment:</b><br>
<?php echo $row['student_comment'] ?: "Not submitted"; ?>
</p>

<h3>Mentor → Student Career Guidance Feedback</h3>

<p><b>Recommended Career Path:</b><br>
<?php echo $row['recommended_career'] ?: "Not submitted"; ?>
</p>

<p><b>Student Strengths:</b><br>
<?php echo $row['student_strengths'] ?: "Not submitted"; ?>
</p>

<p><b>Areas for Improvement:</b><br>
<?php echo $row['improvement_areas'] ?: "Not submitted"; ?>
</p>

<p><b>Recommended Skills:</b><br>
<?php echo $row['recommended_skills'] ?: "Not submitted"; ?>
</p>

<p><b>Suggested Certifications:</b><br>
<?php echo $row['suggested_certifications'] ?: "Not submitted"; ?>
</p>

<p><b>Mentor Remarks:</b><br>
<?php echo $row['mentor_remarks'] ?: "Not submitted"; ?>
</p>

</div>
<?php } ?>
<div style="text-align:center; margin:30px 0;">
    <a href="dashboard.php" 
       style="background:#2563eb; color:white; padding:12px 22px; border-radius:8px; text-decoration:none;">
        Back to Dashboard
    </a>
</div>

</div>

</body>
</html>