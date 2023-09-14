<?php 

include '../components/connect.php';

session_start();

$msg = "";
$update_id = $_GET['update'];

if(isset($_SESSION['admin_id'])){
    $admin_id = $_SESSION['admin_id'] ;
}else{
    $admin_id = '';
    header('location:admin_login.php');  
}

if(isset($_POST['submit'])){
    $promo_poster = $_FILES['promo_poster']['name'];
    $promo_poster_size = $_FILES['promo_poster']['size'];
    $promo_poster_tmp_name = $_FILES['promo_poster']['tmp_name'];
    $promo_poster_folder = '../promos/' . $promo_poster;
    $promo_name = $_POST['promo_name'];
    $promo_status = $_POST['status'];
    $date_uploaded = $_POST['date_uploaded'];
    $select_promo = $conn->prepare("SELECT * FROM `promos` WHERE promo_poster = ?");
    $select_promo->execute([$promo_poster]);

    if (!empty($promo_poster)) {
        if($promo_poster_size > 5000000){
            $msg = "<div class='alert alert-danger'>Image size is too large. Maximum of 5MB only. </div>";
        }else{
            $update_image = $conn->prepare("UPDATE `promos` SET promo_poster = ? WHERE promo_id  = ?");
            $update_image->execute([$promo_poster, $update_id]);
            move_uploaded_file($promo_poster_tmp_name, $promo_poster_folder);
            $_SESSION['msg'] = " <div class='alert-style'> <div class='alert alert-info'> Promo has been updated</div></div>"; 
        }
    }

    $update_promos = $conn->prepare("UPDATE `promos` SET promo_name = ?, status = ?, date_uploaded = now() WHERE promo_id = ?");
    $update_promos->execute([$promo_name, $promo_status, $update_id]);
    header('location:admin_promos.php');
    $_SESSION['msg'] = " <div class='alert-style'> <div class='alert alert-info'> Promo has been updated</div></div>"; 

}


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Manage Promos</title>
    <!-- CSS LINK -->
    <link rel="stylesheet" href="../css/admin.css">
    <!-- FONT AWESOME LINK -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
</head>
<body>

<!-- HEADER -->
<?php include '../components/admin_navigation.php'; ?>

<section class="promos">
<a href="admin_services.php" class="back-btn">
   <i class="fa-solid fa-circle-arrow-left"></i>    Back </a> 
   <div class="add-form-container">
      <form action="" method="post" enctype="multipart/form-data">
         <h1>Edit Promo</h1>
         <?php echo $msg; ?>
            <?php
                $show_promo = $conn->prepare("SELECT * FROM `promos` WHERE promo_id ='$update_id'");
                $show_promo->execute();
                if($show_promo->rowCount() > 0){
                    while($fetch_promo = $show_promo->fetch(PDO::FETCH_ASSOC)){  
            ?>
         <h2>Upload New promo Icon<span>*</span></h2>
         <input type="file" name="promo_poster" class="box" accept="promo_poster/jpg, promo_poster/jpeg, promo_poster/png, promo_poster/webp">
         <input type="hidden" name="old_image" value="<?= $fetch_promo['promo_poster']; ?>">
         <h2>Promo Title<span>*</span></h2>
         <input type="text"  name="promo_name" class="box" maxlength='300' value="<?=$fetch_promo['promo_name']; ?>">
         <input type="hidden" name="promo_id" value="<?= $fetch_promo['promo_id']; ?>">
         <h2>Promo Status<span>*</span></h2>
                <select name="status" required>
                    <option value="SHOW">SHOW</option>
                    <option value="HIDE">HIDE</option>
                </select>
         <input type="hidden"  name="date_uploaded" class="box" maxlength='300' value="<?=$fetch_promo['date_uploaded']; ?>">
         <br><br>
         <input type="submit" class="btn-add" name="submit" value="Add promo" style="width:100%;">
      </form>
            <?php
                }
            }
            ?>
   </div>
</section>

<!-- FOOTER -->
<?php include '../components/admin_footer.php'; ?>

<!-- JAVA SCRIPT -->
<script src="../js/admin.js"></script>

</body>
</html>