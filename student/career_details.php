<?php
session_start();
include("../includes/db.php");

if(!isset($_GET['career_id']) || empty($_GET['career_id']))
{
    echo "<h3 class='text-center mt-5'>Career not selected.</h3>";
    exit();
}

$career_id = $_GET['career_id'];

$query = "SELECT * FROM careers WHERE id='$career_id'";
$result = mysqli_query($conn, $query);
$career = mysqli_fetch_assoc($result);

if(!$career)
{
    echo "<h3 class='text-center mt-5'>Career details not found.</h3>";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Career Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow p-5">
        <h2 class="text-center mb-4">Career Details</h2>

        <h1 class="text-success text-center mb-4">
            <?php echo htmlspecialchars($career['career_name']); ?>
        </h1>

        <hr>

        <h4>Description</h4>
        <p><?php echo htmlspecialchars($career['description']); ?></p>

        <h4>Required Skills</h4>
        <p><?php echo htmlspecialchars($career['required_skills']); ?></p>

        <h4>Average Salary</h4>
        <p>₹ <?php echo htmlspecialchars($career['average_salary']); ?> per year</p>

        <h4>Growth Rate</h4>
        <p><?php echo htmlspecialchars($career['growth_rate']); ?></p>

        <div class="text-center mt-4">
            <a href="roadmap.php" class="btn btn-success">View Career Roadmap</a>
            <br><br>
            <a href="job_comparison.php" class="btn btn-info">Compare Careers</a>
            <br><br>
            <a href="dashboard.php" class="btn btn-primary">Back to Dashboard</a>
        </div>
    </div>
</div>

</body>
</html>