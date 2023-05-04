
<?php 

include '../components/connect.php';

session_start();

$msg = '';

if(isset($_SESSION['customer_id'])){
    $customer_id = $_SESSION['customer_id'] ;

}else{
    $customer_id = '';
  
}

if (isset($_GET['verification'])) {
    $get_verification = $_GET['verification'];
    $check_verification = $conn->prepare("SELECT * FROM `customers` WHERE verification = ?");
    $check_verification->execute([$get_verification]);

    if ($check_verification->rowCount() > 0) {
        $select_verification = $conn->prepare("UPDATE `customers` SET verification ='' WHERE verification = ?");
        $select_verification->execute([$get_verification]);
        $msg = "<div class='alert alert-success'>Account verification has been successfully completed.</div>";
    } else {
        $msg = "<div class='alert alert-warning'>Something went wrong.</div>";
    }
}

if(isset($_POST['submit'])){
    $email_address = $_POST['email_address'];
    $password = $_POST['password']; 
    $select_user = $conn->prepare("SELECT * FROM `customers` WHERE email_address = ? AND password = ?");
    $select_user->execute([$email_address, sha1($password)]);
    if ($select_user->rowCount() === 1 ) {
        $row = $select_user->fetch(PDO::FETCH_ASSOC);
            if (empty($row['verification'])) {
                $_SESSION['customer_id'] = $row['customer_id'];
              header('location:home.php');
              $_SESSION['msg'] = " <div class='alert-style' style='top:20%'>  <div class='alert alert-info'> Logged in successfully! </div> </div>";
                
            } else {
                $msg = "<div class='alert alert-info'>Verify your account first.</div>";
            }
         } else{
        $msg = "<div class='alert alert-danger'>Incorrect username or password</div>";
    } 
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login</title>
    <!-- CSS LINK -->
    <link rel="stylesheet" href="../css/customer.css">
    <!-- FONT AWESOME LINK -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
</head>
<body>

<!-- HEADER -->
<?php include '../components/user_header.php'; ?>


<!-- LOGIN FORM -->
<section class="form-container">
   <form action="" method="post" enctype="multipart/form-data">
      <h1>Login Now</h1>
      <?php echo $msg; ?>
      <h2>Email Address<span>*</span></h2>
      <input type="email" name="email_address" class="box" maxlength="32" oninput="this.value = this.value.replace(/\s/g, '')" required>
      <h2>Password<span>*</span></h2>
      <input id="id_password" type="password" name="password" class="box" maxlength="32"oninput="this.value = this.value.replace(/\s/g, '')" 
      required>
      <br>
      <i style=" position: relative; bottom: 4.666rem; margin-right: -5rem; font-size: 2em; left: 55%; opacity: 0.6;"
       id="togglePassword" class="far fa-eye-slash" onclick="togglePass()"> </i>
      <a href="forgot_password.php"><span>Forgot your password?</span></a>
      <br> <br>

      <input type="submit" value="Login now" name="submit" class="btn">
      <p>Don't have an account? <a href="register.php"><span>Register</span></a></p>
   </form>
</section>


<!-- FOOTER -->
<?php include '../components/user_footer.php'; ?>

<!-- JAVA SCRIPT -->
<script src="../js/customer.js"></script>


</body>
</html>