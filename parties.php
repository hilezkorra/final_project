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
$result = mysqli_query($db, "SELECT P.*, U.`name` as host FROM `parties` P
JOIN `users` U ON P.`user_id` = U.`id` WHERE P.`city` = '$city'");

if(isset($_GET['show']) && $_GET['show']==='all'){
    $result = mysqli_query($db, "SELECT P.*, U.`name` as host FROM `parties` P
    JOIN `users` U ON P.`user_id` = U.`id`");
}else if(isset($_GET['show']) && $_GET['show']==='near'){
    $country = $_SESSION['_user']['country'];
    $result = mysqli_query($db, "SELECT P.*, U.`name` as host FROM `parties` P
    JOIN `users` U ON P.`user_id` = U.`id` WHERE P.`country` = '$country'");
}
// Posts: Error Handling
// Datensätze aus dem "Result" herausziehen
$parties = mysqli_fetch_all($result, MYSQLI_ASSOC);

if(isset($_GET['partyid'])){
    $partyid = $_GET['partyid'];
    $res = mysqli_query($db, "SELECT * FROM `parties` WHERE `id` = $partyid");
// Posts: Error Handling
// Datensätze aus dem "Result" herausziehen
    $partoy = mysqli_fetch_all($res, MYSQLI_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Linker - Parties</title>
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
                <li><a href="parties.php?city=<?=$_SESSION['_user']['city']?>"><img src="img/buttons/parties_sel.png" alt="Parties near me" class="navbutton active"></a></li>
                <li><a href="users.php?city=<?=$_SESSION['_user']['city']?>"><img src="img/buttons/users.png" alt="People near me" class="navbutton"></a></li>
                <li><a href="messages.php"><img src="img/buttons/messages.png" alt="Messages" class="navbutton"></a></li>
                <li><a href="notifications.php"><img src="img/buttons/requests.png" alt="Notifications" class="navbutton"></a></li>
                <li><a href="logout.php"><img src="img/buttons/logout.png" alt="Log-out" class="navbutton"></a></li>
            </ul>
    </nav>
    <?php endif ; ?>
</header>
    <div class="wrapper">
        <div  class="column left">  
            <a href="create.php" id="createbutton">Create a party</a>  
            <ul class="filter">
                <li><a href="parties.php?show=all">Show all parties</a></li>
                <li><a href="parties.php?show=near">Show parties near me</a></li>
            </ul>
            
                <?php foreach($parties as $party) :?>
                <div class="overview">
                    <a href="?&partyid=<?=$party['id'] ?>">
                        <img src="<?=$party['avatar']?>" alt="party picture">
                        <p class="title"><?=(mb_strlen($party['name']) < 20) ? $party['name'] : mb_substr($party['name'], 0, 17) . "..."?></p>
                        <p class="location"><?=$party['country'] . ", " . $party['city']?></p>
                        <p class="bio"><?=(mb_strlen($party['description'])<55) ? htmlspecialchars($party['description']) : mb_substr($party['description'],0,52) . "..." ?></p>
                    </a>
                    <p class="host">Hosted by: <a href="users.php?user=<?=$party['user_id']?>"><?=$party['host']?></a></p>
                </div>
                <?php endforeach ; ?>
            
            
        </div>  
        <div  class="column right">  
            <?php if(!isset($_GET['partyid'])) : ?>
            <div class="selected">
                <img src="<?=$_SESSION['_user']['avatar']?>" alt="avatar image" >
                <h4><?=$_SESSION['_user']['name']?></h4>
                <h5><?=$_SESSION['_user']['country'] . ", " . $_SESSION['_user']['city']?></h5>
                <p><?=$_SESSION['_user']['bio']?></p>
            </div>
            <?php else :?>
            <div class="selected">
                <img src="<?=$partoy[0]['avatar']?>" alt="avatar image" >
                <h2><?=$partoy[0]['name']?></h2>
                <h4 class="loc"><?=$party['country'] . ", " . $party['city']?></h4>
                <p class="date">Party date:<?=$partoy[0]['date']?></p>
                <p class="bio"><?=$partoy[0]['description']?></p>
                <?php if($partoy[0]['user_id'] !== $_SESSION['_user']['id']) : ?>
                    <a class="redbutton" href="message.php?message=<?=$partoy[0]['user_id']?>">Message</a>
                    <a class="redbutton" href="party.php?request=<?=$partoy[0]['id']?>">Request</a>
                <?php elseif ($partoy[0]['user_id'] === $_SESSION['_user']['id']) : ?>
                    <a class="redbutton" href="profile.php?action=editparty&partyid=<?=$partoy[0]['user_id']?>">Edit party</a>
                <?php endif; ?>
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
