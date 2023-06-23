<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once 'config.php';

$username=$email="";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    if (isset($_GET['username'], $_GET['email'])) {
        $stmt = $con->prepare("SELECT * FROM doctors WHERE email=? and username=?");
        $stmt->bind_param('ss', $_GET['email'], $_GET['username']);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows() <= 0) {
            $alert = '<div class="alert alert-danger" role="alert">Invalid username or email</div>';
            header("Location: http://localhost/Intern/login.php?alert=$alert");
        }
        $stmt->close();

        $username = $_GET['username'];
        $email = $_GET['email'];

    } else {
        header('Location: password_recovery.php');
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(empty($username) || empty($email)){
        header('Location: password_recovery.php');
    }
    if (isset($_POST['password'], $_POST['cpassword'])) {
        if ($_POST['password'] != $_POST['cpassword']) {
            echo '<div class="alert alert-danger" role="alert">Passwords are not matching</div>';
        } else {
            $stmt = $con->prepare('UPDATE doctors SET password=? WHERE username=? ');
            // $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $hashed_password=$_POST['password'];
            $stmt->bind_param('sss', $hashed_password, $username, $email);
            $flag = $stmt->execute();
            if ($flag) {
                $alert = '<div class="alert alert-success" role="alert">Successfully Changed Passsword</div>';
                header("Location: http://localhost/Intern/login.php?alert=$alert");
            } else {
                echo '<div class="alert alert-danger" role="alert">Error!</div>';
            }
        }
    } else {
        echo '<div class="alert alert-warning" role="alert">Fill both passwords</div>';
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Reset Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

    <link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>

    <div class="register">
        <h1>Reset</h1>
        <form action="reset_password.php" method="post">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>

            <label for="cpassword">Confirmed Password:</label>
            <input type="password" name="cpassword" id="cpassword" required>
            <input type="submit" value="Reset">
            <p><a href="login.php">Click Here to Login</a></p>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

</body>

</html>