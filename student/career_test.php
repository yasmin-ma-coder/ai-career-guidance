<?php
session_start();
include("../includes/db.php");

$result = mysqli_query($conn, "SELECT * FROM questions");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Career Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5 mb-5">

    <div class="card shadow p-4">
        <h2 class="text-center mb-4">Career Assessment Test</h2>
        <p class="text-center text-muted">
            Answer all questions to get your career recommendation.
        </p>

        <form action="process_test.php" method="POST">

            <?php
            $i = 1;
            while($row = mysqli_fetch_assoc($result))
            {
            ?>
                <div class="card mb-3 shadow-sm">
                    <div class="card-body">

                        <p class="mb-3">
                            <b><?php echo $i . ". " . htmlspecialchars($row['question']); ?></b>
                        </p>

                        <div class="form-check mb-2">
                            <input 
                                class="form-check-input" 
                                type="radio" 
                                name="q<?php echo $row['id']; ?>" 
                                value="Strongly Agree" 
                                id="q<?php echo $row['id']; ?>_1" 
                                required
                            >
                            <label class="form-check-label" for="q<?php echo $row['id']; ?>_1">
                                <?php echo htmlspecialchars($row['option1']); ?>
                            </label>
                        </div>

                        <div class="form-check mb-2">
                            <input 
                                class="form-check-input" 
                                type="radio" 
                                name="q<?php echo $row['id']; ?>" 
                                value="Agree" 
                                id="q<?php echo $row['id']; ?>_2"
                            >
                            <label class="form-check-label" for="q<?php echo $row['id']; ?>_2">
                                <?php echo htmlspecialchars($row['option2']); ?>
                            </label>
                        </div>

                        <div class="form-check mb-2">
                            <input 
                                class="form-check-input" 
                                type="radio" 
                                name="q<?php echo $row['id']; ?>" 
                                value="Neutral" 
                                id="q<?php echo $row['id']; ?>_3"
                            >
                            <label class="form-check-label" for="q<?php echo $row['id']; ?>_3">
                                <?php echo htmlspecialchars($row['option3']); ?>
                            </label>
                        </div>

                        <div class="form-check mb-2">
                            <input 
                                class="form-check-input" 
                                type="radio" 
                                name="q<?php echo $row['id']; ?>" 
                                value="Disagree" 
                                id="q<?php echo $row['id']; ?>_4"
                            >
                            <label class="form-check-label" for="q<?php echo $row['id']; ?>_4">
                                <?php echo htmlspecialchars($row['option4']); ?>
                            </label>
                        </div>

                    </div>
                </div>
            <?php
                $i++;
            }
            ?>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary px-4">Submit Test</button>
                <br><br>
                <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
            </div>

        </form>
    </div>

</div>

</body>
</html>