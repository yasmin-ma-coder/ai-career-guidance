<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['student_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = (int) $_SESSION['student_id'];

$query = mysqli_query($conn, "
    SELECT users.id, users.name, users.email, users.role,
           students.age, students.education, students.interests, students.skills
    FROM users
    LEFT JOIN students ON users.id = students.user_id
    WHERE users.id = $user_id AND users.role = 'student'
");

$student = null;
if ($query && mysqli_num_rows($query) > 0) {
    $student = mysqli_fetch_assoc($query);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | Intelligent Career Roadmap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Poppins', sans-serif; }
        body { background: #f4f7fb; }
        .profile-wrapper {
            max-width: 1000px;
            margin: 40px auto;
        }
        .profile-card {
            background: #fff;
            border-radius: 22px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }
        .profile-header {
            background: linear-gradient(135deg, #0d6efd, #084298);
            color: white;
            padding: 35px;
        }
        .profile-header h2 {
            font-weight: 700;
            margin-bottom: 8px;
        }
        .profile-body {
            padding: 30px;
        }
        .info-box {
            background: #f8f9fa;
            border-radius: 16px;
            padding: 18px;
            height: 100%;
            border: 1px solid #eef1f4;
        }
        .info-label {
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 6px;
        }
        .info-value {
            font-size: 17px;
            font-weight: 600;
            color: #1b2a4e;
            word-break: break-word;
        }
        .action-btn {
            border-radius: 12px;
            padding: 10px 18px;
            font-weight: 500;
        }
        .empty-box {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            padding: 40px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container profile-wrapper">
    <?php if ($student) { ?>
        <div class="profile-card">
            <div class="profile-header d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h2><i class="bi bi-person-circle me-2"></i>My Profile</h2>
                    <p class="mb-0">View your personal and academic details</p>
                </div>
                <div class="mt-3 mt-md-0">
                    <a href="dashboard.php" class="btn btn-light action-btn">
                        <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
                    </a>
                </div>
            </div>

            <div class="profile-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="info-box">
                            <div class="info-label">Full Name</div>
                            <div class="info-value"><?php echo htmlspecialchars($student['name'] ?? 'Not Available'); ?></div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-box">
                            <div class="info-label">Email Address</div>
                            <div class="info-value"><?php echo htmlspecialchars($student['email'] ?? 'Not Available'); ?></div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-box">
                            <div class="info-label">Role</div>
                            <div class="info-value"><?php echo htmlspecialchars($student['role'] ?? 'student'); ?></div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-box">
                            <div class="info-label">Age</div>
                            <div class="info-value"><?php echo htmlspecialchars($student['age'] ?? 'Not Available'); ?></div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-box">
                            <div class="info-label">Education</div>
                            <div class="info-value"><?php echo htmlspecialchars($student['education'] ?? 'Not Available'); ?></div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-box">
                            <div class="info-label">Interests</div>
                            <div class="info-value"><?php echo htmlspecialchars($student['interests'] ?? 'Not Available'); ?></div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="info-box">
                            <div class="info-label">Skills</div>
                            <div class="info-value"><?php echo htmlspecialchars($student['skills'] ?? 'Not Available'); ?></div>
                        </div>
                    </div>
                </div>

               <div class="mt-4 d-flex gap-2 flex-wrap">
    <a href="dashboard.php" class="btn btn-primary action-btn">
        <i class="bi bi-speedometer2 me-1"></i> Dashboard
    </a>
    <a href="edit_profile.php" class="btn btn-warning action-btn">
        <i class="bi bi-pencil-square me-1"></i> Edit Profile
    </a>
    <a href="../logout.php" class="btn btn-outline-danger action-btn">
        <i class="bi bi-box-arrow-right me-1"></i> Logout
    </a>
</div>
            </div>
        </div>
    <?php } else { ?>
        <div class="empty-box">
            <h3 class="fw-bold mb-3">Profile Details Not Found</h3>
            <p class="text-muted">Student record was not found in the database.</p>
            <a href="dashboard.php" class="btn btn-primary action-btn mt-2">Back to Dashboard</a>
        </div>
    <?php } ?>
</div>

</body>
</html>