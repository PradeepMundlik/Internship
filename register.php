<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Registration Page</title>
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

if (isset($_POST['username'], $_POST['password'], $_POST['email'], $_POST['role'])) {
    if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email']) || empty($_POST['role'])) {
        exit('Please fill all the fields!');
    }

    $database_name = $_POST['role'] === 'doctor' ? $DATABASE_NAME_DOCTORS : $DATABASE_NAME_PATIENTS;
    $con = $_POST['role'] === 'doctor' ? $con_doctors : $con_patients;

    $stmt = $con->prepare('SELECT id FROM users WHERE username = ? OR email = ?');
    $stmt->bind_param('ss', $_POST['username'], $_POST['email']);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo 'Username or email already exists! Please login <a href="login.php">here</a>.';
    } else {
        $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $con->prepare('INSERT INTO users (username, password, email) VALUES (?, ?, ?)');
        $stmt->bind_param('sss', $_POST['username'], $hashedPassword, $_POST['email']);
        $stmt->execute();
        echo 'Registration successful! Please login <a href="login.php">here</a>.';
    }

    $stmt->close();
}

$con_patients->close();
$con_doctors->close();
?>
    <div class="register">
        <h1>Register</h1>
        <form action="register.php" method="post">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
            <label>Role:</label>
            <label for="role_patient">
                <input type="radio" name="role" id="role_patient" value="patient" required> Patient
            </label>
            <label for="role_doctor">
                <input type="radio" name="role" id="role_doctor" value="doctor" required> Doctor
            </label>
            <input type="submit" value="Register">
            <p>Already registered? <a href="login.php">Login</a></p>
        </form>
    </div>
</body>
</html>
