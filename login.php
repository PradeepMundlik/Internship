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

    session_start();
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
        header('Location: welcome.php');
        exit;
    }

    $DATABASE_HOST = 'localhost';
    $DATABASE_USER = 'pradeep';
    $DATABASE_PASS = '12345678';
    $DATABASE_NAME = 'kaustubha';
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>