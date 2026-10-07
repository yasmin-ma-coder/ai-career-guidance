<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['student_id']) || !isset($_SESSION['student_role']) || $_SESSION['student_role'] != "student") {
    header("Location: ../login.php");
    exit();
}

$user_id = (int) $_SESSION['student_id'];
$student_name = $_SESSION['student_name'] ?? "Student";
$message = "";

// Get existing student details
$student_query = mysqli_query($conn, "SELECT * FROM students WHERE user_id = '$user_id'");
$student = mysqli_fetch_assoc($student_query);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $age = mysqli_real_escape_string($conn, $_POST['age']);
    $education = mysqli_real_escape_string($conn, $_POST['education']);
    $interests = mysqli_real_escape_string($conn, $_POST['interests']);
    $skills = mysqli_real_escape_string($conn, $_POST['skills']);

    if ($student) {
        $update = mysqli_query($conn, "
            UPDATE students 
            SET age='$age', education='$education', interests='$interests', skills='$skills'
            WHERE user_id='$user_id'
        ");

        if ($update) {
            $message = "Profile updated successfully.";
        } else {
            $message = "Update failed: " . mysqli_error($conn);
        }
    } else {
        $insert = mysqli_query($conn, "
            INSERT INTO students (user_id, age, education, interests, skills)
            VALUES ('$user_id', '$age', '$education', '$interests', '$skills')
        ");

        if ($insert) {
            $message = "Profile saved successfully.";
        } else {
            $message = "Save failed: " . mysqli_error($conn);
        }
    }

    $student_query = mysqli_query($conn, "SELECT * FROM students WHERE user_id = '$user_id'");
    $student = mysqli_fetch_assoc($student_query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile | Intelligent Career Roadmap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Poppins', sans-serif; }
        body { background: #f4f7fb; }
        .profile-box {
            max-width: 850px;
            margin: 40px auto;
            background: #fff;
            border-radius: 22px;
            padding: 35px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }
        .btn-custom {
            border-radius: 12px;
            padding: 10px 18px;
            font-weight: 500;
        }
        .form-control, .form-select, textarea {
            border-radius: 12px;
            padding: 12px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="profile-box">
        <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
            <div>
                <h2 class="fw-bold text-primary mb-2">Edit Profile</h2>
                <p class="text-muted mb-0">Complete your student profile, <?php echo htmlspecialchars($student_name); ?>.</p>
            </div>
            <a href="profile.php" class="btn btn-outline-secondary btn-custom mt-3 mt-md-0">
                <i class="bi bi-arrow-left me-1"></i> Back to Profile
            </a>
        </div>

        <?php if (!empty($message)) { ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php } ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label">Age</label>
                <input type="number" name="age" class="form-control"
                       value="<?php echo htmlspecialchars($student['age'] ?? ''); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Education</label>
                <input type="text" name="education" class="form-control"
                       value="<?php echo htmlspecialchars($student['education'] ?? ''); ?>" placeholder="e.g. MCA" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Interests</label>
                <textarea name="interests" class="form-control" rows="3" placeholder="e.g. AI, Web Development, Data Analysis" required><?php echo htmlspecialchars($student['interests'] ?? ''); ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Skills</label>
                <textarea name="skills" class="form-control" rows="3" placeholder="e.g. PHP, MySQL, HTML, CSS, JavaScript" required><?php echo htmlspecialchars($student['skills'] ?? ''); ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary btn-custom">
                <i class="bi bi-save me-1"></i> Save Profile
            </button>
        </form>
    </div>
</div>

</body>
</html>