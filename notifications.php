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


$errors=[];   
$user_id = $_SESSION['_user']['id'] ?? "";


// Anfrage an die DB senden
if(!isset($_GET['show'])){
    $result = mysqli_query($db, "SELECT * FROM `requests` WHERE `sender_id` = '$user_id' OR `reciever_id` = '$user_id' ORDER BY `sent_at`");
    $requests = mysqli_fetch_all($result, MYSQLI_ASSOC);
}


//IF FILTER IS CHOSEN

if(isset($_GET['show']) && $_GET['show']==='invites'){
    $result = mysqli_query($db, "SELECT * FROM `requests` WHERE `reciever_id` = '$user_id' ORDER BY `sent_at`");
    $requests = mysqli_fetch_all($result, MYSQLI_ASSOC);


}else if(isset($_GET['show']) && $_GET['show']==='requests'){
    $result = mysqli_query($db,"SELECT * FROM `requests` WHERE `sender_id` = '$user_id' ORDER BY `sent_at`");
    $requests = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

foreach($requests as $r){
    if($r['sender_id'] === $_SESSION['_user']['id']) {   
        $p2_id = $r['reciever_id']; 
        $result = mysqli_query($db,"SELECT `name`, `avatar` FROM `users` WHERE `id` = '$p2_id'");
        $userdata[$p2_id] = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }elseif($r['reciever_id'] === $_SESSION['_user']['id']) {
        $p2_id = $r['sender_id']; 
        $result = mysqli_query($db,"SELECT `name`, `avatar` FROM `users` WHERE `id` = '$p2_id'");
        $userdata[$p2_id] = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    $party_id= $r['party_id'];
    $res = mysqli_query($db,"SELECT `name`, `avatar`, `date` FROM `parties` WHERE `id` = '$party_id'");
    $partydata[$party_id]= mysqli_fetch_all($res, MYSQLI_ASSOC);
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Linker - Notifications</title>
    <link rel="stylesheet" href="lib/css/nav.css">
    <link rel="stylesheet" href="lib/css/main.css">
    <link rel="stylesheet" href="lib/css/notifications.css">
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
                <li><a href="users.php?city=<?=$_SESSION['_user']['city']?>"><img src="img/buttons/users.png" alt="People near me" class="navbutton"></a></li>
                <li><a href="messages.php"><img src="img/buttons/messages.png" alt="Messages" class="navbutton"></a></li>
                <li><a href="notifications.php"><img src="img/buttons/requests_sel.png" alt="Notifications" class="navbutton active"></a></li>
                <li><a href="logout.php"><img src="img/buttons/logout.png" alt="Log-out" class="navbutton"></a></li>
            </ul>
    </nav>
    <?php endif ; ?>
</header>
    <div class="wrapper">
        <div  class="column left" style="background-color: #1675f2bb;">  
            <ul class="filter">
                <li><a href="notifications.php?show=invites">Party invites</a></li>
                <li><a href="notifications.php?show=requests">Party join Requests</a></li>
            </ul>
            <div class="parties notifications">
               <h2 class="subtitle">Invites</h2>
            
                <?php foreach($requests as $r) : ?>
                    
                    <?php $partyid = $r['party_id']; ?>
                    <?php if($r['reciever_id'] === $_SESSION['_user']['id']){ $p2 = $r['sender_id']; }else{ $p2 = $r['reciever_id'];} ?>
                    <div class="notification">
                        <a href="parties.php?partyid=<?=$partyid?>">
                            <img class="navavatar" src="<?= $partydata[$partyid][0]['avatar']?>" class="avatar" alt="">
                        </a>
                        <?php if($r['reciever_id'] === $_SESSION['_user']['id'] and $r['status'] === 'pending') : ?>
                            <a href="users.php?userid=<?=$p2?>">
                                <img class="navavatar" src="<?=$userdata[$p2][0]['avatar']?>" alt="Avatar img">
                            </a>
                            <p class="invite">
                            <a href="users.php?userid=<?=$p2?>"><?=$userdata[$p2][0]['name']?></a> has invited you to the <a href="parties.php?partyid=<?=$partyid?>"><?=$partydata[$partyid][0]['name']?></a>
                                event on <?= $partydata[$partyid][0]['date'] ?> . Do you accept the invite?
                            </p>
                                <a class="redbutton" href="notifications.php?action=accepted&partyid=<?=$partyid?>">Accept</a>
                                <a class="redbutton" href="notifications.php?action=declined&partyid=<?=$partyid?>">Decline</a>
                        <?php elseif($r['reciever_id'] === $_SESSION['_user']['id'] and $r['status'] === 'accepted') : ?>
                            <a href="users.php?userid=<?=$p2?>">
                                <img class="navavatar" src="<?=$userdata[$p2][0]['avatar']?>" alt="Avatar img">
                            </a>
                            <p class="invite">
                            You have <b>accepted</b> the invite from <a href="users.php?userid=<?=$p2?>"><?=$userdata[$p2][0]['name']?></a> for the <a href="parties.php?partyid=<?=$partyid?>"><?=$partydata[$partyid][0]['name']?></a>
                                event on <?= $partydata[$partyid][0]['date'] ?>.
                            </p>  
                        <?php elseif($r['reciever_id'] === $_SESSION['_user']['id'] and $r['status'] === 'declined') : ?>
                            <a href="users.php?userid=<?=$p2?>">
                                <img class="navavatar" src="<?=$userdata[$p2][0]['avatar']?>" alt="Avatar img">
                            </a>
                            <p class="invite">
                            You have <b>declined</b> the invite from <a href="users.php?userid=<?=$p2?>"><?=$userdata[$p2][0]['name']?></a> for the <a href="parties.php?partyid=<?=$partyid?>"><?=$partydata[$partyid][0]['name']?></a>
                                event on <?= $partydata[$partyid][0]['date'] ?>.
                            </p>       
                        <?php elseif($r['sender_id'] === $_SESSION['_user']['id'] and $r['status'] === 'accepted') : ?>
                            <a href="users.php?userid=<?=$p2?>">
                                <img class="navavatar" src="<?=$userdata[$p2][0]['avatar']?>" alt="Avatar img">
                            </a>
                            <p class="accepted">
                                <a href="users.php?userid=<?=$p2?>"><?=$userdata[$p2][0]['name']?></a> has 
                                <b>accepted</b> your invitation to <a href="parties.php?partyid<?=$partyid?>">the 
                                <?=$partydata[$partyid][0]['name']?></a> on the 
                                <?=$partydata[$partyid][0]['date']?>.
                            </p>
                        <?php elseif($r['sender_id'] === $_SESSION['_user']['id'] and $r['status'] === 'declined') : ?>
                            <a href="users.php?userid=<?=$p2?>">
                                <img class="navavatar" src="<?=$userdata[$p2][0]['avatar']?>" alt="Avatar img">
                            </a>
                            <p class="declined">
                                <a href="users.php?userid=<?=$p2?>"><?=$userdata[$p2][0]['name']?></a> has <b>declined</b> your invitation to 
                                <a href="parties.php?partyid<?=$partyid?>">the <?=$partydata[$partyid][0]['name']?>
                                </a> on the <?=$partydata[$partyid][0]['date']?>.
                            </p>
                            <a class="redbutton" href="users.php?userid=<?=$p2?>">Invite again</a>
                        <?php elseif($r['sender_id'] === $_SESSION['_user']['id'] and $r['status'] === 'pending') : ?>
                            <a href="users.php?userid=<?=$p2?>">
                                <img class="navavatar" src="<?=$userdata[$p2][0]['avatar']?>" alt="Avatar img">
                            </a>
                            <p class="pending">
                                You have invited <a href="users.php?userid=<?=$userdata[$p2][0]['id']?>"><?=$userdata[$p2][0]['name']?></a> to <thead>
                                <a href="parties.php?partyid<?=$partyid?>">the <?=$partydata[$partyid][0]['name']?>
                                </a> on the <?=$partydata[$partyid][0]['date']?>.
                            </p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
            
        </div>  
        <div  class="column right">  
            <div class="selected">
                <img src="<?=$_SESSION['_user']['avatar']?>" alt="avatar image" >
                <h4><?=$_SESSION['_user']['name']?></h4>
                <h5><?=$_SESSION['_user']['country'] . ", " . $_SESSION['_user']['city']?></h5>
                <p><?=$_SESSION['_user']['bio']?></p>
            </div>

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