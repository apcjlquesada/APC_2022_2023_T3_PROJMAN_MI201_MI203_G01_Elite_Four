<?php 

include '../components/connect.php';

session_start();

$msg ="";

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

if(isset($_GET['delete'])){
    $delete_id = $_GET['delete'];
    $delete_review = $conn->prepare("DELETE FROM `reviews` WHERE review_id = ? ");
    $delete_review->execute([$delete_id]);
    $_SESSION['msg'] = "<div class='alert-style'> <div class='alert alert-danger'>
    Review has been removed. </div></div>";
 }

if(isset($_POST['edit'])){
    $rating = $_POST['rating'];
    $description = $_POST['description'];
    $date_posted = $_POST['date_posted'];
    $review_id = $_POST['review_id'];   
    $update_review = $conn->prepare("UPDATE `reviews` SET rating = ?, description = ?, date_posted = now() WHERE review_id = ?");
    $update_review->execute([$rating, $description, $review_id]);
    header('location:reviews.php');
    $_SESSION['msg'] = "<div class='alert-style'> <div class='alert alert-success'>
    Review has been posted. <i class='fa-solid fa-circle-check'></i></div></div>"; 
}
 
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Reviews</title>
    <!-- CSS LINK -->
    <link rel="stylesheet" href="../css/customer.css">
    <!-- FONT AWESOME LINK -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
     <!-- Swiper Link-->
     <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
     
    
</head>
<body>
    
<!-- HEADER -->
<?php include '../components/user_header.php'; ?>

<section class="review" id="reviews" >
    <h1 class="title"> What our customers say:</h1>
    <div class="swiper-container review-slider">
        <div class="swiper-wrapper" >
                <?php
                    $show_reviews = $conn->prepare("SELECT * FROM `reviews`");
                    $show_reviews->execute();
                    if($show_reviews->rowCount() > 0){
                        while($fetch_reviews = $show_reviews->fetch(PDO::FETCH_ASSOC)){  
                ?>

            <div class="swiper-slide slide">
            <?php 

                if($fetch_reviews['customer_id'] == $customer_id) {    
            ?>
                <div class="r-dropdown3">
                <button class="r-dropbtn3"><i class="fas fa-ellipsis-v"></i></button>       
                <div class="r-dropdown-content3">
                <a href="reviews.php?edit_review_id=<?=$fetch_reviews['review_id']; ?>" class="edit-link"> Edit </a>
                <a href="reviews.php?delete=<?=$fetch_reviews['review_id']; ?>" 
                onclick="return confirm('Are you sure to delete review?');"> Delete </a>
                </div>
            </div>

            <?php  }  ?>
         
                <div class="user">
                <img src="../profile/<?= $fetch_reviews['customer_profile']; ?>" alt="">
                    <div class="user-info">
                        <h3 style="float:left;"></h3>  <?= $fetch_reviews['customer_name']; ?>  </h3> 
                        <span style="font-size: 0.8rem; "></span> <i> <?= $fetch_reviews['date_posted']; ?></i> </span>
                        <div class="stars">
     
                        <?php  if ($fetch_reviews['rating'] == 1) { ?>
                            
                            <i class="fas fa-star" style="color:var(--yellow);"></i>
                            <i class="fas fa-star" ></i>
                            <i class="fas fa-star" ></i>
                            <i class="fas fa-star" ></i>
                            <i class="fas fa-star" ></i>
                        <?php  } ?>

                        <?php  if ($fetch_reviews['rating'] == 2) { ?>
                            
                            <i class="fas fa-star" style="color:var(--yellow);"></i>
                            <i class="fas fa-star" style="color:var(--yellow);"></i>
                            <i class="fas fa-star" ></i>
                            <i class="fas fa-star" ></i>
                            <i class="fas fa-star" ></i>
                        <?php  } ?>
                        <?php  if ($fetch_reviews['rating'] == 3) { ?>
                            
                            <i class="fas fa-star"style="color:var(--yellow);"></i>
                            <i class="fas fa-star" style="color:var(--yellow);"></i>
                            <i class="fas fa-star" style="color:var(--yellow);"></i>
                            <i class="fas fa-star" ></i>
                            <i class="fas fa-star" ></i>
                        <?php  } ?>
                        <?php  if ($fetch_reviews['rating'] == 4) { ?>
                            
                            <i class="fas fa-star"style="color:var(--yellow);"></i>
                            <i class="fas fa-star" style="color:var(--yellow);"></i>
                            <i class="fas fa-star" style="color:var(--yellow);"></i>
                            <i class="fas fa-star" style="color:var(--yellow);"></i>
                            <i class="fas fa-star" ></i>
                        <?php  } ?>

                        <?php  if ($fetch_reviews['rating'] == 5) { ?>
                            
                            <i class="fas fa-star"style="color:var(--yellow);"></i>
                            <i class="fas fa-star" style="color:var(--yellow);"></i>
                            <i class="fas fa-star" style="color:var(--yellow);"></i>
                            <i class="fas fa-star" style="color:var(--yellow);"></i>
                            <i class="fas fa-star" style="color:var(--yellow);"></i>
                        <?php  } ?>

                        </div>
                    </div>
                </div>
                    <p>  <?= $fetch_reviews['description']; ?> </p>
            </div>
            <?php
                }
            }else{
                echo '<p class="empty"> No Reviews added yet...</p>';
            }
            ?>
        </div>  
    </div>
    <div class="write">
         <a href="../customer/write_review.php" class="btn" style="width: 60%">Write a Review</a>
    </div>
   
