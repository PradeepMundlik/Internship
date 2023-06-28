<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['password'], $_POST['cpassword'], $_POST['token'])) {
        if ($_POST['password'] != $_POST['cpassword']) {
            echo '<div class="alert alert-danger" role="alert">Passwords are not matching</div>';
        } else {
            $stmt = $con->prepare("SELECT username, email,time,role FROM recovery WHERE token=?");
            $stmt->bind_param('s', $_POST['token']);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows() > 0) {
                $stmt->bind_result($username, $email, $time, $role);
                $stmt->fetch();
                $curr_time = date('U');
                if ($curr_time - $time > 600) {
                    $alert = '<div class="alert alert-danger" role="alert">Link Expired! Please request again</div>';
                    header('Location: http://localhost/Intern/password_recovery.php?alert=' . $alert);
                } else {
                    if ($role == 'doctor') {
                        $query = 'UPDATE doctors SET password=? WHERE username=? or email=?;';
                    } else {
                        $query = 'UPDATE patients SET password=? WHERE username=? or email=?;';
                    }
                    $stmt = $con->prepare($query);
                    $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    $stmt->bind_param('sss', $hashed_password, $username, $email);
                    $flag = $stmt->execute();
                    if ($flag) {

                        $stmt = $con->prepare("DELETE FROM recovery WHERE token=?");
                        $stmt->bind_param('s', $_POST['token']);
                        $stmt->execute();

                        $alert = '<div class="alert alert-success" role="alert">Successfully Changed Passsword</div>';
                        header('Location: http://localhost/Intern/login.php?alert=' . $alert);
                    } else {
                        echo '<div class="alert alert-danger" role="alert">Error!</div>';
                    }
                }
            } else {
                $alert = '<div class="alert alert-danger" role="alert">Invalid Url! Please request again</div>';
                header("Location: password_recovery.php?alert=" . $alert);
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

            <?php
            if ($_SERVER['REQUEST_METHOD'] == 'GET') {

                if (isset($_GET['token'])) {

                    echo '<input type="hidden" name="token" id="token" value="' . $_GET['token'] . '">';
                } else {
                    exit("Set token");
                }
            }
            ?>

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