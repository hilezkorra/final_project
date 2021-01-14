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
$id = $_SESSION['_user']['id'];
$errors=[];    
$action= $_GET['action'] ?? '';
// Anfrage an die DB senden
$result = mysqli_query($db, "SELECT * FROM `parties` WHERE `user_id` = '$id'");
$parties = mysqli_fetch_all($result, MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if($action ==='editprofile'){
        $name = $_POST['name'] ?? '';
        $bio = $_POST['bio'] ?? '';
        $password = $_POST['password'] ?? '';
        $country = $_POST['countries-selector'] ?? '';
        $city = $_POST['cities-selector'] ?? '';

        $sql = "SELECT `name`, `bio`, `country`, `city` FROM `users` WHERE `id` = '$id'";
        $result = mysqli_query($db, $sql);
        $user = mysqli_fetch_assoc($result);
        mysqli_free_result($result);
        
        if (!password_verify($password, $_SESSION['_user']['password'])) {
            $errors['password'] = "Incorrect password.";
        }
        if (trim($name) === '') {
            $errors['username'] = "Please provide a username.";
        }
        if (trim($city) === '') {
            $errors['city'] = "Please select a city.";
        }
        if (trim($country) === '') {
            $errors['country'] = "Please select a country.";
        }
        if(!$errors){
            $sql = "UPDATE `users` SET `name` = '$name', `city` = '$city', `country` = '$country', `bio` = '$bio'  WHERE `id` = '$id'";
            mysqli_query($db,$sql);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Linker - Profile</title>
    <link rel="stylesheet" href="lib/css/nav.css">
    <link rel="stylesheet" href="lib/css/main.css">
    <link rel="stylesheet" href="lib/css/select.css">
       <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script> 
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="lib/js/register.js" type="text/javascript"></script>
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
    <nav class="buttons">
        <a class="redbutton" href="profile.php?action=editprofile">Edit my profile</a>
        <a class="redbutton" href="profile.php?action=deleteprofile">Delete my profile</a>
    </nav>
    <div class="wrapper">
        <div  class="column left" style="background-color: #1675f2bb;">  
           
            <div class="parties">
                <h2 class="subtitle">My Parties</h2>
                <a class="redbutton" href="profile.php?action=createparty">Create a party</a>
                <?php foreach($parties as $p) :?>
                    <div class="party">
                        <img class="partyimg" src="<?= $p['avatar']?>" alt="party thumbnail">
                        <h4><a href="parties.php?partyid=<?=$p['id']?>"><?= $p['name'] ?></a></h4>
                        <p><?=$p['description']?></p>
                        <a href="profile.php?action=deleteparty&partyid=<?=$p['id']?>"><img class="navbutton" src="img/buttons/delete.png" alt="delete party"></a>
                        <a href="profile.php?action=editparty&partyid=<?=$p['id']?>"><img class="navbutton" src="img/buttons/edit.png" alt="edit party"></a>
                    </div>
                <?php endforeach; ?>    
            </div>
        </div>  
        <div  class="column right">  
        <?php if(!isset($_GET['action'])) : ?>
            <div class="selected">
                <img src="<?=$_SESSION['_user']['avatar']?>" alt="avatar image" >
                <h4><?=$_SESSION['_user']['name']?></h4>
                <h5><?=$_SESSION['_user']['country'] . ", " . $_SESSION['_user']['city']?></h5>
                <p><?=$_SESSION['_user']['bio']?></p>
            </div>
        <?php elseif ($_GET['action'] === 'createparty'):?>
            <h2 class="">Create a party</h2>
            <form action="profile.php?action=createparty" method="post" class="editprofile">
                <div class="name">
                    <?php if (isset($errors['name'])) : ?>
                        <div class="alert"><?= $errors['name'] ?></div>
                    <?php endif; ?>
                    <label for="name">Party name</label>
                    <input type="text" name="name" id="name" placeholder="The Party">
                </div>
                <div class="description"> 
                    <?php if (isset($errors['description'])) : ?>
                        <div class="alert"><?= $errors['description'] ?></div>
                    <?php endif; ?>
                    <label for="description">Description:</label>
                    <textarea col="30" row="30" name="description" id="description"">
                    Party description...
                    </textarea>
                </div> 
                <div class="date"> 
                    <?php if (isset($errors['date'])) : ?>
                        <div class="alert"><?= $errors['date'] ?></div>
                    <?php endif; ?>
                    <label for="date">Description:</label>
                    <input type="date" name="date" id="date">   
                </div> 
                <div class="country">
                    <?php if (isset($errors['country'])) : ?>
                    <div class="alert"><?= $errors['country'] ?></div>
                    <?php endif; ?>  
                    <label for="countries-selector">Select your country</label>
                    <select name="countries-selector" class="select-css" id="countries-selector">
                        <option value="">Pick a country</option>
                    </select>
                </div>
                <div id="city">  
                    <?php if (isset($errors['city'])) : ?>
                        <div class="alert"><?= $errors['city'] ?></div>
                    <?php endif; ?>          
                    <label for="cities-selector">Select your city</label>
                    <select name="cities-selector" class="select-css" id="cities-selector"">
                        <option value="">Pick a city</option>
                    </select>
                </div>
                <div class="">
                    <button class="redbutton" type="submit">Create Party!</button>
                </div>
            </form>     
        <?php elseif ($_GET['action'] === 'editprofile'):?>
            <h2>Edit profile</h2>
            <form action="profile.php?action=editprofile" method="post" class="editprofile">
                <div class="name">
                    <?php if (isset($errors['name'])) : ?>
                        <div class="alert"><?= $errors['name'] ?></div>
                    <?php endif; ?>
                    <label for="name">Username</label>
                    <input type="text" name="name" id="name" value="<?=$_SESSION['_user']['name']?>">
                </div>
                <div class="bio"> 
                    <?php if (isset($errors['bio'])) : ?>
                        <div class="alert"><?= $errors['bio'] ?></div>
                    <?php endif; ?>
                    <label for="bio">Bio:</label>
                    <textarea col="30" row="30" name="bio" id="bio" value="<?=$_SESSION['_user']['bio']?>">
                    <?= trim($_SESSION['_user']['bio']) ?? 'Write a bio...' ?>
                    </textarea>
                </div> 
                <div class="country">
                    <?php if (isset($errors['country'])) : ?>
                    <div class="alert"><?= $errors['country'] ?></div>
                    <?php endif; ?>  
                    <label for="countries-selector">Select your country</label>
                    <select name="countries-selector" class="select-css" id="countries-selector" >
                        <option value="<?=$_SESSION['_user']['country']?>"><?=$_SESSION['_user']['country']?></option>
                    </select>
                </div>
                <div id="city">  
                    <?php if (isset($errors['city'])) : ?>
                        <div class="alert"><?= $errors['city'] ?></div>
                    <?php endif; ?>          
                    <label for="cities-selector">Select your city</label>
                    <select name="cities-selector" class="select-css" id="cities-selector" >
                        <option value="<?=$_SESSION['_user']['city']?>"><?=$_SESSION['_user']['city']?></option>
                    </select>
                </div>
                <div class="password">
                <?php if (isset($errors['password'])) : ?>
                        <div class="alert"><?= $errors['password'] ?></div>
                    <?php endif; ?>    
                    <label for="password">Please enter password in order to confirm changes</label>
                    <input type="password" name="password" id="password">
                </div>
                <div class="">
                    <button class="redbutton" type="submit">Confirm changes</button>
                </div>
            </form>   
        <?php elseif ($_GET['action'] === 'editparty'):?> 
            <h2 class="">Edit party</h2>
            <form action="profile.php?action=createparty" method="post" class="editprofile">
                <div class="name">
                    <?php if (isset($errors['name'])) : ?>
                        <div class="alert"><?= $errors['name'] ?></div>
                    <?php endif; ?>
                    <label for="name">Party name</label>
                    <input type="text" name="name" id="name" placeholder="The Party">
                </div>
                <div class="description"> 
                    <?php if (isset($errors['description'])) : ?>
                        <div class="alert"><?= $errors['description'] ?></div>
                    <?php endif; ?>
                    <label for="description">Description:</label>
                    <textarea col="30" row="30" name="description" id="description"">
                    Party description...
                    </textarea>
                </div> 
                <div class="date"> 
                    <?php if (isset($errors['date'])) : ?>
                        <div class="alert"><?= $errors['date'] ?></div>
                    <?php endif; ?>
                    <label for="date">Description:</label>
                    <input type="date" name="date" id="date">   
                </div> 
                <div class="country">
                    <?php if (isset($errors['country'])) : ?>
                    <div class="alert"><?= $errors['country'] ?></div>
                    <?php endif; ?>  
                    <label for="countries-selector">Select your country</label>
                    <select name="countries-selector" class="select-css" id="countries-selector">
                        <option value="">Pick a country</option>
                    </select>
                </div>
                <div id="city">  
                    <?php if (isset($errors['city'])) : ?>
                        <div class="alert"><?= $errors['city'] ?></div>
                    <?php endif; ?>          
                    <label for="cities-selector">Select your city</label>
                    <select name="cities-selector" class="select-css" id="cities-selector"">
                        <option value="">Pick a city</option>
                    </select>
                </div>
                <div class="">
                    <button class="redbutton" type="submit">Create Party!</button>
                </div>
            </form>     
        <?php endif; ?>
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
