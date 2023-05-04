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

if(isset($_SESSION['customer_id'])){
    $customer_id = $_SESSION['customer_id'] ;
}else{
    $customer_id = '';
}

$captcha = rand(100000, 999999);

if (isset($_POST['submit'])) {
    $profile_picture = $_FILES['profile_picture']['name'];
    $profile_picture_size = $_FILES['profile_picture']['size'];
    $profile_picture_tmp_name = $_FILES['profile_picture']['tmp_name'];
    $profile_picture_folder = '../profile/' . $profile_picture;
    $complete_name = $_POST['complete_name'];
    $email_address = $_POST['email_address'];
    $random = $_POST['random'];
    $e_captcha = $_POST['e_captcha'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    $phone_number = $_POST['phone_number'];
    $verification = sha1(rand());   
    $select_user = $conn->prepare("SELECT * FROM `customers` WHERE email_address = ? ");
    $select_user->execute([$email_address]);

    if ($select_user->rowCount() > 0) {
        $msg = "<div class='alert alert-danger'>Email already exist.</div>";
    } else {
        if ($password != $cpassword) {
            $msg = "<div class='alert alert-danger'>Password and Confirm Password fields do not match.</div>";
        } else {
            if (!preg_match('/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{8,32}$/', $password)) {
                $msg = "<div class='alert alert-danger'>Password should at least contain 8 to 32 characters, 1 upper case, 1 lower case, 1 number, and 1 special character</div>";
            } else { 
                if (!preg_match('/^[0-9]{11}+$/', $phone_number)) {
                    $msg = "<div class='alert alert-danger'>Phone Number Doesn't Exist.</div>";
                } else {
                    if($profile_picture_size > 2000000){
                        $msg = "<div class='alert alert-danger'>Image size is too large.</div>";
                    }else{
                        if ($e_captcha != $random) {
                            $msg = "<div class='alert alert-danger'>Incorrect Captcha. Please try again.</div>";
                        }else{
                        move_uploaded_file($profile_picture_tmp_name, $profile_picture_folder);
                        $insert_user = $conn->prepare("INSERT INTO `customers` (profile_picture, complete_name, email_address, password, phone_number, verification) 
                        VALUES(?,?,?,?,?,?)");
                        $insert_user->execute([$profile_picture, $complete_name, $email_address, sha1($password), $phone_number, $verification]);
                        echo "<div style='display: none;'>";
                        //Create an instance; passing `true` enables exceptions
                        $mail = new PHPMailer(true);
                        try {
                            //Server settings       
                            $mail->SMTPDebug = SMTP::DEBUG_SERVER; //Enable verbose debug output
                            $mail->isSMTP(); //Send using SMTP
                            $mail->Host = 'smtp.gmail.com'; //Set the SMTP server to send through
                            $mail->SMTPAuth = true; //Enable SMTP authentication
                            $mail->Username = 'dmc.mir4.1@gmail.com'; //SMTP username
                            $mail->Password = 'mgafrzprnbgvleph'; //SMTP password
                            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
                            $mail->Port = 465; //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
                            //Recipients
                            $mail->setFrom('dmc.mir4.1@gmail.com');
                            $mail->addAddress($email_address);
                            //Content
                            $mail->isHTML(true); //Set email format to HTML
                            $mail->Subject = 'D7 Auto Service Center: Account Activation';
                            $mail->Body = 'Hi ' .$complete_name. ', this is the last step to activate your account; kindly click the link to verify: <b><a href="http://localhost/D7Web-App/customer/login.php?verification=' . $verification . '">
                                http://localhost/D7Web-App/customer/login.php?verification=' . $verification . '</a></b>'.' if this was not you, kindly disregard this email.';
                            $mail->send();
                            echo 'Message has been sent';
                        } catch (Exception $e) {
                            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                        }
                        echo "</div>";
                            $msg = "<div class='alert alert-info'>We've sent a verification link to your email address. $email_address</div>";
                    }}
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
   <title>Register</title>
    <!-- CSS LINK -->
    <link rel="stylesheet" href="../css/customer.css">
    <!-- FONT AWESOME LINK -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
</head>
<body>

<!-- HEADER -->
<?php include '../components/user_header.php'; ?>

<!-- REGISTRATION FORM -->
<section class="form-container">
   <form action="" method="post" enctype="multipart/form-data">
      <h1>register now</h1>
      <?php echo $msg ?>
      <h2>Upload Profile Picture<span>*</span></h2>
      <input type="file" name="profile_picture" class="box" accept="profile_picture/jpg, profile_picture/jpeg, profile_picture/png, profile_picture/webp" required>
      <h2>Complete Name<span>*</span></h2>
      <input type="text" name="complete_name" class="box" maxlength="32" value="<?php if (isset($_POST['submit'])) { echo $complete_name; } ?>" required>  
      <h2>Email Address<span>*</span></h2>
      <input type="email" name="email_address" class="box" maxlength="32" value="<?php if (isset($_POST['submit'])) { echo $email_address; } ?>" 
        oninput="this.value = this.value.replace(/\s/g, '')" required>
      <h2>Password<span>*</span></h2>
      <span class="reminder"> Password should at least have 8 to 32 characters, 1 upper case, 1 lower case, 1 number, and 1 special character </span>
      <input id="id_password" type="password" name="password" class="box" maxlength="32"oninput="this.value = this.value.replace(/\s/g, '')" required>
      <i style=" position: absolute; margin-top: 2.8rem; font-size: 2rem; left: 63%; opacity: 0.6;"
       id="togglePassword" class="far fa-eye-slash" onclick="togglePass()"></i>
      <h2>Confirm Password<span>*</span></h2>
      <input id="id_passwordconfirm" type="password" name="cpassword" class="box" maxlength="32" oninput="this.value = this.value.replace(/\s/g, '')" required>
      <i style=" position: absolute; margin-top: 2.8rem; font-size: 2rem; left: 63%; opacity: 0.6;"
       id="togglePasswordConfirm" class="far fa-eye-slash" onclick="togglePassConfirm()"></i>
      <h2>Phone Number<span>*</span></h2>
      <input type="tel" name="phone_number" class="box"  maxlength="12" placeholder="e.g. 09XXXXXXXXX" value="<?php if (isset($_POST['submit'])) { echo $phone_number; } ?>" 
        oninput="this.value = this.value.replace(/\s/g, '')" required>
      <h2>CAPTCHA Code<span>*</span></h2>
      <input type="text" class="box" name="e_captcha" required>
      <input type="text" name="random" class="images" value="<?php echo $captcha ?>" readonly> <br> 
      <input type="checkbox" unchecked="unchecked" class="check" required>
      <a href="https://www.freeprivacypolicy.com/live/41f43262-db39-475f-b626-ba10c40822c8" target="_blank"> I agree to the <span>Privacy & Policy</span><span style="color:var(--red);">*</span></a>
      <br> <br> 
      <input type="submit" value="register now" name="submit" class="btn">
      <p>Already have an account? <a href="login.php"><span>Login</span></a></p>
   </form>
</section>

<!-- FOOTER -->
<?php include '../components/user_footer.php'; ?>

<!-- JAVA SCRIPT -->
<script src="../js/customer.js"></script>


</body>
</html>