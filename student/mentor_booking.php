<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['student_id']) || !isset($_SESSION['student_role']) || $_SESSION['student_role'] != "student") {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['student_id'];
$student_name = $_SESSION['student_name'] ?? "Student";

/* Find or create student table record */
$student_query = mysqli_query($conn, "SELECT id FROM students WHERE user_id = '$user_id'");
$student_row = mysqli_fetch_assoc($student_query);

if (!$student_row) {
    mysqli_query($conn, "INSERT INTO students (user_id) VALUES ('$user_id')");
    $student_id = mysqli_insert_id($conn);
} else {
    $student_id = $student_row['id'];
}

/* Get mentor list */
$query = "SELECT mentors.*, users.name 
          FROM mentors 
          JOIN users ON mentors.user_id = users.id";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Book Mentor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5 mb-5">
    <div class="card shadow p-4">

        <h2 class="text-center mb-2">Book a Mentor</h2>
        <p class="text-center text-muted">Welcome, <?php echo htmlspecialchars($student_name); ?></p>

        <form action="payment.php" method="POST">

            <div class="mb-3">
                <label class="form-label">Select Mentor</label>
                <select name="mentor_id" class="form-control" required>
                    <option value="">Choose Mentor</option>

                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <option value="<?php echo $row['user_id']; ?>">
                            <?php echo htmlspecialchars($row['name']); ?> - 
                            <?php echo htmlspecialchars($row['expertise']); ?> | Fee: ₹1000
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Appointment Date</label>
                <!--<input type="date" name="appointment_date" class="form-control" required>-->
                <input type="date"
       name="appointment_date"
       class="form-control"
       min="<?php echo date('Y-m-d'); ?>"
       required>
            </div>

            <div class="mb-3">
                <label class="form-label">Appointment Time</label>
                <input type="time" name="appointment_time" class="form-control" required>
            </div>

            <div class="alert alert-info">
                Mentor session fee: <b>₹1000</b>. Payment is required before booking confirmation.
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-warning">
                    Proceed to Payment
                </button>
                <br><br>
                <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
            </div>

        </form>
    </div>
</div>

<script>
const dateInput = document.querySelector('input[name="appointment_date"]');
const timeInput = document.querySelector('input[name="appointment_time"]');

dateInput.addEventListener("change", function () {

    const today = new Date().toISOString().split("T")[0];

    if (this.value === today) {

        let now = new Date();

        let hours = String(now.getHours()).padStart(2, '0');
        let minutes = String(now.getMinutes()).padStart(2, '0');

        timeInput.min = hours + ":" + minutes;

    } else {

        timeInput.removeAttribute("min");

    }

});
</script>

</body>
</html>