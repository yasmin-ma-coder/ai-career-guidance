<?php
session_start();

// Example student name
$student_name = isset($_SESSION['student_name']) ? $_SESSION['student_name'] : "Student";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard | Intelligent Career Roadmap</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
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
            background: linear-gradient(180deg, #0d6efd, #084298);
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
            background: rgba(255,255,255,0.18);
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

        .section-title {
            font-size: 22px;
            font-weight: 700;
            color: #1b2a4e;
            margin-bottom: 18px;
        }

        .career-card, .roadmap-card, .mentor-card {
            background: white;
            border-radius: 18px;
            padding: 22px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.06);
            height: 100%;
        }

        .career-card h5,
        .roadmap-card h5,
        .mentor-card h5 {
            font-weight: 600;
            color: #1b2a4e;
        }

        .badge-custom {
            background: #e9f2ff;
            color: #0d6efd;
            padding: 8px 12px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 600;
        }

        .progress {
            height: 10px;
            border-radius: 10px;
        }

        .btn-custom {
            border-radius: 12px;
            padding: 10px 18px;
            font-weight: 500;
        }

        .roadmap-step {
            background: #f8f9fa;
            border-left: 4px solid #0d6efd;
            padding: 12px 15px;
            border-radius: 10px;
            margin-bottom: 12px;
        }

        .mentor-card p,
        .career-card p,
        .roadmap-card p {
            color: #6c757d;
            font-size: 14px;
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

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>CareerRoadmap</h2>

        <!--<a href="#" class="active"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>-->
        <a href="career_test.php"><i class="bi bi-ui-checks-grid me-2"></i> Career Test</a>
        <a href="result.php"><i class="bi bi-bar-chart-line me-2"></i> Recommendations</a>
        <a href="resume_check.php"><i class="bi bi-file-earmark-text me-2"></i> Resume Check</a>
        <a href="roadmap.php"><i class="bi bi-signpost-split me-2"></i> Skill Roadmap</a>
        <a href="job_comparison.php"><i class="bi bi-ui-checks-grid me-2"></i>Job Comparison</a>
        <a href="scholarships.php"><i class="bi bi-award me-2"></i> Scholarships</a>
        <a href="mentor_booking.php"><i class="bi bi-calendar-check me-2"></i> Mentor Booking</a>
        <a href="my_appointments.php"><i class="bi bi-calendar-event me-2"></i> My Appointments</a>
        <a href="resources.php"><i class="bi bi-journal-bookmark me-2"></i> Resources</a>
         <a href="my_appointments.php">
    <i class="bi bi-calendar-event me-2"></i> Give Mentor Feedback
</a>
        <a href="view_feedback.php"><i class="bi bi-chat-dots me-2"></i>View Feedback</a>
        <a href="profile.php"><i class="bi bi-person-circle me-2"></i> Profile</a>
        <a href="../logout.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">

        <!-- Top Welcome -->
        <div class="topbar d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h3>Welcome back, <?php echo htmlspecialchars($student_name); ?> 👋</h3>
                <p>Track your career journey, discover opportunities, and build your future with confidence.</p>
            </div>
            <a href="career_test.php" class="btn btn-primary btn-custom mt-3 mt-md-0">Take Career Test</a>
        </div>

        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-6 col-lg-3">
                <div class="card dashboard-card p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 text-muted">Career Matches</p>
                            <h4 class="fw-bold">12</h4>
                        </div>
                        <div class="icon-box bg-blue">
                            <i class="bi bi-briefcase"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card dashboard-card p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 text-muted">Roadmap Progress</p>
                            <h4 class="fw-bold">68%</h4>
                        </div>
                        <div class="icon-box bg-green">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card dashboard-card p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 text-muted">Skills Completed</p>
                            <h4 class="fw-bold">7</h4>
                        </div>
                        <div class="icon-box bg-orange">
                            <i class="bi bi-patch-check"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card dashboard-card p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 text-muted">Mentor Sessions</p>
                            <h4 class="fw-bold">3</h4>
                        </div>
                        <div class="icon-box bg-purple">
                            <i class="bi bi-people"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recommended Careers -->
        <h4 class="section-title">Recommended Careers</h4>
        <div class="row g-4 mb-4">
            <div class="col-lg-4">
                <div class="career-card">
                    <span class="badge-custom">Best Match</span>
                    <h5 class="mt-3">Software Developer</h5>
                    <p>Ideal for students who enjoy coding, logical thinking, and building applications.</p>
                    <p><strong>Skills:</strong> Python, Java, Web Development</p>
                    <p><strong>Growth:</strong> High</p>
                    <a href="result.php" class="btn btn-outline-primary btn-sm btn-custom">View Details</a>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="career-card">
                    <span class="badge-custom">Trending</span>
                    <h5 class="mt-3">Data Analyst</h5>
                    <p>Suitable for students interested in data, visualization, problem solving, and insights.</p>
                    <p><strong>Skills:</strong> Excel, SQL, Python, Power BI</p>
                    <p><strong>Growth:</strong> Very High</p>
                   <a href="result.php" class="btn btn-outline-primary btn-sm btn-custom">View Details</a>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="career-card">
                    <span class="badge-custom">Emerging</span>
                    <h5 class="mt-3">UI/UX Designer</h5>
                    <p>Great for creative students who enjoy designing user-friendly and attractive interfaces.</p>
                    <p><strong>Skills:</strong> Figma, Design Principles, Prototyping</p>
                    <p><strong>Growth:</strong> High</p>
                   <a href="result.php" class="btn btn-outline-primary btn-sm btn-custom">View Details</a>
                </div>
            </div>
        </div>

        <!-- Roadmap + Mentor -->
        <div class="row g-4">
            <div class="col-lg-7">
                <h4 class="section-title">Your Skill Roadmap</h4>
                <div class="roadmap-card">
                    <h5>Software Developer Path</h5>
                    <p class="mb-3">Your personalized journey to become a successful software developer.</p>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Overall Progress</span>
                            <span>68%</span>
                        </div>
                        <div class="progress mt-2">
                            <div class="progress-bar bg-primary" style="width: 68%;"></div>
                        </div>
                    </div>

                    <div class="roadmap-step">Step 1: Learn Programming Fundamentals</div>
                    <div class="roadmap-step">Step 2: Practice Data Structures and Algorithms</div>
                    <div class="roadmap-step">Step 3: Learn Web Development / App Development</div>
                    <div class="roadmap-step">Step 4: Build Real Projects</div>
                    <div class="roadmap-step">Step 5: Prepare Resume and Placement Skills</div>

                    <a href="roadmap.php" class="btn btn-primary btn-custom mt-2">View Full Roadmap</a>
                </div>
            </div>

            <div class="col-lg-5">
                <h4 class="section-title">Mentor Support</h4>
                <div class="mentor-card">
                    <h5>Book a Guidance Session</h5>
                    <p>Connect with a mentor for personalized advice on career selection, roadmap planning, and skill development.</p>

                    <div class="mb-3">
                        <strong>Available Mentor:</strong>
                        <p class="mb-1 mt-2">Dr. Anjali Menon</p>
                        <small class="text-muted">Career Advisor & Academic Expert</small>
                    </div>

                    <div class="mb-3">
                        <strong>Next Slot:</strong>
                        <p class="mb-0 mt-2">Monday, 10:30 AM</p>
                    </div>

                    <a href="mentor_booking.php" class="btn btn-success btn-custom">Book Now</a>
                </div>
            </div>
        </div>

    </div>

</body>
</html>