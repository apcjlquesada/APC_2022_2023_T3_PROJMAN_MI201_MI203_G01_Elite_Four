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
   <title>Gallery</title>
    <!-- CSS LINK -->
    <link rel="stylesheet" href="../css/customer.css">
    <!-- FONT AWESOME LINK -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
 </head>
<body>
<!-- HEADER -->
<?php include '../components/user_header.php'; ?>


<section class="gallery">
    <h1 class="title"> Our Gallery </h1>
    <div class="gal-grid-container">
        <?php
            $show_gallery= $conn->prepare("SELECT * FROM `gallery` WHERE `status` = 'SHOW'");
            $show_gallery->execute();
            if($show_gallery->rowCount() > 0){
                while($fetch_gallery = $show_gallery->fetch(PDO::FETCH_ASSOC)){  
        ?>
        <div class="gal-grid-item">
            <div class ="gal-image"> 
            <img src="../gallery/<?= $fetch_gallery['gallery_picture']; ?>" alt=""
            style =" width: 100%; height: 100%;">
              <div class="gal-overlay">
                <button class="view-button"> View Image </button> </div>
        </div>
        </div>
        <?php
                }
            } else {
                echo '<p class="empty">No Gallery Added Yet...!</p>';
            }
        ?>
    </div>
</section>




<!-- FOOTER -->
<?php include '../components/user_footer.php'; ?>

<!-- Swiper JS -->
<script src="../js/customer.js"></script>

</body>
</html>