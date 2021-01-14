<?php declare(strict_types=1);


$redirect_after_login = 'parties.php';

$db = mysqli_connect('localhost', 'root', '', 'linker', 3306);
mysqli_set_charset($db, 'utf8mb4');

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

session_start();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $email = mb_strtolower($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';


    if ($email === '') {
        $errors['email'] = "Please provide an email.";
    }

    if ($password === '') {
        $errors['password'] = "Please enter a password.";
    }

    if (!$errors) {
        $email = mysqli_escape_string($db, $email);
        
        $sql = "SELECT * FROM `users` WHERE `email` = '$email'";
        $result = mysqli_query($db, $sql);
        $user = mysqli_fetch_assoc($result);
        mysqli_free_result($result);

        if (!$user) {
            $errors['email'] = "We don't know. Who are you?";
        }
    }

    if (!$errors) {

        if (!password_verify($password, $user['password'])) {
            $errors['email'] = "Your login data seems to be incorrect.";
        }
    }

    if (!$errors) {
        
        $_SESSION['_user'] = $user;
        
        header("Location: $redirect_after_login");
        exit();
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta email="viewport" content="width=device-width, initial-scale=1.0">
    <title>Linker</title>
    <link rel="stylesheet" href="lib/css/main.css">
    <link rel="stylesheet" href="lib/css/index.css">
       <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script> <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
<header>
      <h1>Linker Socialising App</h1>
</header>
        <nav id="indexnav">
            <img src="img/logo.png" alt="Linker GmbH Logo" id="mainlogo">
            <ul>    
                <li><a href="register.php" class="redbutton"  style="text-align:center;">REGISTER</a></li>
                <li><a href="login.php" class="redbutton"  style="text-align:center;">LOGIN</a></li>
            </ul>
        </nav>
    <div id="main">
        <label for="login"><h2>Login</h2></label>
        <form action="login.php" method="POST" name="login" id="login">
            <div class="">
                <?php if (isset($errors['email'])) : ?>
                    <div class="alert"><?= $errors['email'] ?></div>
                <?php endif; ?>
                <label for="email">Email</label>
                <input type="text" name="email" id="email">
            </div>
            <div class="">
                <?php if (isset($errors['password'])) : ?>
                    <div class="alert"><?= $errors['password'] ?></div>
                <?php endif; ?>
                <label for="password">Password</label>
                <input type="text" name="password" id="password">
            </div>
            <div class="">
                <button type="submit">Login</button>
            </div>
            <p>Don't have an account? Then go to the <a href="register.php">registration page.</a></p>
        </form>
        </div>
    <footer>
        <ul>
            <li><a href="privacypolicy.php">Privacy Policy</a></li>
            <li><a href="agb.php">AGB</a></li>
        </ul>
        <p>
            Linker Version 0.001
        </p>
    </footer>
</body>
</html>
