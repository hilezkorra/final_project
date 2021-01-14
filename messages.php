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
$userid = $_SESSION['_user']['id'];

function exists_in_array($string, $array){
    for($int=0 ; $int < count($array); $int++){
        if ($array[$int] === $string){
            return true;
        }
    }
    return false;
}

$errors=[]; 
$check = []; 
$i=0;  
$action = $_GET['action'] ?? '';

// Anfrage an die DB senden
$result = mysqli_query($db, "SELECT * FROM `messages` WHERE `reciever_id` = '$userid' OR `sender_id` = '$userid' ORDER BY `sent_at` DESC");
$messages = mysqli_fetch_all($result, MYSQLI_ASSOC);

if(isset($_GET['user'])){
    $p2_id=$_GET['user'] ?? '';
    $res = mysqli_query($db, "SELECT * FROM `messages` WHERE `reciever_id` = '$userid' AND `sender_id` = '$p2_id' OR `sender_id` = '$userid' AND `reciever_id` = '$p2_id' ORDER BY `sent_at`");
    $conversation = mysqli_fetch_all($res, MYSQLI_ASSOC);
    $re = mysqli_query($db, "SELECT * FROM `users` WHERE `id` = '$p2_id'");
    $p2_data = mysqli_fetch_all($re, MYSQLI_ASSOC);
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if($action === 'send'){
            $message = htmlspecialchars($_POST['chat']) ?? '';
            if(trim($message) === ""){
                $errors['messages'] += "The message is empty." ;
            }
            $sender_id = $_SESSION['_user']['id'] ?? '';
            $sender_name = $_SESSION['_user']['name'] ?? '';
            $sender_avatar = $_SESSION['_user']['avatar'] ?? '';
            $reciever_id = $p2_data[0]['id'] ?? '';
            $reciever_name = $p2_data[0]['name'] ?? '';
            $reciever_avatar = $p2_data[0]['avatar'] ?? '';

            if (!$errors) {
                $sql =  "INSERT INTO `messages` (`sender_name`, `sender_avatar`, `sender_id`, `reciever_name`, `reciever_avatar`, `reciever_id`, `message`) ";
                $sql .= "VALUES ('$sender_name', '$sender_avatar', '$sender_id', '$reciever_name', '$reciever_avatar', '$reciever_id', '$message')";
                $success = mysqli_query($db, $sql);
                $res = mysqli_query($db, "SELECT * FROM `messages` WHERE `reciever_id` = '$userid' AND `sender_id` = '$p2_id' OR `sender_id` = '$userid' AND `reciever_id` = '$p2_id' ORDER BY `sent_at`");
                $conversation = mysqli_fetch_all($res, MYSQLI_ASSOC);
            }
        }
        
    }
    if($action === 'delete'){ 
            $p2_id = $_GET['userid'] ?? ''; 
            //DELETES A CONVERSATION WITH THE SELECTED USER USER
            $sql = "DELETE  * FROM `messages` WHERE `reciever_id` = '$userid' AND `sender_id` = '$p2_id' OR `sender_id` = '$userid' AND `reciever_id` = '$p2_id'";
            $success = mysqli_query($db, $sql);
            //CALLS UP THE NEW DATA SO THAT THE DELETED USERS MESSAGES NO LONGER SHOW UP
            $result = mysqli_query($db, "SELECT * FROM `messages` WHERE `reciever_id` = '$userid' OR `sender_id` = '$userid' ORDER BY `sent_at` DESC");
            $messages = mysqli_fetch_all($result, MYSQLI_ASSOC);
        }
        
}
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Linker - Messages</title>
    <link rel="stylesheet" href="lib/css/nav.css">
    <link rel="stylesheet" href="lib/css/main.css">
    <link rel="stylesheet" href="lib/css/messages.css">
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
                <li><a href="messages.php"><img src="img/buttons/messages_sel.png" alt="Messages" class="navbutton active"></a></li>
                <li><a href="notifications.php"><img src="img/buttons/requests.png" alt="Notifications" class="navbutton"></a></li>
                <li><a href="logout.php"><img src="img/buttons/logout.png" alt="Log-out" class="navbutton"></a></li>
            </ul>
    </nav>
    <?php endif ; ?>
</header>
<div class="wrapper">
    <div class="left">  
        <?php foreach($messages as $m) : ?>
            <?php ($_SESSION['_user']['id'] === $m['reciever_id']) ? $p2 = "sender_" : $p2 = "reciever_" ?>
            <?php if (!isset($check[$m[$p2 . 'id']]) or $check[$m[$p2 . 'id']]<$m['id']) :  ?>
                       <?php $check[$m[$p2 . 'id']] = [$m['id']]; ?>

            
                <div class="messageblock">
                <a class="selectmessage" href="messages.php?user=<?=$m[$p2 . 'id']?>">
                    <img class="smallavatar" src="<?= $m[$p2 . 'avatar'] ?>" alt="<?=$m[$p2 . 'name']?>'s avatar'" />
                    <h5 class="name"><?=$m[$p2 . 'name']?></h5>
                    <p class="lastmessage"><?=$m["message"]?></p>
                </a>
                <a href="messages.php?action=delete&userid=<?=$m[$p2 . 'id']?>">
                   <img src="img/buttons/delete.png" class="navbutton deleteall" alt="Delete all messages from <?=$m[$p2 . 'name']?>"/>
                </a>
                </div>
            
            <?php endif; ?>
            
        <?php endforeach; ?>  
    </div>    
    <div class="right">
    <?php if(isset($conversation[0]['sender_name'])) : ?>    
        <?php ($_SESSION['_user']['id'] === $conversation[0]['reciever_id']) ? $per2 = "sender_" : $per2 = "reciever_" ?>
        <h2 class="profile"><a href="users.php?user=<?=$p2_id?>"><img src="<?=$conversation[0][$per2 . 'avatar']?>" alt="<?=$conversation[0][$per2 . 'name']?>'s avatar"><?=$conversation[0][$per2 . 'name']?></a></h2>  
        <?php foreach($conversation as $c) : ?>
                <div class="conversation <?= ($p2_id === $c['sender_id']) ? 'p2' : 'p1'; ?>">
                    <img src="<?= $c['sender_avatar'] ?>" alt="<?=$c['sender_name']?>'s avatar'">
                    <h5 class="name"><?=$c['sender_name']?></h5>
                    <p class="message"><?=$c["message"]?></p>
                    <p class="sent-at">Sent at: <?=$c['sent_at']?></p>
                </div>
        <?php endforeach; ?>  
        <form action="messages.php?action=send&user=<?=$p2_id?>" method="post">
            <label for="chat"  id="chatlabel">Write a message:</label>
            <textarea name="chat" id="chat" cols="100" rows="3"></textarea>
            <input type="image" src="img/buttons/send.png" alt="send message" class="navbutton sendmessage">
        </form>
    <?php elseif(isset($p2_id))   : ?>
        <h2 class="profile"><a href="users.php?user=<?=$p2_id?>"><img src="<?=$p2_data[0]['avatar']?>" alt="<?=$p2_data[0]['name']?>'s avatar"><?=$p2_data[0]['name']?></a></h2>
        <form action="messages.php?action=send&user=<?=$p2_id?>" method="post">
            <label for="chat" id="chatlabel">Write a message:</label>
            <textarea name="chat" id="chat" cols="100" rows="3"></textarea>
            <input type="image" src="img/buttons/send.png" alt="send message" class="navbutton sendmessage">
        </form>
    <?php else : ?>    
    <?php endif ; ?>
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