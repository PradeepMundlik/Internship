<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Welcome Doctor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link href="style.css" rel="stylesheet" type="text/css">

</head>

<body>
    <?php

    error_reporting(E_ALL);
    ini_set("display_errors", 1);

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
            <li><a href="doctor_details.php">Complete Your Details</a></li>
            <!-- Add more patient-specific features here -->
        </ul>
        <a href="logout.php">Logout</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>