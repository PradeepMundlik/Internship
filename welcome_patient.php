<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Welcome Patient</title>
    <link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'patient') {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username'];
?>

<div class="dashboard">
    <h1>Welcome, <?php echo $username; ?>!</h1>
    <h2>Patient Dashboard</h2>
    <ul>
        <li><a href="appointments.php">Appointments</a></li>
        <li><a href="doctors.php">Doctors</a></li>
        <li><a href="medical_records.php">Medical Records</a></li>
        <!-- Add more patient-specific features here -->
    </ul>
    <a href="logout.php">Logout</a>
</div>

</body>
</html>
