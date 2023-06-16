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

    if (isset($_POST['username'], $_POST['password'], $_POST['email'])) {
        if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email'])) {
            exit('Please fill all the fields!');
        }

        $stmt = $con->prepare('SELECT id FROM users WHERE username = ?');
        $stmt->bind_param('s', $_POST['username']);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo 'Username already exists! Please login <a href="login.php">here</a>.';
        } else {
            $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $stmt = $con->prepare('INSERT INTO users (username, password, email) VALUES (?, ?, ?)');
            $stmt->bind_param('sss', $_POST['username'], $hashedPassword, $_POST['email']);
            $stmt->execute();
            echo '<div class="alert alert-success" role="alert">Registration successful! Please login <a href="login.php">here</a></div>';
        }

        $stmt->close();
    }

    $con->close();
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
            <input type="submit" value="Register">
            <p>Already registered? <a href="login.php">Login</a></p>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>