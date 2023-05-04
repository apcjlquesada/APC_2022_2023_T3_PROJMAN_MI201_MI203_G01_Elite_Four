<?php 

include '../components/connect.php';

session_start();

$msg = "";

if(isset($_SESSION['admin_id'])){
    $admin_id = $_SESSION['admin_id'] ;
}else{
    $admin_id = '';
    header('location:admin_login.php');  
}

if(isset($_POST['submit'])){
    $promo_poster = $_FILES['promo_poster']['name'];
    $promo_poster_size = $_FILES['promo_poster']['size'];
    $promo_tmp_name = $_FILES['promo_poster']['tmp_name'];
    $promo_folder = '../promos/' . $promo_poster;
    $promo_name = $_POST['promo_name'];
    $select_promo = $conn->prepare("SELECT * FROM `promos` WHERE promo_name = ?");
    $select_promo->execute([$promo_name]);
 
    if($select_promo->rowCount() > 0){
        $msg = "<div class='alert alert-danger'> Promo already exists.</div>";
    }else{
        if($promo_poster_size > 2000000){
            $msg = "<div class='alert alert-danger'> Image is too large.</div>";
        }else{
            move_uploaded_file($promo_tmp_name, $promo_folder);
            $insert_promo = $conn->prepare("INSERT INTO `promos` (promo_poster, promo_name) VALUES(?,?)");
            $insert_promo->execute([$promo_poster, $promo_name]);
            $msg = "<div class='alert alert-success'>Promo has been uploaded.</div>";
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
   <title>View Promos</title>
    <!-- CSS LINK -->
    <link rel="stylesheet" href="../css/admin.css">
    <!-- FONT AWESOME LINK -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
</head>
<body>

<!-- HEADER -->
<?php include '../components/admin_navigation.php'; ?>

<section class="promos">
    <a href="admin_promos.php" class="back-btn">
   <i class="fa-solid fa-circle-arrow-left"></i>    Back </a> 
    <div class="add-form-container">
        <form action="" method="post" enctype="multipart/form-data">
        <h1>ADD PROMOS</h1>
        <?php echo $msg; ?>
        <h2>Upload Posters<span>*</span></h2>
        <input type="file" name="promo_poster" class="box" accept="promo_poster/jpg, promo_poster/jpeg, promo_poster/png, promo_poster/webp" required>
        <h2>Promo Name<span>*</span></h2>
        <input type="text"  name="promo_name" class="box" maxlength='300'>
         <br><br>
        <input type="submit" class="btn-add" name="submit" value="Add Promos"  style="width:100%;">
        </form>
    </div>
</section>

<!-- FOOTER -->
<?php include '../components/admin_footer.php'; ?>

<!-- JAVA SCRIPT -->
<script src="../js/admin.js"></script>

</body>
</html>