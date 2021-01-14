<?php declare(strict_types=1);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Linker</title>
    <link rel="stylesheet" href="lib/css/main.css">
    <link rel="stylesheet" href="lib/css/index.css">
   
       <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script> 
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="lib/js/nav.js" type="text/javascript"></script>   
</head>
<body>
<header>
      <h1>Linker Socialising App</h1>
</header>
    <?php if (!isset($_SESSION['_user'])) : ?>
        <nav id="indexnav">
            <img src="img/logo.png" alt="Linker GmbH Logo" id="mainlogo">
            <ul>    
                <li><a href="register.php" class="redbutton"  style="text-align:center;">REGISTER</a></li>
                <li><a href="login.php" class="redbutton"  style="text-align:center;">LOGIN</a></li>
            </ul>
        </nav>
    <?php elseif (isset($_SESSION['_user'])) : ?>
        <a href="profile.php"><img src="<?=$_SESSION['_user']['avatar'] ?>" alt="Your avatar" class="navavatar"></a>
    <nav>
            <ul id="mainnav">
                <li><a href="parties.php?city=<?=$_SESSION['_user']['city']?>"><img src="img/buttons/parties_sel.png" alt="Parties near me" class="navbutton active"></a></li>
                <li><a href="users.php?city=<?=$_SESSION['_user']['city']?>"><img src="img/buttons/users.png" alt="People near me" class="navbutton"></a></li>
                <li><a href="messages.php"><img src="img/buttons/messages.png" alt="Messages" class="navbutton"></a></li>
                <li><a href="notifications.php"><img src="img/buttons/requests.png" alt="Notifications" class="navbutton"></a></li>
                <li><a href="logout.php"><img src="img/buttons/logout.png" alt="Log-out" class="navbutton"></a></li>
            </ul>
    </nav>
    <?php endif ; ?>
    <div id="main">
    <label for="join"><h2><blockquote cite="https://s3.amazonaws.com/ellevate-app-uploads-production/image_uploads/25208/original/2.png?1505278391">"Alone we can do so little, together we can do so much."  – Helen Keller</blockquote></h2></label>
        <a href="register.php" id="join" name="join" class="redbutton" style="text-align:center;"><h3><b>Join the community</b></h3></a>
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
