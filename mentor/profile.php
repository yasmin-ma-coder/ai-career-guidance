<?php
session_start();
include("../includes/db.php");

if(!isset($_SESSION['role']) || $_SESSION['role']!="mentor")
{
    header("Location: ../login.php");
    exit();
}

$user_id=$_SESSION['user_id'];

$query=mysqli_query($conn,"SELECT * FROM mentors WHERE user_id='$user_id'");
$mentor=mysqli_fetch_assoc($query);

if(isset($_POST['save']))
{
    $specialization=mysqli_real_escape_string($conn,$_POST['specialization']);
    $qualification=mysqli_real_escape_string($conn,$_POST['qualification']);
    $experience=mysqli_real_escape_string($conn,$_POST['experience']);
    $company=mysqli_real_escape_string($conn,$_POST['company']);
    $skills=mysqli_real_escape_string($conn,$_POST['skills']);
    $languages=mysqli_real_escape_string($conn,$_POST['languages']);
    $bio=mysqli_real_escape_string($conn,$_POST['bio']);

    $photo=$mentor['profile_photo'];

    if(!empty($_FILES['profile_photo']['name']))
    {
        $photo=time()."_".$_FILES['profile_photo']['name'];

        move_uploaded_file(
        $_FILES['profile_photo']['tmp_name'],
        "../uploads/mentor_photos/".$photo);
    }

    mysqli_query($conn,"
    UPDATE mentors SET

    specialization='$specialization',
    qualification='$qualification',
    experience='$experience',
    company='$company',
    skills='$skills',
    languages='$languages',
    bio='$bio',
    profile_photo='$photo'

    WHERE user_id='$user_id'
    ");

    echo "<script>alert('Profile Updated');window.location='profile.php';</script>";
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Mentor Profile</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<div class="container mt-5">

<div class="card shadow p-4">

<h2 class="text-center">My Profile</h2>

<form method="POST" enctype="multipart/form-data">

<label>Profile Photo</label>

<input type="file"
name="profile_photo"
class="form-control mb-3">

<label>Specialization</label>

<input
type="text"
name="specialization"
class="form-control mb-3"
value="<?php echo $mentor['specialization'];?>">

<label>Qualification</label>

<input
type="text"
name="qualification"
class="form-control mb-3"
value="<?php echo $mentor['qualification'];?>">

<label>Experience</label>

<input
type="text"
name="experience"
class="form-control mb-3"
value="<?php echo $mentor['experience'];?>">

<label>Company</label>

<input
type="text"
name="company"
class="form-control mb-3"
value="<?php echo $mentor['company'];?>">

<label>Skills</label>

<textarea
name="skills"
class="form-control mb-3"><?php echo $mentor['skills'];?></textarea>

<label>Languages</label>

<input
type="text"
name="languages"
class="form-control mb-3"
value="<?php echo $mentor['languages'];?>">

<label>About</label>

<textarea
name="bio"
class="form-control mb-3"><?php echo $mentor['bio'];?></textarea>

<button class="btn btn-success" name="save">
Save Profile
</button>

</form>

</div>

</div>

</body>
</html>