<?php
session_start();
include("../includes/db.php");

date_default_timezone_set("Asia/Kolkata");

if(!isset($_SESSION['role']) || $_SESSION['role'] != "mentor")
{
    header("Location: ../login.php");
    exit();
}

$mentor_id = $_SESSION['user_id'];

if(!isset($_GET['id']) || !isset($_GET['status']))
{
    header("Location: appointments.php");
    exit();
}

$id = mysqli_real_escape_string($conn, $_GET['id']);
$status = mysqli_real_escape_string($conn, $_GET['status']);

$result = mysqli_query($conn, "
    SELECT *
    FROM appointments
    WHERE id='$id' AND mentor_id='$mentor_id'
");

if(mysqli_num_rows($result) == 0)
{
    header("Location: appointments.php");
    exit();
}

$row = mysqli_fetch_assoc($result);

if($status == "started")
{
    mysqli_query($conn, "
        UPDATE appointments
        SET meeting_status='started',
            meeting_start=NOW(),
            meeting_end=NULL,
            meeting_duration=NULL
        WHERE id='$id' AND mentor_id='$mentor_id'
    ");
}

elseif($status == "completed")
{
    if(empty($row['meeting_start']))
    {
        header("Location: appointments.php");
        exit();
    }

    mysqli_query($conn, "
        UPDATE appointments
        SET meeting_status='completed',
            meeting_end=NOW(),
            meeting_duration=CONCAT(
                GREATEST(TIMESTAMPDIFF(MINUTE, meeting_start, NOW()), 1),
                ' Minutes'
            )
        WHERE id='$id' AND mentor_id='$mentor_id'
    ");

    $result2 = mysqli_query($conn, "
        SELECT *,
        GREATEST(TIMESTAMPDIFF(MINUTE, meeting_start, meeting_end), 1) AS total_minutes
        FROM appointments
        WHERE id='$id' AND mentor_id='$mentor_id'
    ");

    $row2 = mysqli_fetch_assoc($result2);

    $check_report = mysqli_query($conn, "
        SELECT id 
        FROM meeting_reports 
        WHERE appointment_id='$id'
    ");

    if(mysqli_num_rows($check_report) == 0)
    {
        mysqli_query($conn, "
            INSERT INTO meeting_reports
            (
                appointment_id,
                mentor_id,
                student_id,
                start_time,
                end_time,
                duration_minutes,
                notes,
                created_at
            )
            VALUES
            (
                '".$row2['id']."',
                '".$row2['mentor_id']."',
                '".$row2['student_id']."',
                '".$row2['meeting_start']."',
                '".$row2['meeting_end']."',
                '".$row2['total_minutes']."',
                'Career Guidance Session Completed',
                NOW()
            )
        ");
    }
}

header("Location: appointments.php");
exit();
?>