</section>



<!-- Edit Modal  -->
<div id="EditModal" class="modal-customer">
            <?php
                    $edit_review_id = isset($_GET['edit_review_id']) ? $_GET['edit_review_id'] : 0;
                    $show_reviews = $conn->prepare("SELECT * FROM `reviews` WHERE review_id = " . $edit_review_id);
                    $show_reviews->execute();
                    if($show_reviews->rowCount() > 0){
                        while($fetch_reviews = $show_reviews->fetch(PDO::FETCH_ASSOC)){  
                ?>
   <div class="modal-content-customer">
      <span id="edit-close" class="close">&times;</span>
  
      <form action="" method="post">
      <?php echo $msg; ?>
      <input type="hidden" name="customer_name" class="box" maxlength="32" value="<?=$fetch_profile['complete_name']; ?>">  
      <input type="hidden" name="profile_picture" class="box" maxlength="32" value="<?=$fetch_profile['profile_picture']; ?>">  
      <input type="hidden" name="date_posted" class="box" maxlength="32" value="<?=$fetch_reviews['date_posted']; ?>"> 
      <input type="hidden" name="review_id" class="box" maxlength="32" value="<?=$fetch_reviews['review_id']; ?>"> 
       
      
      <h1>Edit Rating</h1>
      <select name="rating" class="box-star">
        <div class="">
        <option value="1">&#9733;&#9734;&#9734;&#9734;&#9734;</option>  
        <option value="2">&#9733;&#9733;&#9734;&#9734;&#9734;</option>
        <option value="3">&#9733;&#9733;&#9733;&#9734;&#9734;</option>    
        <option value="4">&#9733;&#9733;&#9733;&#9733;&#9734;</option>
        <option value="5">&#9733;&#9733;&#9733;&#9733;&#9733;</option>
        </div>
      </select>
      <h1> Edit Review </h1>
        <textarea name="description" rows="4" column="10"  class="box" maxlength='500' required><?=$fetch_reviews['description']; ?></textarea>  
        <input type="submit" class="btn-add" name="edit" value="Edit Review" style="width:100%;">
   </form>


   </div>
   <?php }} ?>
</div>

<!-- FOOTER -->
<?php include '../components/user_footer.php'; ?>
<!-- Swiper Script -->
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<!-- JAVA SCRIPT -->
<script src="../js/customer.js"></script>


<script>
 // Edit Modal
 var editModal = document.getElementById("EditModal");
 var edit = document.querySelector(".edit-link");
 var editClose = document.getElementById("edit-close");
 
//  edit.onclick = function() {
//     editModal.style.display = "block";
//  }

const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);
const edit_review_id = urlParams.get('edit_review_id');

if (edit_review_id) {
    editModal.style.display = "block";
}

 
editClose.onclick = function() {
    editModal.style.display = "none";
 }
 
 window.onclick = function(event) {
    if (event.target == editModal) {
        editModal.style.display = "none";
    }
 }
</script>

</body>
</html>