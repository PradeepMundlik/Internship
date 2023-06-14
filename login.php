
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
$DATABASE_NAME = 'form';
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

if (mysqli_connect_error()) {
    exit('Error connecting to the database: ' . mysqli_connect_error());
}

if (isset($_POST['username'], $_POST['password'])) {
    if (empty($_POST['username']) || empty($_POST['password'])) {
        exit('Please fill both the username and password fields!');
    }

    $stmt = $con->prepare('SELECT id, password FROM users WHERE username = ?');
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();

        if (password_verify($_POST['password'], $hashed_password)) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $_POST['username'];
            $_SESSION['id'] = $id;
            header('Location: welcome.php');
            exit;
        } else {
            echo 'Incorrect password!';
        }
    } else {
        echo 'Incorrect username!';
    }

    $stmt->close();
}

$con->close();
?>
    <div class="register">
        <h1>Login</h1>
        <form action="login.php" method="post">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
            <input type="submit" value="Login">
            <p>Not yet registered? <a href="register.php">Register</a></p>
        </form>
    </div>
</body>
</html>
