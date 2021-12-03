<?php
// Start the session
session_start();
if(isset($_POST['refresh']))
{
  
  if(isset($_SESSION['six_digit_code']))
  {
    unset($_SESSION['six_digit_code']);
  }
  if(isset($_SESSION['email']))
  {
    unset($_SESSION['email']);
  }
}
?>
<html lang="en">

<head>
  <title>Email Setup</title>
  <meta content="width=device-width, initial-scale=1" name="viewport" />
  <link rel="stylesheet" href="css/styles.css">
  <script src="javascript/script.js"></script>
</head>

<body onload = "start()">

  <div class="container">
    <h1 class="heading">Assignment 1 : PHP</h1>
  </div>
  <div class="container">

    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" id="form1">

      <input type="hidden" name="firstsubmit" value="firstsubmit">


      <?php
      if (isset($_POST['first_form'])) {
      }




      if (!isset($_POST['first_form'])) { ?>
        <input type="text" placeholder="Email" name="email" class="email_holder" id="email">
        <input type="hidden" name="first_form">
        <input type="submit" name="submiter" id="submiter" class="submit" onclick="disableButton('submiter','form1')">
      <?php } else {
        #$_SESSION['refresh'] = True;
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
        <input type="submit" name="relod" value = "Try another email" style = "display : 'inline'" onclick="enableboth()">
        <input type="hidden" name="refresh">
        </form>
        
        <?php
      }

      ?>
    </form>
  </div>


  <?php

  //send email if form has been submitted
  if (isset($_POST['submiter']) && !empty($_POST['submiter'])) {

    if (isset($_POST['email']) && !empty($_POST['email'])) {
      $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

      if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

        #Make the database connection to check if the email already exists
        require('database.php');

        if ($database->checkExists($email) == False) {
         
          $_SESSION['email'] = $email;

          if (!isset($_SESSION['six_digit_code'])) {
            require('Email.php');
      
            $_SESSION['six_digit_code'] = random_int(100000, 999999);

            
            $body = 'Your 6 digit verification code is <strong>' . $_SESSION['six_digit_code'] . '</strong>';
            
            mailer("Verification for email" , $_SESSION['email'],"Verify",$body,"nothing","");
          }
          $database->closeDatabase();
         

  ?>
          <!-- display this when the submit is clicked and email is sent-->
          <div class="container">
            <p class="code-sender">A 6 digit code has been sent to <?php echo $_SESSION['email']; ?>,<i>please search for "Ninad Apte" in the searchbar of your mail app to find it.</i></p>
          </div>
          <div class="container-2">

            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
              <input type="number" placeholder="6 - digit - code " class="Email" maxlength="6" name="number" id="number">
              <input type="submit" name="submit_2" id="submit_2" class="submit" value="Confirm">
              <input type="hidden" name="first_form">
              <!--<input type="hidden" name="firstsubmit" value="firstsubmit">-->

            </form>
          </div>

        <?php
        } else {
          echo '<div class = "container"><p class = "entry invalid">You have already subscribed!</p></div>';
        }
      } else {
        echo '<div class = "container"><p class = "entry invalid">Enter a valid email!</p></div>';
      }
    } else {
      echo '<div class = "container"><p class = "entry invalid">Enter an email!</p></div>';
    }
  }

  $success = False;
  if (isset($_POST['submit_2'])) {

    if (isset($_POST['number'])) {

      if (is_numeric($_POST['number'])) {
        if ($_POST['number'] == $_SESSION['six_digit_code']) {
          $success = True;

          require('database.php');


          if ($database->issuccess()) {


            $date = date('Y-m-d H:i:s');

            $query = 'insert into table(time_snap ,email) values(\'' . $date . '\',\'' . $_SESSION['email'] . '\')';


            $result = $database->query($query);
            $database->closeDatabase();
            if ($result == False) {
              '<div class = "error_msg">Connection problem to the remote database</div>';
            } else {
              echo '<div class = "container"><p class = "entry valid">Successfuly validated, I will send you comic mail every 5 minutes.</p></div>';
              unset($_SESSION['six_digit_code']);
              //send the first comic 
              require('runner.php');
              SendMail($_SESSION['email']);
            }
          } else {
            echo '<div class = "error_msg">For some reason the connection could not be established with the remote database</div>';
          }
        } else {
          echo '<div class = "container"><p class = "entry invalid">Wrong code</p></div>';
        }
      } else {
        echo '<div class = "container"><p class = "entry invalid">Entered value must be a number</p></div>';
      }

      if (!$success) {
        ?>
        <div class="container-2">
          <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <input type="number" placeholder="6 - digit - code " class="Email" maxlength="6" name="number" id="number">
            <input type="submit" name="submit_2" id="submit_2" class="submit" value="Confirm">
            <input type="hidden" name="first_form">
          </form>
        </div>

  <?php
      }
    } else {
      echo 'Please enter the verificaiton code sent to ' . $_SESSION['email'];
    }
  }

  ?>



</body>

</html>