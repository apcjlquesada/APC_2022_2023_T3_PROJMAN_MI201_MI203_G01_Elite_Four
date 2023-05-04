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
   <title>Promos</title>
    <!-- CSS LINK -->
    <link rel="stylesheet" href="../css/customer.css">
    <!-- FONT AWESOME LINK -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <!-- Link Swiper's CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
</head>
<body>
<!-- HEADER -->
<?php include '../components/user_header.php'; ?>

<section class="promos">
    <h1 class="title"> Our Promos </h1>
    <div class="swiper myPromos">
        <div class="swiper-wrapper">
        <?php
            $show_promos= $conn->prepare("SELECT * FROM `promos` WHERE `status` = 'SHOW'");
            $show_promos->execute();
            if($show_promos->rowCount() > 0){
                while($fetch_promos = $show_promos->fetch(PDO::FETCH_ASSOC)){  
        ?>
        <div class="swiper-slide"><img src="../promos/<?= $fetch_promos['promo_poster']; ?>" alt=""></div>
        <?php
                }
            }else{
                echo '<p class="empty">No Promos Added Yet...</p>';
            }
        ?>
        </div>
        <div class="swiper-pagination"></div>
    </div>
</section>


<!-- FOOTER -->
<?php include '../components/user_footer.php'; ?>

<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
<!-- JAVA SCRIPT -->
<script src="../js/customer.js"></script>

</body>
</html>