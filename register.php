<?php
include("includes/db.php");

$message = "";
$message_type = "";

if(isset($_POST['register']))
{
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = $_POST['role'];

    $check_query = "SELECT * FROM users WHERE email='$email'";
    $check_result = mysqli_query($conn, $check_query);

    if(mysqli_num_rows($check_result) > 0)
    {
        $message = "Email already exists. Please use another email.";
        $message_type = "danger";
    }
    else
    {
        $sql = "INSERT INTO users(name, email, password, role)
                VALUES('$name', '$email', '$password', '$role')";

        if(mysqli_query($conn, $sql))
        {
            $message = "Registration successful. You can now login.";
            $message_type = "success";
        }
        else
        {
            $message = "Error during registration.";
            $message_type = "danger";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - Intelligent Career Roadmap</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">Intelligent Career Roadmap</a>
        <div>
            <a href="login.php" class="btn btn-outline-light">Login</a>
        </div>
    </div>
</nav>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow p-4">
                <h2 class="text-center mb-4">Create Account</h2>

                <?php if($message != "") { ?>
                    <div class="alert alert-<?php echo $message_type; ?> text-center">
                        <?php echo $message; ?>
                    </div>
                <?php } ?>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Enter your name" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Select Role</label>
                        <select name="role" class="form-control" required>
                            <option value="">Choose role</option>
                            <option value="student">Student</option>
                            <option value="mentor">Mentor</option>
                        </select>
                    </div>

                    <div class="d-grid">
                        <button type="submit" name="register" class="btn btn-success">Register</button>
                    </div>
                </form>

                <p class="text-center mt-4 mb-0">
                    Already have an account?
                    <a href="login.php">Login here</a>
                </p>
            </div>
        </div>
    </div>
</div>

</body>
</html>