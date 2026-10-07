<?php
session_start();
include("../includes/db.php");

if(!isset($_SESSION['role']) || $_SESSION['role'] != "admin")
{
    header("Location: ../login.php");
    exit();
}

$message = "";

/* add career */
if(isset($_POST['add_career']))
{
    $career_name = $_POST['career_name'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $required_skills = $_POST['required_skills'];
    $average_salary = $_POST['average_salary'];
    $growth_rate = $_POST['growth_rate'];

    $insert_query = "INSERT INTO careers (career_name, category, description, required_skills, average_salary, growth_rate)
                     VALUES ('$career_name', '$category', '$description', '$required_skills', '$average_salary', '$growth_rate')";

    if(mysqli_query($conn, $insert_query))
    {
        $message = "Career added successfully.";
    }
    else
    {
        $message = "Error adding career.";
    }
}

/* delete career */
if(isset($_GET['delete']))
{
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM careers WHERE id='$id'");
    header("Location: manage_careers.php");
    exit();
}

$query = "SELECT * FROM careers ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Careers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5 mb-5">
    <div class="card shadow p-4 mb-4">
        <h2 class="text-center mb-4">Manage Careers</h2>

        <?php if($message != "") { ?>
            <div class="alert alert-info text-center">
                <?php echo $message; ?>
            </div>
        <?php } ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Career Name</label>
                <input type="text" name="career_name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Category</label>
                <select name="category" class="form-control" required>
                    <option value="">Select Category</option>
                    <option value="Technology">Technology</option>
                    <option value="Design">Design</option>
                    <option value="Business">Business</option>
                    <option value="Healthcare">Healthcare</option>
                    <option value="Education">Education</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3" required></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Required Skills</label>
                <textarea name="required_skills" class="form-control" rows="3" required></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Average Salary</label>
                <input type="text" name="average_salary" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Growth Rate</label>
                <input type="text" name="growth_rate" class="form-control" required>
            </div>

            <div class="text-center">
                <button type="submit" name="add_career" class="btn btn-success">Add Career</button>
            </div>
        </form>
    </div>

    <div class="card shadow p-4">
        <h3 class="text-center mb-4">Career List</h3>

        <?php if(mysqli_num_rows($result) > 0) { ?>
            <table class="table table-bordered table-striped">
                <tr>
                    <th>ID</th>
                    <th>Career Name</th>
                    <th>Category</th>
                    <th>Average Salary</th>
                    <th>Growth Rate</th>
                    <th>Action</th>
                </tr>

                <?php while($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['career_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['category']); ?></td>
                        <td><?php echo htmlspecialchars($row['average_salary']); ?></td>
                        <td><?php echo htmlspecialchars($row['growth_rate']); ?></td>
                        <td>
                            <a href="manage_careers.php?delete=<?php echo $row['id']; ?>" 
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Are you sure you want to delete this career?')">
                               Delete
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        <?php } else { ?>
            <div class="alert alert-warning text-center">No careers found.</div>
        <?php } ?>

        <div class="text-center">
            <a href="dashboard.php" class="btn btn-primary">Back to Dashboard</a>
        </div>
    </div>
</div>

</body>
</html>