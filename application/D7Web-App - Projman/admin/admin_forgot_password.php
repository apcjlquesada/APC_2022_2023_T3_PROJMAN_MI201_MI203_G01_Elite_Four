<?php

include '../components/connect.php';
require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

session_start();

$msg = "";

if(isset($_SESSION['admin_id'])){
    $admin_id = $_SESSION['admin_id'] ;
}else{
    $admin_id = '';
}

if (isset($_POST['submit'])) {
    $email_address = $_POST['email_address'];
    $verification = sha1(rand());
    $select_email = $conn->prepare("SELECT * FROM `admins` WHERE email_address = ?");
    $select_email->execute([$email_address]);

    if($select_email->rowCount() > 0){
        $update_verification = $conn->prepare("UPDATE `admins` SET verification = ? WHERE email_address = ?");
        $update_verification->execute([$verification, $email_address]);
        echo "<div style='display: none;'>";
        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);
        try {
            //Server settings       
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                          //Enable verbose debug output
            $mail->isSMTP();                                                //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                           //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                       //Enable SMTP authentication
            $mail->Username   = 'dmc.mir4.1@gmail.com';                     //SMTP username
            $mail->Password   = 'mgafrzprnbgvleph';                         //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;                //Enable implicit TLS encryption
            $mail->Port       = 465;                                        //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
            //Recipients
            $mail->setFrom('dmc.mir4.1@gmail.com');
            $mail->addAddress($email_address);
            //Content
            $mail->isHTML(true);                                            //Set email format to HTML
            $mail->Subject = 'D7 Auto Service Center: Reset Password Verification Link';
            $mail->Body    = 'Hi, it seems that you have forgotten your D7 account password. To reset it, here is the verification link: <b><a href="http://localhost/D7Web-App/admin/admin_change_password.php?reset='.$verification.'">
                http://localhost/D7Web-App/admin/admin_change_password.php?reset='.$verification.'</a></b>';
            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
        echo "</div>";
        $msg = "<div class='alert alert-info'>We've sent a verification link on your email address.</div>";
    } else {
        $msg = "<div class='alert alert-danger'>$email_address - This email address doesn't exist.</div>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Forgot Password</title>
    <!-- CSS LINK -->
    <link rel="stylesheet" href="../css/customer.css">
    <!-- FONT AWESOME LINK -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
</head>
<body>

<!-- FORGOT PASSWORD -->
<section class="form-container">
   <form action="" method="post" enctype="multipart/form-data">
      <h1>Forgot Password</h1>
      <?php echo $msg; ?>
      <h2>Email Address<span>*</span></h2>
     <span style="font-size: 11pt; float:left;">  Enter your email address to receive the reset link. </span>
      <input type="email" name="email_address" class="box" maxlength="32" oninput="this.value = this.value.replace(/\s/g, '')" required>
      
      <br> <br>
      <input type="submit" value="Send Reset Link" name="submit" class="btn">
      <p> Back to <a href="admin_login.php"><span>Login</span></a></p>
   </form>
</section>

<!-- JAVA SCRIPT -->
<script src="../js/admin.js"></script>

</body>
</html>