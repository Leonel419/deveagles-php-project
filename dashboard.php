<?php
// for you to use a session, you have to enable that session
session_start();
include_once('includes/dbConncet.php');
echo $logged_in_email= $_SESSION['email'];
// write a query to query our database
$get_balance_sql="SELECT balance FROM users WHERE email='$logged_in_email'";
$get_balance_query=mysqli_query($conn,$get_balance_sql);
// get user balance
$user_balance=mysqli_fetch_assoc($get_balance_query);
echo $balance=$user_balance['balance'];

// handle from submission
$err="";
$success='';
if(isset($_POST['send'])){
    $email=filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $amount=filter_input(INPUT_POST, "amount", FILTER_SANITIZE_NUMBER_INT);

    $check_email="SELECT * FROM users WHERE email='$email'";
    $check_query=mysqli_query($conn, $check_email);
    // chehckk if the email exist in our db
    echo '<br>' . mysqli_num_rows($check_query);
    if(mysqli_num_rows($check_query)>0){
        // get all user details
        $receiver=mysqli_fetch_assoc($check_query);
      $old_receiver_balance= $receiver['balance'];
        // check if balance is greater than amount
        if($balance >$amount){
            // debit sender 
            $new_sender_balance =($balance-$amount);
            $update_sql="UPDATE users SET balance='$new_sender_balance' WHERE email='$logged_in_email'";
            $update_query=mysqli_query($conn,$update_sql);
            if($update_query){
                // credit receiver
                $receiver_new_balance=$amount+$old_receiver_balance;
                $credit_sql="UPDATE users SET balance ='$receiver_new_balance' WHERE email='$email'";
                $credit_query=mysqli_query($conn,$credit_sql);
                if($credit_query){
                    $success='transfer Successful';
                    header("refresh:4");
                }

            }





        }
        else{
            $err="Insufficient funds";
        }

    }
    else{
        $err="Invalid email";
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
<div class="card">
    <div class="card-body">
        <h2 class="card-title">
                    Balance : <span class="blockquote"><?= "$$balance" ?></span>
        </h2>

        <form action="<?= $_SERVER['PHP_SELF']?>" method="POST">

        <?php if($err!=''): ?>
            <div class="alert alert-danger">
                    <p class="text-center"> <?= $err?> </p>
            </div>
        <?php endif ?>
        <?php if($success!=''): ?>
            <div class="alert alert-success">
                    <p class="text-center"> <?= $success?> </p>
            </div>
        <?php endif ?>

        <h3>Send money to Frinds and Family</h3>
                <div class="form-input">
                    <label for="" class="form-label"> Receipient Email</label>
                    <input type="email" class="form-control" name="email">

                    
                </div>
                <div class="form-input">
                    <label for="" class="form-label"> Amount</label>
                    <input type="number" class="form-control" name="amount">

                    
                </div>

                <button class="btn btn-primary mt-2 rounded-3" name="send">Send Money</button>
            </form>
    </div>
</div>
</body>
</html>