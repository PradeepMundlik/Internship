<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once "config.php";

session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'patient') {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username'];
$userid = $_SESSION['id'];

echo $username;
echo $userid;

if (empty($userid) || empty($username)) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['fname'], $_POST['lname'], $_POST['phone'], $_POST['dob'], $_POST['gender'], $_POST['address'])) {

    // Insertion of data
    $stmt = $con->prepare('INSERT INTO `paitient_details`(`userid`, `firstname`, `lastname`, `phone`, `dob`, `gender`, `address`) VALUES (?,?,?,?,?,?,?);');
    $stmt->bind_param('issssss', $userid, $_POST['fname'], $_POST['lname'], $_POST['phone'], $_POST['dob'], $_POST['gender'], $_POST['address']);
    $stmt->execute();

    echo '<div class="alert alert-success" role="alert">Successfully Completed</div>';
    $stmt->close();
    $con->close();

} else if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    echo '<div class="alert alert-danger" role="alert">Fill all the fields</div>';
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Patient Details</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .container {
            max-width: 400px;
            margin: 50px auto;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Complete Your Details</h2>
        <form action="" method="post">
            <div class="form-group">
                <label for="name">First Name:</label>
                <input type="text" class="form-control" name="fname" placeholder="Enter your full name" required>
            </div>
            <div class="form-group">
                <label for="name">Last Name:</label>
                <input type="text" class="form-control" name="lname" placeholder="Enter your full name" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="tel" class="form-control" name="phone" placeholder="Enter your phone number" required>
            </div>
            <div class="form-group">
                <label for="dob">Date of Birth:</label>
                <input type="date" class="form-control" name="dob" required>
            </div>
            <div class="form-group">
                <label for="gender">Gender:</label>
                <select class="form-control" name="gender">
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <textarea class="form-control" name="address" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>