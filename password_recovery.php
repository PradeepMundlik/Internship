<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once "config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && (!isset($_POST['email']) || empty($_POST['email']))) {
    echo '<div class="alert alert-warning" role="alert">Please fill email id</div>';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if ($_POST['role'] == 'doctor') {
        $query = 'SELECT email, username from doctors where email=?';
    } else {
        $query = 'SELECT email, username from patients where email=?';
    }
    $stmt = $con->prepare($query);
    $stmt->bind_param('s', $_POST['email']);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows <= 0) {
        echo '<div class="alert alert-danger" role="alert">This email id is not registered. Please <a href="register.php">Click here</a> to register</div>';
    } else {
        $stmt->bind_result($to_email, $username);
        $stmt->fetch();

        $stmt = $con->prepare("DELETE FROM recovery WHERE email=? and role=?");
        $stmt->bind_param('ss', $_POST['email'], $_POST['role']);
        $stmt->execute();

        $query = 'INSERT INTO `recovery`(`role`, `token`, `username`, `email`, `time`) VALUES (?,?,?,?,?);';
        $stmt = $con->prepare($query);
        $token = bin2hex(random_bytes(32));
        $role = $_POST['role'];
        $time = date("U");
        $stmt->bind_param('ssssi', $role, $token, $username, $to_email, $time);
        $stmt->execute();
        $stmt->store_result();
        $from_email = 'ai21btech11022@iith.ac.in';
        $subject = 'Password Recovery';
        $url = "http://localhost/Intern/reset_password.php?token=" . $token;
        $msg = 'Password Recovery link: ' . $url . ' Note that this link is valid only for 10 mins';

        try {
            require_once 'password_recovery_config.php';

            //Recipients
            $mail->setFrom($from_email, 'Kaustubha');
            $mail->addAddress($to_email, $username);     //Add a recipient
            // $mail->addAddress('ellen@example.com');               //Name is optional
            $mail->addReplyTo($from_email, 'Kaustubha');
            // $mail->addCC('cc@example.com');
            // $mail->addBCC('bcc@example.com');

            //Attachments
            // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
            // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $msg;
            // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            $alert = '<div class="alert alert-success" role="alert">Recovery mail sent successfully <a href="https://mail.google.com/">Click here</a> to check mail</div>';
            header("Location: http://localhost/Intern/login.php?alert=$alert");
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Password Recovery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

    <link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == 'GET' && isset($_GET['alert'])) {
        echo $_GET['alert'];
    }
    ?>
    <div class="register">
        <h1>Recover Your Account</h1>
        <form action="password_recovery.php" method="post">
            <label for="email">Enter Email:</label>
            <input type="text" name="email" id="email" required>

            <label>Role:</label>
            <label for="role_patient">
                <input type="radio" name="role" id="role_patient" value="patient" required> Patient
            </label>
            <label for="role_doctor">
                <input type="radio" name="role" id="role_doctor" value="doctor" required> Doctor
            </label>

            <input type="submit" value="Send Mail" class="btn btn-primary">
            <p><a href="login.php">Click Here to Login</a> <br>
                <a href="register.php">Click Here to Register</a>
            </p>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

</body>

</html>