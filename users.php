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
$city = $_SESSION['_user']['city'];

$errors=[];    

// Anfrage an die DB senden
$result = mysqli_query($db, "SELECT * FROM `users` WHERE `city` = '$city'");

if(isset($_GET['show']) && $_GET['show']==='all'){
    $result = mysqli_query($db, "SELECT * FROM `users`");
}else if(isset($_GET['show']) && $_GET['show']==='near'){
    $country = $_SESSION['_user']['country'];
    $result = mysqli_query($db, "SELECT * FROM `users` WHERE `country` = '$country'");
}
// Posts: Error Handling
// Datensätze aus dem "Result" herausziehen
$users = mysqli_fetch_all($result, MYSQLI_ASSOC);

if(isset($_GET['userid'])){
    $userid = $_GET['userid'];
    $res = mysqli_query($db, "SELECT * FROM `users` WHERE `id` = $userid");
// Posts: Error Handling
// Datensätze aus dem "Result" herausziehen
    $userr = mysqli_fetch_all($res, MYSQLI_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Linker - Users</title>
    <link rel="stylesheet" href="lib/css/nav.css">
    <link rel="stylesheet" href="lib/css/main.css">
       <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script> <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="lib/js/nav.js" type="text/javascript"></script>   
</head>
<body>
<header>
    <?php if (!($_SESSION['_user'])) : ?>
        <nav>
            <ul id="indexnav">
            <li><img src="img/logo.png" alt="Linker GmbH Logo"></li>
                <li><a href="register.php" class="redbutton"  style="text-align:center;">REGISTER</a></li>
                <li><a href="login.php" class="redbutton"  style="text-align:center;">LOGIN</a></li>
            </ul>
        </nav>
    <?php elseif ($_SESSION['_user']) : ?>
    <a href="profile.php"><img src="<?=$_SESSION['_user']['avatar'] ?>" alt="Your avatar" class="navavatar"></a>
    <nav>
            <ul id="mainnav">
                <li><a href="parties.php?city=<?=$_SESSION['_user']['city']?>"><img src="img/buttons/parties.png" alt="Parties near me" class="navbutton"></a></li>
                <li><a href="users.php?city=<?=$_SESSION['_user']['city']?>"><img src="img/buttons/users_sel.png" alt="People near me" class="navbutton active"></a></li>
                <li><a href="messages.php"><img src="img/buttons/messages.png" alt="Messages" class="navbutton"></a></li>
                <li><a href="notifications.php"><img src="img/buttons/requests.png" alt="Notifications" class="navbutton"></a></li>
                <li><a href="logout.php"><img src="img/buttons/logout.png" alt="Log-out" class="navbutton"></a></li>
            </ul>
    </nav>
    <?php endif ; ?>
</header>
    <div class="wrapper">
        <div  class="column left">  
            <ul class="filter">
                <li><a href="users.php?show=all">Show all users</a></li>
                <li><a href="users.php?show=near">Show users near me</a></li>
            </ul>
            
                <?php foreach($users as $user) :?>
                <div class="overview">
                    <a href="?&userid=<?=$user['id'] ?>">
                        <img src="<?=$user['avatar']?>" alt="user picture">
                        <p class="title"><?=(mb_strlen($user['name']) < 20) ? $user['name'] : mb_substr($user['name'], 0, 17) . "..."?></p>
                        <?php if (isset($user['bio'])) : ?>
                        <p class="bio"><?=(mb_strlen($user['bio'])<55) ? htmlspecialchars($user['bio']) : mb_substr($user['bio'],0,52) . "..." ?></p>
                        <?php else : ?>
                        <p class="nobio">*No bio available*</p>    
                        <?php endif; ?>    
                    </a>
                </div>
                <?php endforeach ; ?>
            
            
        </div>  
        <div  class="column right">  
            <?php if(!isset($_GET['userid'])) : ?>
            <div class="selected">
                <img src="<?=$_SESSION['_user']['avatar']?>" alt="avatar image" >
                <h4><?=$_SESSION['_user']['name']?></h4>
                <h5><?=$_SESSION['_user']['country'] . ", " . $_SESSION['_user']['city']?></h5>
                <p><?=$_SESSION['_user']['bio']?></p>
            </div>
            <?php else :?>
            <div class="selected">   
                <img src="<?=$userr[0]['avatar']?>" alt="avatar image" >
                <h4><?=$userr[0]['name']?></h4>
                <h5><?=$userr[0]['country'] . ", " . $userr[0]['city']?></h5>
                <p class="bio"><?=$userr[0]['bio']?></p>
                <a class="redbutton" href="messages.php?user=<?=$userr[0]['id']?>">Message</a>
                <a class="redbutton" href="users.php?invite=<?=$userr[0]['party_id']?>">Invite</a>
            </div> 
            <?php endif ;?>
        </div>
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