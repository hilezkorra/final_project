<?php declare(strict_types=1);

session_start();

if (!isset($_SESSION['_user'])) {
    header('Location: index.php');
    exit();
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$db = mysqli_connect('localhost', 'root', '', 'linker', 3306);

// Error Handling der Connection
if (mysqli_connect_errno()) {
    echo '<div>Es ist ein Fehler aufgetreten: '
        . mysqli_connect_error()
        . '</div>';
}
$user_id = $_SESSION['_user']['id'];

$errors=[];    



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Linker</title>
    <link rel="stylesheet" href="lib/css/nav.css">
       <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script> <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="lib/js/nav.js" type="text/javascript"></script>   
</head>
<body>
<header>
    <?php if (!($_SESSION['_user'])) : ?>
        <nav>
            <ul>
                <li><a href="register.php">REGISTER</a></li>
                <li><a href="login.php">LOGIN</a></li>
            </ul>
        </nav>
    <?php elseif ($_SESSION['_user']) : ?>
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
</header>
    <div  class="column left">  
        <form action="create.php" method="post" enctype="multiform/form-data">
            <label for="name">Title:</label>
            <input type="text" name="name" id="name">
            <label for="date">Date:</label>
            <input type="date" name="date" id="date">
            <label for="description">Description:</label>
            <input type="text" name="description" id="description">
            <label for="avatar">Upload Image</label>
            <input type="file" name="avatar" id="avatar">
        </form>
        
    </div>  
    <div  class="column right">
        <img src="<?=$_SESSION['_user']['avatar']?>" alt="avatar image" >
        <h4><?=$_SESSION['_user']['name']?></h4>
        <h5><?=$_SESSION['_user']['city']?></h5>#
        <p><?=$_SESSION['_user']['bio']?></p>
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
