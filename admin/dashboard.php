<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
    header("Location: ../login.php");
    exit();
}

$admin_name = $_SESSION['admin_name'] ?? $_SESSION['name'] ?? "Admin";

$total_users = 0;
$total_students = 0;
$total_mentors = 0;
$total_careers = 0;
$total_questions = 0;
$total_feedback = 0;

$q1 = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users");
if ($q1) {
    $row = mysqli_fetch_assoc($q1);
    $total_users = $row['total'];
}

$q2 = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE role='student'");
if ($q2) {
    $row = mysqli_fetch_assoc($q2);
    $total_students = $row['total'];
}

$q3 = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE role='mentor'");
if ($q3) {
    $row = mysqli_fetch_assoc($q3);
    $total_mentors = $row['total'];
}

$q4 = mysqli_query($conn, "SELECT COUNT(*) AS total FROM careers");
if ($q4) {
    $row = mysqli_fetch_assoc($q4);
    $total_careers = $row['total'];
}

$q5 = mysqli_query($conn, "SELECT COUNT(*) AS total FROM questions");
if ($q5) {
    $row = mysqli_fetch_assoc($q5);
    $total_questions = $row['total'];
}

$q6 = mysqli_query($conn, "SELECT COUNT(*) AS total FROM feedback");
if ($q6) {
    $row = mysqli_fetch_assoc($q6);
    $total_feedback = $row['total'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Intelligent Career Roadmap</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #f4f7fb;
            margin: 0;
            padding: 0;
        }

        .sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: linear-gradient(180deg, #212529, #343a40);
            color: white;
            padding: 30px 20px;
            box-shadow: 4px 0 20px rgba(0,0,0,0.08);
        }

        .sidebar h2 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 35px;
            text-align: center;
        }

        .sidebar a {
            display: block;
            color: rgba(255,255,255,0.92);
            text-decoration: none;
            padding: 12px 15px;
            border-radius: 12px;
            margin-bottom: 10px;
            transition: 0.3s;
            font-size: 15px;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: rgba(255,255,255,0.14);
            color: #fff;
            transform: translateX(4px);
        }

        .main-content {
            margin-left: 260px;
            padding: 30px;
        }

        .topbar {
            background: white;
            border-radius: 18px;
            padding: 25px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.06);
            margin-bottom: 25px;
        }

        .topbar h3 {
            font-weight: 700;
            color: #1b2a4e;
            margin-bottom: 8px;
        }

        .topbar p {
            color: #6c757d;
            margin-bottom: 0;
        }

        .dashboard-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.06);
            transition: 0.3s;
            background: white;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
        }

        .icon-box {
            width: 55px;
            height: 55px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }

        .bg-blue { background: #0d6efd; }
        .bg-green { background: #198754; }
        .bg-orange { background: #fd7e14; }
        .bg-purple { background: #6f42c1; }
        .bg-darkgray { background: #495057; }
        .bg-red { background: #dc3545; }

        .section-title {
            font-size: 22px;
            font-weight: 700;
            color: #1b2a4e;
            margin-bottom: 18px;
        }

        .quick-card {
            background: white;
            border-radius: 18px;
            padding: 22px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.06);
            height: 100%;
        }

        .quick-card h5 {
            font-weight: 600;
            color: #1b2a4e;
        }

        .quick-card p {
            color: #6c757d;
            font-size: 14px;
        }

        .btn-custom {
            border-radius: 12px;
            padding: 10px 18px;
            font-weight: 500;
        }

        @media (max-width: 991px) {
            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Admin Panel</h2>

    <a href="dashboard.php" class="active"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
    <a href="manage_users.php"><i class="bi bi-people me-2"></i> Manage Users</a>
    <a href="manage_careers.php"><i class="bi bi-briefcase me-2"></i> Manage Careers</a>
    <a href="meeting_reports.php"><i class="bi bi-file-earmark-text me-2"></i> Meeting Reports</a>
    <a href="manage_questions.php"><i class="bi bi-ui-checks-grid me-2"></i> Manage Questions</a>
    <a href="view_session_feedback.php"><i class="bi bi-file-earmark-text me-2"></i> View Session Feedback</a>
    <a href="../logout.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
</div>

<div class="main-content">

    <div class="topbar">
        <h3>Welcome, <?php echo htmlspecialchars($admin_name); ?> 👋</h3>
        <p>Manage the entire career guidance platform from one place.</p>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-6 col-lg-4">
            <div class="card dashboard-card p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 text-muted">Total Users</p>
                        <h4 class="fw-bold"><?php echo $total_users; ?></h4>
                    </div>
                    <div class="icon-box bg-blue">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card dashboard-card p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 text-muted">Students</p>
                        <h4 class="fw-bold"><?php echo $total_students; ?></h4>
                    </div>
                    <div class="icon-box bg-green">
                        <i class="bi bi-mortarboard-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card dashboard-card p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 text-muted">Mentors</p>
                        <h4 class="fw-bold"><?php echo $total_mentors; ?></h4>
                    </div>
                    <div class="icon-box bg-orange">
                        <i class="bi bi-person-workspace"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card dashboard-card p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 text-muted">Careers</p>
                        <h4 class="fw-bold"><?php echo $total_careers; ?></h4>
                    </div>
                    <div class="icon-box bg-purple">
                        <i class="bi bi-briefcase-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card dashboard-card p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 text-muted">Questions</p>
                        <h4 class="fw-bold"><?php echo $total_questions; ?></h4>
                    </div>
                    <div class="icon-box bg-darkgray">
                        <i class="bi bi-patch-question-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card dashboard-card p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 text-muted">Feedback</p>
                        <h4 class="fw-bold"><?php echo $total_feedback; ?></h4>
                    </div>
                    <div class="icon-box bg-red">
                        <i class="bi bi-chat-square-dots-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <h4 class="section-title">Quick Management</h4>
    <div class="row g-4">
        <div class="col-md-6 col-lg-3">
            <div class="quick-card">
                <h5>Manage Users</h5>
                <p>Add, update, or remove students, mentors, and admins.</p>
                <a href="manage_users.php" class="btn btn-outline-primary btn-custom">Open</a>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="quick-card">
                <h5>Manage Careers</h5>
                <p>Maintain the career list available for recommendation.</p>
                <a href="manage_careers.php" class="btn btn-outline-success btn-custom">Open</a>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="quick-card">
                <h5>Manage Questions</h5>
                <p>Control the assessment questions and category mapping.</p>
                <a href="manage_questions.php" class="btn btn-outline-warning btn-custom">Open</a>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="quick-card">
                <h5>Manage Feedback</h5>
                <p>Review mentor and student feedback records.</p>
                <a href="view_session_feedback.php" class="btn btn-outline-secondary btn-custom">Open</a>
            </div>
        </div>
    </div>

</div>

</body>
</html>