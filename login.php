<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Login Page</title>
    <link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php
session_start();
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header('Location: welcome.php');
    exit;
}

$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME_PATIENTS = 'form';
$DATABASE_NAME_DOCTORS = 'form2';

$con_patients = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME_PATIENTS);
$con_doctors = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME_DOCTORS);

if (mysqli_connect_error()) {
    exit('Error connecting to the database: ' . mysqli_connect_error());
}

if (isset($_POST['username'], $_POST['password'], $_POST['role'])) {
    if (empty($_POST['username']) || empty($_POST['password'])) {
        exit('Please fill both the username and password fields!');
    }

    $con = $_POST['role'] === 'doctor' ? $con_doctors : $con_patients;
    $database_name = $_POST['role'] === 'doctor' ? $DATABASE_NAME_DOCTORS : $DATABASE_NAME_PATIENTS;

    $stmt = $con->prepare('SELECT id, password FROM users WHERE username = ? OR email = ?');
    $stmt->bind_param('ss', $_POST['username'], $_POST['username']);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();

        if (password_verify($_POST['password'], $hashed_password)) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $_POST['username'];
            $_SESSION['id'] = $id;
            $_SESSION['role'] = $_POST['role'];

            if ($_POST['role'] === 'doctor') {
                header('Location: welcome_doctor.php');
            } elseif ($_POST['role'] === 'patient') {
                header('Location: welcome_patient.php');
            }

            exit;
        } else {
            echo 'Incorrect password!';
        }
    } else {
        echo 'Incorrect username or email!';
    }

    $stmt->close();
}

$con_patients->close();
$con_doctors->close();
?>
    <div class="register">
        <h1>Login</h1>
        <form action="login.php" method="post">
            <label for="username">Username or Email:</label>
            <input type="text" name="username" id="username" required>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
            <label>Role:</label>
            <label for="role_patient">
                <input type="radio" name="role" id="role_patient" value="patient" required> Patient
            </label>
            <label for="role_doctor">
                <input type="radio" name="role" id="role_doctor" value="doctor" required> Doctor
            </label>
            <input type="submit" value="Login">
            <p>Not yet registered? <a href="register.php">Register</a></p>
        </form>
    </div>
</body>
</html>
