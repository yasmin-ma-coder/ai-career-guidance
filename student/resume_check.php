<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['student_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['student_id'];

$career_name = "";
$required_skills_text = "";
$matched_skills = [];
$missing_skills = [];
$message = "";
$resume_score = 0;

function h($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/* Get top recommended career */
$rec_query = mysqli_query($conn, "
    SELECT recommended_career 
    FROM recommendations 
    WHERE user_id='$user_id' 
    ORDER BY confidence_score DESC 
    LIMIT 1
");

if ($rec_row = mysqli_fetch_assoc($rec_query)) {
    $career_name = trim($rec_row['recommended_career']);
}

/* Get required skills from new CSV dataset */
if ($career_name != "") {
    $csv_path = realpath(__DIR__ . "/../python_ai/college_dataset.csv");

    if ($csv_path && file_exists($csv_path)) {
        $file = fopen($csv_path, "r");

        if ($file !== false) {
            $headers = fgetcsv($file);

            if ($headers !== false) {
                $headers = array_map('strtolower', array_map('trim', $headers));

                $career_index = array_search("career", $headers);
                $skills_index = array_search("required_skills", $headers);

                $skill_list = [];

                while (($row = fgetcsv($file)) !== false) {
                    if (isset($row[$career_index]) && isset($row[$skills_index])) {
                        if (strtolower(trim($row[$career_index])) == strtolower($career_name)) {
                            $skills = explode(",", $row[$skills_index]);

                            foreach ($skills as $skill) {
                                $skill = trim($skill);
                                if ($skill != "" && !in_array($skill, $skill_list)) {
                                    $skill_list[] = $skill;
                                }
                            }
                        }
                    }
                }

                if (!empty($skill_list)) {
                    $required_skills_text = implode(", ", $skill_list);
                }
            }

            fclose($file);
        }
    }
}

/* Fallback if CSV skills not found */
if ($required_skills_text == "" && $career_name != "") {
    $career_safe = mysqli_real_escape_string($conn, $career_name);

    $career_query = mysqli_query($conn, "
        SELECT required_skills 
        FROM careers 
        WHERE career_name='$career_safe'
    ");

    if ($career_row = mysqli_fetch_assoc($career_query)) {
        $required_skills_text = $career_row['required_skills'];
    }
}

/* Upload and check resume */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_FILES['resume_pdf']) && $_FILES['resume_pdf']['error'] == 0) {

        $file_name = $_FILES['resume_pdf']['name'];
        $tmp_name = $_FILES['resume_pdf']['tmp_name'];
        $extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if ($extension != "pdf") {
            $message = "Please upload only PDF file.";
        } else {
            $upload_dir = "uploads/";

            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $new_file_name = time() . "_" . basename($file_name);
            $upload_path = $upload_dir . $new_file_name;

            if (move_uploaded_file($tmp_name, $upload_path)) {

                $full_path = realpath($upload_path);
                $pythonDir = realpath(__DIR__ . "/../python_ai");
                $pythonFile = $pythonDir . DIRECTORY_SEPARATOR . "extract_resume_text.py";

                $command = 'cd /d ' . escapeshellarg($pythonDir) .
                    ' && set PYTHONIOENCODING=utf-8 && python ' .
                    escapeshellarg($pythonFile) . ' ' .
                    escapeshellarg($full_path) . ' 2>&1';

                $output = trim(shell_exec($command));
                $data = json_decode($output, true);

                $resume_text = strtolower($data['text'] ?? "");

                if ($resume_text == "") {
                    $message = "Could not read text from PDF. Please upload a text-based resume PDF.";
                } else {

                    $required_skills = array_map('trim', explode(',', $required_skills_text));
                    $total_skills = 0;

                    foreach ($required_skills as $skill) {
                        if ($skill != "") {
                            $total_skills++;

                            $skill_lower = strtolower(trim($skill));

                            if (strpos($resume_text, $skill_lower) !== false) {
                                $matched_skills[] = $skill;
                            } else {
                                $missing_skills[] = $skill;
                            }
                        }
                    }

                    if ($total_skills > 0) {
                        $resume_score = round((count($matched_skills) / $total_skills) * 100);
                    }
                }
            } else {
                $message = "Resume upload failed.";
            }
        }
    } else {
        $message = "Please select a PDF resume.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Resume Skill Check</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    background:#f4f7fb;
}
.main-box{
    max-width:1000px;
    margin:50px auto;
}
.card-box{
    background:white;
    padding:35px;
    border-radius:20px;
    box-shadow:0 10px 30px rgba(0,0,0,0.08);
}
.skill-badge{
    display:inline-block;
    background:#e7f1ff;
    color:#0d6efd;
    padding:6px 12px;
    border-radius:20px;
    margin:4px;
    font-size:14px;
}
.missing-badge{
    display:inline-block;
    background:#ffe7e7;
    color:#dc3545;
    padding:6px 12px;
    border-radius:20px;
    margin:4px;
    font-size:14px;
}
</style>
</head>

<body>

<div class="container main-box">
<div class="card-box">

<h2 class="text-center text-primary mb-4">Resume Skill Gap Analysis</h2>

<?php if($career_name == "") { ?>

<div class="alert alert-warning text-center">
Please take the career test first to get a recommendation.
</div>

<?php } else { ?>

<div class="alert alert-info">
<b>Recommended Career:</b> <?php echo h($career_name); ?><br>
<b>Required Skills:</b> <?php echo h($required_skills_text); ?>
</div>

<?php if($message != "") { ?>
<div class="alert alert-warning">
<?php echo h($message); ?>
</div>
<?php } ?>

<form method="POST" enctype="multipart/form-data">

<div class="mb-3">
<label class="form-label">Upload Resume PDF</label>
<input type="file" name="resume_pdf" class="form-control" accept="application/pdf" required>
</div>

<button type="submit" class="btn btn-success">Upload & Check Skills</button>
<a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>

</form>

<?php if ($_SERVER["REQUEST_METHOD"] == "POST" && $message == "") { ?>

<div class="alert alert-primary mt-4">
<b>Resume Skill Match Score:</b> <?php echo h($resume_score); ?>%
</div>

<div class="row mt-4">

<div class="col-md-6">
<div class="card border-success">
<div class="card-body">
<h4 class="text-success">Matched Skills</h4>

<?php if(count($matched_skills) > 0) { ?>
<?php foreach($matched_skills as $skill) { ?>
<span class="skill-badge"><?php echo h($skill); ?></span>
<?php } ?>
<?php } else { ?>
<p class="text-muted">No matching required skills found.</p>
<?php } ?>

</div>
</div>
</div>

<div class="col-md-6">
<div class="card border-danger">
<div class="card-body">
<h4 class="text-danger">Missing Skills</h4>

<?php if(count($missing_skills) > 0) { ?>
<?php foreach($missing_skills as $skill) { ?>
<span class="missing-badge"><?php echo h($skill); ?></span>
<?php } ?>
<?php } else { ?>
<p class="text-success">Excellent! All required skills found.</p>
<?php } ?>

</div>
</div>
</div>

</div>

<?php if(count($missing_skills) > 0) { ?>
<div class="alert alert-light border mt-4">
<h5>Suggestions to Improve Your Resume</h5>
<p>Add projects, certifications, or experience related to these missing skills:</p>
<ul>
<?php foreach($missing_skills as $skill) { ?>
<li>Learn or add project experience in <b><?php echo h($skill); ?></b></li>
<?php } ?>
</ul>
</div>
<?php } ?>

<?php } ?>

<?php } ?>

</div>
</div>

</body>
</html>