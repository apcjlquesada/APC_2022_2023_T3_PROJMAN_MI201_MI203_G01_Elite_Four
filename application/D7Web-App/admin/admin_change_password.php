<?php 

include '../components/connect.php';

session_start();

$msg = "";

if(isset($_SESSION['admin_id'])){
    $admin_id = $_SESSION['admin_id'] ;
}else{
    $admin_id = '';
}

if (isset($_GET['reset'])) {
    $get_verification = $_GET['reset'];
    $check_verification = $conn->prepare("SELECT * FROM `admins` WHERE verification = ?");
    $check_verification->execute([$get_verification]);

    if ($check_verification->rowCount() > 0) {
        if (isset($_POST['submit'])) {
            $password = $_POST['password'];
            $cpassword = $_POST['cpassword'];
            if ($password != $cpassword) {
                $msg = "<div class='alert alert-danger'>Password and Confirm Password fields do not match.</div>";
            } else {
                if (!preg_match('/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{8,32}$/', $password)) {
                    $msg = "<div class='alert alert-danger'>Your password should at least contain 8 to 32 characters, 1 upper case, 1 lower case, 1 number, and 1 special character</div>";
                } else {
                    $select_verification = $conn->prepare("UPDATE `admins` SET password = ?, verification ='' WHERE verification = ?");
                    $select_verification->execute([sha1($password), $get_verification]);
                    $msg = "<div class='alert alert-success'>You've successfully reset your password.</div>";
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
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Change Password</title>
    <!-- CSS LINK -->
    <link rel="stylesheet" href="../css/customer.css">
    <!-- FONT AWESOME LINK -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
</head>
<body>

<!-- CHANGE PASS FORM -->
<section class="form-container">
   <form action="" method="post" enctype="multipart/form-data">
      <h1>Change Password</h1>
      <?php echo $msg; ?>
      <h2>New Password<span>*</span></h2>
      <input type="password" name="password" class="box" maxlength="32"oninput="this.value = this.value.replace(/\s/g, '')" required>
      <h2>Confirm New Password<span>*</span></h2>
      <input type="password" name="cpassword" class="box" maxlength="32"oninput="this.value = this.value.replace(/\s/g, '')" required>
      <br> <br>
      <input type="submit" value="Change Password" name="submit" class="btn">
      <p>Already have an account?<a href="admin_login.php"><span>Login</span></a></p>
   </form>
</section>

<!-- JAVA SCRIPT -->
<script src="../js/admin.js"></script>

</body>
</html>