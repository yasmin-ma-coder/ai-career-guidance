<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['student_id'])) {
    header("Location: ../login.php");
    exit();
}

$education = $_POST['education'] ?? "";
$category = $_POST['category'] ?? "";
$income = $_POST['income'] ?? "";
$career_field = $_POST['career_field'] ?? "";

$scholarships = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $education = mysqli_real_escape_string($conn, $education);
    $category = mysqli_real_escape_string($conn, $category);
    $income = mysqli_real_escape_string($conn, $income);
    $career_field = mysqli_real_escape_string($conn, $career_field);

    $query = "
        SELECT * FROM scholarships
        WHERE education_level='$education'
        AND category='$category'
        AND income_range='$income'
        AND career_field='$career_field'
    ";

    $scholarships = mysqli_query($conn, $query);
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Scholarship Suggestions</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5 mb-5">
<div class="card shadow p-4">

<h2 class="text-center text-primary mb-4">Scholarship Suggestion</h2>

<p class="text-center text-muted">
Find scholarships based on your education, category, income level, and career field.
</p>

<form method="POST" class="mb-4">

<div class="row">

<div class="col-md-3 mb-3">
<label class="form-label">Education</label>
<select name="education" class="form-control" required>
<option value="">Select</option>
<option value="BCA" <?php if($education=="BCA") echo "selected"; ?>>BCA</option>
<option value="BTech" <?php if($education=="BTech") echo "selected"; ?>>BTech</option>
<option value="MCA" <?php if($education=="MCA") echo "selected"; ?>>MCA</option>
<option value="MBA" <?php if($education=="MBA") echo "selected"; ?>>MBA</option>
<option value="Plus Two" <?php if($education=="Plus Two") echo "selected"; ?>>Plus Two</option>
</select>
</div>

<div class="col-md-3 mb-3">
<label class="form-label">Category</label>
<select name="category" class="form-control" required>
<option value="">Select</option>
<option value="General" <?php if($category=="General") echo "selected"; ?>>General</option>
<option value="OBC" <?php if($category=="OBC") echo "selected"; ?>>OBC</option>
<option value="SC/ST" <?php if($category=="SC/ST") echo "selected"; ?>>SC/ST</option>
<option value="Minority" <?php if($category=="Minority") echo "selected"; ?>>Minority</option>
<option value="Female" <?php if($category=="Female") echo "selected"; ?>>Female</option>
</select>
</div>

<div class="col-md-3 mb-3">
<label class="form-label">Income Range</label>
<select name="income" class="form-control" required>
<option value="">Select</option>
<option value="Low" <?php if($income=="Low") echo "selected"; ?>>Low</option>
<option value="Medium" <?php if($income=="Medium") echo "selected"; ?>>Medium</option>
<option value="High" <?php if($income=="High") echo "selected"; ?>>High</option>
</select>
</div>

<div class="col-md-3 mb-3">
<label class="form-label">Career Field</label>
<select name="career_field" class="form-control" required>
<option value="">Select</option>
<option value="Technology" <?php if($career_field=="Technology") echo "selected"; ?>>Technology</option>
<option value="Healthcare" <?php if($career_field=="Healthcare") echo "selected"; ?>>Healthcare</option>
<option value="Business" <?php if($career_field=="Business") echo "selected"; ?>>Business</option>
<option value="Design" <?php if($career_field=="Design") echo "selected"; ?>>Design</option>
<option value="Education" <?php if($career_field=="Education") echo "selected"; ?>>Education</option>
</select>
</div>

</div>

<button type="submit" class="btn btn-success">Find Scholarships</button>
<a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>

</form>

<?php if($scholarships !== null) { ?>

<h4 class="mb-3 text-success">Suggested Scholarships</h4>

<?php if(mysqli_num_rows($scholarships) > 0) { ?>

<?php while($row = mysqli_fetch_assoc($scholarships)) { ?>

<div class="card mb-3 border-success">
<div class="card-body">

<h5><?php echo htmlspecialchars($row['scholarship_name']); ?></h5>

<p><b>Amount:</b> <?php echo htmlspecialchars($row['amount']); ?></p>
<p><b>Description:</b> <?php echo htmlspecialchars($row['description']); ?></p>

<a href="<?php echo htmlspecialchars($row['apply_link']); ?>" target="_blank" class="btn btn-primary btn-sm">
Apply / View Details
</a>

</div>
</div>

<?php } ?>

<?php } else { ?>

<div class="alert alert-warning">
No scholarship found for selected details.
</div>

<?php } ?>

<?php } ?>

</div>
</div>

</body>
</html>