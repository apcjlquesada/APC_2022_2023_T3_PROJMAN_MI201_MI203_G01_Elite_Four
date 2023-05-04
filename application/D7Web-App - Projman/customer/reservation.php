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
$captcha = rand(100000, 999999);

if(isset($_SESSION['customer_id'])){
    $customer_id = $_SESSION['customer_id'] ;
}else{
    $customer_id = '';
}

if (isset($_POST['submit'])) {
    if($customer_id == ''){
        $msg = '<div class="alert alert-danger alert-dismissible fade show" role="alert"> 
        Login to your account first. </div>';
        }else {
            $e_captcha = $_POST['e_captcha'];
            $random = $_POST['random'];
            if ($e_captcha != $random) {
                $msg = "<div class='alert alert-danger'>Incorrect Captcha. Please try again.</div>";
            }else {
                $customer_name = $_POST['customer_name'];
                $customer_email = $_POST['customer_address'];
                $admin_address = $_POST['admin_address'];
                $customer_number = $_POST['customer_number']; 
                $service_type = $_POST['service_type'];
                $car_model = $_POST['car_model'];
                $schedule = $_POST['schedule'];
                $schedule_array = explode("T", $schedule);
                $date_picked = $schedule_array[0];
                $time_picked = $schedule_array[1];
                $date_picked_dt = new DateTime($date_picked);
                $current_date_dt = new DateTime();
                $time_picked_tm = strtotime($time_picked);
                $opening_time_tm = strtotime("08:00:00");
                $closing_time_tm = strtotime("16:00:00");
                $verify_reservation = $conn->prepare("SELECT * FROM `reservations` WHERE customer_id = ?");
                $verify_reservation->execute([$customer_id]);
    
                if ($verify_reservation->rowCount() > 99) {
                    $msg = "<div class='alert alert-danger'>You've reached the limit of the reservation.</div>";
                } else {
                    if ($current_date_dt > $date_picked_dt) {
                        $msg = "<div class='alert alert-danger'>Please pick a date ahead of time.</div>";
                    } else {
                        if ($opening_time_tm > $time_picked_tm || $time_picked_tm > $closing_time_tm) {
                            $msg = "<div class='alert alert-danger'>Please choose a time between 8am to 4pm.</div>";
                        } else {
                            $check_schedule = $conn->prepare("SELECT * FROM `reservations` WHERE DATE(schedule) = DATE(?) AND TIME(schedule) = TIME(?)");
                            $check_schedule->execute([$schedule, $schedule]);
                            if($check_schedule->rowCount() > 0){
                                $msg = "<div class='alert alert-danger'>Slots are full. Please choose a different date or time.</div>";
                            }else {
                            $booked = $conn->prepare("INSERT INTO `reservations` (customer_id, customer_name, service_type, car_model, customer_email, schedule, customer_number) 
                            VALUES(?,?,?,?,?,?,?)");
                            $booked->execute([$customer_id, $customer_name, $service_type, $car_model, $customer_email, $schedule, $customer_number]);
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
                                $mail->addAddress($customer_email);
                                $mail->addCC($admin_address);
                                //Content
                                $mail->isHTML(true); //Set email format to HTML
                                $mail->Subject = 'D7 Auto Center: Booking Confirmation';
                                $mail->Body = 'Hi ' .$customer_name. ' this is to confrim that you have made a reservation with D7 Auto Service Center for ' .$service_type. 
                                ' Kindly check your reservation details on your D7 Account. Thank you!';
                                $mail->send();
                                echo 'Message has been sent';
                             } catch (Exception $e) {
                                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                            }
                            echo "</div>";
                            $msg = "<div class='alert alert-success'>Booking sent successfully.</div>";
                        }
                    }
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
   <title>Reservation</title>
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
        <h1>Book a Reservation</h1>
        <?php echo $msg; ?>
        <input type="hidden" name="customer_name" class="box" maxlength="32" value="<?=$fetch_profile['complete_name']; ?>">  
        <input type="hidden" name="customer_address" class="box" maxlength="32" value="<?=$fetch_profile['email_address']; ?>">
        <input type="hidden" name="customer_number" class="box" maxlength="12"value="<?=$fetch_profile['phone_number']; ?>" >
        <input type="hidden" name="admin_address" class="box" maxlength="12"value="<?=$fetch_admin['email_address']; ?>" >
        <h2>Type of Services<span>*</span></h2>
        <select name="service_type" class="box" required> 
            <option value="" disabled selected>Select Services -- </option> 
                <?php
                $show_services = $conn->prepare("SELECT * FROM `services` WHERE `status` = 'AVAILABLE'");
                $show_services->execute();
                if($show_services->rowCount() > 0){
                    while($fetch_services = $show_services->fetch(PDO::FETCH_ASSOC)){  
                ?>
            <option><?= $fetch_services['service_name']; ?></option> 
                <?php
                    }
                }
                ?>
        </select>
        <h2>Car Model<span>*</span></h2>
        <select name="car_model" class="box" required>
            <option value="" disabled selected>Car Model --</option>
                <?php
                $show_car_model = $conn->prepare("SELECT * FROM `car_models` WHERE `status` = 'AVAILABLE'");
                $show_car_model->execute();
                if($show_car_model->rowCount() > 0){
                    while($fetch_car_model = $show_car_model->fetch(PDO::FETCH_ASSOC)){  
                ?>
            <option><?= $fetch_car_model ['car_model']; ?></option> 
                <?php
                    }
                }
                ?>
        </select>
        <h2>Schedule<span>* Between 8:00am - 4:00pm</span></h2>
        <input type="datetime-local" name="schedule" class="box" required>
        <h2>CAPTCHA Code<span>*</span></h2>
        <input type="text" class="box" name="e_captcha" required>
        <input type="text" name="random" class="images" value="<?php echo $captcha ?>" readonly> <br> 
        <br> <br>
        <input type="submit" value="Book Now!" name="submit" class="btn-submit">
        <p>Don't have an account? <a href="register.php"><span>Register</span></a></p>
   </form>
   <div class="chat">
     <i href="https://www.facebook.com/D7AutoServiceCenter/" class="fa-brands fa-facebook-messenger"></i>  
   </div>
</section>

<!-- FOOTER -->
<?php include '../components/user_footer.php'; ?>

<!-- JAVA SCRIPT -->
<script src="../js/customer.js"></script>

<script>
function roundToNearestHour(date) {
  // Create a new Date object from the input value, and assume it's in the local time zone
  var localDate = new Date(date);
  // Convert the local date to UTC, so we can apply the Philippines time zone offset
  var utcDate = Date.UTC(localDate.getFullYear(), localDate.getMonth(), localDate.getDate(), localDate.getHours(), localDate.getMinutes());
  // Apply the Philippines time zone offset (8 hours ahead of UTC)
  var phOffset = (8 - (localDate.getTimezoneOffset() / 60)) * 60 * 60 * 1000;
  var phDate = new Date(utcDate + phOffset);
  phDate.setDate(localDate.getDate()); // Set the date of phDate to be the same as localDate
  // Round the time to the nearest hour
  phDate.setMinutes(0);
  phDate.setSeconds(0);
  phDate.setMilliseconds(0);
  phDate.setHours(phDate.getHours() + Math.round(phDate.getMinutes() / 60));
  // Return the rounded date in the local time zone
  return phDate;
}

var scheduleInput = document.querySelector('input[name="schedule"]');

scheduleInput.addEventListener('change', function() {
  var roundedDate = roundToNearestHour(this.value);
  var year = roundedDate.getFullYear();
  var month = (roundedDate.getMonth() + 1).toString().padStart(2, '0');
  var day = roundedDate.getDate().toString().padStart(2, '0');
  var hours = roundedDate.getHours().toString().padStart(2, '0');
  var minutes = roundedDate.getMinutes().toString().padStart(2, '0');
  var dateString = year + '-' + month + '-' + day + 'T' + hours + ':' + minutes;
  this.value = dateString;
});

// Get the current date and time
var now = new Date();
// Add one day to the current date
var tomorrow = new Date(now.getFullYear(), now.getMonth(), now.getDate() + 1);
// Format the date as a string in the format expected by the input
var dateString = tomorrow.toISOString().slice(0, 16);
// Set the minimum value of the input to the formatted string
var scheduleInput = document.querySelector('input[name="schedule"]');
scheduleInput.min = dateString;
</script>


</body>
</html>