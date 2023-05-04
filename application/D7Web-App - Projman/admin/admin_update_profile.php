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
    header('location:admin_login.php');  
}

if (isset($_GET['verification'])) {

    $get_verification = $_GET['verification'];
    $check_verification = $conn->prepare("SELECT * FROM `admins` WHERE verification = ?");
    $check_verification->execute([$get_verification]);
 
    if ($check_verification->rowCount() > 0) {
        $select_verification = $conn->prepare("UPDATE `admins` SET verification ='' WHERE verification = ?");
        $select_verification->execute([$get_verification]);
        $msg = "<div class='alert alert-success'>Account verification has been successfully completed.</div>";
    } else {
        $msg = "<div class='alert alert-warning'>Something went wrong.</div>";
    }
  
}

if (isset($_POST['back'])) {    
    header('location:admin_view_profile.php');
}

if (isset($_POST['submit'])) {
    /* Complete Name */
    $complete_name = $_POST['complete_name'];

    if (!empty($complete_name)) {
        $update_name = $conn->prepare("UPDATE `admins` SET complete_name = ? WHERE admin_id = ?");
        $update_name->execute([$complete_name, $admin_id]);
        $msg = "<div class='alert alert-success'>You've successfully updated your complete name.</div>";
    }

    /* Phone Number */
    $phone_number = $_POST['phone_number'];

    if (!empty($phone_number)) {
        if (!preg_match('/^[0-9]{11}+$/', $phone_number)) {
            $msg = "<div class='alert alert-danger'>Phone Number Doens't Exist.</div>";
        }else{
            $update_number = $conn->prepare("UPDATE `admins` SET phone_number = ? WHERE admin_id = ?");
            $update_number->execute([$phone_number, $admin_id]);
            $msg = "<div class='alert alert-success'>You've successfully updated your phone number.</div>";
        }
    }

    /* Email Address*/
    $email_address = $_POST['email_address'];
    $verification = sha1(rand());

    if (!empty($email_address)) {
        $select_email = $conn->prepare("SELECT * FROM `admins` WHERE email_address = ?");
        $select_email->execute([$email_address]);

        if ($select_email->rowCount() > 0) {
            $msg = "<div class='alert alert-danger'>Email already exist.</div>";
        } else {
            $update_email = $conn->prepare("UPDATE `admins` SET email_address = ?, verification = ?  WHERE admin_id = ?");
            $update_email->execute([$email_address, $verification, $admin_id]);
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
                $mail->Subject = 'D7 Auto Service Center: Email Verification';
                $mail->Body = 'Here is the verification link <b><a href="http://localhost/D7Web-App/admin/admin_update_profile.php?verification=' . $verification . '">
                    http://localhost/D7Web-App/admin/admin_update_profile.php?verification=' . $verification . '</a></b>';
                $mail->send();
                echo 'Message has been sent';
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
            echo "</div>";
            $msg = "<div class='alert alert-info'>We've send a verification link on your email address.</div>";
    
        }
    }

    /* Profile Picture */
    $profile_picture = $_FILES['profile_picture']['name'];
    $profile_picture_size = $_FILES['profile_picture']['size'];
    $profile_picture_tmp_name = $_FILES['profile_picture']['tmp_name'];
    $profile_picture_folder = '../profile/' . $profile_picture;

    if (!empty($profile_picture)) {
        if($profile_picture_size > 2000000){
            $msg = "<div class='alert alert-danger'>Image size is too large.</div>";
        }else{
            $update_profile = $conn->prepare("UPDATE `admins` SET profile_picture = ? WHERE admin_id = ?");
            $update_profile->execute([$profile_picture, $admin_id]);
            move_uploaded_file($profile_picture_tmp_name, $profile_picture_folder);
            $msg = "<div class='alert alert-success'>You've successfully changed your profile picture.</div>";
        }
    }

    /* Password */
    $old_pass = sha1($_POST['old_pass']); 
    $new_pass = $_POST['new_pass']; 
    $confirm_pass = $_POST['confirm_pass']; 

    if (!empty($new_pass) || !empty($confirm_pass)){
        $select_prev_pass = $conn->prepare("SELECT password FROM `admins` WHERE admin_id = ?");
        $select_prev_pass->execute([$admin_id]);
        $fetch_prev_pass = $select_prev_pass->fetch(PDO::FETCH_ASSOC);
        $prev_pass = $fetch_prev_pass['password'];
        if ($old_pass === $prev_pass) {
            if ($new_pass != $confirm_pass) {
                $msg = "<div class='alert alert-danger'>New Password and Confirm Password do not match.</div>";
            } else {
                if (!preg_match('/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{8,32}$/', $new_pass)) {
                    $msg = "<div class='alert alert-danger'>Password should at least have 8 to 32 characters, 1 upper case, 1 lower case, 1 number, and 1 special character</div>";
                } else {
                     if (sha1($new_pass) != $prev_pass) {
                        $update_pass = $conn->prepare("UPDATE `admins` SET password = ? WHERE admin_id = ?");
                        $update_pass->execute([sha1($new_pass), $admin_id]);
                        $msg = "<div class='alert alert-success'>You've successfully changed your password.</div>";
                    } else {
                        $msg = "<div class='alert alert-danger'>Enter New Password.</div>";
                    }
                }
            }
        } else {
            $msg = "<div class='alert alert-danger'>Incorrect Old Password.</div>";
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
   <title>AdminUpdate Profile</title>
    <!-- CSS LINK -->
    <link rel="stylesheet" href="../css/admin.css">
    <!-- FONT AWESOME LINK -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
</head>
<body>

<!-- HEADER -->
<?php include '../components/admin_navigation.php'; ?>

<!-- UPDATE FORM -->
<section class="form-container update-form">
   <form action="" method="post" enctype="multipart/form-data">
      <h1>update profile</h1>
      <?php echo $msg; ?>
      <h2>Update Profile Picture:</h2>
      <input type="file" name="profile_picture" class="box" accept="profile_picture/jpg, profile_picture/jpeg, profile_picture/png, profile_picture/webp">
      <h2>Change Name:</h2>
      <input type="text" name="complete_name" placeholder="<?= $fetch_profile['complete_name']; ?>" class="box" maxlength="32">
      <h2>Change Email Address:</h2>
      <input type="email" name="email_address" placeholder="<?= $fetch_profile['email_address']; ?>" class="box" maxlength="32" oninput="this.value = this.value.replace(/\s/g, '')">
      <h2>Change Phone Number:</h2>
      <input type="tel" name="phone_number" placeholder="<?= $fetch_profile['phone_number']; ?>" class="box" maxlength="11">
      <h2>Old Password:</h2>
      <input type="password" name="old_pass"  placeholder="Enter Old Password" class="box" maxlength="32" oninput="this.value = this.value.replace(/\s/g, '')">
      <h2>New Password:</h2>
      <input type="password" name="new_pass"  placeholder="Enter New Password" class="box" maxlength="32" oninput="this.value = this.value.replace(/\s/g, '')">
      <h2>Confirm New Password:</h2>
      <input type="password" name="confirm_pass" placeholder="Confirm New Password" class="box" maxlength="32" oninput="this.value = this.value.replace(/\s/g, '')">
      <br> <br>
      <input type="submit" value="update now" name="submit" class="btn">
      <br> <br>
      <input type="submit" value="Go Back" name="back" class="btn">
   </form>
</section>

<!-- FOOTER -->
<?php include '../components/admin_footer.php'; ?>

<!-- JAVA SCRIPT -->
<script src="../js/admin.js"></script>

</body>
</html>