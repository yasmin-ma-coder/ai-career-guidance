<?php
include("../includes/db.php");

$query = "SELECT career_name FROM job_comparisons";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Job Comparison</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="text-center mb-4">Compare Careers</h2>

    <form action="compare_result.php" method="POST">
        <div class="row">
            <div class="col-md-6">
                <label>Select Career 1</label>
                <select name="career1" class="form-control" required>
                    <option value="">Select Career</option>
                    <?php while($row = mysqli_fetch_assoc($result)) { ?>
                        <option value="<?php echo $row['career_name']; ?>">
                            <?php echo $row['career_name']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="col-md-6">
                <label>Select Career 2</label>
                <select name="career2" class="form-control" required>
                    <option value="">Select Career</option>
                    <?php
                    $result2 = mysqli_query($conn, "SELECT career_name FROM job_comparisons");
                    while($row = mysqli_fetch_assoc($result2)) {
                    ?>
                        <option value="<?php echo $row['career_name']; ?>">
                            <?php echo $row['career_name']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <br>

        <button type="submit" class="btn btn-primary">Compare</button>
    </form>
</div>

</body>
</html>