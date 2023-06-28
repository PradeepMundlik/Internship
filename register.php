<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Registration Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>
    <?php

    error_reporting(E_ALL);
    ini_set("display_errors", 1);

    $DATABASE_HOST = 'localhost';
    $DATABASE_USER = 'pradeep';
    $DATABASE_PASS = '12345678';
    $DATABASE_NAME = 'kaustubha';
    // $DATABASE_NAME_DOCTORS = 'form2';

    $con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

    if (mysqli_connect_error()) {
        exit('Error connecting to the database: ' . mysqli_connect_error());
    }

    if (isset($_POST['username'], $_POST['password'], $_POST['cpassword'], $_POST['email'], $_POST['role'])) {
        if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['cpassword']) || empty($_POST['email']) || empty($_POST['role'])) {
            exit('Please fill all the fields!');
        }

        if ($_POST['password'] != $_POST['cpassword']) {
            echo '<div class="alert alert-warning" role="alert">Passwords are not matching</div>';
        } else {

            if ($_POST['role'] == 'doctor') {
                $query = 'SELECT id, password FROM doctors WHERE username = ? OR email = ?';
            }
            if ($_POST['role'] == 'patient') {
                $query = 'SELECT id, password FROM patients WHERE username = ? OR email = ?';
            }

            $stmt = $con->prepare($query);
            $stmt->bind_param('ss', $_POST['username'], $_POST['email']);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                echo '<div class="alert alert-info" role="alert">Username or email already exists! Please login <a href="login.php">here</a></div>';
            } else {
                $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
                if ($_POST['role'] == 'doctor') {
                    $query = 'INSERT INTO doctors (username, password, email) VALUES (?, ?, ?)';
                }
                if ($_POST['role'] == 'patient') {
                    $query = 'INSERT INTO patients (username, password, email) VALUES (?, ?, ?)';
                }
                $stmt = $con->prepare($query);
                $stmt->bind_param('sss', $_POST['username'], $hashedPassword, $_POST['email']);
                $stmt->execute();
                $stmt->store_result();
                echo '<div class="alert alert-success" role="alert">Registration successful! Please login <a href="login.php">here</a></div>';
            }

            $stmt->close();
        }
    }

    $con->close();
    ?>
    <div class="register">
        <h1>Register</h1>
        <form action="register.php" method="post">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>
            <label for="password">Password:</label>
            <input type="password" name="cpassword" id="cpassword" required>
            <label for="cpassword">Confirm Password:</label>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>