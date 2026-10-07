<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['student_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['student_id'];

$query = mysqli_query($conn, "SELECT * FROM recommendations WHERE user_id='$user_id' ORDER BY confidence_score DESC");

$results = [];
while ($row = mysqli_fetch_assoc($query)) {
    $results[] = $row;
}

if (count($results) == 0) {
?>
<!DOCTYPE html>
<html>
<head>
<title>No Recommendation</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background:#f4f7fb">
<div class="container mt-5">
<div class="card p-4 text-center">
<h3>No Recommendation Available Yet</h3>
<p>Please take the career test first.</p>
<a href="career_test.php" class="btn btn-primary">Take Career Test</a>
<a href="dashboard.php" class="btn btn-secondary mt-2">Back to Dashboard</a>
</div>
</div>
</body>
</html>
<?php
exit();
}

$education = $_POST['education'] ?? "BCA";
$location = $_POST['location'] ?? "Kerala";
$budget = $_POST['budget'] ?? "Medium";

$top_career = $results[0]['recommended_career'];

$pythonDir = realpath(__DIR__ . "/../python_ai");
$pythonFile = $pythonDir . DIRECTORY_SEPARATOR . "predict_college.py";

$command = 'cd /d ' . escapeshellarg($pythonDir) .
    ' && set PYTHONIOENCODING=utf-8 && python ' . escapeshellarg($pythonFile) . ' ' .
    escapeshellarg($top_career) . ' ' .
    escapeshellarg($education) . ' ' .
    escapeshellarg($location) . ' ' .
    escapeshellarg($budget) . ' 2>&1';

$output = trim(shell_exec($command));

$ai_suggestion = json_decode($output, true);

if ($ai_suggestion === null) {
    $ai_suggestion = [
        "suggestions" => [],
        "error" => $output
    ];
}

$suggestions = $ai_suggestion['suggestions'] ?? [];
$error = $ai_suggestion['error'] ?? "";

function h($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Career Recommendations</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    background:#f4f7fb;
    font-family:Poppins, Arial, sans-serif;
}
.container-box{
    max-width:1050px;
    margin:40px auto;
}
.card-box{
    background:white;
    padding:30px;
    border-radius:20px;
    box-shadow:0 10px 30px rgba(0,0,0,0.08);
}
.ai-box{
    background:#f0fff4;
    border:1px solid #198754;
    border-radius:18px;
    padding:25px;
}
.info-card{
    border-radius:15px;
    border:1px solid #d9e8ff;
}
.info-label{
    font-weight:600;
    color:#198754;
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
</style>
</head>

<body>

<div class="container container-box">
<div class="card-box">

<h2 class="mb-4 text-primary">Your Career Recommendations</h2>

<?php foreach ($results as $row) { ?>
<div class="card mb-3">
<div class="card-body">
<h5><?php echo h($row['recommended_career']); ?></h5>
<p>Confidence Score: <b><?php echo h($row['confidence_score']); ?>%</b></p>
</div>
</div>
<?php } ?>

<form method="POST" class="card p-3 mb-4">
<h5 class="mb-3 text-primary">Customize Course & College Suggestion</h5>

<div class="row">
<div class="col-md-4 mb-3">
<label class="form-label">Education</label>
<select name="education" class="form-control" required>
<option value="Plus Two" <?php if($education=="Plus Two") echo "selected"; ?>>Plus Two</option>
<option value="BCA" <?php if($education=="BCA") echo "selected"; ?>>BCA</option>
<option value="BTech" <?php if($education=="BTech") echo "selected"; ?>>BTech</option>
<option value="MCA" <?php if($education=="MCA") echo "selected"; ?>>MCA</option>
<option value="MBA" <?php if($education=="MBA") echo "selected"; ?>>MBA</option>
<!--<option value="PhD" <?php if($education=="PhD") echo "selected"; ?>>PhD</option>-->
</select>
</div>

<div class="col-md-4 mb-3">
<label class="form-label">Location</label>
<select name="location" class="form-control" required>
<option value="Kerala" <?php if($location=="Kerala") echo "selected"; ?>>Kerala</option>
<option value="Karnataka" <?php if($location=="Karnataka") echo "selected"; ?>>Karnataka</option>
<option value="Tamil Nadu" <?php if($location=="Tamil Nadu") echo "selected"; ?>>Tamil Nadu</option>
<option value="Telangana" <?php if($location=="Telangana") echo "selected"; ?>>Telangana</option>
<option value="Maharashtra" <?php if($location=="Maharashtra") echo "selected"; ?>>Maharashtra</option>
<option value="Delhi" <?php if($location=="Delhi") echo "selected"; ?>>Delhi</option>
<option value="Gujarat" <?php if($location=="Gujarat") echo "selected"; ?>>Gujarat</option>
</select>
</div>

<div class="col-md-4 mb-3">
<label class="form-label">Budget</label>
<select name="budget" class="form-control" required>
<option value="Low" <?php if($budget=="Low") echo "selected"; ?>>Low</option>
<option value="Medium" <?php if($budget=="Medium") echo "selected"; ?>>Medium</option>
<option value="High" <?php if($budget=="High") echo "selected"; ?>>High</option>
</select>
</div>
</div>

<button type="submit" class="btn btn-success">Get AI Suggestion</button>
</form>

<div class="ai-box mt-4 mb-4">
<h4 class="text-success mb-3">AI-Based Course & College Suggestion</h4>

<p><b>Recommended Career:</b> <?php echo h($top_career); ?></p>
<p><b>Selected Education:</b> <?php echo h($education); ?></p>
<p><b>Selected Location:</b> <?php echo h($location); ?></p>
<p><b>Selected Budget:</b> <?php echo h($budget); ?></p>

<hr>

<?php if (empty($suggestions)) { ?>

<div class="alert alert-warning">
No course or college suggestion available for this selection.
<?php if ($error != "") { ?>
<br><small><b>Error:</b> <?php echo h($error); ?></small>
<?php } ?>
</div>

<?php } else { ?>

<?php foreach ($suggestions as $index => $s) { ?>
<div class="card info-card mb-3">
<div class="card-body">

<h5 class="text-primary mb-3">Recommendation <?php echo $index + 1; ?></h5>

<div class="row">
<div class="col-md-6 mb-2">
<span class="info-label">Recommended Course:</span><br>
<?php echo h($s['course'] ?? 'Not available'); ?>
</div>

<div class="col-md-6 mb-2">
<span class="info-label">Recommended College:</span><br>
<?php echo h($s['college'] ?? 'Not available'); ?>
</div>

<div class="col-md-6 mb-2">
<span class="info-label">Course Duration:</span><br>
<?php echo h($s['duration'] ?? 'Not available'); ?>
</div>

<div class="col-md-6 mb-2">
<span class="info-label">Average Fees:</span><br>
<?php echo h($s['average_fees'] ?? 'Not available'); ?>
</div>

<div class="col-md-6 mb-2">
<span class="info-label">Placement Rate:</span><br>
<?php echo h($s['placement'] ?? 'Not available'); ?>
</div>

<div class="col-md-6 mb-2">
<span class="info-label">Expected Salary:</span><br>
<?php echo h($s['expected_salary'] ?? 'Not available'); ?>
</div>
</div>

<div class="mt-3">
<span class="info-label">Required Skills:</span><br>
<?php
$skills = explode(",", $s['required_skills'] ?? '');
foreach ($skills as $skill) {
    if (trim($skill) != "") {
        echo "<span class='skill-badge'>" . h(trim($skill)) . "</span>";
    }
}
?>
</div>

</div>
</div>
<?php } ?>

<?php } ?>

<p class="text-muted mb-0">
This suggestion is generated using a AI model based on the selected education, location, and budget.
</p>
</div>

<a href="dashboard.php" class="btn btn-primary">Back to Dashboard</a>

</div>
</div>

</body>
</html>