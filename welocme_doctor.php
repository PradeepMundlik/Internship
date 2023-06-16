<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Welcome Doctor</title>
    <link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'doctor') {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username'];
?>

<div class="dashboard">
    <h1>Welcome, <?php echo $username; ?>!</h1>
    <h2>Doctor Dashboard</h2>
    <ul>
        <li><a href="appointments.php">Appointments</a></li>
        <li><a href="patients.php">Patients</a></li>
    </ul>
    <a href="logout.php">Logout</a>
</div>

</body>
</html>
