<?php
// Improved compare_result.php
include("../includes/db.php");
if(!isset($_POST['career1'])||!isset($_POST['career2'])){header("Location: job_comparison.php");exit();}
$career1=$_POST['career1'];$career2=$_POST['career2'];
$q1=mysqli_query($conn,"SELECT * FROM job_comparisons WHERE career_name='$career1'");
$q2=mysqli_query($conn,"SELECT * FROM job_comparisons WHERE career_name='$career2'");
$c1=mysqli_fetch_assoc($q1);$c2=mysqli_fetch_assoc($q2);
if(!$c1||!$c2){die("Comparison data not found.");}
function h($v){return htmlspecialchars((string)$v,ENT_QUOTES,'UTF-8');}
?><!DOCTYPE html><html><head><meta charset="UTF-8">
<title>Career Comparison</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{background:linear-gradient(135deg,#eef5ff,#f8fbff)}
.wrap{max-width:1100px;margin:40px auto}.card{padding:25px;border-radius:18px}
th{background:#0d6efd;color:#fff}.box{border:1px solid #ddd;border-radius:12px;padding:15px;height:100%}
</style></head><body>
<div class="container wrap"><div class="card shadow">
<h2 class="text-center text-primary mb-4">Career Comparison Result</h2>
<table class="table table-bordered table-hover">
<tr><th>Category</th><th><?=h($career1)?></th><th><?=h($career2)?></th></tr>
<tr><td>Salary</td><td>₹<?=h($c1['salary_min'])?> - ₹<?=h($c1['salary_max'])?></td><td>₹<?=h($c2['salary_min'])?> - ₹<?=h($c2['salary_max'])?></td></tr>
<tr><td>Job Demand</td><td><?=h($c1['job_demand'])?>%</td><td><?=h($c2['job_demand'])?>%</td></tr>
<tr><td>Growth</td><td><?=h($c1['growth_rate'])?>%</td><td><?=h($c2['growth_rate'])?>%</td></tr>
</table>
<div class="row">
<div class="col-md-6"><div class="box"><h4><?=h($career1)?></h4><b>Skills</b><p><?=h($c1['skills'])?></p><b>Tools</b><p><?=h($c1['tools'])?></p><b>Description</b><p><?=h($c1['description'])?></p></div></div>
<div class="col-md-6"><div class="box"><h4><?=h($career2)?></h4><b>Skills</b><p><?=h($c2['skills'])?></p><b>Tools</b><p><?=h($c2['tools'])?></p><b>Description</b><p><?=h($c2['description'])?></p></div></div>
</div>
<div class="mt-4 text-center">
<a href="job_comparison.php" class="btn btn-secondary">Compare Again</a>
<a href="dashboard.php" class="btn btn-primary">Back to Dashboard</a>
</div>
</div></div></body></html>