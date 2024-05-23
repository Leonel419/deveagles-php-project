<?php
session_start();
include('includes/dbConncet.php');
include('includes/helpers.php');


    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

// Get the current timestamp in milliseconds
$currentTimestamp = round(microtime(true) * 1000);

// Calculate the timestamp for the next 10 minutes
$futureTimestamp = $currentTimestamp + (10 * 60 * 1000);

echo "Current Timestamp (ms): " . $currentTimestamp . "\n";
echo "Future Timestamp (ms): " . $futureTimestamp . "\n";

    require 'PHPMailer/src/Exception.php';
    require 'PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/src/SMTP.php';

    //Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);





// handle php form request
$err='';
if($_SERVER['REQUEST_METHOD']=="POST"){
    echo 'hii';
    // take form inputs
    $email=filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password=filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);


    // validate form inputs
    if(empty($email) || empty($password)){
        $err="Provide user details";
    }

    else{

        // check if user email already exist in db
        $email_exist_sql="SELECT * FROM users WHERE email='$email'";
        $email_exist_query=mysqli_query($conn,$email_exist_sql);
        // count the number of rows , if its =or greater than 1 , then the emial exists
      echo  mysqli_num_rows($email_exist_query);
      if( mysqli_num_rows($email_exist_query)>0){
        $err="email already exist";
      }
      
      else{
            
        // call otp generate function
        $otp=generateOtp();

        // has user password
        $salt=[
            'cost'=>16
        ];
        $hash_password=password_hash($password,PASSWORD_BCRYPT,$salt);


        // store user info in db
        $insert_sql="INSERT INTO users (email,password,otp,otp_expired) 
        VALUES('$email' ,'$hash_password','$otp', $futureTimestamp)";
        $insert_query=mysqli_query($conn,$insert_sql);
        // store user email in session
            $_SESSION['email']=$email;
        // send user mail if user data are inserted successfully
        if($insert_query){
            //send user mail
            try {
                //Server settings
              
                $mail->isSMTP();                                            //Send using SMTP
                $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                $mail->Username   = 'obedchidera1010@gmail.com';                     //SMTP username, renme to paste your own email
                $mail->Password   = 'ndbe nnyl fmnz kvmq';                               //SMTP password, remember to paste your own gmail app password
               
                $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
            
                //Recipients
                $mail->setFrom('obedchidera1010@gmail.com', 'devEagles');
                $mail->addAddress($email);     //Add a recipient
                     
              
            
               
                //Content
                $mail->isHTML(true);                                  //Set email format to HTML
                $mail->Subject = 'otp sent';
                $mail->Body    = "This is your otp , use to confirm email <b>$otp</b>, OTP expiress in 10 minutes";
                $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
            
               if( $mail->send()){

                   echo 'Message has been sent';
                //    route the user to the confirm email-page
                header("location:confirm-email.php");
               }
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        }
      }

    }

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

    <div class="container">
    <?php if($err) : ?>
    <div class="alert alert-danger ">
        <p class="text-white"> <?= $err ?></p>
    </div>
    <?php endif ?>
        <!-- using $SERVER['php_self]' is more secured so we use it -->
        <form action="<?= $_SERVER['PHP_SELF']?>" method="POST">


            
            <div>
                <label for="exampleInputEmail1" class="form-label mt-4">Email</label>
                <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email" name="email">
                <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
            </div>
            <div>
                <label for="exampleInputPassword1" class="form-label mt-4">Password</label>
                <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password" autocomplete="off" name="password">
            </div>
        </div>
        <input class="btn btn-primary" type="submit" name="register">Regsiter</input>
    </form>

</body>

</html>