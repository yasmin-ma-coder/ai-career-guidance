<?php
session_start();
include("../includes/db.php");

/* GET USER */
$user_id = $_SESSION['student_id'] ?? null;

/* GET TOP AI RECOMMENDED CAREER */
$recommended_career = "";

if($user_id){
    $rec_query = mysqli_query($conn, "
        SELECT recommended_career 
        FROM recommendations 
        WHERE user_id='$user_id' 
        ORDER BY confidence_score DESC 
        LIMIT 1
    ");

    if($rec_row = mysqli_fetch_assoc($rec_query)){
        $recommended_career = $rec_row['recommended_career'];
    }
}

/* FETCH ALL CAREERS (for dropdown) */
$career_query = mysqli_query($conn, "SELECT * FROM careers");

/* DEFAULT VALUES */
$roadmap_result = null;
$selected_career_name = "";

/* AUTO LOAD AI ROADMAP */
if($recommended_career != ""){
    $career_data = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT id FROM careers WHERE career_name='$recommended_career'"
    ));

    if($career_data){
        $career_id = $career_data['id'];
        $selected_career_name = $recommended_career;

        $roadmap_result = mysqli_query($conn,
            "SELECT * FROM roadmaps WHERE career_id='$career_id' ORDER BY step_number ASC"
        );
    }
}

/* MANUAL SELECTION (OVERRIDE AI) */
if(isset($_POST['career_id'])){
    $career_id = $_POST['career_id'];

    $name_query = mysqli_query($conn, "SELECT career_name FROM careers WHERE id='$career_id'");
    $name_row = mysqli_fetch_assoc($name_query);
    $selected_career_name = $name_row['career_name'];

    $roadmap_result = mysqli_query($conn,
        "SELECT * FROM roadmaps WHERE career_id='$career_id' ORDER BY step_number ASC"
    );
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Career Roadmap</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
.step-card{
border-left:5px solid #0d6efd;
border-radius:10px;
}
</style>

</head>

<body class="bg-light">

<div class="container mt-5">

<h2 class="text-center mb-4">Career Roadmap</h2>

<!-- SHOW AI MESSAGE -->
<?php if($recommended_career != "") { ?>
<div class="alert alert-success text-center">
    AI Recommended Career: <b><?php echo $recommended_career; ?></b>
</div>
<?php } ?>

<!-- MANUAL SELECT -->
<form method="POST">
<div class="row mb-4">
    <div class="col-md-8">
        <select name="career_id" class="form-control" required>
            <option value="">Select Career (Manual)</option>

            <?php while($career = mysqli_fetch_assoc($career_query)) { ?>
                <option value="<?php echo $career['id']; ?>">
                    <?php echo $career['career_name']; ?>
                </option>
            <?php } ?>

        </select>
    </div>

    <div class="col-md-4">
        <button type="submit" class="btn btn-primary w-100">
            Show Roadmap
        </button>
    </div>
</div>
</form>

<!-- SHOW ROADMAP -->
<?php if($selected_career_name != "") { ?>
<h4 class="mb-3 text-success">
    <?php echo htmlspecialchars($selected_career_name); ?> Roadmap
</h4>
<?php } ?>

<?php
if($roadmap_result && mysqli_num_rows($roadmap_result) > 0){
    while($row = mysqli_fetch_assoc($roadmap_result)){
?>

<div class="card mb-3 shadow step-card">
    <div class="card-body">
        <h5 class="text-primary">Step <?php echo $row['step_number']; ?></h5>
        <p><?php echo htmlspecialchars($row['step_description']); ?></p>
    </div>
</div>

<?php
    }
}else if($selected_career_name != ""){
    echo "<div class='alert alert-warning'>No roadmap available.</div>";
}
?>

<div class="text-center mt-4">
<a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
</div>

</div>

</body>
</html>