<?php
session_start();
include("../includes/db.php");

if(!isset($_SESSION['role']) || $_SESSION['role'] != "admin")
{
    header("Location: ../login.php");
    exit();
}

$message = "";

/* add question */
if(isset($_POST['add_question']))
{
    $question = $_POST['question'];
    $option1 = $_POST['option1'];
    $option2 = $_POST['option2'];
    $option3 = $_POST['option3'];
    $option4 = $_POST['option4'];
    $category = $_POST['category'];

    $insert_query = "INSERT INTO questions (question, option1, option2, option3, option4, category)
                     VALUES ('$question', '$option1', '$option2', '$option3', '$option4', '$category')";

    if(mysqli_query($conn, $insert_query))
    {
        $message = "Question added successfully.";
    }
    else
    {
        $message = "Error adding question.";
    }
}

/* delete question */
if(isset($_GET['delete']))
{
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM questions WHERE id='$id'");
    header("Location: manage_questions.php");
    exit();
}

$query = "SELECT * FROM questions ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Questions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5 mb-5">

    <div class="card shadow p-4 mb-4">
        <h2 class="text-center mb-4">Manage Questions</h2>

        <?php if($message != "") { ?>
            <div class="alert alert-info text-center">
                <?php echo $message; ?>
            </div>
        <?php } ?>

        <form method="POST">

            <div class="mb-3">
                <label class="form-label">Question</label>
                <textarea name="question" class="form-control" rows="3" required></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Option 1</label>
                <input type="text" name="option1" class="form-control" value="Strongly Agree" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Option 2</label>
                <input type="text" name="option2" class="form-control" value="Agree" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Option 3</label>
                <input type="text" name="option3" class="form-control" value="Neutral" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Option 4</label>
                <input type="text" name="option4" class="form-control" value="Disagree" required>
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

            <div class="text-center">
                <button type="submit" name="add_question" class="btn btn-success">Add Question</button>
            </div>

        </form>
    </div>

    <div class="card shadow p-4">
        <h3 class="text-center mb-4">Question List</h3>

        <?php if(mysqli_num_rows($result) > 0) { ?>
            <table class="table table-bordered table-striped">
                <tr>
                    <th>ID</th>
                    <th>Question</th>
                    <th>Category</th>
                    <th>Action</th>
                </tr>

                <?php while($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['question']); ?></td>
                        <td><?php echo htmlspecialchars($row['category']); ?></td>
                        <td>
                            <a href="manage_questions.php?delete=<?php echo $row['id']; ?>"
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Are you sure you want to delete this question?')">
                               Delete
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        <?php } else { ?>
            <div class="alert alert-warning text-center">No questions found.</div>
        <?php } ?>

        <div class="text-center">
            <a href="dashboard.php" class="btn btn-primary">Back to Dashboard</a>
        </div>
    </div>

</div>

</body>
</html>