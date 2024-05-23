<?php
// start session
    session_start();
    // store seessioun email in a variable
    $user_email= $_SESSION['email'];
    if(isset($_POST['check'])){
        // collect otp from user
        $otp=filter_input(INPUT_POST, 'check', FILTER_SANITIZE_EMAIL);

        // "check_otp_sql="


    }


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
<body>

<div class="container">

<div class="alert alert-danger ">
    <p class="text-white"><?= $err ?  $err : ''?> </p>
</div>
    <!-- using $SERVER['php_self]' is more secured so we use it -->
    <form action="<?= $_SERVER['PHP_SELF']?>" method="POST">


        
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