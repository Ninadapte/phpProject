<?php
session_start();


?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>Unsubscribe</title>
</head>
<body>
    
    <?php
        if(!isset($_POST['submit2_unsubscribe']))
        {?>
    
    <div class="container">
        <h1>Unsubscribe</h1>
    </div>    
    

    <?php
        if(isset($_POST['email_unsubscribe']))
        {
            $email = filter_var($_POST['email_unsubscribe'], FILTER_SANITIZE_EMAIL);
            if(!filter_var($email, FILTER_VALIDATE_EMAIL))
            {
                echo '<div class = "container"><p class = "entry invalid">Invalid Email address</p></div>';
                unset($_POST['submit1_unsubscribe']);
            }
            else
            {
                require('database.php');
                $_SESSION['email_unsubscribe'] =  $email;
                
                
                $query = 'select * from table where email = "'.$_SESSION['email_unsubscribe'].'"';
                $result = $database->query($query);
                
                if($result->num_rows > 0)
                {
                    if(!isset($_SESSION['six_digit_code_unsubscribe']))
                    {
                         $_SESSION['six_digit_code_unsubscribe'] = random_int(100000, 999999);
                    }
                    require('Email.php');               
                    $body = 'Your 6 digit verification code to unsubscribe to comics is <strong>' . $_SESSION['six_digit_code_unsubscribe'] . '</strong>';            
                    mailer("Verification for email" , $_SESSION['email_unsubscribe'],"Verify",$body,"nothing","");
                    echo '<p class="code-sender">A 6 digit code has been sent to '.$_POST['email_unsubscribe'].'<i>please search for "Ninad Apte" in the searchbar of your mail app to find it.</i></p>';
                }
                else
                {
                    unset($_POST['submit1_unsubscribe']);
                    echo '<div class = "container"><p class = "entry invalid">Email has not yet subscribed for comics</p></div>';
                }
                $database->closeDatabase();
        }

        }
        if(!isset($_POST['submit1_unsubscribe']))
        { ?>
        <div class="container">
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method = "Post">
    <input type="text" name="email_unsubscribe" placeholder = "Enter your email" class = "emailler">
    <input type="submit" name="submit1_unsubscribe" class = "form_button">
    </form>
    </div>
     <?php
        }
        else {
        
        
            
        
            
           
            
       

     ?>
    <div class="container">
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method= "POST">
    <input type="number" name="code_unsubscribe" placeholder = "Enter the verification codes" class = "emailler">
    <input type="submit" name="submit2_unsubscribe" class = "form_button">
    </form>
    </div>
     
    <?php 
    
    
    

    ?>

    <?php 
        }
         
} else
    {
        if(!empty($_POST['code_unsubscribe']))
        {
            $code =  $_POST['code_unsubscribe'];
            $secode = $_SESSION['six_digit_code_unsubscribe'];
            if($secode == $code)
            {
                require('database.php');
                $query = 'delete from table where email ="'.$_SESSION['email_unsubscribe'].'"';
                
                $result = $database->query($query);
                if($result== True)
                {
                echo '<div class = "container"><p class = "entry valid">Unsubscribed successfully!</p></div>';
                unset($_SESSION['six_digit_code_unsubscribe']);
                }
                else
                {
                    echo '<div class = "container"><p class = "entry invalid">Problem in unsubscribing!</p></div>';
                }
                $database->closeDatabase();
            }
            else{
                echo '<div class = "container"><p class = "entry invalid">Invalid code!</p></div>';
                
            }
        }
        else
        {
            echo '<div class = "container"><p class = "Invalid entry">Invalid code!</p></div>';
        }
    }?>

</body>
</html>
