<?php 

include '../components/connect.php';

session_start();

if(isset($_SESSION['customer_id'])){
    $customer_id = $_SESSION['customer_id'] ;
}else{
    $customer_id = '';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>About Us</title>
    <!-- CSS LINK -->
    <link rel="stylesheet" href="../css/customer.css">
    <!-- FONT AWESOME LINK -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
</head>
<body>
<!-- HEADER -->
<?php include '../components/user_header.php'; ?>

<!-- About Start -->
<section class="about">
<h1 class="title"> About Us </h1>
   <div class="row">
      <div class="image">
         <img src="../images/d7-p1.jpeg" alt="">
      </div>
      <div class="content">
         <h3>why choose us?</h3>
         <p>Since technology is continuously evolving, our business keeps a 
            pulse on the newest goods in the automotive sector to ensure 
            that we are always on top of our game. Our worldwide network of skilled detailers, 
            tinters, and auto professionals is extensive. We have the advantage of
             using the newest technology, tools, and techniques to give your vehicle 
             the care it needs because of our research into the newest goods and techniques.
         </p>
      </div>
   </div>
</section> 


<!-- FOOTER -->
<?php include '../components/user_footer.php'; ?>

<!-- JAVA SCRIPT -->
<script src="../js/customer.js"></script>

</body>
</html>