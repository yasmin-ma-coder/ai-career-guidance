<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['student_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['student_id'];

/* Get student's top recommended career */
$rec_query = mysqli_query($conn, "
    SELECT recommended_career 
    FROM recommendations 
    WHERE user_id='$user_id' 
    ORDER BY confidence_score DESC 
    LIMIT 1
");

$career_name = "";
$career_id = 0;

if($rec_row = mysqli_fetch_assoc($rec_query)) {
    $career_name = $rec_row['recommended_career'];

    $career_query = mysqli_query($conn, "
        SELECT id FROM careers 
        WHERE career_name='$career_name'
    ");

    if($career_row = mysqli_fetch_assoc($career_query)) {
        $career_id = $career_row['id'];
    }
}

/* Fetch resources */
$resources = null;

if($career_id != 0) {
    $resources = mysqli_query($conn, "
        SELECT mentor_resources.*, users.name AS mentor_name
        FROM mentor_resources
        JOIN users ON mentor_resources.mentor_id = users.id
        WHERE mentor_resources.career_id='$career_id'
        ORDER BY mentor_resources.created_at DESC
    ");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Career Resources</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5 mb-5">
<div class="card shadow p-4">

<h2 class="text-center text-primary mb-4">Career Learning Resources</h2>

<?php if($career_name == "") { ?>

<div class="alert alert-warning text-center">
Please take the career test first to view resources.
</div>

<?php } else { ?>

<div class="alert alert-info">
<b>Recommended Career:</b> <?php echo htmlspecialchars($career_name); ?>
</div>

<?php if($resources && mysqli_num_rows($resources) > 0) { ?>

<?php while($row = mysqli_fetch_assoc($resources)) { ?>

<div class="card mb-3 border-primary">
<div class="card-body">

<h5><?php echo htmlspecialchars($row['title']); ?></h5>

<p class="mb-1">
<b>Type:</b> <?php echo htmlspecialchars($row['resource_type']); ?>
</p>

<p class="mb-1">
<b>Added By:</b> <?php echo htmlspecialchars($row['mentor_name']); ?>
</p>

<p><?php echo htmlspecialchars($row['description']); ?></p>

<a href="<?php echo htmlspecialchars($row['resource_link']); ?>" 
   target="_blank" 
   class="btn btn-success btn-sm">
Open Resource
</a>

</div>
</div>

<?php } ?>

<?php } else { ?>

<div class="alert alert-warning">
No resources available for your recommended career yet.
</div>

<?php } ?>

<?php } ?>

<div class="text-center mt-3">
<a href="dashboard.php" class="btn btn-primary">Back to Dashboard</a>
</div>

</div>
</div>

</body>
</html>