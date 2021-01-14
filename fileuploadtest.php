<?php
echo __DIR__.'/lib/upload.bla';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], __DIR__."/img/users/{$_SESSION['_user']['id']}avatar.jpg");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="fileuploadtest.php" method="POST" enctype="multipart/form-data">
    Select image to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload Image" name="submit">
    </form>
</body>
</html>