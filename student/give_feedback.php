<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['student_id'])) {
    header("Location: ../login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

if (!isset($_GET['appointment_id'])) {
    die("Appointment ID missing");
}

$appointment_id = $_GET['appointment_id'];

$app = mysqli_query($conn, "
    SELECT appointments.*, users.name AS mentor_name
    FROM appointments
    JOIN users ON appointments.mentor_id = users.id
    WHERE appointments.id = '$appointment_id'
    AND appointments.student_id = '$student_id'
");

$row = mysqli_fetch_assoc($app);

if (!$row) {
    die("Appointment not found");
}

if (isset($_POST['submit'])) {
    $rating = $_POST['rating'];
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);
    $mentor_id = $row['mentor_id'];

    $check = mysqli_query($conn, "
        SELECT * FROM session_feedback 
        WHERE appointment_id = '$appointment_id'
    ");

    if (mysqli_num_rows($check) > 0) {
        mysqli_query($conn, "
            UPDATE session_feedback 
            SET student_rating = '$rating',
                student_comment = '$comment'
            WHERE appointment_id = '$appointment_id'
        ");
    } else {
        mysqli_query($conn, "
            INSERT INTO session_feedback
            (appointment_id, student_id, mentor_id, student_rating, student_comment)
            VALUES
            ('$appointment_id', '$student_id', '$mentor_id', '$rating', '$comment')
        ");
    }

    echo "<script>alert('Feedback submitted successfully'); window.location='my_appointments.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Give Mentor Feedback</title>
<style>
body{
    margin:0;
    font-family:Arial;
    background:#f4f6f9;
}
.container{
    width:520px;
    margin:60px auto;
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
}
label{
    font-weight:bold;
    display:block;
    margin-top:15px;
}
select,textarea{
    width:100%;
    padding:12px;
    margin-top:8px;
    border:1px solid #ccc;
    border-radius:8px;
}
textarea{
    height:120px;
}
button{
    width:100%;
    margin-top:22px;
    padding:13px;
    background:#4f46e5;
    color:white;
    border:none;
    border-radius:8px;
    font-size:16px;
    cursor:pointer;
}
button:hover{
    background:#3730a3;
}
.back{
    display:block;
    text-align:center;
    margin-top:15px;
    text-decoration:none;
    color:#4f46e5;
}
</style>
</head>
<body>

<div class="container">
<h2>Give Mentor Feedback</h2>

<div class="info">
    <b>Mentor:</b> <?php echo htmlspecialchars($row['mentor_name']); ?><br>
    <b>Date:</b> <?php echo htmlspecialchars($row['appointment_date']); ?><br>
    <b>Time:</b> <?php echo htmlspecialchars($row['appointment_time']); ?>
</div>

<form method="POST">

<label>Rating</label>
<select name="rating" required>
    <option value="">Select Rating</option>
    <option value="5">★★★★★ Excellent</option>
    <option value="4">★★★★ Good</option>
    <option value="3">★★★ Average</option>
    <option value="2">★★ Poor</option>
    <option value="1">★ Very Poor</option>
</select>

<label>Comment</label>
<textarea name="comment" placeholder="Write your experience about the career guidance session..." required></textarea>

<button type="submit" name="submit">Submit Feedback</button>

</form>

<a href="my_appointments.php" class="back">Back to My Appointments</a>

</div>

</body>
</html>