<?php
// start session
    session_start();
    include('includes/dbConncet.php');
    $err='';
    // store seessioun email in a variable
    $user_email= $_SESSION['email'];
    if(isset($_POST['check'])){
        // collect otp from user
        $otp=filter_input(INPUT_POST, 'otp', FILTER_SANITIZE_EMAIL);

        $check_otp_sql="SELECT * FROM users WHERE email='$user_email'";
        // test your query
        $check_otp_query=mysqli_query($conn,$check_otp_sql);
        // fetch the user details in the db in an array format
        $user=mysqli_fetch_assoc($check_otp_query);
        $user_otp=$user['otp'];
        // compare if user otp matches the one in the database

        if($user_otp==$otp){
            // take user to login page
            header("location:login.php");
        }
        else{
            $err="Invalid OTP";
        }
        // $user_otp=


    }


?>


  <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles/styles.css">
</he<!DOCTYPE html>
<html lang="en">
<head>
  ad>
<body>
<body>

<div class="container">


    <!-- using $SERVER['php_self]' is more secured so we use it -->
    <form action="<?= $_SERVER['PHP_SELF']?>" method="POST">


        
             <?php if($err) : ?>
                <div class="alert alert-danger ">
                    <p class="text-white"> <?= $err ?></p>
                </div>
                <?php endif ?>
      
        <div>
            <label for="exampleInputEmail1" class="form-label mt-4">check mailbox for rmail</label>
            <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email" name="otp">
            <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
        </div>
       
    </div>
    <input class="btn btn-primary" type="submit" name="check"></input>
</form>

</body>
</body>
</html>