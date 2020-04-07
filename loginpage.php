<?php
session_start();

if(isset($_COOKIE['username']) && isset($_COOKIE['email']) && isset($_COOKIE['password'])) {
    echo "Cookie named is set!";
    $_SESSION["username"] = $_COOKIE['username'];
    $_SESSION["email"] = $_COOKIE['email'];
    $_SESSION["password"] = $_COOKIE['password'];
    header("Location: homepage.php");
}


class TableRows extends RecursiveIteratorIterator {
    function __construct($it) {
        parent::__construct($it, self::LEAVES_ONLY);
    }

}
if(isset($_POST["submit"])) {
    try {
        include_once("connection.php"); 
        // prepare and bind
        $stmt = $conn->prepare("SELECT username, email, userpassword FROM users WHERE username=:username AND email=:email AND userpassword=:userpassword");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':userpassword', $userpassword);
        
        // set parameters and execute
        $username =  $_POST['username'];
        $userpassword =  $_POST['userpassword'];
        $email =  $_POST['email'];
        $stmt->execute();
        //set the resulting array to associative
        $Login_uname = '';
        $Login_email = '';
        $Login_password = '';
        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $count = 1;
        foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$row_value) {
            if ($count == 1){
                $Login_uname = $row_value;
            }
            elseif ($count == 2){
                $Login_email = $row_value;
            }
            elseif ($count == 3){
                $Login_password = $row_value;
            }
            $count++;
        }
        $count = 1;
        echo $Login_uname ."<br>";
        echo $Login_email;
        echo $Login_password;
        if ($Login_email != '' && isset($_POST['remember'])){
            setcookie('username', $Login_uname, time() + (86400 * 30), "/"); // 86400 = 1 day
            setcookie('email', $Login_email, time() + (86400 * 30), "/"); // 86400 = 1 day
            setcookie('password', $Login_password, time() + (86400 * 30), "/"); // 86400 = 1 day
            echo "Is remembered";
            $_SESSION["username"] = $Login_uname;
            $_SESSION["email"] = $Login_email;
            $_SESSION["password"] = $Login_password;
            header("Location: http://localhost/login_reg/homepage.php");
            exit();
        }
        elseif($Login_email != '') {
            echo "Not is remember";
            $_SESSION["username"] = $Login_uname;
            $_SESSION["email"] = $Login_email;
            $_SESSION["password"] = $Login_password;
            header("Location: http://localhost/login_reg/homepage.php");
            exit();
        }
        echo "Login sucessful!!!!!!!!!!";
        $conn = null;
        }
    catch(PDOException $e)
        {
        echo "Connection failed: " . $e->getMessage();
        }
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
<?php echo "<h1>Login From is Here</h1>" ?>
    <form id="loginForm" method="POST" action="loginpage.php">
        <input type="text" name="username" placeholder="U-name"><br>
        <input type="text" name="email" placeholder="E-mail"><br>
        <input type="password" name="userpassword" placeholder="Password" class="showpassword"><br> 
        <input type="submit" name="submit" value="Log in">
        <input type="checkbox" name="remember"><label for="rememberMe">Remember me</label>
    </form>
</body>
</html>