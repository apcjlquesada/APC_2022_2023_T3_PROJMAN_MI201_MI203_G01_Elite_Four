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

if (isset($_POST['submit'])) {
    if($customer_id == ''){
        $msg = "<div class='alert alert-danger'> Login to your account </div>";
    }else {
        $rating = $_POST['rating'];
        $reviews = $_POST['reviews'];
        $customer_name = $_POST['customer_name'];
        $profile_picture = $_POST['profile_picture'];
        $email_address = $_POST['email_address'];
        $admin_address = $_POST['admin_address'];
        $insert_review = $conn->prepare("INSERT INTO `reviews` (customer_id, customer_profile, customer_name, rating, description) VALUE (?,?,?,?,?)");
        $insert_review->execute([$customer_id, $profile_picture, $customer_name, $rating, $reviews]);
       
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
            $mail->addAddress($admin_address);
            //Content
            $mail->isHTML(true); //Set email format to HTML
            $mail->Subject = 'Review Notification';
            $mail->Body = 'Hi Admin!, this is to inform you that a new review has abeen posted by your customer named ' .$customer_name;
            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
        echo "</div>";
        header('location:reviews.php');
        $_SESSION['msg'] = "<div class='alert-style'> <div class='alert alert-success'>Review posted</div></div>";     
    } 
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title> Write Review </title>
    <!-- CSS LINK -->
    <link rel="stylesheet" href="../css/customer.css"> 
    <!-- FONT AWESOME LINK -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
</head>
<body>

<!-- HEADER -->
<?php include '../components/user_header.php'; ?>

<!-- RESERVATION -->
<section class="form-container" id="bg"> 
        <?php
            $select_admin = $conn->prepare("SELECT email_address FROM `admins`");
            $select_admin->execute();
            if($select_admin->rowCount() > 0){
                $fetch_admin = $select_admin->fetch(PDO::FETCH_ASSOC);
            }
        ?>
   <form action="" method="post">
      <h1> Rate Us</h1>
      <?php echo $msg; ?>
      <input type="hidden" name="customer_name" class="box" maxlength="32" value="<?=$fetch_profile['complete_name']; ?>">  
      <input type="hidden" name="profile_picture" class="box" maxlength="32" value="<?=$fetch_profile['profile_picture']; ?>">  
      <input type="hidden" name="email_address" class="box" maxlength="32" value="<?=$fetch_profile['email_address']; ?>">  
      <input type="hidden" name="admin_address" class="box" maxlength="32" value="<?=$fetch_admin['email_address']; ?>"> 
      <h2>Rating<span>*</span></h2> 
      <select name="rating" class="box-star">
        <div class="">
        <option value="1">&#9733;&#9734;&#9734;&#9734;&#9734;</option>  
        <option value="2">&#9733;&#9733;&#9734;&#9734;&#9734;</option>
        <option value="3">&#9733;&#9733;&#9733;&#9734;&#9734;</option>    
        <option value="4">&#9733;&#9733;&#9733;&#9733;&#9734;</option>
        <option value="5">&#9733;&#9733;&#9733;&#9733;&#9733;</option>
        </div>
      </select>
      <h2> How was your experience?<span>*</span></h2>
         <textarea name="reviews" rows="4" column="10"  class="box" maxlength='500' required></textarea>
         <input type="submit" class="btn-add" name="submit" value="Submit Feedback">
   </form>
</section>

<!-- FOOTER -->
<?php include '../components/user_footer.php'; ?>

<!-- JAVA SCRIPT -->
<script src="../js/customer.js"></script>

</body>
</html>