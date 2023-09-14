<?php 

include '../components/connect.php';

session_start();

$msg = "";

if(isset($_SESSION['msg'])) {
    echo $_SESSION['msg'];
    unset($_SESSION['msg']);
    echo "<script>
        setTimeout(function() {
            var element = document.querySelector('.alert-style');
            element.classList.add('hide');
            setTimeout(function() {
                element.parentNode.removeChild(element);
            }, 500);
        }, 2000);
    </script>";
   
}

if(isset($_SESSION['customer_id'])){
    $customer_id = $_SESSION['customer_id'] ;
}else{
    $customer_id = '';
} 

$insert_visit = $conn->prepare("INSERT INTO website_visits () VALUES ()");
$insert_visit->execute();

?>

<!DOCTYPE html>
<html lang="en" data-aos="fade-up">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Home</title>
   <!-- Swiper Link-->
   <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <!-- CSS LINK -->
    <link rel="stylesheet" href="../css/customer.css">
    <!-- FONT AWESOME LINK -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
</head>
<body>

<!-- HEADER -->
<?php include '../components/user_header.php'; ?>   
<!-- Header End --> 

<!-- Home Start -->
<section class="home">
    <?php echo $msg; ?>
   <div class="content">
      <h3 style="opacity: 0.9">Welcome to D7 Auto Service Center</h3>
      <p style="font-family: Lucida Calligraphy;  color: var(--yellow)""> We value all of our customers. #D7Cares "</p>
      <a href="reservation.php" class="btn-booknow">Book Now!</a>
      <br> <br>
    <a href="https://momento360.com/e/u/c700bd9cc06442faa2e2b213cd769564?utm_campaign=embed&utm_source=other&heading=174.62&pitch=-11.36&field-of-view=100&size=medium&display-plan=true"
    target="_blank" class="tour-btn"> 
    <br> <br> <br>
    <b>Take a 360&deg virtual tour </b></a>
   </div>
   <br>
</section>
   

<!-- Home End -->

<!-- FOOTER -->
<?php include '../components/user_footer.php'; ?>


<script src="../js/customer.js"></script>

</body>
</html>