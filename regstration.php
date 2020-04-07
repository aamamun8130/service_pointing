<?php
class TableRows extends RecursiveIteratorIterator {
    function __construct($it) {
        parent::__construct($it, self::LEAVES_ONLY);
    }
}
if(isset($_POST["submit"])) {
    try {
        include_once("connection.php");
        // regstration username and email validation start
        $stmt1 = $conn->prepare("SELECT username FROM users WHERE username=:username");
        $stmt2 = $conn->prepare("SELECT email FROM users WHERE  email=:email");
        
        $stmt1->bindParam(':username', $username);
        $stmt2->bindParam(':email', $email);

        // set parameters and execute for validating reg form
        $username =  $_POST['username'];
        $email =  $_POST['email'];
        $stmt1->execute();
        $stmt2->execute();

        $result_user= $stmt1->setFetchMode(PDO::FETCH_ASSOC);
        $result_email= $stmt2->setFetchMode(PDO::FETCH_ASSOC);
        
        $fondusername = 'false';
        $fondEmail = 'false';

        foreach(new TableRows(new RecursiveArrayIterator($stmt1->fetchAll())) as $k=>$row_value_user) {
            if ($row_value_user == $username){
                $fondusername = 'true';
                echo "User Name Exist !!!!";
                break;
            }
        }

        foreach(new TableRows(new RecursiveArrayIterator($stmt2->fetchAll())) as $k=>$row_value_email) {
            if ($row_value_email == $email){
                $fondEmail = 'true';
                echo "User Email Exist !!!!";
                break;
            }
        }

        if ( $fondusername == 'false' && $fondEmail == 'false'){
            echo "not found any user";
            /// Reg data insert processs startstart
            //prepare and bind
            $stmt = $conn->prepare("INSERT INTO users (username, email, userpassword)
            VALUES (:username, :email, :userpassword)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':userpassword', $userpassword);
        
            // set parameters and execute
            $username =  $_POST['username'];
            $userpassword =  $_POST['userpassword'];
            $email =  $_POST['email'];
            $stmt->execute();
            echo "New records created successfully";
            $conn = null;
            /// Reg data insert processs start end
        }

        // regstration username and email validation end 
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
    <?php echo "<h1>Registration From is Here</h1>" ?>
    <form id="loginForm" method="POST" action="regstration.php">
        <input type="text" name="username" placeholder="U-name"><br>
        <input type="text" name="email" placeholder="E-mail"><br>
        <input type="password" name="userpassword" placeholder="Password" class="showpassword"><br> 
        <input type="submit" name="submit" value="Register"></form>
</body>
</html>