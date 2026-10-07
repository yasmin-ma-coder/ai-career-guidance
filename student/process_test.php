<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['student_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['student_id'];

$scores = array(
    "Technology" => 0,
    "Design" => 0,
    "Business" => 0,
    "Healthcare" => 0,
    "Education" => 0
);

$query = "SELECT * FROM questions";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Questions query failed: " . mysqli_error($conn));
}

// Delete old answers and recommendations for this user
mysqli_query($conn, "DELETE FROM answers WHERE user_id='$user_id'");
mysqli_query($conn, "DELETE FROM recommendations WHERE user_id='$user_id'");

while ($row = mysqli_fetch_assoc($result)) {
    $qid = $row['id'];
    $category = $row['category'];

    if (isset($_POST['q' . $qid])) {
        $answer = $_POST['q' . $qid];

        if ($answer == "Strongly Agree") {
            $scores[$category] += 4;
        } elseif ($answer == "Agree") {
            $scores[$category] += 3;
        } elseif ($answer == "Neutral") {
            $scores[$category] += 2;
        } else {
            $scores[$category] += 1;
        }

        $insert_answer = mysqli_query($conn, "
            INSERT INTO answers(user_id, question_id, selected_option)
            VALUES('$user_id', '$qid', '$answer')
        ");

        if (!$insert_answer) {
            die("Answer insert failed: " . mysqli_error($conn));
        }
    }
}

$top_category = array_keys($scores, max($scores))[0];

if ($top_category == "Technology") {
    $careers = array("Software Developer", "Data Analyst", "Cyber Security Specialist");
} elseif ($top_category == "Design") {
    $careers = array("Graphic Designer", "UI/UX Designer");
} elseif ($top_category == "Business") {
    $careers = array("Business Analyst", "Marketing Manager");
} elseif ($top_category == "Healthcare") {
    $careers = array("Doctor", "Nurse");
} else {
    $careers = array("Teacher", "Professor");
}

foreach ($careers as $career) {
    $confidence = rand(80, 95);

    $insert_recommendation = mysqli_query($conn, "
        INSERT INTO recommendations(user_id, recommended_career, confidence_score)
        VALUES('$user_id', '$career', '$confidence')
    ");

    if (!$insert_recommendation) {
        die("Recommendation insert failed: " . mysqli_error($conn));
    }
}

header("Location: result.php");
exit();
?>