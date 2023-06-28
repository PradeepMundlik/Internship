<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Welcome Patient</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link href="style.css" rel="stylesheet" type="text/css">

</head>

<body>
    <?php

    error_reporting(E_ALL);
    ini_set("display_errors", 1);

    session_start();
    if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'patient') {
        header('Location: login.php');
        exit;
    }

    $username = $_SESSION['username'];

    if($_SERVER["REQUEST_METHOD"]=='GET' && isset($_GET['alert'])){
        echo $_GET['alert'];
    }
    ?>
    
    <div class="dashboard">
        <h1>Welcome, <?php echo $username; ?>!</h1>
        <h2>Patient Dashboard</h2>
        <ul>
            <li><a href="appointments.php">Appointments</a></li>
            <li><a href="doctors.php">Doctors</a></li>
            <li><a href="medical_records.php">Medical Records</a></li>
            <li><a href="patient_details.php">Complete Your Details</a></li>
            <li><a href="change_password.php?email=<?php echo $username; ?>&role=patient">Click Here to change password</a></li>
            <!-- Add more patient-specific features here -->
        </ul>
        <a href="logout.php">Logout</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>