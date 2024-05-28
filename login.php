
<?php
// start a session 
session_start();
    $err="";
    include('includes/dbConncet.php');

  if(isset($_POST['login'])){
    echo $email=filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    echo $password=filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);

    // chechk in my db where email inDB matches user email
    $login_sql="SELECT * FROM users WHERE email='$email'";
    $login_query=mysqli_query($conn,$login_sql);
    if($login_query){
        $user=mysqli_fetch_assoc($login_query);
        echo $hashed_password=$user['password'];
        // compare user password with the one in the database
        $password_true=password_verify($password,$hashed_password);
        if($password_true){
            // store user emial in session
            $_SESSION['email']=$email;
            header('location:dashboard');

        }
        else{
            $err="Invalid  login password";
        }
    }
    else{
        $err="Invalid logins e,mail";
        // see the actual error that's not making your DB operation to run
        echo mysqli_error($conn);
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


        <!-- using $SERVER['php_self]' is more secured so we use it -->
        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">



            <?php if ($err) : ?>
                <div class="alert alert-danger ">
                    <p class="text-white"> <?= $err ?></p>
                </div>
            <?php endif ?>

            <div>
                <label for="exampleInputEmail1" class="form-label mt-4">check mailbox for rmail</label>
                <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email" name="email">
                <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
            </div>
            <div>
                <label for="exampleInputEmail1" class="form-label mt-4">check mailbox for rmail</label>
                <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email" name="password">
                <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
            </div>
        


    </div>
    <input class="btn btn-primary" type="submit" name="login"></input>
    </form>
</body>

</html>

