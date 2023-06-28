<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once "config.php";

if ($_SERVER['REQUEST_METHOD'] == 'GET' && (!isset($_GET['email']) || empty($_GET['email'])) && (!isset($_GET['role']) || empty($_GET['role']))) {
    echo '<div class="alert alert-warning" role="alert">Please fill email id</div>';
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    if ($_GET['role'] == 'doctor') {
        $query = 'SELECT email, username from doctors where email=? or username=?';
    } else {
        $query = 'SELECT email, username from patients where email=? or username=?';
    }
    $stmt = $con->prepare($query);
    $stmt->bind_param('ss', $_GET['email'], $_GET['email']);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows <= 0) {
        echo '<div class="alert alert-danger" role="alert">This email id is not registered. Please <a href="patient_details.php">Click here</a> to return</div>';
        exit();
    } else {
        $stmt->bind_result($to_email, $username);
        $stmt->fetch();

        $stmt = $con->prepare("DELETE FROM recovery WHERE (email=? or username=?) and role=?");
        $stmt->bind_param('sss', $_GET['email'], $_GET['email'], $GET['role']);
        $stmt->execute();

        $query = 'INSERT INTO `recovery`(`role`, `token`, `username`, `email`, `time`) VALUES (?,?,?,?,?);';
        $stmt = $con->prepare($query);
        $token = bin2hex(random_bytes(32));
        $role = $_GET['role'];
        $time = date("U");
        $stmt->bind_param('ssssi', $role, $token, $username, $to_email, $time);
        $stmt->execute();
        $stmt->store_result();

        $stmt->close();
        $con->close();
        $from_email = 'ai21btech11022@iith.ac.in';
        $subject = 'Password Recovery';
        $url = "http://localhost/Intern/reset_password.php?token=" . $token;
        $msg = 'Password Reset link: ' . $url . ' >Note that this link is valid only for 10 mins';

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
            if($_GET['role']=='doctor'){
                $alert = '<div class="alert alert-success" role="alert">Recovery mail sent successfully <a href="https://mail.google.com/">Click here</a> to check mail</div>';
                header("Location: http://localhost/Intern/welcome_doctor.php?alert=$alert");
            }
            if($_GET['role']=='patient'){
                $alert = '<div class="alert alert-success" role="alert">Recovery mail sent successfully <a href="https://mail.google.com/">Click here</a> to check mail</div>';
                header("Location: http://localhost/Intern/welcome_patient.php?alert=$alert");
            }
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
?>