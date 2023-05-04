
<?php 

include '../components/connect.php';

session_start();

$msg = '';

if(isset($_SESSION['admin_id'])){
    $admin_id = $_SESSION['admin_id'] ;
}else{
    $admin_id = '';
}

if(isset($_POST['submit'])){
    $email_address = $_POST['email_address'];
    $password = ($_POST['password']); 
    $select_user = $conn->prepare("SELECT * FROM `admins` WHERE email_address = ? AND password = ?");
    $select_user->execute([$email_address, sha1($password)]);
 
    if ($select_user->rowCount() === 1 ) {
        $row = $select_user->fetch(PDO::FETCH_ASSOC);
            if (empty($row['verification'])) {
                $_SESSION['admin_id'] = $row['admin_id'];
                header('location:dashboard.php');
                $_SESSION['msg'] =
                " <div class='alert-style' style='top:20%'> 
                <div class='alert alert-info'> Logged in successfully! </div></div>";
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
   <title>Admin Login</title>
    <!-- CSS LINK -->
    <link rel="stylesheet" href="../css/customer.css">
    <!-- FONT AWESOME LINK -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
</head>
<body>

<!-- LOGIN FORM -->
<section class="form-container">
   <form action="" method="post" enctype="multipart/form-data">
      <h1>Welcome Admin</h1>
      <?php echo $msg; ?>
      <h2>Email Address<span>*</span></h2>
      <input type="email" name="email_address" class="box" maxlength="32" oninput="this.value = this.value.replace(/\s/g, '')" required>
      <h2>Password<span>*</span></h2>
      <input id="id_password" type="password" name="password" class="box" maxlength="32" oninput="this.value = this.value.replace(/\s/g, '')" required> 
      <i style=" position: relative; bottom: 2em; padding-right: 0.5em; font-size: 2.5em; left: 45%; opacity: 0.6;"
       id="togglePassword" class="far fa-eye-slash" onclick="togglePass()"> </i>
        <br>
      <a href="admin_forgot_password.php"><span>Forgot your password?</span></a>
      <br> <br>
      <input type="submit" value="Login now" name="submit" class="btn">
   </form>
</section>

<script src="../js/admin.js"></script>

</body>
</html>