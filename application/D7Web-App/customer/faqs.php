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
   <title>Frequently Ask Questions</title>
    <!-- CSS LINK -->
    <link rel="stylesheet" href="../css/customer.css">
    <!-- FONT AWESOME LINK -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
</head>
<body>

<!-- HEADER -->
<?php include '../components/user_header.php'; ?>

<!-- FAQ -->
<section class="faqs">
<h1 class="title">Frequently Asked Questions </h1>
   <div class="row">
    <?php
        $show_faqs = $conn->prepare("SELECT * FROM `faqs` WHERE `status` = 'SHOW'");
        $show_faqs->execute();
        if($show_faqs->rowCount() > 0){
            while($fetch_faqs = $show_faqs->fetch(PDO::FETCH_ASSOC)){  
    ?>
      <div class="faq">
         <div class="box">
            <h3><?= $fetch_faqs['faq_title']; ?></h3>
            <p><?= $fetch_faqs['faq_description'];?></p>
         </div>
            <?php
                }
            }else{
                echo '<p class="empty">There are no FAQs yet... </p>';
                
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