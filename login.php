<?php
session_start();
include("includes/db.php");

$error = "";

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' AND password='$password'");

    if ($query && mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_assoc($query);

        // Common session values
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['role'] = $row['role'];
        $_SESSION['name'] = $row['name'];
        $_SESSION['email'] = $row['email'];

        // Student session + redirect
        if ($row['role'] == 'student') {
            $_SESSION['student_id'] = $row['id'];
            $_SESSION['student_name'] = $row['name'];
            $_SESSION['student_email'] = $row['email'];
            $_SESSION['student_role'] = $row['role'];

            header("Location: student/dashboard.php");
            exit();
        }

        // Admin session + redirect
        if ($row['role'] == 'admin') {
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['admin_name'] = $row['name'];
            $_SESSION['admin_email'] = $row['email'];

            header("Location: admin/dashboard.php");
            exit();
        }

        // Mentor session + redirect
        if ($row['role'] == 'mentor') {
            $_SESSION['mentor_id'] = $row['id'];
            $_SESSION['mentor_name'] = $row['name'];
            $_SESSION['mentor_email'] = $row['email'];

            header("Location: mentor/dashboard.php");
            exit();
        }

        $error = "Invalid user role.";
    } else {
        $error = "Invalid email or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Intelligent Career Roadmap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #eef4ff, #f8fbff);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            width: 100%;
            max-width: 450px;
            background: #fff;
            border-radius: 22px;
            padding: 35px;
            box-shadow: 0 12px 35px rgba(0,0,0,0.08);
        }

        .login-card h2 {
            font-weight: 700;
            color: #1b2a4e;
        }

        .form-control {
            border-radius: 12px;
            padding: 12px 14px;
        }

        .btn-login {
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="login-card">
    <h2 class="text-center mb-3">User Login</h2>
    <p class="text-center text-muted mb-4">Login to continue to your dashboard</p>

    <?php if (!empty($error)) { ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php } ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <button type="submit" name="login" class="btn btn-primary w-100 btn-login">Login</button>
    </form>

    <div class="text-center mt-3">
        <a href="register.php" class="text-decoration-none">Create new account</a>
    </div>
</div>

</body>
</html>