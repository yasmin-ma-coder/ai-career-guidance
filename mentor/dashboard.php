<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != "mentor") {
    header("Location: ../login.php");
    exit();
}

$mentor_name = $_SESSION['mentor_name'] ?? $_SESSION['name'] ?? "Mentor";
$mentor_id = $_SESSION['user_id'] ?? 0;

// Stats
$total = 0;
$pending = 0;
$approved = 0;

$q1 = mysqli_query($conn, "SELECT COUNT(*) AS total FROM appointments WHERE mentor_id='$mentor_id'");
if ($q1) $total = mysqli_fetch_assoc($q1)['total'];

$q2 = mysqli_query($conn, "SELECT COUNT(*) AS total FROM appointments WHERE mentor_id='$mentor_id' AND status='pending'");
if ($q2) $pending = mysqli_fetch_assoc($q2)['total'];

$q3 = mysqli_query($conn, "SELECT COUNT(*) AS total FROM appointments WHERE mentor_id='$mentor_id' AND status='approved'");
if ($q3) $approved = mysqli_fetch_assoc($q3)['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Mentor Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
*{font-family:'Poppins',sans-serif;}

body{background:#f4f7fb;}

.sidebar{
    width:260px;
    height:100vh;
    position:fixed;
    background:linear-gradient(180deg,#198754,#0f5132);
    color:white;
    padding:25px;
}

.sidebar h2{text-align:center;margin-bottom:30px;}

.sidebar a{
    display:block;
    color:white;
    padding:10px;
    border-radius:10px;
    margin-bottom:10px;
    text-decoration:none;
}

.sidebar a:hover,
.sidebar a.active{
    background:rgba(255,255,255,0.2);
}

.main{
    margin-left:260px;
    padding:30px;
}

.card-box{
    border-radius:18px;
    padding:20px;
    background:white;
    box-shadow:0 5px 15px rgba(0,0,0,0.08);
}

.icon{
    width:50px;height:50px;
    border-radius:10px;
    display:flex;
    align-items:center;
    justify-content:center;
    color:white;
}

.bg1{background:#198754;}
.bg2{background:#fd7e14;}
.bg3{background:#0d6efd;}

.quick-card{
    background:white;
    padding:20px;
    border-radius:15px;
    box-shadow:0 5px 15px rgba(0,0,0,0.08);
}
</style>

</head>

<body>

<!-- Sidebar -->
<div class="sidebar">
    <h2>Mentor Panel</h2>

    <a href="dashboard.php" class="active">Dashboard</a>
    <a href="appointments.php">Appointments</a>
    <a href="appointments.php">Give Feedback</a>
    <a href="view_student_feedback.php">View Student Feedback</a>
    <a href="add_resources.php">Add Career Resources</a>
    <a href="../logout.php">Logout</a>
</div>

<!-- Main -->
<div class="main">

    <!-- Welcome -->
    <div class="card-box mb-4">
        <h3>Welcome, <?php echo htmlspecialchars($mentor_name); ?> 👋</h3>
        <p class="text-muted">Manage student appointments and provide guidance.</p>
    </div>

    <!-- Stats -->
    <div class="row g-4 mb-4">

        <div class="col-md-4">
            <div class="card-box d-flex justify-content-between">
                <div>
                    <p>Total Appointments</p>
                    <h4><?php echo $total; ?></h4>
                </div>
                <div class="icon bg1">
                    <i class="bi bi-calendar-check"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-box d-flex justify-content-between">
                <div>
                    <p>Pending</p>
                    <h4><?php echo $pending; ?></h4>
                </div>
                <div class="icon bg2">
                    <i class="bi bi-hourglass-split"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-box d-flex justify-content-between">
                <div>
                    <p>Approved</p>
                    <h4><?php echo $approved; ?></h4>
                </div>
                <div class="icon bg3">
                    <i class="bi bi-patch-check"></i>
                </div>
            </div>
        </div>

    </div>

    <!-- Quick Actions -->
    <h4 class="mb-3">Quick Actions</h4>

    <div class="row g-4">

        <div class="col-md-6">
            <div class="quick-card">
                <h5>View Appointments</h5>
                <p>Check and manage student bookings</p>
                <a href="appointments.php" class="btn btn-success">Open</a>
            </div>
        </div>
        

        <div class="col-md-6">
            <div class="quick-card">
                <h5>Give Feedback</h5>
                <p>Provide guidance and evaluation</p>
                <a href="appointments.php" class="btn btn-primary">Open</a>
            </div>
        </div>

    </div>

</div>

</body>
</html>