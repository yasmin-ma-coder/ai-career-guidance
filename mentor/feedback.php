<?php
session_start();
include("../includes/db.php");

if(!isset($_SESSION['role']) || $_SESSION['role'] != "mentor"){
    header("Location: ../login.php");
    exit();
}

$mentor_id = $_SESSION['user_id'];

if(!isset($_GET['appointment_id'])){
    die("Appointment ID missing");
}

$appointment_id = mysqli_real_escape_string($conn, $_GET['appointment_id']);

$app = mysqli_query($conn,"
SELECT a.*, s.name AS student_name
FROM appointments a
JOIN users s ON a.student_id = s.id
WHERE a.id='$appointment_id' AND a.mentor_id='$mentor_id'
");

$row = mysqli_fetch_assoc($app);

if(!$row){
    die("Appointment not found");
}

if(isset($_POST['submit'])){
    $student_id = $row['student_id'];

    $recommended_career = mysqli_real_escape_string($conn,$_POST['recommended_career']);
    $student_strengths = mysqli_real_escape_string($conn,$_POST['student_strengths']);
    $improvement_areas = mysqli_real_escape_string($conn,$_POST['improvement_areas']);
    $recommended_skills = mysqli_real_escape_string($conn,$_POST['recommended_skills']);
    $suggested_certifications = mysqli_real_escape_string($conn,$_POST['suggested_certifications']);
    $mentor_remarks = mysqli_real_escape_string($conn,$_POST['mentor_remarks']);

    $check = mysqli_query($conn,"SELECT * FROM session_feedback WHERE appointment_id='$appointment_id'");

    if(mysqli_num_rows($check) > 0){
        mysqli_query($conn,"
        UPDATE session_feedback SET
            recommended_career='$recommended_career',
            student_strengths='$student_strengths',
            improvement_areas='$improvement_areas',
            recommended_skills='$recommended_skills',
            suggested_certifications='$suggested_certifications',
            mentor_remarks='$mentor_remarks'
        WHERE appointment_id='$appointment_id'
        ");
    } else {
        mysqli_query($conn,"
        INSERT INTO session_feedback
        (appointment_id, student_id, mentor_id, recommended_career, student_strengths, improvement_areas, recommended_skills, suggested_certifications, mentor_remarks)
        VALUES
        ('$appointment_id','$student_id','$mentor_id','$recommended_career','$student_strengths','$improvement_areas','$recommended_skills','$suggested_certifications','$mentor_remarks')
        ");
    }

    echo "<script>alert('Career guidance feedback submitted successfully'); window.location='appointments.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Student Career Feedback</title>
<style>
body{
    margin:0;
    font-family:Arial, sans-serif;
    background:#f4f6f9;
}
.container{
    width:700px;
    margin:40px auto;
    background:white;
    padding:30px;
    border-radius:14px;
    box-shadow:0 8px 25px rgba(0,0,0,0.12);
}
h2{
    text-align:center;
    color:#333;
}
.info{
    background:#eef2ff;
    padding:15px;
    border-radius:10px;
    margin-bottom:20px;
    line-height:1.7;
}
label{
    font-weight:bold;
    display:block;
    margin-top:15px;
}
input, textarea{
    width:100%;
    padding:12px;
    margin-top:8px;
    border:1px solid #ccc;
    border-radius:8px;
    box-sizing:border-box;
}
textarea{
    height:90px;
    resize:vertical;
}
button{
    width:100%;
    margin-top:22px;
    padding:14px;
    background:#16a34a;
    color:white;
    border:none;
    border-radius:8px;
    font-size:16px;
    cursor:pointer;
}
button:hover{
    background:#15803d;
}
.back{
    display:block;
    text-align:center;
    margin-top:15px;
    text-decoration:none;
    color:#2563eb;
}
</style>
</head>
<body>

<div class="container">
<h2>Career Guidance Feedback</h2>

<div class="info">
    <b>Student:</b> <?php echo htmlspecialchars($row['student_name']); ?><br>
    <b>Appointment ID:</b> <?php echo htmlspecialchars($appointment_id); ?><br>
    <b>Date:</b> <?php echo htmlspecialchars($row['appointment_date']); ?><br>
    <b>Time:</b> <?php echo htmlspecialchars($row['appointment_time']); ?>
</div>

<form method="POST">

<label>Recommended Career Path</label>
<input type="text" name="recommended_career" placeholder="Example: Software Developer, Data Analyst, UI/UX Designer" required>

<label>Student Strengths</label>
<textarea name="student_strengths" placeholder="Mention student's strong areas..." required></textarea>

<label>Areas for Improvement</label>
<textarea name="improvement_areas" placeholder="Mention what the student should improve..." required></textarea>

<label>Recommended Skills</label>
<textarea name="recommended_skills" placeholder="Example: Python, SQL, Communication, Projects..." required></textarea>

<label>Suggested Certifications / Courses</label>
<textarea name="suggested_certifications" placeholder="Example: Python certification, Data Analytics course..." required></textarea>

<label>Final Mentor Remarks</label>
<textarea name="mentor_remarks" placeholder="Final career guidance remarks..." required></textarea>

<button type="submit" name="submit">Submit Career Feedback</button>

</form>

<a href="appointments.php" class="back">Back to Appointments</a>

</div>

</body>
</html>