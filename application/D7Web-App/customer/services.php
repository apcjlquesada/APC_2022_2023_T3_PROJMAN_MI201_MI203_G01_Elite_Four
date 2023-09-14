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
   <title>Services</title>
    <!-- CSS LINK -->
    <link rel="stylesheet" href="../css/customer.css">
    <!-- FONT AWESOME LINK -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
</head>
<body>

<!-- HEADER -->
<?php include '../components/user_header.php'; ?>

<!-- SERVICES -->
<section class="services">

<h1 class="title"> Our Services </h1>
   <div class="box-container">
            <?php
             $show_services = $conn->prepare("SELECT * FROM `services` WHERE `status` = 'AVAILABLE'");
                $show_services->execute();
                if($show_services->rowCount() > 0){
                    while($fetch_services = $show_services->fetch(PDO::FETCH_ASSOC)){  
            ?>
      <div class="box">
         <img src="../services/<?= $fetch_services['service_picture']; ?>" alt="">
         <h3> <?= $fetch_services['service_name']; ?></h3>
         <p>  <?= $fetch_services['service_description']; ?> </p>
      </div>
            <?php
                }
            }else{
                echo '<p class="empty">NO SERVICE INFORMATION</p>';
            }
            ?>

    </div>
  
</div>
</section>


<!-- FOOTER -->
<?php include '../components/user_footer.php'; ?>


<!-- JAVA SCRIPT -->
<script src="../js/customer.js"></script>

</body>
</html>