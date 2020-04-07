<?php
session_start();
if(!isset($_SESSION["username"])){
    header("Location: loginpage.php");
}

if(isset($_POST["submit_logout"])) {
    session_destroy();
    setcookie("username", "", time() - 3600, "/");
    setcookie("email", "", time() - 3600, "/");
    setcookie("password", "", time() - 3600, "/");
    echo var_dump($_COOKIE);
    header("Location: loginpage.php");
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
    <h1> This is your home page!!!!!!!!!!!!! </h1>
    <form method="POST" action="homepage.php">
        
        <input type="submit" name="submit_logout" value="LogOut">
        
    </form>
</body>
</html>