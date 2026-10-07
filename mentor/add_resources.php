<?php
session_start();
include("../includes/db.php");

if(!isset($_SESSION['role']) || $_SESSION['role'] != "mentor")
{
    header("Location: ../login.php");
    exit();
}

$mentor_id = $_SESSION['user_id'];
$message = "";

if($_SERVER["REQUEST_METHOD"] == "POST")
{
    $career_id = mysqli_real_escape_string($conn, $_POST['career_id']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $resource_type = mysqli_real_escape_string($conn, $_POST['resource_type']);
    $resource_link = mysqli_real_escape_string($conn, $_POST['resource_link']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $insert = mysqli_query($conn, "
        INSERT INTO mentor_resources
        (mentor_id, career_id, title, resource_type, resource_link, description)
        VALUES
        ('$mentor_id', '$career_id', '$title', '$resource_type', '$resource_link', '$description')
    ");

    if($insert) {
        $message = "Resource added successfully.";
    } else {
        $message = "Error adding resource: " . mysqli_error($conn);
    }
}

$careers = mysqli_query($conn, "SELECT * FROM careers ORDER BY career_name ASC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Career Resources</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5 mb-5">
<div class="card shadow p-4">

<h2 class="text-center mb-4">Add Career Resources</h2>

<?php if($message != "") { ?>
<div class="alert alert-info text-center">
<?php echo htmlspecialchars($message); ?>
</div>
<?php } ?>

<form method="POST">

<div class="mb-3">
<label class="form-label">Select Career</label>
<select name="career_id" class="form-control" required>
<option value="">Choose Career</option>
<?php while($row = mysqli_fetch_assoc($careers)) { ?>
<option value="<?php echo $row['id']; ?>">
<?php echo htmlspecialchars($row['career_name']); ?>
</option>
<?php } ?>
</select>
</div>

<div class="mb-3">
<label class="form-label">Resource Title</label>
<input type="text" name="title" class="form-control" placeholder="Example: Best Python Course for Beginners" required>
</div>

<div class="mb-3">
<label class="form-label">Resource Type</label>
<select name="resource_type" class="form-control" required>
<option value="Course">Course</option>
<option value="Video">Video</option>
<option value="Website">Website</option>
<option value="PDF">PDF</option>
<option value="Interview Tips">Interview Tips</option>
<option value="Project Idea">Project Idea</option>
</select>
</div>

<div class="mb-3">
<label class="form-label">Resource Link</label>
<input type="url" name="resource_link" class="form-control" placeholder="https://example.com" required>
</div>

<div class="mb-3">
<label class="form-label">Description</label>
<textarea name="description" class="form-control" rows="4" placeholder="Explain why this resource is useful"></textarea>
</div>

<div class="text-center">
<button type="submit" class="btn btn-success">Add Resource</button>
<br><br>
<a href="dashboard.php" class="btn btn-primary">Back to Dashboard</a>
</div>

</form>

</div>
</div>

</body>
</html>