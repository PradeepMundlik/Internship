<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

    <link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>
    <?php

    error_reporting(E_ALL);
    ini_set("display_errors", 1);

    if($_SERVER["REQUEST_METHOD"]=='GET' && isset($_GET['alert'])){
        $alert=$_GET['alert'];
        echo $_GET['alert'];
    }

    session_start();
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
        if ($_SESSION['role'] == 'doctor') header('Location: welcome_doctor.php?alert='.$alert);
        else if ($_SESSION['role'] == 'patient') header('Location: welcome_patient.php?alert='.$alert);
        exit;
    }

    

    $DATABASE_HOST = 'localhost';
    $DATABASE_USER = 'pradeep';
    $DATABASE_PASS = '12345678';
    $DATABASE_NAME = 'kaustubha';
    // $DATABASE_NAME_DOCTORS = 'form2';

    $con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
    // $con_doctors = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME_DOCTORS);

    if (mysqli_connect_error()) {
        exit('Error connecting to the database: ' . mysqli_connect_error());
    }

    if (isset($_POST['username'], $_POST['password'], $_POST['role'])) {
        if (empty($_POST['username']) || empty($_POST['password'])) {
            exit('<div class="alert alert-warning" role="alert">Please fill both the username and password fields!</div>');
        }

        // $con = $_POST['role'] === 'doctor' ? $con_doctors : $con_patients;
        // $database_name = $_POST['role'] === 'doctor' ? $DATABASE_NAME_DOCTORS : $DATABASE_NAME_PATIENTS;

        if ($_POST['role'] == 'doctor') {
            $query = 'SELECT id, password FROM doctors WHERE username = ? OR email = ?';
        }
        if ($_POST['role'] == 'patient') {
            $query = 'SELECT id, password FROM patients WHERE username = ? OR email = ?';
        }
        $stmt = $con->prepare($query);
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

                if ($_POST['role'] == 'doctor') {
                    header('Location: welcome_doctor.php');
                } else if ($_POST['role'] == 'patient') {
                    header('Location: welcome_patient.php');
                }

                exit;
            } else {
                echo '<div class="alert alert-danger" role="alert">Incorrect Password!</div>';
            }
        } else {
            echo '<div class="alert alert-danger" role="alert">Incorrect Username or Email!</div>';
        }

        $stmt->close();
    }

    $con->close();
    // $con_doctors->close();
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
            <p>Forget Password ? <a href="password_recovery.php">Click here</a> <br>
                Not yet registered? <a href="register.php">Register</a></p>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

</body>

</html>